import sys
import json
import datetime
import psycopg2
import pandas as pd
import numpy as np
from ebaysdk.finding import Connection as Finding
from ebaysdk.exception import ConnectionError

def getEbayResults(data):
    itemList=[]
    try:
        while True:
            api = Finding(config_file=None, appid='MarkKobz-HapunKak-PRD-b16e2f5cf-5c58ea0f')
            response = api.execute('findCompletedItems',data)
            assert(response.reply.ack == 'Success')
            assert(type(response.reply.timestamp) == datetime.datetime)
            assert(type(response.reply.searchResult.item) == list)
            assert(type(response.dict()) == dict)
            for item in response.reply.searchResult.item:
                resp={'itemId':item.itemId,
                      'title':item.title,
                      'price':item.sellingStatus.currentPrice.value,
                      'end_time':item.listingInfo.endTime,
                      'url':item.viewItemURL,
                      'img':item.galleryURL,
                      'listing_type':item.listingInfo.listingType,
                      'seller_name':item.sellerInfo.sellerUserName,
                      'seller_feedback':item.sellerInfo.positiveFeedbackPercent}
                try:
                    resp['bids']=int(item.sellingStatus.bidCount)
                except:
                    m=''
                try:
                    resp['postage_price']=item.shippingInfo.shippingServiceCost.value
                except:
                    m=''
                try:
                    resp['ePID']=item.productId.value
                except:
                    m='' 
                itemList.append(resp)
            print("tot pages: "+response.reply.paginationOutput.totalPages)
            data['paginationInput']['pageNumber']+=1
            if ((int(response.reply.paginationOutput.pageNumber)+1)>int(response.reply.paginationOutput.totalPages)):
                break
        return itemList
    except ConnectionError as e:
        #if any errors appeared, partly completed response returned
        failureInfo={
            'success':False,
            'info':e
        }

        print(json.dumps(failureInfo))
        return itemList




con=psycopg2.connect(
	host="127.0.0.1",
	database="app",
	user="postgres",
	password="root",
    port="5432")

#obtaining id from shell, looking for the record in the db
id=str(sys.argv[1])
cur=con.cursor()
cur.execute("select * from user_queries where id=%s",(id))
row=cur.fetchone()

#Reading the sql response
keywords=row[1]
buying_type=row[2]
condition=row[4]
catId=row[5]
productId=row[8]

#Generating an array of all the filters for the request
itemFilter=[]
buying_type_dict={'name':'ListingType','value':buying_type}
soldOnly ={'name':'SoldItemsOnly','value': True}
locatedIn={'name':'LocatedIn','value':'GB'}

itemFilter.append(buying_type_dict)
itemFilter.append(soldOnly)
itemFilter.append(locatedIn)

#reading all conditions from user_query
condArray=[]
for conditionCode in condition:
    condArray.append(str(conditionCode))
conditions={'name':'Condition','value':condArray}
itemFilter.append(conditions)

request = {
            'keywords': keywords,
            'itemFilter': itemFilter,
            'categoryId':str(catId),
            'globalId':'EBAY-GB',
            'siteId':'3',
            'paginationInput':{'entriesPerPage':100,'pageNumber':1},
            'outputSelector':'SellerInfo'
            }

            
if (productId!=None and productId.isnumeric()):
    request['productId']=productId

#print(request)
#request['paginationInput']['pageNumber']=44
#print(request['paginationInput']['pageNumber'])

finalItems=getEbayResults(request)


if (len(finalItems)<=0):
    failureInfo={
            'success':False,
            'info':'finalItems<=0'
        }

    print(json.dumps(failureInfo))

else:
    #mining data, setting up pandas
    df=pd.read_json(json.dumps(finalItems, indent=4, sort_keys=True, default=str))
    df=df.set_index('end_time')
    aggregates={}

    #medianes
    medianDaily=df.price.resample('D').median().to_json(date_format='iso')
    medianWeekly=df.price.resample('W').median().to_json(date_format='iso')
    medianMonthly=df.price.resample('M').median().to_json(date_format='iso')
    medianAllTime=df.price.median()
    medianes={
        'daily':json.loads(medianDaily),
        'weekly':json.loads(medianWeekly),
        'montlhy':json.loads(medianMonthly),
        'all_time':medianAllTime
    }
    aggregates['medianes']=medianes

    #sums
    sumWeekly=df.price.resample('W').sum().to_json(date_format='iso')
    sumDaily=df.price.resample('D').sum().to_json(date_format='iso')
    sumMonthly=df.price.resample('M').sum().to_json(date_format='iso')
    sumAllTime=df.price.sum()
    sums={
        'daily':json.loads(sumDaily),
        'weekly':json.loads(sumWeekly),
        'monthly':json.loads(sumMonthly),
        'all_time':sumAllTime
    }
    aggregates['sums']=sums

    #generating Sellers dataframe and converting to dict
    df['seller_total_sold']=df.groupby(['seller_name'])['price'].transform('sum')
    df['seller_sales_count']=df.groupby('seller_name')['seller_name'].transform('count')
    sellers=df.filter(['seller_name','seller_sales_count','seller_total_sold'],axis=1)
    sellers=sellers.set_index('seller_name')
    sellers=sellers.drop_duplicates(keep='first')
    sellers2=df.groupby('seller_name')['itemId'].apply(list)
    finalSellers=pd.merge(sellers,sellers2,on=['seller_name'])
    finalSellers=finalSellers.sort_values(by=['seller_sales_count'],ascending=False)
    finalSellers=finalSellers.to_dict()


    response={
        'items':finalItems,
        'sellers':finalSellers,
        'aggregates':aggregates
    }
    try:
        #final db insert
        cur.execute("INSERT INTO query_data(query_id,data) VALUES (%s, %s) RETURNING id",(id,json.dumps(response, indent=4, sort_keys=True, default=str)))
        #query_data obtained id:
        id_inserted=cur.fetchone()[0]
        ##need to make sure there is only one record
        successInfo={
            'success':True,
            'query_data_id':id_inserted,
            'user_queries_id':id
        }
        print(json.dumps(successInfo))
        cur.execute("UPDATE user_queries SET checked=%s,last_check=%s,thumbnail=%s,query_data_id=%s WHERE id=%s",(True,datetime.datetime.now(),finalItems[0]['img'],id_inserted,id))
        con.commit()
    except psycopg2.Error as e:
        failureInfo={
            'success':False,
            'info':e
        }
        print(json.dumps(failureInfo))
        

    

#ebay request

#nado funkciju dlja fetcha vseh stranic a potom result sravnitj s imejushimsja dannimi

                  
#getEbayResults(request)
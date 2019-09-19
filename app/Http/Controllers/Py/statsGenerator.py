import sys
import json
import datetime
import psycopg2
import pandas as pd
import numpy
from ebaysdk.finding import Connection as Finding
from ebaysdk.exception import ConnectionError
from sklearn.impute import SimpleImputer as Imputer
from sklearn.preprocessing import PolynomialFeatures
from sklearn import linear_model
import env_info as db

def getEbayResults(data):
    itemList=[]
    try:
        while True:
            api = Finding(config_file=None, appid=db.ebay_finding)
            response = api.execute('findCompletedItems',data)
            if (response.reply.ack != 'Success'):
                break
            assert(type(response.reply.timestamp) == datetime.datetime)
            assert(type(response.reply.searchResult.item) == list)
            assert(type(response.dict()) == dict)
            for item in response.reply.searchResult.item:
                
                resp={'itemId':item.itemId,
                      'title':item.title,
                      'price':item.sellingStatus.currentPrice.value,
                      'end_time':item.listingInfo.endTime.strftime('%Y-%m-%d %H:%M:%S'),
                      'url':item.viewItemURL,
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
                try:
                    resp['img']=item.galleryURL
                except:
                    resp['img']='http://krivoy.co.uk/img/processing.jpg'
                    
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
            'info':str(e)
        }

        print(json.dumps(failureInfo))
        return itemList

def generate_pred_x(X,step=1,amount=30):
    result=numpy.array([X[len(X)-1]+step])
    for i in range(amount-1):
        result=numpy.vstack((result,numpy.array([result[len(result)-1]+step])))
    return result


#price prediction in a month by lin reg
def linRegPrediction(df,period):
    if period=='D':
        step=1
        amount=30
    elif period=='W':
        step=7
        amount=4
    elif period=='M':
        step=30
        amount=1
    else:
        return None
    
    X = (df.index -  df.index[0]).days.values.reshape(-1, 1)
    y=df.values.reshape(-1,1)
    imputer = Imputer()
    y_imputed = imputer.fit_transform(y)
    # Create linear regression object
    reg = linear_model.LinearRegression()
    reg.fit(X, y_imputed)
    X_pred=generate_pred_x(X,step,amount)
    X_pred=numpy.append(X,X_pred).reshape(-1,1)
    y_pred=reg.predict(X_pred)
    response = {}
    for i in range(len(X_pred)):
        singlePred={X_pred[i][0].item():y_pred[i][0].item()}
        response.update(singlePred)
    return response

def polyRegPrediction(df, period):
    if period=='D':
        step=1
        amount=30
    elif period=='W':
        step=7
        amount=4
    elif period=='M':
        step=30
        amount=1
    else:
        return None

    X = (df.index -  df.index[0]).days.values.reshape(-1, 1)
    y=df.values.reshape(-1,1)
    imputer = Imputer()
    y_imputed = imputer.fit_transform(y)
    
    X_test=generate_pred_x(X,step,amount)
    X_test=numpy.append(X,X_test).reshape(-1,1)
    poly=PolynomialFeatures(degree=3)
    X_=poly.fit_transform(X)
    X_test_=poly.fit_transform(X_test)

    lin=linear_model.LinearRegression()
    lin.fit(X_,y_imputed)
    y_pred=lin.predict(X_test_)
    response = {}
    for i in range(len(X_test_)):
        singlePred={X_test[i].item():y_pred[i][0].item()}
        response.update(singlePred)
    return response


##CONFIG IS DIFFERENT ON THE SERVER
con=db.con

#obtaining id from shell, looking for the record in the db
id=str(sys.argv[1])
cur=con.cursor()
cur.execute("select * from user_queries where id={}".format(id))
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
            'globalId':'EBAY-GB',
            'siteId':'3',
            'paginationInput':{'entriesPerPage':100,'pageNumber':1},
            'outputSelector':'SellerInfo'
            }
if(catId!=None):
    request['categoryId']=str(catId)
    
if (productId!=None and productId.isnumeric()):
    request['productId']=productId


print(request)
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
##TO DO medianes
    #medianes
    medianDaily=df.price.resample('D').median()
    dailyReg=linRegPrediction(medianDaily,'D')
    dailyPoly=polyRegPrediction(medianDaily,'D')
    medianDaily=medianDaily.to_json(date_format='iso')

    medianWeekly=df.price.resample('W').median()
    weeklyReg=linRegPrediction(medianWeekly,'W')
    weeklyPoly=polyRegPrediction(medianWeekly,'W')
    medianWeekly=medianWeekly.to_json(date_format='iso')

    medianMonthly=df.price.resample('M').median()
    monthlyReg=linRegPrediction(medianMonthly,'M')
    monthlyPoly=polyRegPrediction(medianMonthly,'M')
    medianMonthly=medianMonthly.to_json(date_format='iso')
    
    medianAllTime=df.price.median()
    medianes={
        'daily':json.loads(medianDaily),
        'weekly':json.loads(medianWeekly),
        'monthly':json.loads(medianMonthly),
        'all_time':medianAllTime
    }
    predictions={
        'daily':dailyReg,
        'weekly':weeklyReg,
        'monthly':monthlyReg,
        'dailyPoly':dailyPoly,
        'weeklyPoly':weeklyPoly,
        'monthlyPoly':monthlyPoly
    }
    
    aggregates['medianes']=medianes
    aggregates['predictions']=predictions

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
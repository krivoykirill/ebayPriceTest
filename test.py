import sys
import json
import datetime
import psycopg2
from ebaysdk.finding import Connection as Finding
from ebaysdk.exception import ConnectionError

def getEbayResults(data):

    itemList=[]
    
    try:
        while True:
            api = Finding(appid="MarkKobz-HapunKak-PRD-b16e2f5cf-5c58ea0f", config_file=None)
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
                    #print('bids added '+item.sellingStatus.bidCount)    
                itemList.append(resp)
            data['paginationInput']['pageNumber']+=1
            #print(data['paginationInput']['pageNumber'])
            print("TOTAL PAGES: "+response.reply.paginationOutput.totalPages)
            if ((int(response.reply.paginationOutput.pageNumber)+1)>int(response.reply.paginationOutput.totalPages)):
                print("TOTAL ENTRIES: "+response.reply.paginationOutput.totalEntries)
                break

        return itemList

            #print(response.dict())
            #print(f"Title:{item.title}, price: {item.sellingStatus.currentPrice.value} bids: {item.sellingStatus.bidCount} post: {item.shippingInfo.shippingServiceCost.value}")
            #processing data
    except ConnectionError as e:
        #if stopped in the middle of pages and not 101, continue from the faulty page
        return itemList




con=psycopg2.connect(
	host="127.0.0.1",
	database="app",
	user="postgres",
	password="root",
    port="5432")

#To modify id thing with shell
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
    ePID={'productId':productId}
    request.update(ePID)
print(request)

#print(request)
#request['paginationInput']['pageNumber']=44
#print(request['paginationInput']['pageNumber'])

finalResponse=getEbayResults(request)


if len(finalResponse)<0:
    print("no results found")
else:
    try:
        #final
        cur.execute("INSERT INTO query_data(query_id,data) VALUES (%s, %s) RETURNING id",(id,json.dumps(finalResponse, indent=4, sort_keys=True, default=str)))
        id_inserted=cur.fetchone()[0]
        print("SUCCESS %s %s %s %s"%(True,datetime.datetime.now(),finalResponse[0]['img'],id_inserted))
        cur.execute("UPDATE user_queries SET checked=%s,last_check=%s,thumbnail=%s,query_data_id=%s WHERE id=%s",(True,datetime.datetime.now(),finalResponse[0]['img'],id_inserted,id))
        con.commit()
    except:
        print("insertion error")
        

    

#ebay request

#nado funkciju dlja fetcha vseh stranic a potom result sravnitj s imejushimsja dannimi

                  
#getEbayResults(request)
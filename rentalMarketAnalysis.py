import urllib2
import re
import time
from bs4 import BeautifulSoup
import time
import datetime
import json
import MySQLdb

db = MySQLdb.connect(host="",    # your host, usually localhost
				 user="",         # your username
				 passwd="",  # your password
				 db="")        # name of the data base		
cur = db.cursor() 
                 
markets = [
['1','https://washingtondc.craigslist.org/search/apa?query=Columbia+heights&search_distance=4&postal=20001&min_bedrooms=2&max_bedrooms=2&min_bathrooms=1&max_bathrooms=1&minSqft=500&maxSqft=700&availabilityMode=0&sale_date=all+dates'],
['2','https://washingtondc.craigslist.org/search/doc/hhh?query=columbia+heights+petworth&search_distance=4&postal=20010&min_bedrooms=3&max_bedrooms=3&min_bathrooms=1&max_bathrooms=2&availabilityMode=0&sale_date=all+dates'],
['3','https://washingtondc.craigslist.org/search/doc/apa?query=columbia+heights+petworth&search_distance=4&postal=20010&min_bedrooms=1&max_bedrooms=1&min_bathrooms=1&max_bathrooms=1&minSqft=450&maxSqft=650&availabilityMode=0&sale_date=all+dates'],
['4','https://winchester.craigslist.org/search/apa?query=Front+royal+va&search_distance=18&postal=22630&min_bedrooms=3&max_bedrooms=3&min_bathrooms=1&max_bathrooms=2&maxSqft=1600&availabilityMode=0&sale_date=all+dates']
]

today = str(datetime.datetime.now())

for market in markets:
	searchUrl = market[1]	
	resultsPage = urllib2.urlopen(searchUrl)
	resultHtml = BeautifulSoup(resultsPage, 'html.parser')
	resultItems = resultHtml.find('ul', attrs={'class':'rows'})
	results = resultItems.find_all('li',attrs={'class':'result-row'})
	marketRates = []
	uniquePosts = []	
	for result in results:
		title = result.find('a', attrs={'class':'result-title hdrlnk'}).decode_contents()
		rentalPriceSpan = result.find('span', attrs={'class':'result-price'})
		if rentalPriceSpan:
			rentalPriceStr = rentalPriceSpan.text.strip()
			rentalPrice = int(rentalPriceStr.replace('$',''))
		else:
			rentalPriceStr = '0'
		
		identifier = title + rentalPriceStr		
		if identifier not in uniquePosts:
			marketRates.append(rentalPrice)
			uniquePosts.append(identifier)	
				
	market = market[0]
	if len(marketRates)>0:
		minRent = str(min(marketRates))
		maxRent = str(max(marketRates))
		numResults = str(len(marketRates))		
		avgRent = str(sum(marketRates)/len(marketRates))
	else:
		minRent = "0"
		maxRent = "0"
		numResults = "0"
		avgRent = "0"
	##print [minRent,maxRent,numResults,avgRent]
	SQL = "INSERT INTO aptListings (listingDate, propertyId, avgRent, minRent, maxRent, numUnits) VALUES ('" + today + "','" + market + "','" + avgRent + "','" + minRent + "','" + maxRent + "','" + numResults + "');"
	cur.execute(SQL)		
	#marketAnalysis = marketAnalysis + "\"" + market+"\":{\"minRate\":"+minRate+", \"maxRate\":"+maxRate+",\"numbResults\":"+numbResults+",\"avgRent\":"+avgRent+"},"
	time.sleep(1)
db.commit()
db.close()


import sys
from collections import defaultdict
from decimal import Decimal
import networkx as nx
import csv


replaceRootPath = "/home/pradeep/solr-5.3.1/example/"
givenRootPath = "D:/data/crawl/"
docId = defaultdict(lambda : "string")

csvFile = open('docid.csv',"r")
csvFile.seek(0)

reader = csv.DictReader(csvFile)	
for row in reader:
	docId[str(row['DocURL'])] = str(row['DocID']).replace(givenRootPath,replaceRootPath)
csvFile.close()
print len(docId)


G = nx.DiGraph()

csvFile = open('pagerankdata.csv',"r")
csvFile.seek(0)

reader = csv.DictReader(csvFile)	
for row in reader:
	G.add_edge(str(row['Page']),str(row['Outgoing Links']))
csvFile.close()

print len(G)

pr = nx.pagerank(G)

outputFile = open("external_pageRank.txt","w")
for url,score in pr.iteritems():
	if url in docId.keys():
		outputFile.write(str(docId[url])+"="+str(Decimal(score))+"\n")
outputFile.close()

scores = []
outputFile = open("external_pageRank_Complete.txt","w")
for url,score in pr.iteritems():
	if url in docId.keys():
		outputFile.write(str(docId[url])+"="+str(Decimal(score))+"\n")
	else:
		outputFile.write(str(url)+"="+str(Decimal(score))+"\n")
	scores.append(Decimal(score))
outputFile.close()

print min(scores)

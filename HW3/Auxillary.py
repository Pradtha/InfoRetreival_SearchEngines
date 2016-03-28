import sys
import csv
from collections import defaultdict
import networkx as nx


csvFile = open('pagerankdata.csv',"r")
csvFile.seek(0)

g = nx.DiGraph()
reader = csv.DictReader(csvFile)	
for row in reader:
	g.add_edge(str(row['Page']),str(row['Outgoing Links']))
csvFile.close()


csvFile = open('PageRankData.csv',"w")
csvFile.seek(0)
csvFile.write("Page,Outgoing Links\n")

for n in g.nodes():
	s = str(n)
	for e in g.neighbors(n):
		s = s + "," + str(e) 
	s = s + "\n";
	csvFile.write(s)
csvFile.close()


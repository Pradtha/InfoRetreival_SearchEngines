import sys
from collections import defaultdict
import networkx as nx
import csv

G = nx.Graph()
with open('pagerankdatasolr.csv') as csvFile:
	reader = csv.DictReader(csvFile)	
	for row in reader:
		G.add_edge(row['Page'],row['Outgoing Links'])
print len(G)

pr = nx.pagerank(G, alpha=0.9)
		

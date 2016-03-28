import sys
import csv
from collections import defaultdict

csvFile = open('pagerankdata.csv',"r")
csvFile.seek(0);

temp = []
reader = csv.DictReader(csvFile)	
for row in reader:

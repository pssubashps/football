from bs4 import BeautifulSoup
from urllib2 import urlopen
from sys import exit
import csv
import MySQLdb
conn = MySQLdb.connect(host= "localhost",user="root",passwd="root",db="football")
print "subash"
html = urlopen('http://www.footywire.com/afl/footy/supercoach_round?year=2015&round=23&p=&s=T').read()
soup = BeautifulSoup(html, "lxml")
boccat = soup.findChildren("table")
f = open('aa.txt','w')
f.write(str(boccat[10]))
f.close()
rows = boccat[10].findChildren(['tr'])
with open('eggs.csv', 'wb') as csvfile:
    writer = csv.writer(csvfile)
    head = [];
    head.append("ddd")
    head.append("ddd")
    head.append("ddd")
    head.append("ddd")
    head.append("ddd")
    head.append("ddd")
    head.append("ddd")
    writer.writerow(head)
    header = 0
    for row in rows:
        print "-------------------"
        cells = row.findChildren('td')
        print cells
        #exit(0)
        ra = []
        for cell in cells:
            value = cell.string
            print value 
            ra.append(value)
        if header == 0 :
            header = 1
        else:
            writer.writerow(ra)           
        
       

x = conn.cursor()
try:
    x.execute("""INSERT INTO upload_file_log (upload_type,upload_file,upload_status)VALUES (%s,%s,%s)""",('PLD','eggs.csv','Q'))
    conn.commit()
except:
    conn.rollback()
    print "mysql error"
conn.close()

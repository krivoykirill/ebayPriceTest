import csv
import psycopg2
import env_info as db

con=db.con
cur=con.cursor()


with open('catIDs.csv','r') as csv_file:
    csv_reader = csv.reader(csv_file)
    next(csv_reader)
    for line in csv_reader:
        cur.execute('insert into category_id(category_id,category_name) values (%s,%s)',(line[0],line[1]))
    con.commit()
print('done')
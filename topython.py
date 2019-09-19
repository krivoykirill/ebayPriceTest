import psycopg2

con=psycopg2.connect(
	host="127.0.0.1",
	database="lara",
	user="postgres",
	password="Lpkoji91!")

cur = con.cursor()
cur.execute("select * from users")
rows= cur.fetchall()
for r in rows:
	print(f"id {r[0]} {r[1]} {r[2]} {r[3]}")

con.commit()
cur.close()
con.close()


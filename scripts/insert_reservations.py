import random
from datetime import datetime, timedelta

statuses = ['Confirmed', 'Pending', 'Cancelled', 'Checked-in']
num_reservations = 500
cust_id_range = (1, 1000)
hotel_id_range = (1, 10)

with open("insert_reservations.sql", "w", encoding="utf-8") as file:
    file.write("INSERT INTO Reservation (Cust_ID, Hotel_ID, Start_Date, End_Date, Status)\nVALUES\n")

    for i in range(num_reservations):
        cust_id = random.randint(*cust_id_range)
        hotel_id = random.randint(*hotel_id_range)

        start_date = datetime.now() + timedelta(days=random.randint(1, 60))
        end_date = start_date + timedelta(days=random.randint(1, 10))

        start_date_str = start_date.strftime('%Y-%m-%d %H:%M:%S')
        end_date_str = end_date.strftime('%Y-%m-%d %H:%M:%S')

        status = random.choice(statuses)

        line = f"({cust_id}, {hotel_id}, '{start_date_str}', '{end_date_str}', '{status}')"
        line += ";" if i == num_reservations - 1 else ","
        file.write(line + "\n")

print("An Insert Reservations.sql file containing 500 orders using Hotel_ID is created.")

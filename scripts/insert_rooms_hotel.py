import random
room_distribution = {
    'Single': 30,
    'Double': 30,
    'Suite': 30,
    'Deluxe': 20
}


room_prices = {
    'Single': 500.00,
    'Double': 800.00,
    'Suite': 1300.00,
    'Deluxe': 2000.00
}

wings = ['A', 'B', 'C', 'D']
room_no = 101  

with open("insert_rooms_hotel_2.sql", "w", encoding="utf-8") as file:
    file.write("INSERT INTO Room (Room_No, Hotel_ID, Room_Type, Price, Floor, Wing)\nVALUES\n")

    total = sum(room_distribution.values())
    counter = 0

    for room_type, count in room_distribution.items():
        for _ in range(count):
            floor = random.randint(1, 10)
            wing = random.choice(wings)
            price = room_prices[room_type]
            hotel_id = 10

            line = f"({room_no}, {hotel_id}, '{room_type}', {price}, {floor}, '{wing}')"
            line += ";" if counter == total - 1 else ","
            file.write(line + "\n")

            room_no += 1
            counter += 1

print("A file insert rooms_hotel_1.sql containing 200 rooms of Hotel 1 has been created.")

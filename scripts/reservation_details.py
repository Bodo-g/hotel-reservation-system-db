hotel_room_counts = {
    1: 200,
    2: 200,
    3: 170,
    4: 180,
    5: 170,
    6: 220,
    7: 200,
    8: 160,
    9: 160,
    10: 110
}

room_ranges = {}
current_room_id = 1

for hotel_id, count in hotel_room_counts.items():
    start_id = current_room_id
    end_id = current_room_id + count - 1
    room_ranges[hotel_id] = (start_id, end_id)
    current_room_id = end_id + 1

import random

num_reservations = 500
reservation_details_lines = ["INSERT INTO Reservation_Details (Reservation_ID, Room_ID)\nVALUES"]

for reservation_id in range(1, num_reservations + 1):
    hotel_id = ((reservation_id - 1) % 10) + 1  
    room_start, room_end = room_ranges[hotel_id]
    room_id = random.randint(room_start, room_end)

    line = f"({reservation_id}, {room_id})"
    if reservation_id != num_reservations:
        line += ","
    else:
        line += ";"
    reservation_details_lines.append(line)

script_content = "\n".join(reservation_details_lines)
file_path = "/mnt/data/insert_reservation_details.sql"

with open(file_path, "w", encoding="utf-8") as file:
    file.write(script_content)

file_path

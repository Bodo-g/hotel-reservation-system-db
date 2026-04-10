from faker import Faker
import random

fake = Faker()


hotels_info = {
    1: ("Cairo", "Egypt", "+20"),
    2: ("Alexandria", "Egypt", "+20"),
    3: ("Marrakech", "Morocco", "+212"),
    4: ("Riyadh", "Saudi Arabia", "+966"),
    5: ("Dubai", "UAE", "+971"),
    6: ("Doha", "Qatar", "+974"),
    7: ("Amman", "Jordan", "+962"),
    8: ("Beirut", "Lebanon", "+961"),
    9: ("Istanbul", "Turkey", "+90"),
    10: ("Almaty", "Kazakhstan", "+7")
}

job_titles = [
    "Receptionist", "Housekeeping", "Manager", "Securety", "Chef", "Waiter",
    "Accountant", "Cleaner", "Bellboy", "Supervisor"
]

def generate_mobile(country_code):
    if country_code == "+20":  
        return f"{country_code} 1{random.randint(0,9)}{random.randint(10000000,99999999)}"
    elif country_code == "+212":  
        return f"{country_code} 6{random.randint(10000000,99999999)}"
    elif country_code == "+966":  
        return f"{country_code} 5{random.randint(10000000,99999999)}"
    elif country_code == "+971":  
        return f"{country_code} 5{random.randint(1000000,9999999)}"
    elif country_code == "+974":  
        return f"{country_code} {random.randint(10000000,99999999)}"
    elif country_code == "+962": 
        return f"{country_code} 7{random.randint(10000000,99999999)}"
    elif country_code == "+961":  
        return f"{country_code} 3{random.randint(1000000,9999999)}"
    elif country_code == "+90": 
        return f"{country_code} 5{random.randint(100000000,999999999)}"
    elif country_code == "+7":
        return f"{country_code} 7{random.randint(100000000,999999999)}"
    else:
        return fake.phone_number()

total_employees = 600

# التوزيع السابق
prev_counts = {
    1: 25, 2: 24, 3: 20, 4: 21, 5: 18, 6: 27, 7: 22, 8: 19, 9: 22, 10: 22
}
prev_total = sum(prev_counts.values())

hotel_ratios = {k: v / prev_total for k, v in prev_counts.items()}
new_counts = {k: int(v * total_employees) for k, v in hotel_ratios.items()}
current_sum = sum(new_counts.values())
if current_sum < total_employees:
    new_counts[1] += (total_employees - current_sum)

insert_values = []

for hotel_id, count in new_counts.items():
    city, country, country_code = hotels_info[hotel_id]
    for _ in range(count):
        name = fake.name().replace("'", "''")  # تعويض علامات الاقتباس المفردة
        job = random.choice(job_titles)
        mobile = generate_mobile(country_code)
        address = fake.street_address().replace('\n', ', ').replace("'", "''") + f", {city}, {country}"
        val = f"('{name}', '{job}', '{mobile}', '{address}', {hotel_id})"
        insert_values.append(val)

insert_query = "INSERT INTO Employee (Emp_name, Job_description, Mobile_no, Address, Hotel_ID) VALUES\n"
insert_query += ",\n".join(insert_values) + ";"

file_path_600 = r"C:\Users\Studymode\Desktop\Data Project\Script\insert_employees_600.sql"

with open(file_path_600, "w") as f:
    f.write(insert_query)

file_path_600

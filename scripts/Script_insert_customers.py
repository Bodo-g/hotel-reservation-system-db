from faker import Faker
import random

# إعداد Faker بلغات متعددة لتنوع الجنسيات
locales = ['en_US', 'en_GB', 'en_CA', 'en_AU', 'en_IN']
fake = Faker(locales)

# عدد العملاء
num_customers = 1000

# اسم ملف الإخراج
output_file = "insert_customers.sql"

# تجميع كل الصفوف
values = []

for _ in range(num_customers):
    f_name = fake.first_name().replace("'", "''")
    l_name = fake.last_name().replace("'", "''")
    email = fake.unique.email().replace("'", "''")
    mobile_no = ''.join([str(random.randint(0, 9)) for _ in range(random.randint(10, 15))])
    dob = fake.date_of_birth(minimum_age=18, maximum_age=70).strftime('%Y-%m-%d')
    
    # تقصير الجنسية إلى 50 حرف كحد أقصى
    nationality = fake.country()[:50].replace("'", "''")
    
    address = fake.street_address().replace("'", "''")
    city = fake.city().replace("'", "''")
    state = fake.state().replace("'", "''")
    pin_code = fake.postcode().replace("'", "''")

    row = f"('{f_name}', '{l_name}', '{email}', '{mobile_no}', '{dob}', '{nationality}', '{address}', '{city}', '{state}', '{pin_code}')"
    values.append(row)


with open(output_file, "w", encoding="utf-8") as f:
    f.write("INSERT INTO Customer (F_Name, L_Name, Email, Mobile_no, DOB, Nationality, Address, City, State, Pin_code) VALUES\n")
    f.write(",\n".join(values))
    f.write(";\n")

print(f"{num_customers} customer records written successfully to '{output_file}'.")

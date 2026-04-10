# 🏨 Hotel Reservation System (Advanced Database Project)

## 📌 Overview
This project is a full-scale relational database system designed to simulate real-world hotel operations.  
It manages reservations, rooms, customers, employees, services, payments, and system security.

The system demonstrates advanced SQL concepts, database design principles, and backend-oriented thinking.

---

## 🚀 Key Features

- Hotel and Room Management System  
- Customer Registration and Reservation System  
- Payment and Invoice Handling  
- Employee and Role Management  
- Authentication System (Employee & Customer Login)  
- Logging and Activity Tracking  
- Service Requests and Room Maintenance  
- Cancellation Handling with Automatic Status Updates  

---

## 🧠 Technical Highlights

- Designed a normalized relational database (up to 3NF)  
- Implemented complex relationships (1:M, M:N)  
- Used Foreign Keys, Constraints, and Composite Keys  
- Developed advanced Triggers for business logic  
- Prevented double booking using SQL triggers  
- Built authentication system with roles and login tracking  
- Implemented logging and monitoring system  

---

## 🔐 Security Features

- Employee & Customer authentication system  
- Role-based access control (RBAC)  
- Password handling structure (for demonstration purposes)  
- Login history tracking  
- System logs for auditing and monitoring  

---

## 🏗️ Database Structure

Main entities include:

- Hotel  
- Customer  
- Reservation  
- Room  
- Payment  
- Employee  
- Service  
- Invoice  
- Logs  

The system is designed to be scalable and modular.

---

## ⚙️ Technologies Used

- MySQL  
- SQL (Advanced Queries, Triggers, Constraints)  
- Python (for data generation using Faker)  
- PHP & HTML (used for basic local testing interface)  

---

## 📊 Data Generation

Custom Python scripts were used to generate realistic data:

- 1000+ customer records  
- 500+ reservations  
- Room distribution and pricing simulation  
- Reservation relationships and linking  

This simulates a real-world system and helps in testing scalability and performance.

---

## 📁 Project Structure
hotel-reservation-system-db/
│
├── schema.sql
├── Insert.sql
│
├── scripts/
│ ├── insert_customers.py
│ ├── insert_reservations.py
│ ├── insert_rooms_hotel.py
│ └── reservation_details.py
│
├── docs/
│ └── erd.png
│
├── php+css/
│
└── README.md



---

## 🚀 How to Run

### 📌 Prerequisites
- MySQL or MariaDB  
- Python 3  
- (Optional) XAMPP / Local server  

---

### 🗄️ 1. Setup Database

Create database:

```sql
CREATE DATABASE hotel_system;
USE hotel_system;

Import schema:

source schema.sql;

Insert sample data:

source Insert.sql;


🐍 2. Run Python Scripts (Optional)
cd scripts
Run:
python insert_customers.py
python insert_reservations.py
python insert_rooms_hotel.py
python reservation_details.py

🌐 3. Run PHP Interface (Optional)
Move php+css to XAMPP htdocs
Start Apache & MySQL
Open:
http://localhost/php+css/


📊 ERD Diagram

🎯 Learning Outcomes

Through this project, I gained:

Strong understanding of database design
Experience with advanced SQL features
Ability to implement real-world business logic
Backend-oriented system thinking
Data simulation and testing techniques
🔮 Future Improvements
Add indexing for performance optimization
Implement stored procedures
Build REST API (Node.js / Flask)
Connect to a full web application
👨‍💻 Author

Abdulrahman Mohamed Gaber
Software Engineering & Cybersecurity Enthusiast

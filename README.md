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

- Designed normalized relational database (up to 3NF)  
- Implemented complex relationships (1:M, M:N)  
- Used Foreign Keys, Constraints, and Composite Keys  
- Developed advanced Triggers for business logic  
- Prevented double booking using SQL triggers  
- Built authentication system with roles and login tracking  
- Implemented logging and monitoring system  

---

## 🔐 Security Features

- Employee & Customer login systems  
- Role-based access control  
- Password hashing structure  
- Login history tracking  
- System logs for auditing  

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
- PHP & HTML (basic integration for testing)  

---

## 📊 Data Generation

Custom Python scripts were used to generate realistic data:

- Customer data (1000+ records)  
- Reservations (500+ records)  
- Room distribution and pricing  
- Reservation details linking  

This simulates a real-world system and helps in testing scalability.

---

## 📁 Project Structure

```
hotel-reservation-system-db/
│
├── schema.sql
│
├── scripts/
│   ├── insert_customers.py
│   ├── insert_reservations.py
│   ├── insert_rooms_hotel.py
│   └── reservation_details.py
│
├── docs/
│   └── erd.png
│
└── README.md
```
---

## 📊 ERD Diagram

The system is supported by a detailed Entity Relationship Diagram (ERD)  
showing all entities, relationships, and keys.

---

## 🎯 Learning Outcomes

Through this project, I gained:

- Strong understanding of database design  
- Experience with advanced SQL features  
- Ability to implement real-world business logic  
- Backend-oriented system thinking  
- Data simulation and testing techniques  

---

## 🔮 Future Improvements

- Add indexing for performance optimization  
- Implement stored procedures  
- Build REST API (Node.js / Flask)  
- Connect to a full web application  

---

## 👨‍💻 Author

**Abdulrahman Mohamed Gaber**  
Software Engineering & Cybersecurity Enthusiast  

GitHub: https://github.com/Bodo-g

---

## ⭐ Final Note

This project goes beyond basic database design and represents a complete backend-oriented system prototype with real-world logic and scalability considerations.

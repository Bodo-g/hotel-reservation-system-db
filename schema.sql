CREATE DATABASE hotel_booking_system;
USE hotel_booking_system;


CREATE TABLE Hotel (
    Hotel_ID INT AUTO_INCREMENT PRIMARY KEY,
    Hotel_Name VARCHAR(100) NOT NULL,
    Location VARCHAR(100),
    Address VARCHAR(200),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);





CREATE TABLE Customer (
    Cust_ID INT AUTO_INCREMENT PRIMARY KEY,
    F_Name VARCHAR(50) NOT NULL,
    L_Name VARCHAR(50) NOT NULL,
    Email VARCHAR(100) UNIQUE NOT NULL,
    Mobile_no VARCHAR(20) NOT NULL,
    DOB DATE NOT NULL,
    Nationality VARCHAR(50),
    Address VARCHAR(200),
    City VARCHAR(50),
    State VARCHAR(50),
    Pin_code VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT chk_mobile CHECK (Mobile_no REGEXP '^[0-9]{10,15}$')
);
DELIMITER $$
CREATE TRIGGER check_dob_before_insert
BEFORE INSERT ON Customer
FOR EACH ROW
BEGIN
    IF NEW.DOB > CURDATE() THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'DOB cannot be in the future';
    END IF;
END$$
DELIMITER ;
CREATE TABLE Reservation (
    Reservation_ID INT AUTO_INCREMENT PRIMARY KEY,
    Cust_ID INT,
    Hotel_ID INT,
    Start_Date DATETIME,
    End_Date DATETIME,
    Status VARCHAR(50) NOT NULL DEFAULT 'Confirmed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (Cust_ID) REFERENCES Customer(Cust_ID),
    FOREIGN KEY (Hotel_ID) REFERENCES Hotel(Hotel_ID)
);





DELIMITER $$
CREATE TRIGGER check_reservation_dates_before_insert
BEFORE INSERT ON Reservation
FOR EACH ROW
BEGIN
    IF NEW.End_Date <= NEW.Start_Date THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'End date must be after start date';
    END IF;
END$$
CREATE TRIGGER check_reservation_dates_before_update
BEFORE UPDATE ON Reservation
FOR EACH ROW
BEGIN
    IF NEW.End_Date <= NEW.Start_Date THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'End date must be after start date';
    END IF;
END$$
DELIMITER ;
CREATE TABLE Room (
    Room_ID INT AUTO_INCREMENT PRIMARY KEY,
    Room_No INT NOT NULL,
    Hotel_ID INT NOT NULL,
    Room_Type VARCHAR(50) NOT NULL,
    Price DECIMAL(10,2) NOT NULL CHECK (Price >= 0),
    Is_Available BOOLEAN DEFAULT TRUE,
    Floor INT,
    Wing CHAR(1),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (Hotel_ID) REFERENCES Hotel(Hotel_ID),
    UNIQUE(Room_No, Hotel_ID)  
);
CREATE TABLE Reservation_Details (
    Reservation_ID INT,
    Room_ID INT,
    PRIMARY KEY (Reservation_ID, Room_ID),
    FOREIGN KEY (Reservation_ID) REFERENCES Reservation(Reservation_ID),
    FOREIGN KEY (Room_ID) REFERENCES Room(Room_ID)
);
DELIMITER $$
CREATE TRIGGER prevent_double_booking
BEFORE INSERT ON Reservation_Details
FOR EACH ROW
BEGIN
    DECLARE roomStart DATETIME;
    DECLARE roomEnd DATETIME;
    SELECT Start_Date, End_Date INTO roomStart, roomEnd
    FROM Reservation
    WHERE Reservation_ID = NEW.Reservation_ID;
    IF EXISTS (
        SELECT 1
        FROM Reservation_Details rd
        JOIN Reservation r ON r.Reservation_ID = rd.Reservation_ID
        WHERE rd.Room_ID = NEW.Room_ID
        AND (
            (roomStart BETWEEN r.Start_Date AND r.End_Date) OR
            (roomEnd BETWEEN r.Start_Date AND r.End_Date) OR
            (roomStart <= r.Start_Date AND roomEnd >= r.End_Date)
        )
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Room already booked for the selected period';
    END IF;
END$$
DELIMITER ;
CREATE TABLE Payment (
    Payment_ID INT AUTO_INCREMENT PRIMARY KEY,
    Reservation_ID INT,
    Payment_amount DECIMAL(10,2) NOT NULL CHECK (Payment_amount >= 0),
    Payment_date DATE,
    Payment_for VARCHAR(100),
    Payment_method VARCHAR(50) NOT NULL,
    Currency VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (Reservation_ID) REFERENCES Reservation(Reservation_ID)
);
CREATE TABLE Room_Pricing (
    Room_No INT,
    Hotel_ID INT,
    Start_Date DATE,
    End_Date DATE,
    Price DECIMAL(10,2),
    PRIMARY KEY (Room_No, Hotel_ID, Start_Date),
    FOREIGN KEY (Room_No, Hotel_ID) REFERENCES Room(Room_No, Hotel_ID),
    CHECK (End_Date > Start_Date)
);
CREATE TABLE Employee (
    Emp_ID INT AUTO_INCREMENT PRIMARY KEY,
    Emp_Name VARCHAR(100) NOT NULL,
    Job_Description VARCHAR(100),
    Mobile_No VARCHAR(20),
    Address VARCHAR(200),
    Hotel_ID INT,
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Updated_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (Hotel_ID) REFERENCES Hotel(Hotel_ID)
);
ALTER TABLE Customer AUTO_INCREMENT = 1;
CREATE TABLE Supervisor (
    Emp_ID INT PRIMARY KEY,
    Department VARCHAR(100) NOT NULL,
    Start_Date DATE,
    FOREIGN KEY (Emp_ID) REFERENCES Employee(Emp_ID) ON DELETE CASCADE
);
CREATE TABLE Accountant (
    Emp_ID INT PRIMARY KEY,
    Certification VARCHAR(100) NOT NULL,
    Work_Hours VARCHAR(50),
    FOREIGN KEY (Emp_ID) REFERENCES Employee(Emp_ID) ON DELETE CASCADE
);
CREATE TABLE Waiter (
    Emp_ID INT PRIMARY KEY,
    Shift ENUM('Morning', 'Evening', 'Night') NOT NULL,
    Section_Assigned VARCHAR(100),
    FOREIGN KEY (Emp_ID) REFERENCES Employee(Emp_ID) ON DELETE CASCADE
);
CREATE TABLE Cleaner (
    Emp_ID INT PRIMARY KEY,
    Cleaning_Area VARCHAR(100),
    Shift_Start TIME,
    Shift_End TIME,
    FOREIGN KEY (Emp_ID) REFERENCES Employee(Emp_ID) ON DELETE CASCADE
);
CREATE TABLE Bellboy (
    Emp_ID INT PRIMARY KEY,
    Luggage_Capacity INT CHECK (Luggage_Capacity >= 0),
    Floor_Assigned INT CHECK (Floor_Assigned >= 0),
    FOREIGN KEY (Emp_ID) REFERENCES Employee(Emp_ID) ON DELETE CASCADE
);
CREATE TABLE Housekeeping (
    Emp_ID INT PRIMARY KEY,
    Floor_Assigned INT CHECK (Floor_Assigned >= 0),
    Tools_Assigned TEXT,
    FOREIGN KEY (Emp_ID) REFERENCES Employee(Emp_ID) ON DELETE CASCADE
);
CREATE TABLE Manager (
    Emp_ID INT PRIMARY KEY,
    Department VARCHAR(100) DEFAULT 'General Management',
    Experience_Years INT DEFAULT 5,
    FOREIGN KEY (Emp_ID) REFERENCES Employee(Emp_ID) ON DELETE CASCADE
);
CREATE TABLE Receptionist (
    Emp_ID INT PRIMARY KEY,
    Shift ENUM('Morning', 'Evening', 'Night') DEFAULT 'Morning',
    Language_Skills VARCHAR(100) DEFAULT 'English',
    FOREIGN KEY (Emp_ID) REFERENCES Employee(Emp_ID) ON DELETE CASCADE
);
CREATE TABLE Securety (
    Emp_ID INT PRIMARY KEY,
    Shift ENUM('Day', 'Night') DEFAULT 'Day',
    Weapon_License BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (Emp_ID) REFERENCES Employee(Emp_ID) ON DELETE CASCADE
);
CREATE TABLE Chef (
    Emp_ID INT PRIMARY KEY,
    Specialty VARCHAR(100) DEFAULT 'International Cuisine',
    Experience_Years INT DEFAULT 3,
    FOREIGN KEY (Emp_ID) REFERENCES Employee(Emp_ID) ON DELETE CASCADE
);
CREATE TABLE Feedback (
    Feedback_ID INT AUTO_INCREMENT PRIMARY KEY,
    Cust_ID INT,
    Hotel_ID INT,
    Rating INT CHECK (Rating BETWEEN 1 AND 5),
    Comments VARCHAR(500) NOT NULL DEFAULT 'No comments provided',
    Feedback_Date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (Cust_ID) REFERENCES Customer(Cust_ID),
    FOREIGN KEY (Hotel_ID) REFERENCES Hotel(Hotel_ID)
);
CREATE TABLE Service (
    Service_ID INT AUTO_INCREMENT PRIMARY KEY,
    Service_Name VARCHAR(100),
    Description TEXT,
    Price DECIMAL(10,2)
);
ALTER TABLE Service
ADD CONSTRAINT unique_service_name UNIQUE (Service_Name);
CREATE TABLE Invoice (
    Invoice_ID INT AUTO_INCREMENT PRIMARY KEY,
    Reservation_ID INT NOT NULL,
    Invoice_Date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Total_Amount DECIMAL(10,2) NOT NULL CHECK (Total_Amount >= 0),
    FOREIGN KEY (Reservation_ID) REFERENCES Reservation(Reservation_ID),
    UNIQUE (Reservation_ID)
);
CREATE TABLE Invoice_Details (
    Invoice_Detail_ID INT AUTO_INCREMENT PRIMARY KEY,
    Invoice_ID INT NOT NULL,
    Service_ID INT NOT NULL,
    Quantity INT NOT NULL CHECK (Quantity > 0),
    Line_Total DECIMAL(10,2) NOT NULL CHECK (Line_Total >= 0),
    FOREIGN KEY (Invoice_ID) REFERENCES Invoice(Invoice_ID),
    FOREIGN KEY (Service_ID) REFERENCES Service(Service_ID)
);

CREATE TABLE Room_Service_Assignment (
    Assignment_ID INT AUTO_INCREMENT PRIMARY KEY,
    Emp_ID INT NOT NULL,
    Room_No INT NOT NULL,
    Hotel_ID INT NOT NULL,
    Assigned_Date DATE NOT NULL,
    Task_Description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (Emp_ID) REFERENCES Employee(Emp_ID), -- تعديل هنا
    FOREIGN KEY (Room_No, Hotel_ID) REFERENCES Room(Room_No, Hotel_ID),
    UNIQUE (Room_No, Hotel_ID, Assigned_Date)
);
DELIMITER $$

CREATE TRIGGER distribute_employee_by_job
AFTER INSERT ON Employee
FOR EACH ROW
BEGIN
  IF NEW.Job_Description = 'Housekeeping' THEN
    INSERT INTO Housekeeping (Emp_ID, Floor_Assigned, Tools_Assigned)
    VALUES (NEW.Emp_ID, 1, 'Standard Tools');

  ELSEIF NEW.Job_Description = 'Waiter' THEN
    INSERT INTO Waiter (Emp_ID, Shift, Section_Assigned)
    VALUES (NEW.Emp_ID, 'Morning', 'Dining Area');

  ELSEIF NEW.Job_Description = 'Bellboy' THEN
    INSERT INTO Bellboy (Emp_ID, Luggage_Capacity, Floor_Assigned)
    VALUES (NEW.Emp_ID, 3, 1);

  ELSEIF NEW.Job_Description = 'Cleaner' THEN
    INSERT INTO Cleaner (Emp_ID, Cleaning_Area, Shift_Start, Shift_End)
    VALUES (NEW.Emp_ID, 'All Floors', '08:00:00', '16:00:00');

  ELSEIF NEW.Job_Description = 'Accountant' THEN
    INSERT INTO Accountant (Emp_ID, Certification, Work_Hours)
    VALUES (NEW.Emp_ID, 'CPA', 'Full-time');

  ELSEIF NEW.Job_Description = 'Supervisor' THEN
    INSERT INTO Supervisor (Emp_ID, Department, Start_Date)
    VALUES (NEW.Emp_ID, 'General', CURDATE());

  ELSEIF NEW.Job_Description = 'Manager' THEN
    INSERT INTO Manager (Emp_ID, Department, Experience_Years)
    VALUES (NEW.Emp_ID, 'General Management', 5);

  ELSEIF NEW.Job_Description = 'Receptionist' THEN
    INSERT INTO Receptionist (Emp_ID, Shift, Language_Skills)
    VALUES (NEW.Emp_ID, 'Morning', 'English');

  ELSEIF NEW.Job_Description = 'Securety' THEN
    INSERT INTO Securety (Emp_ID, Shift, Weapon_License)
    VALUES (NEW.Emp_ID, 'Day', FALSE);

  ELSEIF NEW.Job_Description = 'Chef' THEN
    INSERT INTO Chef (Emp_ID, Specialty, Experience_Years)
    VALUES (NEW.Emp_ID, 'International Cuisine', 3);
  END IF;
END$$

DELIMITER ;


DELIMITER $$

CREATE TRIGGER set_assigned_date
BEFORE INSERT ON Room_Service_Assignment
FOR EACH ROW
BEGIN
    IF NEW.Assigned_Date IS NULL THEN
        SET NEW.Assigned_Date = CURRENT_DATE;
    END IF;
END$$

DELIMITER ;
CREATE TABLE Employee_Login (
		Login_ID INT AUTO_INCREMENT PRIMARY KEY,
		Emp_ID INT UNIQUE, 
		Username VARCHAR(50) NOT NULL UNIQUE,
		Password_Hash VARCHAR(100) NOT NULL, 
		Role ENUM('admin', 'receptionist', 'room_service', 'manager') NOT NULL DEFAULT 'receptionist',
		Is_Active BOOLEAN DEFAULT TRUE,
		Password_Updated_At DATETIME DEFAULT CURRENT_TIMESTAMP,
		created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		FOREIGN KEY (Emp_ID) REFERENCES Employee(Emp_ID)
);
ALTER TABLE Employee_Login
MODIFY COLUMN Role ENUM(
  'admin', 'receptionist', 'room_service', 'manager',
  'Housekeeping', 'Cleaner', 'Waiter', 'Bellboy',
  'Chef', 'Securety', 'Accountant', 'Supervisor'
) NOT NULL;
CREATE TABLE Customer_Login (
    Login_ID INT AUTO_INCREMENT PRIMARY KEY,
    Cust_ID INT UNIQUE, 
    Username VARCHAR(50) NOT NULL UNIQUE,
    Password_Hash CHAR(64) NOT NULL, 
    Is_Active BOOLEAN DEFAULT TRUE,
    Password_Updated_At DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (Cust_ID) REFERENCES Customer(Cust_ID)
);
CREATE TABLE Login_History_Employee (
    Login_History_ID INT AUTO_INCREMENT PRIMARY KEY,
    Emp_ID INT NOT NULL,
    Username VARCHAR(50) NOT NULL,
    Login_Time DATETIME DEFAULT CURRENT_TIMESTAMP,
    Logout_Time DATETIME,
    IP_Address VARCHAR(45), 
    Device_Info VARCHAR(255),
    Status ENUM('success', 'failure') DEFAULT 'success',
    FOREIGN KEY (Emp_ID) REFERENCES Employee(Emp_ID)
);
CREATE TABLE Login_History_Customer (
    Login_History_ID INT AUTO_INCREMENT PRIMARY KEY,
    Cust_ID INT NOT NULL,
    Username VARCHAR(50) NOT NULL,
    Login_Time DATETIME DEFAULT CURRENT_TIMESTAMP,
    Logout_Time DATETIME,
    IP_Address VARCHAR(45),
    Device_Info VARCHAR(255),
    Status ENUM('success', 'failure') DEFAULT 'success',
    FOREIGN KEY (Cust_ID) REFERENCES Customer(Cust_ID)
);
CREATE TABLE Logs (
    Log_ID INT AUTO_INCREMENT PRIMARY KEY,
    Emp_ID INT,                             
    Module VARCHAR(100),                     
    Action VARCHAR(255),                     
    Log_Date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (Emp_ID) REFERENCES Employee(Emp_ID)
);
CREATE TABLE Cancellation (
    Cancellation_ID INT AUTO_INCREMENT PRIMARY KEY,
    Reservation_ID INT UNIQUE,
    Cancel_Date DATETIME DEFAULT CURRENT_TIMESTAMP,
    Reason TEXT,
    Cancelled_By VARCHAR(100), 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (Reservation_ID) REFERENCES Reservation(Reservation_ID)
);
DELIMITER $$

CREATE TRIGGER update_reservation_status_after_cancellation
AFTER INSERT ON Cancellation
FOR EACH ROW
BEGIN
    UPDATE Reservation
    SET Status = 'Cancelled'
    WHERE Reservation_ID = NEW.Reservation_ID;
END$$

DELIMITER ;
CREATE TABLE Service_Request (
    Request_ID INT AUTO_INCREMENT PRIMARY KEY,
    Cust_ID INT,
    Room_No INT,
    Hotel_ID INT,
    Service_ID INT,  
    Request_Date DATETIME DEFAULT CURRENT_TIMESTAMP,
    Status ENUM('Pending', 'In Progress', 'Completed', 'Rejected') DEFAULT 'Pending',
    Notes TEXT,
    FOREIGN KEY (Cust_ID) REFERENCES Customer(Cust_ID),
    FOREIGN KEY (Room_No, Hotel_ID) REFERENCES Room(Room_No, Hotel_ID),
    FOREIGN KEY (Service_ID) REFERENCES Service(Service_ID)
);
CREATE TABLE Room_Maintenance (
    Maintenance_ID INT AUTO_INCREMENT PRIMARY KEY,
    Room_No INT,
    Hotel_ID INT,
    Description TEXT,
    Start_Date DATETIME NOT NULL,
    End_Date DATETIME,
    Status ENUM('Scheduled', 'In Progress', 'Completed') DEFAULT 'Scheduled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CHECK (End_Date IS NULL OR End_Date > Start_Date),
    FOREIGN KEY (Room_No, Hotel_ID) REFERENCES Room(Room_No, Hotel_ID)
);

DELIMITER $$

CREATE TRIGGER delete_reservation_on_cancel
AFTER INSERT ON Cancellation
FOR EACH ROW
BEGIN
    DELETE FROM Reservation
    WHERE Reservation_ID = NEW.Reservation_ID;
END$$

DELIMITER ;



INSERT INTO Hotel (Hotel_Name, Location, Address) VALUES
('Grand Palace', 'Cairo, Egypt', '123 Nile St, Garden City'),
('Grand Palace', 'Alexandria, Egypt', '456 Beach Rd, Corniche'),
('Grand Palace', 'Marrakech, Morocco', '789 Atlas Rd, Medina'),
('Grand Palace', 'Riyadh, Saudi Arabia', '12 King Fahd Rd, Olaya'),
('Grand Palace', 'Dubai, UAE', '88 Sheikh Zayed Rd, Downtown'),
('Grand Palace', 'Doha, Qatar', '34 Corniche St, West Bay'),
('Grand Palace', 'Amman, Jordan', '21 Rainbow St, Jabal Amman'),
('Grand Palace', 'Beirut, Lebanon', '99 Bliss St, Ras Beirut'),
('Grand Palace', 'Istanbul, Turkey', '100 Taksim Sq, Beyoğlu'),
('Grand Palace', 'Almaty, Kazakhstan', '55 Abay Ave, Medeu District');

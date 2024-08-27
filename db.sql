create database chude8;
use chude8;

CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       username VARCHAR(50) NOT NULL UNIQUE,
                       password VARCHAR(255) NOT NULL,
                       role ENUM('user', 'employee', 'doctor', 'cashier', 'nurse', 'manager') NOT NULL,
                       full_name VARCHAR(100),
                       phone VARCHAR(20),
                       email VARCHAR(100)
);
INSERT INTO users (username, password, role, full_name, phone, email) VALUES
                                                                          ('admin', '1', 'manager', 'Nguyen Van A', '0901234567', 'admin@example.com'),
                                                                          ('employee1', '1', 'employee', 'Le Thi B', '0901234568', 'employee1@example.com'),
                                                                          ('doctor1', '1', 'doctor', 'Tran Van C', '0901234569', 'doctor1@example.com'),
                                                                          ('cashier1', '1', 'cashier', 'Pham Thi D', '0901234570', 'cashier1@example.com'),
                                                                          ('nurse1', '1', 'nurse', 'Nguyen Thi E', '0901234571', 'nurse1@example.com'),
                                                                          ('user1', '1', 'user', 'Hoang Van F', '0901234572', 'user1@example.com');


CREATE TABLE services (
                          id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                          name VARCHAR(255) NOT NULL,
                          price DECIMAL(10, 2) NOT NULL
);

INSERT INTO services (name, price) VALUES ('Tiêm phòng cúm', 200000);
INSERT INTO services (name, price) VALUES ('Tiêm phòng viêm gan B', 300000);
INSERT INTO services (name, price) VALUES ('Tiêm phòng uốn ván', 150000);

CREATE TABLE vaccination_records (
                                     id INT AUTO_INCREMENT PRIMARY KEY,
                                     fullname nvarchar(200),
                                     gender ENUM('male', 'female', 'other') NOT NULL, -- Giới tính của bệnh nhân
                                     dob DATE NOT NULL,                         -- Ngày sinh của bệnh nhân
                                     address VARCHAR(255) NOT NULL,             -- Địa chỉ của bệnh nhân
                                     phone VARCHAR(15) NOT NULL,                -- Số điện thoại của bệnh nhân
                                     email VARCHAR(100) NOT NULL,               -- Email của bệnh nhân
                                     doctor_id INT,
                                     service_id INT,
                                     appointment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                     status ENUM('pending', 'paid', 'vaccinated') NOT NULL DEFAULT 'pending'
);






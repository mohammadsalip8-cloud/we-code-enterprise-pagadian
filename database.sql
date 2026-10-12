CREATE TABLE rooms (
  id INT AUTO_INCREMENT PRIMARY KEY,
  room_number VARCHAR(20) NOT NULL,
  room_type VARCHAR(50),
  capacity INT DEFAULT 2,
  monthly_rate DECIMAL(10,2),
  status ENUM('Vacant','Occupied','Full') DEFAULT 'Vacant'
);

CREATE TABLE boarders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  qr_code VARCHAR(100) UNIQUE,
  fullname VARCHAR(100),
  contact VARCHAR(20),
  parent_contact VARCHAR(20),
  room_id INT,
  photo VARCHAR(255),
  date_registered DATE,
  FOREIGN KEY (room_id) REFERENCES rooms(id)
);

CREATE TABLE billing (
  id INT AUTO_INCREMENT PRIMARY KEY,
  boarder_id INT,
  month_year VARCHAR(20),
  rent DECIMAL(10,2),
  water DECIMAL(10,2) DEFAULT 0,
  electricity DECIMAL(10,2) DEFAULT 0,
  total DECIMAL(10,2),
  status ENUM('Paid','Unpaid','Overdue') DEFAULT 'Unpaid',
  date_paid DATE NULL,
  FOREIGN KEY (boarder_id) REFERENCES boarders(id)
);

CREATE TABLE logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  boarder_id INT,
  log_type ENUM('IN','OUT'),
  log_time DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (boarder_id) REFERENCES boarders(id)
);

INSERT INTO rooms (room_number, room_type, capacity, monthly_rate) VALUES
('Room 101', 'Bed Spacer', 4, 1500),
('Room 102', 'Bed Spacer', 4, 1500),
('Room 201', 'Single Room', 2, 2500);
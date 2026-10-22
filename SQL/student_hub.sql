-- ============================================
-- Practical 8: MySQL Schema Design, ER Model,
-- PDO Connectivity, and Prepared Statements
-- ============================================

-- Create Database
CREATE DATABASE IF NOT EXISTS student_hub;
USE student_hub;

-- =====================
-- Table 1: departments
-- =====================
CREATE TABLE IF NOT EXISTS departments (
    dept_id INT AUTO_INCREMENT PRIMARY KEY,
    dept_name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =====================
-- Table 2: students
-- =====================
CREATE TABLE IF NOT EXISTS students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(15) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    dept_id INT NOT NULL,
    semester INT DEFAULT 1,
    year_of_study INT DEFAULT 1,
    cgpa DECIMAL(3,1) DEFAULT 0.0,
    role ENUM('student', 'admin') DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (dept_id) REFERENCES departments(dept_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================
-- Table 3: events
-- =====================
CREATE TABLE IF NOT EXISTS events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    event_type ENUM('Academic', 'Cultural', 'Sports', 'Workshop', 'Technical') NOT NULL,
    event_date DATE NOT NULL,
    venue VARCHAR(150),
    time VARCHAR(50),
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES students(student_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- =====================
-- Table 4: registrations (students register for events)
-- =====================
CREATE TABLE IF NOT EXISTS registrations (
    reg_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    event_id INT NOT NULL,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(event_id) ON DELETE CASCADE,
    UNIQUE KEY unique_registration (student_id, event_id)
) ENGINE=InnoDB;

-- =====================
-- Stored Procedure (Advanced)
-- =====================
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS GetStudentsByDept(IN p_dept_id INT)
BEGIN
    SELECT s.student_id, s.name, s.email, s.semester, s.cgpa, d.dept_name
    FROM students s
    JOIN departments d ON s.dept_id = d.dept_id
    WHERE s.dept_id = p_dept_id
    ORDER BY s.name;
END //
DELIMITER ;

-- =====================
-- Seed Data: Departments
-- =====================
INSERT INTO departments (dept_name) VALUES
('Computer Science'),
('Information Technology'),
('Electronics'),
('Mechanical'),
('Civil');

-- =====================
-- Seed Data: Students
-- =====================
INSERT INTO students (name, email, phone, password_hash, dept_id, semester, year_of_study, cgpa) VALUES
('Arjun Patel', 'arjun@student.edu.in', '9876543210', SHA2('pass123', 256), 1, 5, 3, 8.5),
('Priya Sharma', 'priya@student.edu.in', '9876543211', SHA2('pass123', 256), 1, 5, 3, 9.1),
('Rahul Verma', 'rahul@student.edu.in', '9876543212', SHA2('pass123', 256), 2, 3, 2, 7.8),
('Sneha Iyer', 'sneha@student.edu.in', '9876543213', SHA2('pass123', 256), 3, 7, 4, 8.2),
('Amit Kumar', 'amit@student.edu.in', '9876543214', SHA2('pass123', 256), 4, 1, 1, 7.5),
('Kavita Reddy', 'kavita@student.edu.in', '9876543215', SHA2('pass123', 256), 2, 5, 3, 8.9),
('Rohan Desai', 'rohan@student.edu.in', '9876543216', SHA2('pass123', 256), 5, 3, 2, 6.8);

-- =====================
-- Seed Data: Events
-- =====================
INSERT INTO events (title, description, event_type, event_date, venue, time, created_by) VALUES
('Tech Fest 2026', 'Annual technology festival with project demos and talks.', 'Technical', '2026-11-15', 'Main Auditorium', '10:00 AM', 1),
('Coding Hackathon', '24-hour coding competition for all departments.', 'Technical', '2026-11-20', 'CS Lab', '9:00 AM', 2),
('Cultural Night', 'Evening of dance, drama, and music performances.', 'Cultural', '2026-12-05', 'Open Amphitheatre', '6:00 PM', 3),
('Sports Day', 'Annual inter-department sports competition.', 'Sports', '2026-12-10', 'University Ground', '8:00 AM', NULL),
('AI Workshop', 'Hands-on workshop on machine learning basics.', 'Workshop', '2026-11-25', 'Seminar Hall', '2:00 PM', 1);

-- =====================
-- Seed Data: Registrations
-- =====================
INSERT INTO registrations (student_id, event_id) VALUES
(1, 1), (1, 2), (2, 1), (2, 3), (3, 2),
(4, 3), (5, 4), (6, 1), (6, 5), (7, 4);

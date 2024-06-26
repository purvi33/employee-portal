
USE employee_management3;

-- Create users table
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `dob` date NOT NULL,
  `pob` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create admins table
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `dob` date NOT NULL,
  `pob` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    project_name VARCHAR(255) NOT NULL,
    task_name VARCHAR(255) NOT NULL,
    task_description TEXT,
    task_assigned_by VARCHAR(255),
    start_date DATE,
    end_date DATE,
    priority ENUM('important', 'urgent', 'low priority') NOT NULL,
    admin_comments TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

ALTER TABLE tasks
  ADD COLUMN admin_id INT,
  ADD FOREIGN KEY (admin_id) REFERENCES admins(id);
USE employee_management3;

-- Create attendance table
CREATE TABLE `attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('present', 'absent', 'leave') NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create leave_applications table
CREATE TABLE `leave_applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `leave_type` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `comments` text,
  `status` enum('pending', 'approved', 'denied') DEFAULT 'pending',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

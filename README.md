🏛 Civic Grievance Portal | Advanced Civic Issue Management System

[
]
[
]
[
]
[
]
[
]
[
]
[
]

🌐 Live Demo

🚀 Experience the platform in action:
👉 municipal-complaint.kesug.com

🚀 Project Overview

Civic Grievance Portal is an advanced civic issue management system designed to streamline citizen complaints and municipal administration.

It provides a secure, role-based platform enabling:

Citizens to submit complaints with real-time tracking

Admins to monitor, update, and resolve complaints efficiently

Automated notifications and OTP-based authentication

Data-driven insights via analytics for municipal authorities

Built with security, scalability, and transparency in mind for modern civic governance.

🎯 Core Functionalities
👤 Citizen Panel

Register & login securely

Submit civic complaints with description & attachments

Track complaint status in real-time

Provide feedback on resolved issues

View personal complaint history

🛠 Admin Panel

Secure admin authentication

View, update, and delete complaints

Change status of complaints (Pending, In Progress, Resolved)

Send notifications to citizens via email (PHPMailer)

View analytics & complaint statistics

Full database control

🏗 System Architecture

Frontend (HTML, CSS, JavaScript)
↓
Backend (PHP)
↓
Database (MySQL)
↓
SMTP Services (PHPMailer)

🔐 Security & Access Control

Role-based authentication (Citizen / Admin)

OTP-based password reset

Secure session handling

CSRF-safe form handling

File upload validation for attachments

🧩 Modules Implemented
Module	Description
👤 User Authentication	Secure Citizen & Admin Login
📝 Complaint Management	Submit, update, and resolve civic complaints
🔔 Notifications	Email alerts for status updates
📊 Analytics	Admin view of complaints statistics
📨 Feedback	Citizens can submit post-resolution feedback
📁 Media Uploads	Attach images or documents to complaints
🛠 Technology Stack
Backend

PHP 8+

PHPMailer

Database

MySQL

Frontend

HTML5

CSS3

JavaScript

Tools & Infrastructure

XAMPP

Composer

GitHub

📁 Project Structure
civic-grievance-portal/
├── admin_dashboard.php
├── user_dashboard.php
├── new_complaint.php
├── view_complaint.php
├── view_complaints.php
├── edit_complaint.php
├── delete_complaint.php
├── update_status.php
├── change_status.php
├── send_notification.php
├── submit_feedback.php
├── submit_feedback_handler.php
├── forgot_password.php
├── reset_password.php
├── verify_otp.php
├── login.php
├── logout.php
├── register.php
├── db.php
├── complaints.sql
├── assets/          # CSS, JS, images
├── uploads/         # Media uploads
├── api/             # Any API endpoints
├── PHPMailer-master/ # SMTP library
└── README.md
⚙ Installation & Setup
1️⃣ Clone Repository
git clone https://github.com/joyswapnilrajparadeshi-cmd/civic-grievance-portal.git
2️⃣ Import Database

Create Database: community_complaints

Import File: complaints.sql

3️⃣ Configure Database

Edit db.php:

$server = "localhost";  
$user   = "root";  
$pass   = "";  
$db     = "community_complaints";
4️⃣ Start Server

Start Apache & MySQL using XAMPP

5️⃣ Access System

Open in your browser:
http://localhost/community_complaint_system/

👨‍💻 Author

Paradeshi Joy Swapnil Raj
B.Tech CSE | Full Stack Developer | AI & ML Enthusiast

📧 Email: joyswapnilrajparadeshi@gmail.com

🌐 Portfolio: https://joyswapnilrajparadeshi-cmd.github.io/portfolio/

⭐ Support
If you find this project useful, please star ⭐ the repository and support my work!

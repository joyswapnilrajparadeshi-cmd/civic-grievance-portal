🏛️ Civic Grievance Portal | Advanced Community Complaint System

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

Civic Grievance Portal is an advanced, full-stack community complaint system designed to streamline the reporting, management, and resolution of civic issues for municipalities.

It provides a secure, role-based platform for:

Citizens to submit and track complaints

Municipal administrators to manage and resolve grievances

Automated notifications and email alerts

Analytics and reporting for efficient governance

Built with production-grade scalability, security, and modern UI for real-world deployment.

🎯 Core Functionalities
👤 Citizen / User Panel

Secure Registration & Login

Submit new civic complaints with details and images

Track complaint status in real-time

Receive notifications for updates

Submit feedback for resolved complaints

🛠 Admin Panel

Secure Admin Authentication

View, approve, update, and resolve complaints

Change status and priority of complaints

Send manual notifications to users via email

Dashboard with analytics and complaint summaries

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

Role-based authentication (Admin / Citizen)

Secure session management

OTP-based password reset

CSRF-safe forms

File upload security

🧩 Modules Implemented
Module	Description
👤 User Authentication	Secure login & registration for Citizens and Admin
📝 Complaints	Submit, view, and manage complaints
🔔 Notifications	Admin-triggered email alerts for status updates
📊 Analytics	View statistics and complaint resolution reports
🛠 Admin Controls	Update complaint status, manage users, and resolve issues
📩 Feedback	Citizens can submit feedback after resolution
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

🖼 Platform Screenshots
🏠 Landing Page / Citizen Dashboard
<img width="1200" alt="Landing Page" src="uploads/landing_page.png" />
📝 Submit Complaint Form
<img width="1200" alt="Submit Complaint" src="uploads/new_complaint.png" />
🛠 Admin Dashboard / Complaint Management
<img width="1200" alt="Admin Dashboard" src="uploads/admin_dashboard.png" />
🔔 Notifications & Feedback
<img width="1200" alt="Notifications & Feedback" src="uploads/notifications_feedback.png" />
📊 Analytics & Reports
<img width="1200" alt="Analytics" src="uploads/analytics.png" />
📁 Project Structure
civic-grievance-portal/
├── admin_dashboard.php
├── user_dashboard.php
├── PHPMailer-master/          # SMTP library
├── api/                       # APIs (if any)
├── assets/                     # CSS, JS, Images
├── uploads/                    # User-uploaded files
├── db.php                      # Database config
├── complaints.sql              # Database schema
├── new_complaint.php
├── view_complaints.php
├── update_status.php
├── send_notification.php
├── reset_password.php
├── forgot_password.php
├── verify_otp.php
└── README.md
⚙ Installation & Setup
1️⃣ Clone Repository
git clone https://github.com/joyswapnilrajparadeshi-cmd/civic-grievance-portal.git
2️⃣ Import Database

Create Database: civic_grievances

Import File: complaints.sql

3️⃣ Configure Database

Edit db.php:

$server = "localhost";
$user   = "root";
$pass   = "";
$db     = "civic_grievances";
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

⭐ Support:
If you find this project useful, please star ⭐ the repository and support my work!

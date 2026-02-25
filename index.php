<?php
session_start(); // Start the session at the very beginning
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Complaint System</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background: linear-gradient(to bottom, #e8f5e9, #a5d6a7);
        }

        /* Header */
        header {
            background: linear-gradient(to right, #2e7d32, #66bb6a);
            color: white;
            padding: 1.5rem 0;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        header h1 {
            font-size: 2.5rem;
            margin: 0;
            letter-spacing: 1.5px;
        }

        /* Navigation Bar */
        nav {
            display: flex;
            justify-content: center;
            background: #004d40;
            padding: 0.75rem 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 1rem;
            font-size: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: background 0.3s ease, color 0.3s ease;
        }

        nav a:hover {
            background: #66bb6a;
            color: #004d40;
        }

        /* Hero Section */
        .hero {
            text-align: center;
            background: url('assets/hero-bg.jpg') no-repeat center center/cover;
            color: white;
            padding: 6rem 2rem;
            position: relative;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
        }

        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
        }

        .hero a {
            background: #43a047;
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s;
        }

        .hero a:hover {
            background: #2e7d32;
            transform: translateY(-3px);
        }

        /* Features Section */
        .features {
            padding: 4rem 2rem;
            background: #f1f8e9;
            text-align: center;
        }

        .features h2 {
            font-size: 2rem;
            color: #2e7d32;
            margin-bottom: 2rem;
        }

        .features .feature {
            display: inline-block;
            width: 22%;
            margin: 1%;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .features .feature:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .features .feature h3 {
            color: #388e3c;
        }

        /* Testimonials Section */
        .testimonials {
            background: linear-gradient(to bottom, #81c784, #66bb6a);
            padding: 4rem 2rem;
            color: white;
            text-align: center;
        }

        .testimonials h2 {
            margin-bottom: 2rem;
            font-size: 2rem;
        }

        .testimonials p {
            font-size: 1.2rem;
            font-style: italic;
        }

        /* About Section */
        .about {
            padding: 4rem 2rem;
            background: #e8f5e9;
            text-align: center;
        }

        .about h2 {
            font-size: 2rem;
            color: #2e7d32;
        }

        /* Footer */
        footer {
            background: #004d40;
            color: white;
            text-align: center;
            padding: 1.5rem 0;
        }

        footer a {
            color: #80cbc4;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php
    $isLoggedIn = isset($_SESSION['user_id']);
    $isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    ?>

    <header>
        <h1>Municipal Community Complaint System</h1>
    </header>
    <nav>
        <a href="index.php">Home</a>
        <?php if ($isLoggedIn): ?>
            <?php if ($isAdmin): ?>
                <a href="admin_dashboard.php">Admin Dashboard</a>
            <?php else: ?>
                <a href="user_dashboard.php">User Dashboard</a>
            <?php endif; ?>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="register.php">Register</a>
            <a href="login.php">Login</a>
        <?php endif; ?>
        <a href="#about">About Us</a>
    </nav>

    <section class="hero">
        <h1>Welcome to the Municipal Community Complaint System</h1>
        <p>Your one-stop solution for tracking and resolving community issues.</p>
        <?php if ($isLoggedIn): ?>
            <a href="new_complaint.php">Submit a New Complaint</a>
        <?php else: ?>
            <a href="login.php">Log in to Submit a Complaint</a>
        <?php endif; ?>
    </section>

    <section class="features">
        <h2>Our Features</h2>
        <div>
            <div class="feature">
                <h3>Complaint Tracking</h3>
                <p>Monitor the status of your complaints in real-time.</p>
            </div>
            <div class="feature">
                <h3>Admin Dashboard</h3>
                <p>Powerful tools for managing and resolving complaints efficiently.</p>
            </div>
            <div class="feature">
                <h3>Analytics</h3>
                <p>Gain insights into the most common issues in your community.</p>
            </div>
            <div class="feature">
                <h3>Mobile-Friendly</h3>
                <p>Access the system seamlessly on any device.</p>
            </div>
        </div>
    </section>

    <section class="testimonials">
        <h2>User Testimonials</h2>
        <p>"This system has revolutionized how we handle community complaints!" - A Satisfied User</p>
        <p>"The analytics feature helps us address recurring issues efficiently." - Local Admin</p>
    </section>

    <section class="about" id="about">
        <h2>About Us</h2>
        <p>The Municipal Community Complaint System aims to create a streamlined process for resolving issues in your neighborhood.</p>
    </section>

    <footer>
        <p>&copy; 2025 Municipal Community Complaint System. All rights reserved.</p>
        <p>Contact us at <a href="mailto:support@ap.gov.in">contact@communitysystem.com</a></p>
    </footer>
</body>
</html>

<?php
session_start();
require 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rozee.pk Clone - Homepage</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background: linear-gradient(90deg, #007bff, #00c4ff);
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }
        .job-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin: 10px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        .job-card:hover {
            transform: translateY(-5px);
        }
        .categories {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .category {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .category:hover {
            background: #0056b3;
        }
        nav {
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 10px;
            background: #333;
        }
        nav a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        nav a:hover {
            color: #00c4ff;
        }
        @media (max-width: 768px) {
            .job-card, .category {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Rozee.pk Clone</h1>
        <p>Find Your Dream Job Today!</p>
    </header>
    <nav>
        <a href="#" onclick="window.location.href='index.php'">Home</a>
        <a href="#" onclick="window.location.href='search_jobs.php'">Search Jobs</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="#" onclick="window.location.href='profile.php'">Profile</a>
            <?php if ($_SESSION['user_type'] == 'employer'): ?>
                <a href="#" onclick="window.location.href='post_job.php'">Post Job</a>
            <?php else: ?>
                <a href="#" onclick="window.location.href='track_jobs.php'">Track Applications</a>
            <?php endif; ?>
            <a href="#" onclick="window.location.href='logout.php'">Logout</a>
        <?php else: ?>
            <a href="#" onclick="window.location.href='login.php'">Login</a>
            <a href="#" onclick="window.location.href='signup.php'">Signup</a>
        <?php endif; ?>
    </nav>
    <div class="container">
        <h2>Featured Jobs</h2>
        <?php
        $sql = "SELECT * FROM jobs ORDER BY created_at DESC LIMIT 5";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<div class='job-card'>";
            echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
            echo "<p>" . htmlspecialchars($row['company']) . " - " . htmlspecialchars($row['location']) . "</p>";
            echo "<p>" . htmlspecialchars($row['description']) . "</p>";
            echo "<a href='apply_job.php?job_id=" . $row['id'] . "'>Apply Now</a>";
            echo "</div>";
        }
        ?>
        <h2>Trending Categories</h2>
        <div class="categories">
            <div class="category">IT & Software</div>
            <div class="category">Marketing</div>
            <div class="category">Finance</div>
            <div class="category">Engineering</div>
        </div>
    </div>
</body>
</html>

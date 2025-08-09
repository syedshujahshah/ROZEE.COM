<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'employer') {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $company = $_POST['company'];
    $location = $_POST['location'];
    $salary = $_POST['salary'];
    $job_type = $_POST['job_type'];
    $category = $_POST['category'];
    $user_id = $_SESSION['user_id'];
    $sql = "INSERT INTO jobs (title, description, company, location, salary, job_type, category, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $title, $description, $company, $location, $salary, $job_type, $category, $user_id);
    if ($stmt->execute()) {
        echo "<script>window.location.href='index.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Job - Rozee.pk Clone</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }
        .post-job-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        h2 {
            color: #007bff;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        input, select, textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        @media (max-width: 768px) {
            .post-job-container {
                margin: 10px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="post-job-container">
        <h2>Post a Job</h2>
        <form method="POST">
            <input type="text" name="title" placeholder="Job Title" required>
            <textarea name="description" placeholder="Job Description" required></textarea>
            <input type="text" name="company" placeholder="Company Name" required>
            <input type="text" name="location" placeholder="Location" required>
            <input type="text" name="salary" placeholder="Salary" required>
            <select name="job_type" required>
                <option value="full-time">Full-Time</option>
                <option value="part-time">Part-Time</option>
                <option value="remote">Remote</option>
            </select>
            <select name="category" required>
                <option value="IT">IT & Software</option>
                <option value="Marketing">Marketing</option>
                <option value="Finance">Finance</option>
                <option value="Engineering">Engineering</option>
            </select>
            <button type="submit">Post Job</button>
        </form>
        <button onclick="window.location.href='index.php'">Back to Home</button>
    </div>
</body>
</html>

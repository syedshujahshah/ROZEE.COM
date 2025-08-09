<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'job_seeker') {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}
$job_id = $_GET['job_id'] ?? 0;
$sql = "SELECT * FROM jobs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$job = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $sql = "INSERT INTO applications (user_id, job_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $job_id);
    if ($stmt->execute()) {
        echo "<script>window.location.href='track_jobs.php';</script>";
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
    <title>Apply for Job - Rozee.pk Clone</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }
        .apply-container {
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
        .job-details {
            margin-bottom: 20px;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        button:hover {
            background: #0056b3;
        }
        @media (max-width: 768px) {
            .apply-container {
                margin: 10px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="apply-container">
        <h2>Apply for Job</h2>
        <div class="job-details">
            <h3><?php echo htmlspecialchars($job['title']); ?></h3>
            <p><?php echo htmlspecialchars($job['company']); ?> - <?php echo htmlspecialchars($job['location']); ?></p>
            <p><?php echo htmlspecialchars($job['description']); ?></p>
            <p>Salary: <?php echo htmlspecialchars($job['salary']); ?> | Type: <?php echo htmlspecialchars($job['job_type']); ?></p>
        </div>
        <form method="POST">
            <button type="submit">Apply Now</button>
        </form>
        <button onclick="window.location.href='search_jobs.php'">Back to Search</button>
    </div>
</body>
</html>

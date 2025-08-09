<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'job_seeker') {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}
$user_id = $_SESSION['user_id'];
$sql = "SELECT j.title, j.company, j.location, a.status FROM applications a JOIN jobs j ON a.job_id = j.id WHERE a.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Applications - Rozee.pk Clone</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }
        .track-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        h2 {
            color: #007bff;
        }
        .application-card {
            background: #f9f9f9;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
            .track-container {
                margin: 10px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="track-container">
        <h2>Your Job Applications</h2>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="application-card">
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p><?php echo htmlspecialchars($row['company']); ?> - <?php echo htmlspecialchars($row['location']); ?></p>
                <p>Status: <?php echo htmlspecialchars($row['status']); ?></p>
            </div>
        <?php endwhile; ?>
        <button onclick="window.location.href='index.php'">Back to Home</button>
    </div>
</body>
</html>

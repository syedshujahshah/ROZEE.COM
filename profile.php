<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_SESSION['user_type'] == 'job_seeker' && isset($_FILES['resume'])) {
        $resume = $_FILES['resume']['name'];
        $target = "uploads/" . basename($resume);
        move_uploaded_file($_FILES['resume']['tmp_name'], $target);
        $sql = "UPDATE users SET resume = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $resume, $user_id);
        $stmt->execute();
    } elseif ($_SESSION['user_type'] == 'employer') {
        $company = $_POST['company'];
        $sql = "UPDATE users SET company = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $company, $user_id);
        $stmt->execute();
    }
    echo "<script>window.location.href='profile.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Rozee.pk Clone</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }
        .profile-container {
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
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        input, button {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .resume-link {
            color: #007bff;
            text-decoration: none;
        }
        .resume-link:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .profile-container {
                margin: 10px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>Your Profile</h2>
        <p>Name: <?php echo htmlspecialchars($user['name']); ?></p>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
        <?php if ($_SESSION['user_type'] == 'employer'): ?>
            <form method="POST">
                <input type="text" name="company" placeholder="Company Name" value="<?php echo htmlspecialchars($user['company'] ?? ''); ?>">
                <button type="submit">Update Profile</button>
            </form>
        <?php else: ?>
            <form method="POST" enctype="multipart/form-data">
                <input type="file" name="resume" accept=".pdf">
                <button type="submit">Upload Resume</button>
            </form>
            <?php if ($user['resume']): ?>
                <p>Resume: <a class="resume-link" href="uploads/<?php echo htmlspecialchars($user['resume']); ?>">View Resume</a></p>
            <?php endif; ?>
        <?php endif; ?>
        <button onclick="window.location.href='index.php'">Back to Home</button>
    </div>
</body>
</html>

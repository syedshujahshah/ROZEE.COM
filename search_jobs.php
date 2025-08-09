<?php
session_start();
require 'db.php';
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$location = $_GET['location'] ?? '';
$job_type = $_GET['job_type'] ?? '';
$salary_min = $_GET['salary_min'] ?? '';
$salary_max = $_GET['salary_max'] ?? '';

$sql = "SELECT * FROM jobs WHERE 1=1";
$params = [];
$types = "";
if ($search) {
    $sql .= " AND title LIKE ?";
    $params[] = "%$search%";
    $types .= "s";
}
if ($category) {
    $sql .= " AND category = ?";
    $params[] = $category;
    $types .= "s";
}
if ($location) {
    $sql .= " AND location LIKE ?";
    $params[] = "%$location%";
    $types .= "s";
}
if ($job_type) {
    $sql .= " AND job_type = ?";
    $params[] = $job_type;
    $types .= "s";
}
if ($salary_min) {
    $sql .= " AND salary >= ?";
    $params[] = $salary_min;
    $types .= "s";
}
if ($salary_max) {
    $sql .= " AND salary <= ?";
    $params[] = $salary_max;
    $types .= "s";
}
$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Jobs - Rozee.pk Clone</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }
        .search-container {
            max-width: 1200px;
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
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        input, select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            flex: 1;
            min-width: 150px;
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
        @media (max-width: 768px) {
            .search-container {
                margin: 10px;
                padding: 15px;
            }
            input, select {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="search-container">
        <h2>Search Jobs</h2>
        <form method="GET">
            <input type="text" name="search" placeholder="Job Title" value="<?php echo htmlspecialchars($search); ?>">
            <input type="text" name="location" placeholder="Location" value="<?php echo htmlspecialchars($location); ?>">
            <select name="category">
                <option value="">All Categories</option>
                <option value="IT" <?php if ($category == 'IT') echo 'selected'; ?>>IT & Software</option>
                <option value="Marketing" <?php if ($category == 'Marketing') echo 'selected'; ?>>Marketing</option>
                <option value="Finance" <?php if ($category == 'Finance') echo 'selected'; ?>>Finance</option>
                <option value="Engineering" <?php if ($category == 'Engineering') echo 'selected'; ?>>Engineering</option>
            </select>
            <select name="job_type">
                <option value="">All Job Types</option>
                <option value="full-time" <?php if ($job_type == 'full-time') echo 'selected'; ?>>Full-Time</option>
                <option value="part-time" <?php if ($job_type == 'part-time') echo 'selected'; ?>>Part-Time</option>
                <option value="remote" <?php if ($job_type == 'remote') echo 'selected'; ?>>Remote</option>
            </select>
            <input type="number" name="salary_min" placeholder="Min Salary" value="<?php echo htmlspecialchars($salary_min); ?>">
            <input type="number" name="salary_max" placeholder="Max Salary" value="<?php echo htmlspecialchars($salary_max); ?>">
            <button type="submit">Search</button>
        </form>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="job-card">
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p><?php echo htmlspecialchars($row['company']); ?> - <?php echo htmlspecialchars($row['location']); ?></p>
                <p><?php echo htmlspecialchars($row['description']); ?></p>
                <p>Salary: <?php echo htmlspecialchars($row['salary']); ?> | Type: <?php echo htmlspecialchars($row['job_type']); ?></p>
                <a href="apply_job.php?job_id=<?php echo $row['id']; ?>">Apply Now</a>
            </div>
        <?php endwhile; ?>
        <button onclick="window.location.href='index.php'">Back to Home</button>
    </div>
</body>
</html>

<?php
session_start();

// Include the database connection
include('db_connection.php');

// Default filter values
$name_filter = $_GET['name'] ?? '';
$status_filter = $_GET['status'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

// Construct the SQL query with filters
$query = "SELECT id, name, phone, email, status, notes, assigned_to, updated_at FROM leads WHERE 1";

// Apply filters if provided
if (!empty($name_filter)) {
    $query .= " AND name LIKE '%" . $conn->real_escape_string($name_filter) . "%'";
}
if (!empty($status_filter)) {
    $query .= " AND status = '" . $conn->real_escape_string($status_filter) . "'";
}
if (!empty($start_date) && !empty($end_date)) {
    $query .= " AND updated_at BETWEEN '$start_date' AND '$end_date'";
}

$query .= " ORDER BY updated_at DESC";

$leads = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vinnu CRM</title>
	<link rel="icon" type="image/png" href="https://t3.ftcdn.net/jpg/01/69/63/44/360_F_169634442_WOTYC516BNftu8SnBbvVBEV3UvRBpYHB.jpg"> <!-- If you saved as PNG -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            color: #333;
        }
        .container {
            width: 90%;
            margin: 30px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #007bff;
        }
        .filters {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 20px;
        }
        .filters input, .filters select, .filters button {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 1rem;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #007bff;
            color: #fff;
        }
        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 8px 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Lead Updates</h1>

        <a href="javascript:history.back()" class="back-btn">⬅ Back</a>

        <form method="GET" class="filters">
            <input type="text" name="name" placeholder="Search by Name" value="<?= htmlspecialchars($name_filter) ?>">
            <select name="status">
                <option value="">All Statuses</option>
                <option value="new" <?= $status_filter === 'new' ? 'selected' : '' ?>>New</option>
                <option value="contacted" <?= $status_filter === 'contacted' ? 'selected' : '' ?>>Contacted</option>
                <option value="converted" <?= $status_filter === 'converted' ? 'selected' : '' ?>>Converted</option>
                <option value="not interested" <?= $status_filter === 'not interested' ? 'selected' : '' ?>>Not Interested</option>
            </select>
            <input type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
            <input type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>">
            <button type="submit">Filter</button>
        </form>

        <table>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Status</th>
                <th>Notes</th>
                <th>Assigned To</th>
                <th>Last Updated</th>
            </tr>
            <?php while ($lead = $leads->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($lead['name']); ?></td>
                    <td><?= htmlspecialchars($lead['phone']); ?></td>
                    <td><?= htmlspecialchars($lead['email']); ?></td>
                    <td><?= htmlspecialchars($lead['status']); ?></td>
                    <td><?= htmlspecialchars($lead['notes']); ?></td>
                    <td><?= htmlspecialchars($lead['assigned_to'] ?? 'Unassigned'); ?></td>
                    <td><?= htmlspecialchars($lead['updated_at']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>

<?php

session_start();

include "../navbar.php";

include "../connect.php"; // Database connection

// Initialize filter variable (default is 'all')
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$jobs = [];

// Handle job verification (accept or reject) via POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Accept or Reject job based on the 'verified' value
    if (isset($_POST['job_id']) && isset($_POST['verified'])) {
        $job_id = mysqli_real_escape_string($conn, $_POST['job_id']);
        $verified = mysqli_real_escape_string($conn, $_POST['verified']);
        
        // Update job verification status
        $update_query = "UPDATE jobs SET verified = '$verified' WHERE job_id = '$job_id'";
        mysqli_query($conn, $update_query);
    }

    // Handle job deletion
    if (isset($_POST['delete_job_id'])) {
        $delete_job_id = mysqli_real_escape_string($conn, $_POST['delete_job_id']);
        $delete_query = "DELETE FROM jobs WHERE job_id = '$delete_job_id'";
        mysqli_query($conn, $delete_query);
    }
}

// Prepare SQL query based on the filter
if ($filter == 'verified') {
    $query = "SELECT * FROM jobs WHERE verified = 1 ORDER BY joining_date DESC";
} elseif ($filter == 'unverified') {
    $query = "SELECT * FROM jobs WHERE verified = 0 ORDER BY joining_date DESC";
} else {
    $query = "SELECT * FROM jobs ORDER BY joining_date DESC";
}

// Execute the query to fetch the jobs
$result = mysqli_query($conn, $query);
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $jobs[] = $row;
    }
}

include "../disconnect.php"; // Close DB connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body.light-mode {
            background-color: white;
            color: black;
        }

        body.dark-mode {
            background-color: black; /* Dark background */
            color: white; /* Light text color */
        }

        .admin-jobs, .admin-jobs table {
            background-color: inherit;
            color: inherit;
        }
    </style>
</head>
<body>
<div class="container admin-jobs mt-5">
    <h3>Admin Profile</h3>
    
    <!-- Filter Form -->
    <form method="GET" action="" class="mb-4">
        <select name="filter" class="form-control w-25 d-inline" onchange="this.form.submit()">
            <option value="all" <?php echo $filter == 'all' ? 'selected' : ''; ?>>All Jobs</option>
            <option value="verified" <?php echo $filter == 'verified' ? 'selected' : ''; ?>>Accepted Jobs</option>
            <option value="unverified" <?php echo $filter == 'unverified' ? 'selected' : ''; ?>>Unverified Jobs</option>
        </select>
    </form>

    <h4>Jobs</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Company</th>
                <th>Role</th>
                <th>Location</th>
                <th>Salary</th>
                <th>Joining Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($jobs as $job): ?>
                <tr>
                    <td><?php echo htmlspecialchars($job['company']); ?></td>
                    <td><?php echo htmlspecialchars($job['role']); ?></td>
                    <td><?php echo htmlspecialchars($job['location']); ?></td>
                    <td><?php echo htmlspecialchars($job['salary']); ?></td>
                    <td><?php echo htmlspecialchars($job['joining_date']); ?></td>
                    <td>
                        <?php if ($job['verified']): ?>
                            <span class="badge badge-success">Verified</span>
                        <?php else: ?>
                            <span class="badge badge-warning">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <!-- Accept button -->
                        <form action="" method="POST" style="display:inline;">
                            <input type="hidden" name="job_id" value="<?php echo $job['job_id']; ?>">
                            <input type="hidden" name="verified" value="1">
                            <?php if ($job['verified'] == 0): ?>
                                <button class="btn btn-success" type="submit">Accept</button>
                            <?php else: ?>
                                <button class="btn btn-success" disabled type="submit">Accept</button>
                            <?php endif; ?>
                        </form>

                        <!-- Reject button -->
                        <form action="" method="POST" style="display:inline;">
                            <input type="hidden" name="job_id" value="<?php echo $job['job_id']; ?>">
                            <input type="hidden" name="verified" value="0">
                            <?php if ($job['verified'] != 0): ?>
                                <button class="btn btn-danger" type="submit">Reject</button>
                            <?php else: ?>
                                <button class="btn btn-danger" disabled type="submit">Reject</button>
                            <?php endif; ?>
                        </form>

                        <!-- Delete button -->
                        <form action="" method="POST" style="display:inline;">
                            <input type="hidden" name="delete_job_id" value="<?php echo $job['job_id']; ?>">
                            <button class="btn btn-warning" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>

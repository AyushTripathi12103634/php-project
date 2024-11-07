<?php
session_start();

include "../navbar.php";
include "../connect.php"; // Database connection

// Get the current user's email from the session
$email = $_SESSION['email'] ?? null;

// If no email is found in the session, redirect to login page
if (!$email) {
    header("Location: /php/login.php");
    exit();
}

// Fetch the jobs that the user has applied to
$query = "SELECT j.job_id, j.company, j.role, j.location, j.salary, j.joining_date
          FROM jobs j
          JOIN jobs_applied ja ON j.job_id = ja.job_id
          WHERE ja.email = '$email'"; // Fetch applied jobs for the logged-in user

$result = mysqli_query($conn, $query);
$applied_jobs = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $applied_jobs[] = $row;
    }
}

// Handle the unapply (delete) action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['job_id'])) {
    $job_id = mysqli_real_escape_string($conn, $_POST['job_id']);

    // Delete the application from the jobs_applied table
    $delete_query = "DELETE FROM jobs_applied WHERE email = '$email' AND job_id = '$job_id'";
    $delete_result = mysqli_query($conn, $delete_query);

    if ($delete_result) {
        $_SESSION['message'] = "You have successfully unapplied from the job!";
        $_SESSION['status'] = 200;
    } else {
        $_SESSION['message'] = "Failed to unapply from the job!";
        $_SESSION['status'] = 400;
    }

    // Redirect to the same page to show the message
    header("Location: /php/candidate/jobs_applied.php");
    exit();
}

include "../disconnect.php"; // Close DB connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Applied Jobs</title>
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

        .applied, .applied table {
            background-color: inherit;
            color: inherit;
        }
    </style>
</head>
<body>
<div class="applied container mt-5">
    <h3>Your Applied Jobs</h3>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['status'] == 200 ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['message']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['message']); unset($_SESSION['status']); ?>
    <?php endif; ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Company</th>
                <th>Role</th>
                <th>Location</th>
                <th>Salary</th>
                <th>Joining Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($applied_jobs) == 0): ?>
                <tr>
                    <td colspan="6" class="text-center">No applied jobs found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($applied_jobs as $job): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($job['company']); ?></td>
                        <td><?php echo htmlspecialchars($job['role']); ?></td>
                        <td><?php echo htmlspecialchars($job['location']); ?></td>
                        <td><?php echo htmlspecialchars($job['salary']); ?></td>
                        <td><?php echo htmlspecialchars($job['joining_date']); ?></td>
                        <td>
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="job_id" value="<?php echo $job['job_id']; ?>">
                                <button class="btn btn-danger" onclick="return confirm('Are you sure you want to unapply for this job?')">Unapply</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>

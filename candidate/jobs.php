<?php

session_start();

include "../navbar.php";

include "../connect.php"; // Database connection

// Fetch all available jobs
$query = "SELECT * FROM jobs WHERE verified = 1 ORDER BY joining_date DESC"; // Only show verified jobs
$result = mysqli_query($conn, $query);
$jobs = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $jobs[] = $row;
    }
}

// Handle the application submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['job_id']) && isset($_SESSION['email'])) {
        $job_id = mysqli_real_escape_string($conn, $_POST['job_id']);
        $email = mysqli_real_escape_string($conn, $_SESSION['email']); // Assuming the user's email is stored in session

        // Check if the user has already applied for this job
        $check_query = "SELECT * FROM jobs_applied WHERE email = '$email' AND job_id = '$job_id'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            // User has already applied
            $_SESSION['message'] = "You have already applied for this job!";
            $_SESSION['status'] = 400;
            $_SESSION['next'] = "/php/candidate/jobs.php";
        } else {
            // User has not applied, proceed with inserting the application
            $query = "INSERT INTO jobs_applied (email, job_id) VALUES ('$email', '$job_id')";
            $application_result = mysqli_query($conn, $query);

            if ($application_result) {
                $_SESSION['message'] = "Application submitted successfully!";
                $_SESSION['status'] = 200;
                $_SESSION['success'] = true;
                $_SESSION['next'] = "/php/candidate/jobs.php";
            } else {
                $_SESSION['message'] = "Failed to apply for the job!";
                $_SESSION['status'] = 400;
                $_SESSION['next'] = "/php/candidate/jobs.php";
            }
        }

        // Redirect to the response page to show the message
        header("Location: /php/response.php");
        exit();
    }
}

include "../disconnect.php"; // Close DB connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Jobs</title>
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

        .available-jobs, .available-jobs table {
            background-color: inherit;
            color: inherit;
        }
    </style>
</head>
<body>
<div class="available-jobs container mt-5">
    <h3>Available Jobs</h3>

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
            <?php if (count($jobs) == 0): ?>
                <tr>
                    <td colspan="6" class="text-center">No jobs available.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($jobs as $job): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($job['company']); ?></td>
                        <td><?php echo htmlspecialchars($job['role']); ?></td>
                        <td><?php echo htmlspecialchars($job['location']); ?></td>
                        <td><?php echo htmlspecialchars($job['salary']); ?></td>
                        <td><?php echo htmlspecialchars($job['joining_date']); ?></td>
                        <td>
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="job_id" value="<?php echo $job['job_id']; ?>">
                                <button class="btn btn-primary">Apply</button>
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

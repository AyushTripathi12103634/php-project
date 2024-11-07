<?php
session_start();
include "../connect.php"; // Database connection

// Ensure job_id is passed in the URL
if (isset($_GET['job_id'])) {
    $job_id = mysqli_real_escape_string($conn, $_GET['job_id']);
} else {
    $_SESSION['message'] = "Job ID not provided!";
    $_SESSION['status'] = 400;
    $_SESSION['success'] = false;
    $_SESSION['next'] = "/php/employer/jobs.php"; // Redirect to the jobs list if no job_id is provided
    header("Location: /php/response.php");
    exit();
}

// Get applicants for the job (join jobs_applied with auth to get email and name)
$query = "
    SELECT a.name, a.email
    FROM jobs_applied ja
    JOIN auth a ON ja.email = a.email
    WHERE ja.job_id = '$job_id'
";

$result = mysqli_query($conn, $query);
$applicants = mysqli_fetch_all($result, MYSQLI_ASSOC);

include "../disconnect.php"; // Close DB connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicants for Job #<?php echo $job_id; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3>Applicants for Job ID: <?php echo $job_id; ?></h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Applicant Name</th>
                <th>Applicant Email</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($applicants) > 0): ?>
                <?php foreach ($applicants as $applicant): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($applicant['name']); ?></td>
                        <td><?php echo htmlspecialchars($applicant['email']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2">No applicants for this job yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php

session_start();

include "../navbar.php"; 
include "../connect.php"; 

$created_by_email = $_SESSION['email'];
$jobs = [];

if (isset($created_by_email)) {
    $result = mysqli_query($conn, "SELECT id FROM auth WHERE email = '$created_by_email' LIMIT 1");
    $created_by_id = ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result)['id'] : null;

    if ($created_by_id) {
        $job_query = "SELECT * FROM jobs WHERE created_by = '$created_by_id' ORDER BY joining_date DESC";
        $job_result = mysqli_query($conn, $job_query);

        if ($job_result && mysqli_num_rows($job_result) > 0) {
            while ($row = mysqli_fetch_assoc($job_result)) {
                $jobs[] = $row;
            }
        }
    }
}

include "../disconnect.php"; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Posted Jobs</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <style>
        body.light-mode .jobs-list {
            background-color: white;
            color: black;
        }

        body.dark-mode .jobs-list {
            background-color: black; /* Dark background */
            color: white; /* Light text color */
        }

        body.light-mode .jobs-list table {
            background-color: inherit;
            color: inherit;
        }

        body.dark-mode .jobs-list table {
            background-color: inherit; /* Dark background */
            color: inherit; /* Light text color */
        }
    </style>
</head>
<body>
<div class="container mt-5 jobs-list">
    <h3>Your Posted Jobs</h3>
    <?php if (count($jobs) > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Role</th>
                    <th>Location</th>
                    <th>Salary</th>
                    <th>Joining Date</th>
                    <th>Verified Status</th>
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
                            <?php if ($job['verified']): ?>
                                <a href="/php/employer/view_applicants.php?job_id=<?php echo $job['job_id']; ?>" class="btn btn-info">View Applicants</a>
                            <?php endif; ?>
                            <form action="delete_job.php" method="POST" style="display:inline;">
                                <input type="hidden" name="job_id" value="<?php echo $job['job_id']; ?>">
                                <?php if (!$job['verified']): ?>
                                    <button class="btn btn-danger" type="submit">Delete</button>
                                <?php else: ?>
                                    <button class="btn btn-danger" disabled type="submit">Delete</button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No jobs posted yet.</p>
    <?php endif; ?>
</div>
</body>
</html>

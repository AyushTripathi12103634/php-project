<?php
session_start();
include "connect.php";

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['isLogin']) && $_SESSION['isLogin'];
$userRole = $_SESSION['role'] ?? '';
$userId = $_SESSION['id'] ?? '';
$userEmail = $_SESSION['email'] ?? '';

// Handle logout or redirection if necessary
if (!$isLoggedIn) {
    $redirectMessage = "Please login to view details";
} else {
    $redirectMessage = "Welcome User: $userId";
}

?>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <style>
        .home {
            padding: 20px;
        }

        ul a {
            text-decoration: none;
            color: inherit;
            cursor: pointer;
        }

        .light-mode .home {
            background-color: #e1e1e1;
            color: black;
        }

        .dark-mode .home {
            background-color: #1e1e1e;
            color: white;
        }

        .view {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 85vh;
            padding: 40px 60px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .view::-webkit-scrollbar {
            display: none;
        }

        .view {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<?php include "navbar.php"; ?>

<div class="view">
    <div class="home">
        <?php if ($isLoggedIn): ?>
            <h1><?= $redirectMessage ?></h1>
            <h3>You are logged in as <?= $userRole ?></h3>
            <h5>Below are the features you can access:</h5>
            <ul>
                <?php if ($userRole == 'admin'): ?>
                    <a href="<?= '/php/admin/profile.php' ?>"><li>Accept job listings</li></a>
                    <a href="<?= '/php/admin/profile.php' ?>"><li>Reject job listings</li></a>
                <?php endif; ?>

                <?php if ($userRole == 'Employer'): ?>
                    <a href="<?= '/php/employer/create_job.php' ?>"><li>Create Jobs</li></a>
                    <a href="<?= '/php/employer/jobs.php' ?>"><li>Delete Jobs</li></a>
                    <a href="<?= '/php/employer/jobs.php' ?>"><li>View Applicants</li></a>
                    <a href="<?= '/php/employer/jobs.php' ?>"><li>View Jobs</li></a>
                <?php endif; ?>

                <?php if ($userRole == 'Candidate'): ?>
                    <a href="<?= '/php/candidate/jobs_applied.php' ?>"><li>View Applied Jobs</li></a>
                    <a href="<?= '/php/candidate/jobs.php' ?>"><li>Apply for jobs</li></a>
                <?php endif; ?>
            </ul>
        <?php else: ?>
            <h1><?= $redirectMessage ?></h1>
        <?php endif; ?>
    </div>
</div>

<?php session_write_close(); ?>

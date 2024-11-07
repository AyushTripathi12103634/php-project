<?php

$isLoggedIn = isset($_SESSION['isLogin']) && $_SESSION['isLogin'];
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
?>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body.light-mode {
            background-color: white;
            color: black;
        }

        body.dark-mode {
            background-color: black;
            color: white;
        }

        .navbar {
            background-color: #e1e1e1;
        }

        body.dark-mode .navbar {
            background-color: #1e1e1e;
        }

        .footer {
            background-color: #e1e1e1;
            color: black;
        }

        body.dark-mode .footer {
            background-color: #1e1e1e;
            color: white;
        }
    </style>
</head>

<body id="body">
    <base href="/php/">
    <div class="d-flex justify-content-between">
        <div class="ml-5 mt-3">
            <a href="index.php"><h1>Job Board</h1></a>
        </div>
        <div class="mr-5 mt-3 w-50 d-flex justify-content-between align-items-center">

            <?php if (!$isLoggedIn): ?>
                <a href="auth/login.php">Login</a>
                <a href="auth/register.php">Register</a>
                <a href="auth/admin.php">Admin Login</a>
            <?php endif; ?>

            <?php if ($isLoggedIn): ?>
                <?php if ($role == "Candidate"): ?>
                    <a href="candidate/jobs.php">Jobs</a>
                    <a href="candidate/jobs_applied.php">Jobs Applied</a>
                <?php endif; ?>
                <?php if ($role == "Employer"): ?>
                    <a href="employer/jobs.php">Jobs</a>
                    <a href="employer/create_job.php">Create Job</a>
                <?php endif; ?>
                <?php if ($role == "admin"): ?>
                    <a href="admin/profile.php">Profile</a>
                <?php endif; ?>
                <a href="auth/logout.php">Logout</a>
            <?php endif; ?>
            
            <button id="theme-toggle" class="btn" onclick="toggleTheme()">
            </button>

        </div>
    </div>

    <script>
        function toggleTheme() {
            const body = document.getElementById('body');
            const currentTheme = body.classList.contains('dark-mode') ? 'dark' : 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            body.classList.remove(currentTheme + '-mode');
            body.classList.add(newTheme + '-mode');

            localStorage.setItem('theme', newTheme);

            const button = document.getElementById('theme-toggle');
            button.innerText = newTheme === 'dark' ? 'Dark Mode' : 'Light Mode';
            button.classList.remove(currentTheme === 'dark' ? 'btn-dark' : 'btn-light');
            button.classList.add(newTheme === 'dark' ? 'btn-dark' : 'btn-light');
        }

        window.onload = function() {
            const savedTheme = localStorage.getItem('theme');
            const body = document.getElementById('body');
            const button = document.getElementById('theme-toggle');

            if (savedTheme) {
                body.classList.add(savedTheme + '-mode');
                button.innerText = savedTheme === 'dark' ? 'Dark Mode' : 'Light Mode';
                button.classList.add(savedTheme === 'dark' ? 'btn-dark' : 'btn-light');
            } else {
                body.classList.add('light-mode');
                button.innerText = 'Light Mode';
                button.classList.add('btn-light');
            }
        }
    </script>

</body>
<?php
session_start();

$message = $_SESSION['message'] ?? '';
$status = $_SESSION['status'] ?? ''; 
$success = $_SESSION['success'] ?? false; 
$redirectUrl = $_SESSION['next'] ?? ''; 

unset($_SESSION['message']);
unset($_SESSION['status']);
unset($_SESSION['success']);
unset($_SESSION['next']);
unset($_SESSION['code']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Response</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body>
    <div class="card <?php echo $success ? 'bg-success text-white' : 'bg-danger text-white'; ?>">
        <div class="card-body text-center">
            <h4 class="card-title"><?php echo $success ? 'Success' : 'Error'; ?></h4>
            <p class="card-text"><?php echo htmlspecialchars($message); ?></p>
            <a href="<?php echo htmlspecialchars($redirectUrl); ?>" class="btn btn-light">Go to Next</a>
            <div class="mt-3">
                <p>Response Code: <strong><?php echo htmlspecialchars($status); ?></strong></p>
            </div>
        </div>
    </div>
</body>
</html>

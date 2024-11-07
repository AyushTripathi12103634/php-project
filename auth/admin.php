<?php
session_start();

$isLoggedIn = isset($_SESSION['isLogin']) && $_SESSION['isLogin'];

include "../connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = 'Admin'; // Hardcoded for admin login

    $sql = "SELECT * FROM auth WHERE email = ? AND role = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $email, $role);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['user_password'])) {
                $_SESSION['isLogin'] = true;
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['message'] = "Login successful!";
                $_SESSION['status'] = 200;
                $_SESSION['success'] = true;
                $_SESSION['next'] = "/php";
            } else {
                $_SESSION['message'] = "Incorrect password!";
                $_SESSION['status'] = 401;
                $_SESSION['success'] = false;
                $_SESSION['next'] = "/php/atuh/admin.php";
            }
        } else {
            $_SESSION['message'] = "Invalid email or role!";
            $_SESSION['status'] = 400;
            $_SESSION['success'] = false;
            $_SESSION['next'] = "/php/auth/admin.php";
        }

        $stmt->close();
    } else {
        $_SESSION['message'] = "Error in database query!";
        $_SESSION['status'] = 500;
        $_SESSION['success'] = false;
        $_SESSION['next'] = "/php/auth/admin.php";
    }

    include "../disconnect.php";

    header("Location: /php/response.php");
    exit();
}
?>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>

<?php include "../navbar.php"; ?>

<div class="view">
    <div class="auth-login">
        <?php if ($isLoggedIn): ?>
            <script>
                window.location.href = "/php/admin/dashboard"; // Redirect if already logged in
            </script>
        <?php endif; ?>
        
        <form method="POST" action="">
            <h3 class="text-center mb-3">Admin Login</h3>
            
            <input class="form-control mb-3" name="email" type="email" required placeholder="Enter Email">
            
            <div class="input-group mb-3">
                <input class="form-control" name="password" type="password" id="password" required placeholder="Enter Password">
                <div class="input-group-append">
                    <span class="input-group-text" id="toggle-password" style="cursor: pointer;">
                        <i class="fas fa-eye" id="eye-icon"></i>
                    </span>
                </div>
            </div>

            <div class="d-flex justify-content-center w-100">
                <button class="btn btn-primary" type="submit">Submit</button>
            </div>
        </form>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert <?php echo ($_SESSION['success'] ? 'alert-success' : 'alert-danger'); ?> mt-3">
                <?php echo $_SESSION['message']; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    const togglePassword = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-icon');

    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        eyeIcon.classList.toggle('fa-eye');
        eyeIcon.classList.toggle('fa-eye-slash');
    });
</script>

<style>
    .auth-login {
        padding: 20px 20px;
    }

    .light-mode .auth-login {
        background-color: #e1e1e1;
        color: black;
    }

    .dark-mode .auth-login {
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
<?php
session_start();
include 'includes/db.php';

/* -----------------------------
   ERROR HOLDER
------------------------------ */
$error = "";

/* -----------------------------
   STUDENT LOGIN
------------------------------ */
if (isset($_POST['student_login'])) {

    $email = trim($_POST['student_email']);
    $password = $_POST['student_password'];

    $sql = "SELECT * FROM students WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user) {

        if (password_verify($password, $user['password'])) {

            session_regenerate_id(true);

            $_SESSION['student_id'] = $user['id'];
            $_SESSION['student_name'] = $user['name'];
            $_SESSION['student_email'] = $user['email'];

            header("Location: student-dashboard.php");
            exit();

        } else {
            $error = "Invalid password";
        }

    } else {
        $error = "Student not found";
    }
}


/* -----------------------------
   ADMIN LOGIN (SIMPLE DEMO)
------------------------------ */
if (isset($_POST['admin_login'])) {

    $email = trim($_POST['admin_email']);
    $password = $_POST['admin_password'];

    // Demo admin (no DB required for assignment)
    $admin_email = "admin@greenfield.edu";
    $admin_pass = "admin123";

    if ($email === $admin_email && $password === $admin_pass) {

        session_regenerate_id(true);

        $_SESSION['admin_id'] = 1;
        $_SESSION['admin_name'] = "Administrator";

        header("Location: admin-dashboard.php");
        exit();

    } else {
        $error = "Invalid admin credentials";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Greenfield Institute</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/login.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

<nav class="navbar">
    <div class="nav-container">
        <a href="index.php" class="logo">Greenfield<span>Institute</span></a>

        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="courses.php">Courses</a></li>
            <li><a href="login.php" class="active">Login</a></li>
            <li><a href="register.php">Register</a></li>
        </ul>
    </div>
</nav>

<main>
    <div class="container">
        <div class="form-container">

            <h2>Welcome Back</h2>
            <p>Login to access your dashboard</p>

            <!-- ERROR -->
            <?php if (!empty($error)): ?>
                <div class="alert alert-error">
                    ✗ <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- STUDENT LOGIN -->
            <form method="POST">

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="student_email" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="student_password" required>
                </div>

                <button type="submit" name="student_login" class="btn btn-primary" style="width:100%;">
                    Login as Student
                </button>

            </form>

            <hr style="margin:20px 0;">

            <!-- ADMIN LOGIN -->
            <form method="POST">

                <div class="form-group">
                    <label>Admin Email</label>
                    <input type="email" name="admin_email" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="admin_password" required>
                </div>

                <button type="submit" name="admin_login" class="btn btn-secondary" style="width:100%;">
                    Login as Admin
                </button>

            </form>

        </div>
    </div>
</main>

<script src="../js/main.js"></script>

</body>
</html>
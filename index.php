<?php
session_start();
session_regenerate_id();
include "inc/function.php";
include "config/koneksi.php";

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $login = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");
    $loginUser = mysqli_fetch_assoc($login);

    if ($loginUser && password_verify($_POST['password'], $loginUser['password'])) {
        $_SESSION['USERNAME'] = $loginUser['name'];
        header("Location: main.php?page=dashboard");
        exit();
    } else {
        $_SESSION['error'] = "Email atau Password Salah!";
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h4>Log in</h4>
                        <?php statusLogin(); ?>
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Email address</label>
                                <input type="email" class="form-control" name="email" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" required />
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="rememberCheck" />
                                <label class="form-check-label" for="rememberCheck">Save Login Information</label>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
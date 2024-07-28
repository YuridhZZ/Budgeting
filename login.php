<!DOCTYPE html>
<html lang="en">

<?php
session_start();
include('./db_connect.php');
ob_start();

// Fetch system settings and store them in the session
$system = $conn->query("SELECT * FROM system_settings")->fetch_array();
foreach ($system as $k => $v) {
    $_SESSION['system'][$k] = $v;
}

ob_end_flush();
?>

<?php
// Redirect to home if the user is already logged in
if (isset($_SESSION['login_id'])) {
    header("location:index.php?page=home");
}
?>

<?php include 'header.php' ?>

<body class="hold-transition login-page" style="background: url(assets/Bg/Bg_1.jpg); background-size: cover;">
    <div class="login-box">
        <div class="login-logo">
            <a href="#" class="text-white"><b><?php echo $_SESSION['system']['name'] ?></b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <form action="" id="login-form">

                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" name="email" required placeholder="Enter your email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="password" required placeholder="Enter your password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <p><a href="forgot_password.php" class="text-muted">Forgot your password?</a></p>
                            </div>
                        </div>
                        <!-- Submit button -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                    </div>
                </form>

            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <script>
        $(document).ready(function () {
            $('#login-form').submit(function (e) {
                e.preventDefault();
                start_load();

                // Remove previous error messages
                if ($(this).find('.alert-danger').length > 0)
                    $(this).find('.alert-danger').remove();

                // Submit the login form via AJAX
                $.ajax({
                    url: 'ajax.php?action=login',
                    method: 'POST',
                    data: $(this).serialize(),
                    error: err => {
                        console.log(err);
                        end_load();
                    },
                    success: function (resp) {
                        if (resp == 1) {
                            location.href = 'index.php?page=home';
                        } else {
                            $('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>');
                            end_load();
                        }
                    }
                });
            });
        });
    </script>

    <?php include 'footer.php' ?>

</body>

</html>

<?php
// Start the session
session_start();

// Include the database configuration file
require_once "config.php";

// Check if the user is already logged in, redirect them to a different page
if(isset($_SESSION['username'])) {
    header("location: dashboard.php");
    exit;
}

// Check if the login form has been submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    /// Get the input values from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Create a connection to the database
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query to retrieve the user with the input username and password
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the query returned a row
    if ($result->num_rows == 1) {
        // Store the username in the session variable
        $_SESSION['username'] = $username;

        // Redirect the user to the dashboard page
        header("location: dashboard.php");
    } else {
        // Display an error message if the credentials are incorrect
        $error = "Invalid username or password.";
    }
    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>

    <?php include 'includes/head.php'; ?>
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="../../index2.html" class="h1"><b>Cepe's BHMS</b></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <form action="index.php" method="post">
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        <input type="username" id="username" name="username" class="form-control" placeholder="Username">
                    </div>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-default" id="togglePassword">
                                <span class="fas fa-eye"></span>
                            </button>
                        </div>
                    </div>
                    <?php if(isset($error)): ?>
                        <div class="text-center mb-4">
                            <span style="color:red;"><?php echo $error; ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                    <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Log In</button>
                        </div>
                    <!-- /.col -->
                    </div>
                </form>

                <p class="mb-2 mt-3">
                    <a href="forgot-password.html">I forgot my password</a>
                </p>
                <!-- <p class="mb-0">
                    <a href="register.html" class="text-center">Register a new membership</a>
                </p> -->
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="../../assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../../assets/dist/js/adminlte.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>

    <script>
    $(document).ready(function() {
        $('.toggle-password').on('click', function() {
        $(this).toggleClass('fa-eye fa-eye-slash');
        let input = $($(this).attr('toggle'));
        if (input.attr('type') == 'password') {
            input.attr('type', 'text');
        } else {
            input.attr('type', 'password');
        }
        });
    });
    </script>


    <!-- <script>
        $('#togglePassword').on('click', function() {
            var passwordField = $('#password');
            var passwordFieldType = passwordField.attr('type');
            if (passwordFieldType == 'password') {
                passwordField.attr('type', 'text');
                $(this).html('<span class="fas fa-eye-slash"></span>');
            } else {
                passwordField.attr('type', 'password');
                $(this).html('<span class="fas fa-eye"></span>');
            }
        });
    </script> -->
</body>
</html>
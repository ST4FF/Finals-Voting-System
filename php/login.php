<?php
    session_start();
    @include 'database/config.php';

    $error = [];

    if (isset($_POST['submit'])){
        $email = trim($_POST['user']);
        $password_input = trim($_POST['pass']);

        
        if (empty($email) || empty($password_input)){
            $error[] = 'Please fill in both email and password.';
        } else {
            $email = mysqli_real_escape_string($conn, $email);
            $password = $password_input;

            $select = "SELECT * FROM user_form WHERE email='$email'";
            $result = mysqli_query($conn, $select);

            if (mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_assoc($result);

                if (password_verify($password_input, $row['password'])){
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['user_name'] = $row['name'];
                    $_SESSION['user_role'] = $row['role'];
                    $_SESSION['profile_picture'] = $row['profile_picture'];

                    if ($row['role'] === 'admin'){
                        header('location: admin-dashboard.php');
                    }else{
                        header('location: voter-dashboard.php');
                    }

                    exit;
                }else{
                    $error[] = 'Incorrect Password!';
                }
            }else{
                $error[] = 'User Not Found!';
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="login1.css">
    <title>Login Page</title>
</head>
    <body>
        <div class="form-container">
            <form action="" method="post">
                <h2>Login</h2>
                <?php
                    if(isset($error)){
                        foreach($error as $errorMsg){
                            echo '<span class="error-message">' . $errorMsg . '</span>';
                        }
                    }
                ?>
                <input type="text" name="user" id="input" placeholder="Enter email">
                <input type="password" name="pass" id="input" class="loginPwdChange" placeholder="Enter password">
                <input type="submit" name="submit" value="Login Now" class="form-btn">
                <p>Don't have an account? <a href="registration.php">Register Now</a></p>
            </form>
        </div>
    </body>
</html>
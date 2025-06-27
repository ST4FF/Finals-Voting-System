<?php
    @include 'database/config.php';

    if(isset($_POST['submit'])){
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password_raw = $_POST['pass'];
        $confirm_password_raw = $_POST['cpass'];
        $role = mysqli_real_escape_string($conn, $_POST['role']);
        $error = [];

        if($password_raw !== $confirm_password_raw) {
            $error[] = 'Passwords do not match!';
        }elseif(!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/", $password_raw)) {
            $error[] = 'Password must be at least 8 characters long and include an uppercase letter, a lowercase letter, a number, and a special character.';
        }

        $profile_picture_path = '';
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $image_name = basename($_FILES["profile_picture"]["name"]);
            $unique_name = time() . "_" . $image_name;
            $target_file = $target_dir . $unique_name;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

            if(!in_array($imageFileType, $allowed_types)) {
                $error[] = "Only JPG, JPEG, PNG & GIF files are allowed.";
            }elseif($_FILES["profile_picture"]["size"] > 2 * 1024 * 1024) {
                $error[] = "File too large. Max 2MB allowed.";
            }elseif(!move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $error[] = "Failed to upload profile picture.";
            }else {
                $profile_picture_path = mysqli_real_escape_string($conn, $target_file);
            }
        }else{
            $error[] = "Please upload a profile picture.";
        }

        if (empty($error)) {
            $hashed_password = password_hash($password_raw, PASSWORD_DEFAULT);

            $insert = "INSERT INTO user_form (name, email, password, role, profile_picture) 
                    VALUES ('$name', '$email', '$hashed_password', '$role', '$profile_picture_path')";
            
            mysqli_query($conn, $insert);
            header("Location: login.php");
            exit;
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="registration1.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Registration Page</title>
</head>
    <body>
        <div class="form-container">
            <form action="" method="post" enctype="multipart/form-data">
                <h2>Register Now</h2>
                <?php
                    if(isset($error)){
                        foreach($error as $error){
                            echo '<span class="error-message">' .$error. '</span>';
                        }
                    }
                ?>
                <input type="text" name="name" required placeholder="Enter name">
                <input type="email" name="email" required placeholder="Enter email">
                <input type="password" name="pass" required placeholder="Enter password">
                <input type="password" name="cpass" required placeholder="Confirm password">

                <select name="role" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="voter">Voter</option>
                    <option value="admin">Admin</option>
                </select>

                <div class="profile-upload">
                    <label for="profile_picture" class="input-label">Upload Profile Picture</label>
                    <input type="file" name="profile_picture" accept="image/*" required>
                </div>
                
                <input type="submit" name="submit" value="Register Now" class="form-btn">
                <p>Already have an account? <a href="login.php">Login Now</a></p>
            </form>
        </div>
    </body>
</html>


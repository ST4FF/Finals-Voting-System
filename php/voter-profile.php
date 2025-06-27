<?php
    session_start();
    @include 'database/config.php';

    if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'voter'){
        header("Location: login.php");
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $info_message = $error_message = "";

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['new_profile_picture'])){
        $img_name = $_FILES['new_profile_picture']['name'];
        $tmp_name = $_FILES['new_profile_picture']['tmp_name'];
        $img_path = 'uploads/' . uniqid() . '_' . basename($img_name);

        if(move_uploaded_file($tmp_name, $img_path)){
            $conn -> query("UPDATE user_form SET profile_picture='$img_path' WHERE id='$user_id'");
            $info_message = "Profile picture have been updated!!";
        }else{
            $error_message = "Failed to update profile picture!!";
        }
    }

    if(isset($_POST['update_password'])){
        $new_pass = $_POST['new_password'];
        $confirm_pass = $_POST['confirm_password'];

        if($new_pass !== $confirm_pass){
            $error_message = "Password does not match!";
        }else if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $new_pass)){
            $error_message = "Password must be at least 8 characters long and include an uppercase letter, 
                              lowercase letter, number, and special character.";
        }else{
            $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
            $conn -> query("UPDATE user_form SET password='$hashed' WHERE id='$user_id'");
            $info_message = "Password updated successfully!";
        }
    }

    if(isset($_POST['delete_account'])){
        $conn -> query("DELETE FROM user_form WHERE id='$user_id'");
        session_unset();
        session_destroy();
        header("Location: login.php?deleted=true");
        exit;
    }

    $result = $conn -> query("SELECT * FROM user_form WHERE id='$user_id'");
    $user = $result -> fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="sidebar.css">
    <title>Voter Profile Page</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap');

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }
        .container{
            padding: 20px;
            margin-left: 78px;
            transition: all 0.5s ease-in-out;
        }
        .sidebar.active ~ .container{
            margin-left: 240px;
        }
        .profile-box{
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.5);
            padding: 30px;
            max-width: 500px;
            margin: 30px auto;
        }
        .profile-box h2{
            font-size: 24px;
            margin-bottom: 20px;
            color: #1d1b31;
        }
        .profile-field{
            margin-bottom: 15px;
        }
        .profile-field strong{
            display: inline-block;
            width: 100px;
            color: #157115;
        }
        .profile-box input,
        .profile-box button{
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 15px;
        }
        .profile-box button{
            background-color: #157115;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }
        .profile-box button:hover{
            background-color: #1ea81e;
        }
        .profile-box .delete-button{
            background-color: #c0392b;
        }
        .success{
            color: #1ea81e;
            margin-bottom: 10px;
        }
        .error{
            color: #c0392b;
            margin-bottom: 10px;
        }
        .profile-pic-container{
            position: relative;
            width: 60px;
            height: 60px;
            border: 2px solid #000;
            border-radius: 10px;
            overflow: hidden;
        }
        .profile-pic-container img{
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .profile-pic-container .overlay{
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            color: white;
            font-size: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            border-radius: 10px;
            transition: opacity 0.3s;
            text-align: center;
        }
        .profile-pic-container:hover .overlay{
            opacity: 1;
        }
        .profile-pic-container input[type="file"]{
            display: none;
        }
    </style>
</head>
    <body>
        <?php include 'sidebar.php'?>
        <div class="container">
            <div class="profile-box">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <h2>My Profile</h2>
                        <p><strong>Name: </strong> <?php echo htmlspecialchars($user['name']); ?></p>
                        <p><strong>Email: </strong> <?php echo htmlspecialchars($user['email']); ?></p>
                        <p><strong>Role: </strong> <?php echo htmlspecialchars($user['role']); ?></p>
                    </div>
                    <form method="post" enctype="multipart/form-data" class="profile-pic-container">
                        <label for="profilePicInput" class="profile-picture-label">
                            <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture">
                            <div class="overlay">Chage Profile</div>
                        </label>

                        <input type="file" name="new_profile_picture" id="profilePicInput" accept="image/*" onchange="this.form.submit()">
                    </form>
                </div>
                
                <hr><br>

                <?php if($info_message) echo "<p class='success'>$info_message</p>"; ?>
                <?php if($error_message) echo "<p class='error'>$error_message</p>"; ?>

                <form method="post">
                    <h3>Change Password</h3>
                    <input type="password" name="new_password" required placeholder="Enter new password">
                    <input type="password" name="confirm_password" required placeholder="Enter confirm password">
                    <button type="submit" name="update_password">Update Password</button>
                </form>

                <form method="post" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                    <button type="submit" name="delete_account" class="delete-button" style="background-color: red; color: white;">Delete Account</button>
                </form>
            </div>
        </div>

        <script src="sidebar.js"></script>
        <script src="voter-profile.js"></script>
    </body>
</html>
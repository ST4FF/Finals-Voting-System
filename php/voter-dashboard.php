<?php
    session_start();
    @include 'database/config.php';

    if(isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIIVITY'] > 900)){
        session_unset();
        session_destroy();
        header("Location: login.php?timeout=true");
        exit;
    }

    $_SESSION['LAST_ACTIVITY'] = time();
    $user_id = $_SESSION['user_id'];
    $query = "SELECT profile_picture FROM user_form WHERE id='$user_id'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    $profile_image = !empty($user['profile_picture']) ? $user['profile_picture'] : 'assets/default.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="sidebar.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Online Voting System Sidebar</title>
</head>
    <body>
        <div class="sidebar">
            <div class="logo-content">
                <div class="logo">
                    <i class="bx bxs-user-check"></i>
                    <div class="logo-name">Online Voting System</div>
                </div>

                <i class='bx bx-menu' id="btn"></i>
            </div>

            <ul class="nav_list">
                <li>
                    <i class='bx bx-search'></i>
                    <input type="text" placeholder="Search...">
                    <span class="tooltip">Search</span>
                </li>

                <li>
                    <a href="#">
                        <i class="bx bxs-dashboard"></i>
                        <span class="links_name">Dashboard</span>
                    </a>
                    <span class="tooltip">Dashboard</span>
                </li>

                <li>
                    <a href="voters.php">
                        <i class="bx bxs-group"></i>
                        <span class="links_name">Candidates</span>
                    </a>
                    <span class="tooltip">Candidates</span>
                </li>

                <li>
                    <a href="#">
                        <i class="bx bxs-checkbox-checked"></i>
                        <span class="links_name">Vote Now</span>
                    </a>
                    <span class="tooltip">Vote Now</span>
                </li>

                <li>
                    <a href="#">
                        <i class='bx bxs-pie-chart-alt'></i>
                        <span class="links_name">Voting Results</span>
                        
                    </a>
                    <span class="tooltip">Voting Results</span>
                </li>

                <li>
                    <a href="voter-profile.php">
                        <i class="bx bxs-cog"></i>
                        <span class="links_name">Voter Profile</span>
                    </a>
                    <span class="tooltip">Voter Profile</span>
                </li>
            </ul>

            <div class="profile-content">
                <div class="profile">
                    <div class="profile-details">
                        <img src="<?php echo $profile_image; ?>" alt="profile" style="object-fit: cover;">
                        <div class="name-role">
                            <div class="name"><?php echo $_SESSION['user_name']; ?></div>
                            <div class="role"><?php echo ucfirst($_SESSION['user_role']); ?></div>
                        </div>
                    </div>

                    <a href="login.php">
                        <i class="bx bx-log-out" id="log-out"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="home-content">
            <div class="text">Hello, Voter!</div>
        </div>

        <script src="sidebar.js"></script>
    </body>
</html>
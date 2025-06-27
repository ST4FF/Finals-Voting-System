<?php
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    $profile_image = isset($_SESSION['profile_picture']) && $_SESSION['profile_picture'] != ''
                     ? $_SESSION['profile_picture']
                     : 'uploads/default.png';
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

                <i class='bx bx-menu-alt-left' id="btn"></i>
            </div>

            <ul class="nav_list">
                <li>
                    <i class='bx bx-search'></i>
                    <input type="text" placeholder="Search...">
                </li>

                <li>
                    <a href="#">
                        <i class="bx bxs-dashboard"></i>
                        <span class="links_name">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <i class="bx bxs-group"></i>
                        <span class="links_name">Candidates</span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <i class="bx bxs-checkbox-checked"></i>
                        <span class="links_name">Vote Now</span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <i class='bx bxs-pie-chart-alt'></i>
                        <span class="links_name">Voting Results</span>
                    </a>
                </li>

                <li>
                    <a href="voter-profile.php">
                        <i class="bx bxs-cog"></i>
                        <span class="links_name">Voter Profile</span>
                    </a>
                </li>
            </ul>

            <div class="profile-content">
                <div class="profile">
                    <div class="profile-details">
                        <img src="<?php echo $profile_image; ?>" alt="profile" style="object-fit: cover;">
                        <div class="name-role">
                            <div class="name"><?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?></div>
                            <div class="role"><?php echo htmlspecialchars($_SESSION['user_role'] ?? ''); ?></div>
                        </div>
                    </div>

                    <a href="login.php">
                        <i class="bx bx-log-out" id="log-out"></i>
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
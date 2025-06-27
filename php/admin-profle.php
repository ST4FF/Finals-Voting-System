<?php
    session_start();
    @include 'database/config.php';

    if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin'){
        header("Location: login.php");
        exit;
    }

    if(isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 900)){
        session_unset();
        session_destroy();
        header("Location: login.php?timeout=true");
        exit;
    }

    $_SESSION['LAST_ACTIVITY'] = time();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
                    <a href="admin-profile.php">
                        <i class="bx bxs-cog"></i>
                        <span class="links_name">Admin Profile</span>
                    </a>
                </li>
            </ul>

            <div class="profile-content">
                <div class="profile">
                    <div class="profile-details">
                        <img src="<?php echo $_SESSION['profile_picture'] ?? 'assets/admin.png'; ?>" alt="profile">
                        <div class="name-role">
                            <div class="name"><?php echo $_SESSION['user_name']; ?></div>
                            <div class="role"><?php echo ucfirst($_SESSION['user_role']); ?></div>
                        </div>
                    </div>
                    
                    <a href="logout.php"><i class="bx bx-log-out" id="log-out"></i></a>
                </div>
            </div>
        </div>

        <div class="home-content">
            <div class="text">Admin Profile</div>
            <div class="profile-section">
                <h2>Profile Information</h2>
                <p><strong>Name:</strong> <?php echo $_SESSION['user_name']; ?></p>
                <p><strong>Email:</strong> <?php echo $_SESSION['user_email']; ?></p>
                <p><strong>Role:</strong> <?php echo ucfirst($_SESSION['user_role']); ?></p>
            </div>
        </div>

        <script src="sidebar.js"></script>
    </body>
</html>
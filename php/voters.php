<?php
    session_start();
    @include 'database/config.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $message = "";

    $stmt = $conn->prepare("SELECT has_voted FROM user_form WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $has_voted = $user['has_voted'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$has_voted) {
        if (!isset($_POST['candidate_id'])) {
            $message = "No candidate selected.";
        } else {
            $candidate_id = $_POST['candidate_id'];

            $vote_stmt = $conn->prepare("INSERT INTO votes (user_id, candidate_id) VALUES (?, ?)");
            $vote_stmt->bind_param("ii", $user_id, $candidate_id);
            $vote_stmt->execute();

            $update_stmt = $conn->prepare("UPDATE user_form SET has_voted = 1 WHERE id = ?");
            $update_stmt->bind_param("i", $user_id);
            $update_stmt->execute();

            $has_voted = 1;
            $message = "Your vote has been submitted successfully!";
        }
    }

    $candidate_query = $conn -> query("SELECT * FROM candidates");
    $candidates = $candidate_query -> fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter's Page</title>
</head>
    <body>
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>

        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <?php if ($has_voted): ?>
            <p class="error">You have already voted!</p>

        <?php else: ?>
            <form action="voters.php" method="post">
                <h3>Select Your Candidate: </h3>
                <?php foreach ($candidates as $candidate): ?>
                    <div class="candidtate">
                        <input type="radio" name="candidate_id" value="<?php echo $candidate['id']; ?>" required>
                        <?php echo htmlspecialchars($candidate['name']); ?>
                    </div>

                <?php endforeach; ?>
                <input type="submit" value="Submit Vote">
            </form>
        <?php endif; ?>
    </body>
</html>
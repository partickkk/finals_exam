<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'APPLICANT') {
    header('Location: login.php');
    exit;
}

$jobPosts = fetchJobPosts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/applicantdash.css">

    <title>Applicant Dashboard</title>
    
</head>
<body>
    <h1>Applicant Dashboard</h1>
    <form action="core/handleforms.php" method="POST">
        <button type="submit" name="logout">Logout</button>
    </form>
    <h2>Available Job Posts</h2>
    <a href="messages.php">Go to Messages</a>
    <table border="1">
        <tr>
            <th>Job Title</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($jobPosts as $job): ?>
            <tr>
                <td><?= htmlspecialchars($job['title']) ?></td>
                <td><?= htmlspecialchars($job['description']) ?></td>
                <td>
                    <form action="core/handleforms.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="job_post_id" value="<?= $job['id'] ?>">
                        <label for="resume">Upload Resume:</label>
                        <input type="file" name="resume" id="resume" required>
                        <br>
                        <textarea name="message" placeholder="Why are you the best fit?" required></textarea>
                        <br>
                        <button type="submit" name="apply_to_job">Apply</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
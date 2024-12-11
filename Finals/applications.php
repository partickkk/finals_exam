<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';

if ($_SESSION['role'] !== 'hr') {
    echo "Access Denied.";
    exit;
}

$applications = getApplicationsByHR($pdo, $_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <h1>Applications</h1>

    <?php foreach ($applications as $app): ?>
        <div>
            <h3>Job: <?php echo htmlspecialchars($app['title']); ?></h3>
            <p>Applicant: <?php echo htmlspecialchars($app['applicant_name']); ?></p>
            <p>Status: <?php echo htmlspecialchars($app['status']); ?></p>
            <p>Cover Letter: <?php echo htmlspecialchars($app['cover_letter']); ?></p>
            <a href="resumes/<?php echo htmlspecialchars($app['resume']); ?>" download>Download Resume</a>
            <form action="core/handleForms.php" method="POST">
                <input type="hidden" name="application_id" value="<?php echo $app['application_id']; ?>">
                <select name="status">
                    <option value="accepted">Accept</option>
                    <option value="rejected">Reject</option>
                </select>
                <button type="submit" name="updateApplicationStatus">Update</button>
            </form>
        </div>
    <?php endforeach; ?>
</body>
</html>
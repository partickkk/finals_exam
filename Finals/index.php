<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['role'] === 'hr') {
    header("Location: hr_dashboard.php");
} else {
    header("Location: applicant_dashboard.php");
}
?>
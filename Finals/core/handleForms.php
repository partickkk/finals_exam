<?php
require_once 'dbConfig.php';
require_once 'models.php';



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['login'])) {
      $username = $_POST['username'];
      $user_password = $_POST['user_password'];
      $user = authenticateUser($username, $user_password);
      if ($user) {
          $_SESSION['user'] = $user;
          header("Location: " . ($user['role'] === 'HR' ? '../hr_dashboard.php' : '../applicant_dashboard.php'));
      } else {
          header('Location: ../login.php?error=Invalid username or user_password');
      }
      exit;
  }

  if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    
    header('Location: ../login.php');
    exit;
}
if (isset($_POST['register'])) {
    // Retrieve user input
    $username = trim($_POST['username']);
    $user_password = trim($_POST['user_password']);
    $role = trim($_POST['role']);

    // Input validation
    if (empty($username) || empty($user_password) || empty($role)) {
        header('Location: ../register.php?error=All fields are required');
        exit;
    }

    try {
        // Check for existing username
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $existingUser  = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existingUser ) {
            header('Location: ../register.php?error=Username already taken');
            exit;
        }

        // Hash the user_password
        $hashed_password = password_hash($user_password, PASSWORD_BCRYPT);

        // Insert new user into the database
        $stmt = $pdo->prepare("INSERT INTO users (username, user_password, role) VALUES (:username, :user_password, :role)");
        $stmt->execute([
            'username' => $username,
            'user_password' => $hashed_password,
            'role' => $role
        ]);

        // Regenerate session ID and set session variables
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'username' => $username,
            'role' => $role,
            'id' => $pdo->lastInsertId()
        ];

        // Redirect to the login page after successful registration
        header("Location: ../login.php?success=Registration successful, please log in.");
        exit;

    } catch (PDOException $e) {
        // Log the error message
        error_log($e->getMessage());
        header('Location: ../register.php?error=Registration failed, please try again');
        exit;
    }
}


if (isset($_POST['add_job_post'])) {
      $title = $_POST['title'];
      $description = $_POST['description'];
      addJobPost($title, $description);
      header('Location: ../hr_dashboard.php');
      exit;
  }

if (isset($_POST['apply_to_job'])) {
  $applicant_id = $_SESSION['user']['id'];
  $job_post_id = $_POST['job_post_id'];
  $message = $_POST['message'];
  $resumeFileName = $_FILES['resume']['name'];
  $tempFileName = $_FILES['resume']['tmp_name'];
  $fileExtension = pathinfo($resumeFileName, PATHINFO_EXTENSION);

  $uniqueID = sha1(md5(rand(1,9999999)));

  $resumeFileNameToSave = $uniqueID . "." . $fileExtension;
  $resumeFolder = "../resumes/" . $resumeFileNameToSave;

  if (!is_dir("../resumes")) {
      mkdir("../resumes", 0777, true);
  }

  if (move_uploaded_file($tempFileName, $resumeFolder)) {
      applyToJob($applicant_id, $job_post_id, $resumeFileNameToSave, $message);
      header('Location: ../applicant_dashboard.php');
      exit;
  } else {
      echo "Error uploading the resume. Please try again.";
  }
}

  if (isset($_POST['update_application_status'])) {
      $application_id = $_POST['application_id'];
      $status = $_POST['status'];
      updateApplicationStatus($application_id, $status);
      header('Location: ../hr_dashboard.php');
      exit;
  }

  if (isset($_POST['send_message'])) {
    $sender_id = $_SESSION['user']['id'];
    $receiver_username = $_POST['receiver_username']; 
    $content = $_POST['content'];

    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
    $stmt->execute(['username' => $receiver_username]);
    $receiver = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($receiver) {
        $receiver_id = $receiver['id']; 
        $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, content, sent_at)
                               VALUES (:sender_id, :receiver_id, :content, NOW())");
        $stmt->execute([
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'content' => $content
        ]);
        header('Location: ../messages.php');
    } else {
        echo "Error: Receiver not found!";
    }
}
}
?>
<?php
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['change-password'];
    $newEmail = $_POST['change-email'];
    $newBio = $_POST['add-bio'];
    $newAddress = $_POST['change-address'];

    // Validate email format
    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        header("Location: settings.php?error=Invalid email format");
        exit();
    }

    // Hash password if provided
    $passwordHash = $newPassword ? password_hash($newPassword, PASSWORD_BCRYPT) : $user['password_hash'];

    // Update user settings in the database
    $sql = "UPDATE users SET password_hash = ?, email = ?, bio = ?, address = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssss', $passwordHash, $newEmail, $newBio, $newAddress, $email);

    if ($stmt->execute()) {
        $_SESSION['email'] = $newEmail; // Update session email if changed
        header("Location: ../pages/settings.php?success=true");
        exit();
    } else {
        header("Location: ../pages/settings.php?error=" . urlencode($conn->error));
        exit();
    }
}
?>

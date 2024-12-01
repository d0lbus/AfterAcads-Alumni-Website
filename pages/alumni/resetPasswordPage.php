<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reset Password</title>
  <link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" />
  <link rel="stylesheet" href="../../style/alumni/resetPassword.css" />
</head>

<body>
  <div class="reset-container">
        <div class="reset-box">
            <img class="logo" src="../../assets/logo.png" alt="logo" />
            <h1>Reset Password</h1>
        
            <!-- resetPasswordPage.html -->
            <form action="#" method="POST">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" required />

                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required />

                <!-- <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>" /> -->
                
                <div class="button-group">
                    <button class="buttonsize send-btn" type="submit" name="resetPassword">Reset Password</button>
                </div>
            </form>

        </div>
    </div>
</body>

</html>
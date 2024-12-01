<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Log-in</title>
  <link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" />
  <link rel="stylesheet" href="../../style/alumni/forgotPWCode.css" />
</head>

<body>
    <div class="reset-container">
        <div class="reset-box">
            <img class="logo" src="../../assets/logo.png" alt="logo" />
            <h1>Password reset</h1>
            <p>We sent a code to <b>your-email@example.com</b></p>

            <!-- Code Input -->
            <!-- <div class="code-inputs">
                <input type="text" maxlength="1" class="code-box" required />
                <input type="text" maxlength="1" class="code-box" required />
                <input type="text" maxlength="1" class="code-box" required />
                <input type="text" maxlength="1" class="code-box" required />
            </div>

            <button class="buttonsize continue-btn" type="button">Continue</button> -->

            <div class="code-inputs">
                <form action="#" method="POST">
                    <input type="text" id="code" name="code" maxlength="1" class="code-box" required />
                    <input type="text" id="code" name="code" maxlength="1" class="code-box" required />
                    <input type="text" id="code" name="code" maxlength="1" class="code-box" required />
                    <input type="text" id="code" name="code" maxlength="1" class="code-box" required />
                    <!-- <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>" /> -->

                    <div class="continue-btn-container">
                        <button class="buttonsize continue-btn" type="submit" onclick="window.location.href='resetPasswordPage.php'" name="verifyCode">Continue</button>
                    </div>
                    
                </form>
            </div>

            <p>
            Didn’t receive the email? <a href="forgotPasswordPage.php" class="resend-link">Click to resend</a>
            </p>
            <a href="loginpage.php" class="back-link">← Back to log in</a>
        </div>
    </div>
    
</body>
</html>
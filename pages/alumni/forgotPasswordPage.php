<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Log-in</title>
  <link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" />
  <link rel="stylesheet" href="../../style/alumni/forgotPassword.css" />
</head>

<body>
  <div class="forgot-container">
        <div class="forgot-box">
            <img class="logo" src="../../assets/logo.png" alt="logo" />
            <h1>Forgot Password</h1>
            <p>Please enter your <b>email address</b> to reset your password</p>
        
            <form action="../../config/general/forgotpassword.php" method="POST">
                <label for="emailOrPhone"></label>
                <input type="text" id="emailOrPhone" name="emailOrPhone" placeholder="Email or Phone" required />

                <div class="button-group">
                    <a class="buttonsize cancel-btn" href="loginpage.php">Cancel</a>
                    <button class="buttonsize send-btn" type="submit" name="resetPassword">Send</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
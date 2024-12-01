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
        
            <form action="#" method="POST">
                <label for="Email"></label>
                <input type="email" id="email" name="email" placeholder="Enter Your Email" required />

                <div class="button-group">
                    <button class="buttonsize cancel-btn" type="button" onclick="window.location.href='loginpage.php'">Cancel</button>
                    <button class="buttonsize send-btn" type="submit" onclick="window.location.href='forgotPWCodePage.php'" name="sendResetCode">Send</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
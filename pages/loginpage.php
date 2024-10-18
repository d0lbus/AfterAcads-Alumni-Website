<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Log-in</title>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap"
    />
    <link rel="stylesheet" href="../style/login.css" />
  </head>
  <body class="body-color2">
    <div class="login-container">
      <div class="login-box">
        <img
          class="logocircle"
          src="../assets/alumnilogo.png"
          width=""
          alt=""
        />
        <h2 style="color: black">Log In</h2>
        <p>
          By continuing, you agree to our User Agreement and acknowledge that
          you understand the Privacy Policy.
        </p>

        <form action="../config/login.php" method="POST">
          <label for="email"></label>
          <input type="email" id="email" name="email" placeholder="Email" required />
      
          <label for="password"></label>
          <input type="password" id="password" name="password" placeholder="Password *" required />
      
          <a href="#">Forgot Password?</a>
          <p>New to AfterAcads? <a href="signuppage.php">Sign Up</a></p>
      
          <button class="buttonsize" type="submit" name="signIn">Login</button>
      </form>
      
  
      </div>
    </div>
  </body>
</html>

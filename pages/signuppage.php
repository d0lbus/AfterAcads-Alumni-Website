<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign-up</title>
  <link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" />
  <link rel="stylesheet" href="../style/signup.css" />
</head>

<body class="body-color2">
  <div class="signup-container">
    <div class="signup-box">
      <img
        class="logocircle"
        src="../assets/alumnilogo.png"
        width=""
        alt="" />
      <h2 style="color: black">Sign Up</h2>
      <p>Log in with your SLU email</p>

      <form action="../config/signup.php" method="POST">
        <label for="firstName">First Name</label>
        <input type="text" id="firstName" name="firstName" placeholder="First name *" required />

        <label for="lastName">Last Name</label>
        <input type="text" id="lastName" name="lastName" placeholder="Last name *" required />

        <label for="email">SLU Email</label>
        <input type="email" id="email" name="email" placeholder="yourname@slu.edu.ph *" required />

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password *" required />

        <div class="agreement-section">
          <input type="checkbox" id="agree-terms" name="agree-terms" required />
          <label style="color: black" for="agree-terms">
            I agree to the<a href="#" class="terms-link"> Terms of Use</a> and
            <a href="#" class="terms-link"> Privacy Policy</a>.
          </label>
        </div>
        <button class="buttonsize" type="submit">Sign Up</button>
      </form>


      <!-- Agreement Section with Checkboxes -->

      <button class="buttonsize" type="submit" value="Sign In" name="signIn">
        Log in with SLU Email
      </button>

      <p class="login-prompt">
        Already have an account?
        <a href="loginpage.php" class="login-link">Log in</a>
      </p>
    </div>
  </div>
</body>

</html>
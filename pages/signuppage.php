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
        class="logo"
        src="../assets/logoBlue.png"
        width=""
        alt="" />
      <h2 style="color: black">Create an Account</h2>

      <form action="../config/signup.php" method="POST">
        <div class="name-fields">
        <!-- <label for="firstName">First Name</label> -->
        <input type="text" id="firstName" name="firstName" placeholder="First name *" required />

        <!-- <label for="lastName">Last Name</label> -->
        <input type="text" id="lastName" name="lastName" placeholder="Last name *" required />
        </div>

        <div>
        <label for="email">SLU Email</label>
        <input type="email" id="email" name="email" placeholder="yourname@slu.edu.ph *" required />

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Password *" required />
        
        <label for="confirmPassword">Confirm Password</label>
        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password *" required />

        <p id="error-message" style="color: red; display: none;">Passwords do not match.</p>
        </div>

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
      <p class="login-prompt">
        Already have an account?
        <a href="loginpage.php" class="login-link">Log in</a>
      </p>
    </div>
  </div>


  <!-- Jerilyn Cahanap -->
  <script>
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirmPassword');
    const errorMessage = document.getElementById('error-message');
    const submitButton = document.getElementById('submit-button');

    // Check passwords on input in the confirmPassword field
    confirmPassword.addEventListener('input', () => {
      if (confirmPassword.value === password.value) {
        errorMessage.style.display = 'none'; // Hide error message
        confirmPassword.style.borderColor = 'green'; // Add success indicator
        submitButton.disabled = false; // Enable the submit button
      } else {
        errorMessage.style.display = 'block'; // Show error message
        confirmPassword.style.borderColor = 'red'; // Add error indicator
        submitButton.disabled = true; // Disable the submit button
      }
    });
  </script>

</body>

</html>
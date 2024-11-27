<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign-up</title>
  <link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" />
  <link rel="stylesheet" href="../../style/signup.css" />
</head>

<body class="body-color2">
  <div class="signup-container">
    <div class="signup-box">
      <img
        class="logo"
        src="../assets/logo.png"
        width=""
        alt="" />
      <h2 style="color: black">Create an Account</h2>

      <form action="../config/signup.php" method="POST">
        <div class="name-fields">
          <div class="name-field">
            <label for="firstName">First Name</label>
            <input type="text" id="firstName" name="firstName" placeholder="First name *" required />
          </div>

          <div class="name-field">
            <label for="middleName">Middle Name</label>
            <input type="text" id="middleName" name="middleName" placeholder="Middle name *" required />
          </div>

          <div class="name-field">
            <label for="lastName">Last Name</label>
            <input type="text" id="lastName" name="lastName" placeholder="Last name *" required />
          </div>
        </div>


        <div class="fields">
          

          <div class="gender-container">
            <label class="gender-label">Gender</label>
            <div class="gender">
              <div class="gender-option">
                <input type="radio" id="gender-male" name="gender" value="male" required />
                <label for="gender-male">Male</label>
              </div>
              <div class="gender-option">
                <input type="radio" id="gender-female" name="gender" value="female" required />
                <label for="gender-female">Female</label>
              </div>
              <div class="gender-option">
                <input type="radio" id="gender-notToSay" name="gender" value="prefer-not-to-say" required />
                <label for="gender-notToSay">Prefer not to say</label>
              </div>
            </div>
          </div>

          <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="yourname@slu.edu.ph *" required />
          </div>

          <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Password *" required />
          </div>

          <div class="field">
            <label for="confirmPassword">Confirm Password</label>
            <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password *" required />
          </div>

          <p id="error-message" style="color: red; display: none;">Passwords do not match.</p>
        </div>

        <p style="font-size: 0.9rem; margin-bottom: 0; margin-top: 20px;"> By continuing, you agree to our User Agreement and acknowledge that
        you understand the Privacy Policy.
        </p>

        <div class="agreement-section">
          <input type="checkbox" id="agree-terms" name="agree-terms" required />
          <label style="color: black" for="agree-terms">
            I agree to the<a href="#" class="terms-link"> Terms of Use</a> and
            <a href="#" class="terms-link"> Privacy Policy</a>.
          </label>
        </div>
        <button class="buttonsize" type="submit">SIGN UP</button>
      </form>


      <!-- Agreement Section with Checkboxes -->
      <p class="login-prompt">
        Already have an account?
        <a href="loginpage.php" class="login-link">Log in</a>
      </p>
    </div>
  </div>

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
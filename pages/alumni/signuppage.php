<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign-up</title>
  <link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" />
  <link rel="stylesheet" 
    href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" />
  <link rel="stylesheet" href="../../style/alumni/signup.css" />
</head>

<body class="body-color2">
  <div class="signup-container">
    <div class="signup-box">
      <img
        class="logo"
        src="../../assets/logo.png"
        alt="logo" />
      <h2 style="color: black">Create an Account</h2>

      <form action="../../config/alumni/signup.php" method="POST">
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

        <!-- File Upload Section -->
        <label style="font-weight: bold">Upload a file for validation that you are an alumni in SLU</label>
        <div class="file-upload-section">
          <h4>Upload File Here</h4>
          <div class="upload-box">
            <label for="alumniFile" class="upload-label">
              <div class="upload-icon">
                <i class="las la-cloud-upload-alt"></i>
              </div>
              <p style="text-align: center">Click or drag files here</p>
              <p class="upload-subtext" style="text-align: center">Files supported: Images, PDFs, DOCX</p>
              <p class="upload-maxsize" style="text-align: center">Maximum size: </p>
            </label>
            <input type="file" id="file-upload" class="upload-input" multiple accept=".jpg, .jpeg, .png, .pdf, .docx"/>
            <button type="button" class="browse-btn">Browse</button>
          </div>
          <div class="file-preview">
            <ul id="file-list">
              <li>
                <span class="file-name"></span>
                <!-- <i class="las la-trash-alt delete-icon"></i> -->
              </li>
            </ul>
          </div>
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
        errorMessage.style.display = 'none'; 
        confirmPassword.style.borderColor = 'green'; 
        submitButton.disabled = false; 
      } else {
        errorMessage.style.display = 'block'; 
        confirmPassword.style.borderColor = 'red'; 
        submitButton.disabled = true; 
      }
    });


    // // file upload script
    // const uploadInput = document.getElementById('alumniFile');
    // const filePreview = document.querySelector('.file-preview ul');

    // // Add event listener for file selection
    // uploadInput.addEventListener('change', (event) => {
    //   // Clear previous files
    //   filePreview.innerHTML = '';

    //   const file = event.target.files[0];

    //   if (file) {
    //     // Validate file size (2MB = 2 * 1024 * 1024 bytes)
    //     if (file.size > 2 * 1024 * 1024) {
    //       alert('File size exceeds the 2MB limit.');
    //       uploadInput.value = ''; // Clear input
    //       return;
    //     }

    //     // Add file details to the preview section
    //     const li = document.createElement('li');
    //     const fileNameSpan = document.createElement('span');
    //     fileNameSpan.classList.add('file-name');
    //     fileNameSpan.textContent = file.name;

    //     const deleteIcon = document.createElement('i');
    //     deleteIcon.classList.add('las', 'la-trash-alt', 'delete-icon');
    //     deleteIcon.addEventListener('click', () => {
    //       li.remove(); // Remove file preview
    //       uploadInput.value = ''; // Clear file input
    //     });

    //     li.appendChild(fileNameSpan);
    //     li.appendChild(deleteIcon);
    //     filePreview.appendChild(li);
    //   }
    // });

    document.addEventListener('DOMContentLoaded', () => {
      const fileInput = document.getElementById('file-upload');
      const fileList = document.getElementById('file-list');

      // Store uploaded files in an array
      let uploadedFiles = [];

      // Handle file uploads
      fileInput.addEventListener('change', (event) => {
        const files = Array.from(event.target.files);
        files.forEach((file) => {
          // Add files to the uploadedFiles array
          uploadedFiles.push(file);
          displayFile(file);
        });

        // Reset the file input to allow re-uploading the same files
        fileInput.value = '';
      });

      // Display file in the file list
      function displayFile(file) {
        const li = document.createElement('li');

        const fileName = document.createElement('span');
        fileName.textContent = file.name;
        fileName.className = 'file-name';

        // Show preview when the file name is clicked
        fileName.addEventListener('click', () => previewFile(file));

        const deleteButton = document.createElement('span');
        deleteButton.innerHTML = '❌'; 
        deleteButton.className = 'delete-icon'; 

        // Delete file when the delete button is clicked
        deleteButton.addEventListener('click', () => {
          uploadedFiles = uploadedFiles.filter((f) => f !== file);
          li.remove();
        });

        li.appendChild(fileName);
        li.appendChild(deleteButton);
        fileList.appendChild(li);
      }

      // Preview file
      function previewFile(file) {
        const reader = new FileReader();

        reader.onload = () => {
          if (file.type.startsWith('image/')) {
            // Preview image
            const img = document.createElement('img');
            img.src = reader.result;
            img.style.maxWidth = '100%';
            img.style.border = '1px solid #ddd';
            img.style.marginTop = '10px';

            alert('Previewing the image. Check it below.'); // Optional alert
            document.body.appendChild(img);
          } else if (file.type === 'application/pdf') {
            // Preview PDF
            const iframe = document.createElement('iframe');
            iframe.src = reader.result;
            iframe.style.width = '100%';
            iframe.style.height = '500px';

            document.body.appendChild(iframe);
          } else {
            // For DOCX or other files, download them instead
            const a = document.createElement('a');
            a.href = reader.result;
            a.download = file.name;
            a.textContent = `Click to download: ${file.name}`;
            document.body.appendChild(a);
          }
        };

        if (file.type.startsWith('image/') || file.type === 'application/pdf') {
          reader.readAsDataURL(file); // Read image or PDF as DataURL
        } else {
          reader.readAsArrayBuffer(file); // Read DOCX or other files as ArrayBuffer
        }
      }
    });


  </script>

</body>

</html>
// References:
// - GeeksforGeeks: "PHP session management: starting, unsetting, and destroying sessions" - https://www.geeksforgeeks.org/php-sessions/
// - StackOverflow: "How to end a session in PHP" - https://stackoverflow.com/questions/3659738/how-to-logout-or-destroy-session-in-php
// - YouTube: "PHP session logout tutorial" - https://www.youtube.com/watch?v=6g42jFLwYpg
// - ChatGPT: "Explanation of PHP session_destroy() and session_unset()" - ChatGPT (2024)

<?php
session_start(); // Start the session
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
header('Location: ../../pages/alumni/loginpage.php'); // Redirect to login page
exit();
?>

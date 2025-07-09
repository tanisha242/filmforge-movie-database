<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FilmForge - Login</title>
    <link rel="stylesheet" href="styles3.css">
    <script>
        function toggleForm() 
	{
            var loginForm = document.getElementById('loginForm');
            var signupForm = document.getElementById('signupForm');
            var toggleLogin = document.querySelector('.toggle-login');
            var toggleSignup = document.querySelector('.toggle-signup');

            if (loginForm.style.display === "none") 
	    {
                loginForm.style.display = "block";
                signupForm.style.display = "none";
                toggleLogin.style.display = "none";
                toggleSignup.style.display = "block";
            } 
	   else 
	   {
                    loginForm.style.display = "none";
                signupForm.style.display = "block";
                toggleLogin.style.display = "block";
                toggleSignup.style.display = "none";
            }
        }

            function validateSignupForm() 
	    {
            var username = document.getElementById("signupUsername").value;
            var email = document.getElementById("signupEmail").value;
            var password = document.getElementById("signupPassword").value;

            if (username == "" || email == "" || password == "")
	    {
                alert("All fields are required.");
                return false;
            }

            return true;
        }

            function validateLoginForm() 
	    {
            var username = document.getElementById("loginUsername").value;
            var password = document.getElementById("loginPassword").value;

            if (username == "" || password == "") 
	    {
                alert("Both fields are required.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Welcome to FilmForge</h1>

            <!-- Signup Form - Visible by default -->
            <form id="signupForm" action="signup.php" method="POST" onsubmit="return validateSignupForm()">
                <h2>Sign Up</h2>
                <input id="signupUsername" type="text" name="username" placeholder="Username" required>
                <input id="signupEmail" type="email" name="email" placeholder="Email" required>
                <input id="signupPassword" type="password" name="password" placeholder="Password" required>
                <button type="submit">Sign Up</button>
            </form>

            <!-- Link to show login form -->
            <div class="toggle-login">
                <p>Already a user? <a href="javascript:void(0);" onclick="toggleForm()">Login here</a></p>
            </div>

            <hr>

            <!-- Login Form - Hidden by default -->
            <form id="loginForm" action="login.php" method="POST" onsubmit="return validateLoginForm()" style="display: none;">
                <h2>Login</h2>
                <input id="loginUsername" type="text" name="username" placeholder="Username" required>
                <input id="loginPassword" type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
            <!-- Link to show signup form -->
            <div class="toggle-signup" style="display: none;">
                <p>Don't have an account? <a href="javascript:void(0);" onclick="toggleForm()">Sign Up here</a></p>
            </div>
        </div>
    </div>
</body>
</html>

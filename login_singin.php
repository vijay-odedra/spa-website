<?php
session_start();

$login_msg = "";
$signup_msg = "";

// Connect to database
$conn = new mysqli("localhost", "root", "", "spa-system");
if ($conn->connect_error) {
    // no error message
}

/* ---------- SIGNUP ---------- */
if (isset($_POST['signup'])) {
    $name = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if passwords match
    if ($_POST['password'] !== $_POST['signup_confirm_password']) {
        $signup_msg = "Passwords do not match!";
    } else {
        // Check if email already exists
        $check_email = $conn->query("SELECT * FROM users WHERE email='$email'");
        if ($check_email->num_rows > 0) {
            $signup_msg = "Email already exists!";
        } else {
            $insert = "INSERT INTO users (full_name, email, password)
                       VALUES ('$name', '$email', '$password')";
            if ($conn->query($insert)) {
                $signup_msg = "Account created successfully! You can now login.";
            } else {
                $signup_msg = "Error creating account. Please try again.";
            }
        }
    }
}

/* ---------- LOGIN ---------- */
if (isset($_POST['login'])) {
    $username = $_POST['login_username'];
    $password = $_POST['login_password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$username'");

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Create user session
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['full_name'],
                'email' => $user['email']
            ];

            header("Location: index.php");
            exit();
        } else {
            $login_msg = "Invalid password!";
        }
    } else {
        $login_msg = "User not found!";
    }
}

$conn->close();
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relaxo Spa | Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="login_singin.css">

</head>
<body>
    <div class="background-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <div class="container">
        <div class="logo">
            <div class="logo-container">
                <div class="logo-img">
                    <i class="fas fa-spa"></i>
                </div>
                <div class="logo-text">Relaxo<span>Spa</span></div>
            </div>
            <p>Relax • Rejuvenate • Restore</p>
        </div>

        <div class="form-container">
            <div class="form-toggle">
                <button class="toggle-btn active" id="login-toggle">Login</button>
                <button class="toggle-btn" id="signup-toggle">Sign Up</button>
            </div>

            <!-- Login Form -->
            <form class="form active" id="login-form" method="post" action="">
                <h2 class="form-title">Welcome Back</h2>

                <?php if (!empty($login_msg)): ?>
                    <div class="message error"><?php echo $login_msg; ?></div>
                <?php endif; ?>

                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" placeholder="Email Address" name="login_username" required>
                </div>

                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" placeholder="Password" name="login_password" required>
                </div>

                <div class="remember-forgot">
                    <label><input type="checkbox"> Remember me</label>
                    <a href="#">Forgot Password?</a>
                </div>

                <button type="submit" name="login" class="btn">Login <i class="fas fa-arrow-right"></i></button>

                <div class="social-login">
                    <p>Or login with</p>
                    <div class="social-icons">
                        <div class="social-icon facebook"><i class="fab fa-facebook-f"></i></div>
                        <div class="social-icon google"><i class="fab fa-google"></i></div>
                        <div class="social-icon apple"><i class="fab fa-apple"></i></div>
                    </div>
                </div>
            </form>

            <!-- Signup Form -->
            <form class="form" id="signup-form" action="" method="post">
                <h2 class="form-title">Create Account</h2>

                <?php if (!empty($signup_msg)): ?>
                    <div class="message <?php echo strpos($signup_msg, 'successfully') !== false ? 'success' : 'error'; ?>">
                        <?php echo $signup_msg; ?>
                    </div>
                <?php endif; ?>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" placeholder="Full Name" name="fullname" required>
                </div>

                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" placeholder="Email Address" name="email" required>
                </div>

                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" placeholder="Password" name="password" required>
                </div>

                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" placeholder="Confirm Password" name="signup_confirm_password" required>
                </div>

                <button type="submit" name="signup" class="btn">Sign Up <i class="fas fa-user-plus"></i></button>

                <div class="social-login">
                    <p>Or sign up with</p>
                    <div class="social-icons">
                        <div class="social-icon facebook"><i class="fab fa-facebook-f"></i></div>
                        <div class="social-icon google"><i class="fab fa-google"></i></div>
                        <div class="social-icon apple"><i class="fab fa-apple"></i></div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginToggle = document.getElementById('login-toggle');
            const signupToggle = document.getElementById('signup-toggle');
            const loginForm = document.getElementById('login-form');
            const signupForm = document.getElementById('signup-form');
            
            // Toggle between login and signup forms
            loginToggle.addEventListener('click', function() {
                loginToggle.classList.add('active');
                signupToggle.classList.remove('active');
                loginForm.classList.add('active');
                signupForm.classList.remove('active');
            });
            
            signupToggle.addEventListener('click', function() {
                signupToggle.classList.add('active');
                loginToggle.classList.remove('active');
                signupForm.classList.add('active');
                loginForm.classList.remove('active');
            });
        
            // Add animation to input fields on focus
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });

            // Simple form validation
            const signupFormElement = document.getElementById('signup-form');
            signupFormElement.addEventListener('submit', function(e) {
                const password = document.querySelector('input[name="password"]').value;
                const confirmPassword = document.querySelector('input[name="signup_confirm_password"]').value;
                
                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Passwords do not match!');
                    return false;
                }
                
                if (password.length < 6) {
                    e.preventDefault();
                    alert('Password must be at least 6 characters long!');
                    return false;
                }
                
                return true;
            });
        });
    </script>
</body>
</html>

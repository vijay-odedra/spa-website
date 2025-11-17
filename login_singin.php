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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        :root {
            --primary: #8B7355;
            --secondary: #D4C1A6;
            --accent: #A67B5B;
            --light: #F8F4F0;
            --dark: #3E3E3E;
            --text: #4A4A4A;
            --white: #FFFFFF;
            --shadow: 0 5px 15px rgba(0,0,0,0.1);
            --transition: all 0.3s ease;
        }

        body {
            background-color: var(--light);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1544168185-5f7bf3535e93?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2071&q=80') no-repeat center center/cover;
        }

        .background-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            animation: float 15s infinite ease-in-out;
        }

        .shape-1 {
            width: 300px;
            height: 300px;
            background-color: var(--primary);
            top: -100px;
            left: -100px;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 200px;
            height: 200px;
            background-color: var(--accent);
            bottom: -50px;
            right: 10%;
            animation-delay: 2s;
        }

        .shape-3 {
            width: 150px;
            height: 150px;
            background-color: var(--secondary);
            top: 20%;
            right: -50px;
            animation-delay: 4s;
        }

        .container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
            z-index: 10;
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
            animation: fadeIn 1s ease-out;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .logo-img {
            width: 60px;
            height: 60px;
            margin-right: 15px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 1.8rem;
            border: 2px solid rgba(255,255,255,0.3);
        }

        .logo-text {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 600;
            color: var(--white);
        }

        .logo-text span {
            color: var(--secondary);
        }

        .logo p {
            color: rgba(255,255,255,0.8);
            font-size: 1rem;
            margin-top: 5px;
        }

        .form-container {
            background-color: var(--white);
            border-radius: 15px;
            padding: 30px;
            box-shadow: var(--shadow);
            animation: slideUp 0.8s ease-out;
        }

        .form-toggle {
            display: flex;
            margin-bottom: 25px;
            border-radius: 10px;
            overflow: hidden;
            background-color: #f5f5f5;
        }

        .toggle-btn {
            flex: 1;
            padding: 12px;
            text-align: center;
            background: transparent;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            color: var(--text-light);
        }

        .toggle-btn.active {
            background-color: var(--primary);
            color: white;
        }

        .form {
            display: none;
        }

        .form.active {
            display: block;
            animation: fadeIn 0.5s ease-out;
        }

        .form-title {
            margin-bottom: 20px;
            text-align: center;
            color: var(--dark);
            font-weight: 500;
            font-size: 1.5rem;
        }

        .input-group {
            margin-bottom: 20px;
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
        }

        .input-group input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: var(--transition);
            background-color: #f9f9f9;
            font-family: 'Poppins', sans-serif;
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(139, 115, 85, 0.2);
            background-color: white;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .remember-forgot label {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .remember-forgot input {
            margin-right: 8px;
        }

        .remember-forgot a {
            color: var(--primary);
            text-decoration: none;
            transition: var(--transition);
        }

        .remember-forgot a:hover {
            color: var(--accent);
            text-decoration: underline;
        }

        .btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            background-color: var(--primary);
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
        }

        .btn:hover {
            background-color: var(--accent);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .btn i {
            margin-left: 8px;
        }

        .message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .message.error {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }

        .message.success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }

        .social-login {
            margin-top: 25px;
            text-align: center;
        }

        .social-login p {
            margin-bottom: 15px;
            color: var(--text);
            font-size: 0.9rem;
            position: relative;
        }

        .social-login p::before, .social-login p::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 30%;
            height: 1px;
            background-color: #e0e0e0;
        }

        .social-login p::before {
            left: 0;
        }

        .social-login p::after {
            right: 0;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f5f5f5;
            color: var(--text);
            transition: var(--transition);
            cursor: pointer;
        }

        .social-icon:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        .social-icon.facebook:hover {
            background-color: #3b5998;
            color: white;
        }

        .social-icon.google:hover {
            background-color: #dd4b39;
            color: white;
        }

        .social-icon.apple:hover {
            background-color: #000000;
            color: white;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            33% {
                transform: translateY(-20px) rotate(120deg);
            }
            66% {
                transform: translateY(10px) rotate(240deg);
            }
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .container {
                padding: 15px;
            }
            
            .form-container {
                padding: 20px;
            }
            
            .logo-text {
                font-size: 2rem;
            }
            
            .logo-img {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
            }
        }
    </style>
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

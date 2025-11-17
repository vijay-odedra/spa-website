<?php
// Database connection
$servername = "localhost"; 
$username = "root";        
$password = "";            
$dbname = "spa-system";        

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name'])) {

    $name       = $conn->real_escape_string($_POST['name']);
    $email      = $conn->real_escape_string($_POST['email']);
    $phone      = $conn->real_escape_string($_POST['phone']);
    $service    = $conn->real_escape_string($_POST['service']);
    $date       = $conn->real_escape_string($_POST['date']);
    $time       = $conn->real_escape_string($_POST['time']);
    $therapist  = $conn->real_escape_string($_POST['therapist']);
    $notes      = $conn->real_escape_string($_POST['notes']);

    // Insert query
    $sql = "INSERT INTO appointments (name, email, phone, service, date, time, therapist, notes)
            VALUES ('$name', '$email', '$phone', '$service', '$date', '$time', '$therapist', '$notes')";

    if ($conn->query($sql) === TRUE) {
        $success_message = "Appointment booked successfully!";
        echo "<script>alert('Appointment booked successfully!');</script>";
        // No redirect - stays on same page
    } else {
        $error_message = "Error: " . $conn->error;
        echo "<script>alert('Error booking appointment: " . addslashes($conn->error) . "');</script>";
    }
}

$conn->close();
?>
<?php
session_start();

// Check if user is logged in
$currentUser = null;
if (isset($_SESSION['user'])) {
    $currentUser = $_SESSION['user'];
}

// Handle logout
if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    session_destroy();
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relaxo Spa - Premium Wellness & Relaxation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <nav class="navbar">
                <div class="logo-container">
                    <div class="logo-img">
                        <i class="fas fa-spa"></i>
                    </div>
                    <a href="#" class="logo-text" data-page="home">Relaxo<span>Spa</span></a>
                </div>
                
                <ul class="nav-links">
                    <li><a href="#" data-page="home">Home</a></li>
                    <li><a href="#" data-page="about">About Us</a></li>
                    <li><a href="#" data-page="services">Services</a></li>
                    <li><a href="#" data-page="packages">Packages</a></li>
                    <li><a href="#" data-page="blog">Blog</a></li>
                    <li><a href="#" data-page="contact">Contact</a></li>
                </ul>
                
                <div class="nav-btns">
                    <a href="#" class="btn" data-page="booking">Book Now</a>
                    
                    <!-- User Profile (shown when logged in) -->
                    <div class="user-profile" id="userProfile" style="display: none;">
                        <div class="user-avatar" id="userAvatar">U</div>
                        <div class="user-name user-name-desktop" id="userName">User</div>
                        <div class="user-name user-name-mobile" id="userNameMobile" style="display: none;">U</div>
                        <div class="user-dropdown" id="userDropdown">
                            <a href="#"><i class="fas fa-user"></i> My Profile</a>
                            <a href="#"><i class="fas fa-calendar-alt"></i> My Appointments</a>
                            <a href="#" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </div>
                    </div>
                    
                    <!-- Login/Signup Links (shown when not logged in) -->
                    <div class="auth-links" id="authLinks">
                        <a href="login_singin.php" class="auth-link" id="loginLink">Login</a>
                    </div>
                    
                    <button class="menu-toggle" id="menuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>  
            </nav>
        </div>
    </header>

    <!-- Mobile Navigation -->
    <div class="mobile-nav" id="mobileNav">
        <button class="mobile-nav-close" id="mobileNavClose">
            <i class="fas fa-times"></i>
        </button>
        
        <ul class="mobile-nav-links">
            <li><a href="#" data-page="home">Home</a></li>
            <li><a href="#" data-page="about">About Us</a></li>
            <li><a href="#" data-page="services">Services</a></li>
            <li><a href="#" data-page="packages">Packages</a></li>
            <li><a href="#" data-page="blog">Blog</a></li>
            <li><a href="#" data-page="contact">Contact</a></li>
        </ul>
        
        <div class="mobile-nav-btns">
            <a href="#" class="btn" data-page="booking">Book Now</a>
            
            <!-- Mobile Login/User Profile -->
            <div id="mobileAuthSection">
                <!-- Login link shown when not logged in -->
                <a href="login_singin.php" class="btn btn-secondary" id="mobileLoginLink">Login</a>
                
                <!-- User profile shown when logged in - Mobile shows only avatar -->
                <div class="user-profile-mobile" id="mobileUserProfile" style="display: none;">
                    <div class="user-info">
                        <div class="user-avatar" id="mobileUserAvatar">U</div>
                        <!-- Mobile view shows only avatar, no name -->
                    </div>
                    <div class="user-actions">
                        <a href="#"><i class="fas fa-user"></i> My Profile</a>
                        <a href="#"><i class="fas fa-calendar-alt"></i> My Appointments</a>
                        <a href="#" id="mobileLogoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Home Page -->
    <div id="home" class="page active">
        <!-- Hero Section -->
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <h1>Rejuvenate Your Mind, Body & Soul</h1>
                    <p>Experience ultimate relaxation and wellness with our premium spa treatments designed to restore your natural balance and inner peace.</p>
                    <div class="hero-btns">
                        <a href="#" class="btn" data-page="services">Explore Services</a>
                        <a href="#" class="btn btn-secondary" data-page="booking">Book Appointment</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section id="services-preview">
            <div class="container">
                <div class="section-title">
                    <h2>Our Premium Services</h2>
                    <p>Indulge in our carefully curated treatments designed to provide complete relaxation and rejuvenation.</p>
                </div>
                
                <div class="services-grid">
                    <div class="service-card">
                        <div class="service-img">
                            <img src="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Massage Therapy">
                        </div>
                        <div class="service-content">
                            <h3>Massage Therapy</h3>
                            <p>Relieve tension and improve circulation with our therapeutic massage techniques.</p>
                            <div class="service-price">$85 - 60 min</div>
                            <a href="#" class="btn" data-page="service-detail">Book Now</a>
                        </div>
                    </div>
                    
                    <div class="service-card">
                        <div class="service-img">
                            <img src="https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Facial Treatment">
                        </div>
                        <div class="service-content">
                            <h3>Facial Treatment</h3>
                            <p>Revitalize your skin with our customized facial treatments using premium products.</p>
                            <div class="service-price">$75 - 50 min</div>
                            <a href="#" class="btn" data-page="service-detail">Book Now</a>
                        </div>
                    </div>
                    
                    <div class="service-card">
                        <div class="service-img">
                            <img src="https://images.unsplash.com/photo-1591228127791-8e2eaef098d3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Ayurveda Therapy">
                        </div>
                        <div class="service-content">
                            <h3>Ayurveda Therapy</h3>
                            <p>Restore balance with ancient Ayurvedic treatments for holistic wellness.</p>
                            <div class="service-price">$95 - 75 min</div>
                            <a href="#" class="btn" data-page="service-detail">Book Now</a>
                        </div>
                    </div>
                    
                    <div class="service-card">
                        <div class="service-img">
                            <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Beauty Treatment">
                        </div>
                        <div class="service-content">
                            <h3>Beauty Treatment</h3>
                            <p>Enhance your natural beauty with our specialized beauty and grooming services.</p>
                            <div class="service-price">$65 - 45 min</div>
                            <a href="#" class="btn" data-page="service-detail">Book Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="stats">
            <div class="container">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number" data-count="5000">0</div>
                        <div class="stat-text">Happy Clients</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" data-count="12">0</div>
                        <div class="stat-text">Years Experience</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" data-count="25">0</div>
                        <div class="stat-text">Expert Therapists</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" data-count="50">0</div>
                        <div class="stat-text">Services & Treatments</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="testimonials">
            <div class="container">
                <div class="section-title">
                    <h2>What Our Clients Say</h2>
                    <p>Discover why our clients keep coming back for our exceptional spa experiences.</p>
                </div>
                
                <div class="testimonial-slider">
                    <div class="testimonial-slide active">
                        <div class="client-img">
                            <img src="../spa-website/photos/IMG_4420.jpeg" alt="Jessica Miller">
                        </div>
                        <div class="testimonial-text">
                            <p>"The best spa experience I've ever had! The therapists are incredibly skilled and the atmosphere is so peaceful. I left feeling completely rejuvenated."</p>
                        </div>
                        <div class="client-name">Jessica Miller</div>
                    </div>
                    
                    <div class="testimonial-slide">
                        <div class="client-img">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1974&q=80" alt="Michael Thompson">
                        </div>
                        <div class="testimonial-text">
                            <p>"As someone with chronic back pain, the therapeutic massage at Relaxo Spa has been life-changing. The therapists truly understand how to target problem areas."</p>
                        </div>
                        <div class="client-name">Michael Thompson</div>
                    </div>
                    
                    <div class="testimonial-slide">
                        <div class="client-img">
                            <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Sophia Williams">
                        </div>
                        <div class="testimonial-text">
                            <p>"The facial treatment transformed my skin! My complexion has never looked better. I can't recommend Relaxo Spa enough for their skincare expertise."</p>
                        </div>
                        <div class="client-name">Sophia Williams</div>
                    </div>
                    
                    <div class="slider-dots">
                        <span class="dot active" data-slide="0"></span>
                        <span class="dot" data-slide="1"></span>
                        <span class="dot" data-slide="2"></span>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- About Page -->
    <div id="about" class="page">
        <section class="about-header">
            <div class="container">
                <h1>About Relaxo Spa</h1>
                <p>Discover our story, mission, and the team behind your wellness journey</p>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="section-title">
                    <h2>Our Journey</h2>
                    <p>From a small wellness center to a premier spa destination</p>
                </div>
                
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-year">2010</div>
                            <h3>Our Humble Beginnings</h3>
                            <p>Relaxo Spa opened its doors with just three treatment rooms and a vision to provide authentic wellness experiences.</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-year">2013</div>
                            <h3>Expansion & Growth</h3>
                            <p>We expanded our facility to include a meditation garden and added Ayurvedic treatments to our services.</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-year">2016</div>
                            <h3>Award Recognition</h3>
                            <p>Received the "Best Wellness Center" award for our innovative approach to holistic treatments.</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-year">2020</div>
                            <h3>Digital Transformation</h3>
                            <p>Launched our online booking platform and virtual wellness consultations to adapt to changing times.</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-year">2023</div>
                            <h3>Present Day</h3>
                            <p>Now serving over 5,000 clients with a team of 25 expert therapists and 50+ treatments.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="testimonials">
            <div class="container">
                <div class="section-title">
                    <h2>Our Values</h2>
                    <p>The principles that guide everything we do</p>
                </div>
                
                <div class="services-grid">
                    <div class="service-card">
                        <div class="service-content">
                            <div class="value-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <h3>Wellness Focused</h3>
                            <p>Every treatment is designed with your holistic wellbeing in mind, addressing mind, body, and spirit.</p>
                        </div>
                    </div>
                    
                    <div class="service-card">
                        <div class="service-content">
                            <div class="value-icon">
                                <i class="fas fa-award"></i>
                            </div>
                            <h3>Expert Therapists</h3>
                            <p>Our certified professionals have years of experience and continuous training in the latest techniques.</p>
                        </div>
                    </div>
                    
                    <div class="service-card">
                        <div class="service-content">
                            <div class="value-icon">
                                <i class="fas fa-leaf"></i>
                            </div>
                            <h3>Natural Products</h3>
                            <p>We use only the highest quality, natural and organic products that are ethically sourced and sustainable.</p>
                        </div>
                    </div>
                    
                    <div class="service-card">
                        <div class="service-content">
                            <div class="value-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3>Client-Centered</h3>
                            <p>Your comfort and satisfaction are our top priorities, with personalized treatments for your unique needs.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="team">
            <div class="container">
                <div class="section-title">
                    <h2>Meet Our Expert Team</h2>
                    <p>Our certified therapists are dedicated to providing you with an exceptional spa experience.</p>
                </div>
                
                <div class="team-grid">
                    <div class="team-member">
                        <div class="member-img">
                            <img src="../spa-website/photos/1.jpg" alt="Sarah Johnson">
                        </div>
                        <div class="member-info">
                            <h3>Sarah Johnson</h3>
                            <div class="member-role">Head Massage Therapist</div>
                            <p>10+ years experience in therapeutic and relaxation massage techniques.</p>
                        </div>
                    </div>
                    
                    <div class="team-member">
                        <div class="member-img">
                            <img src="https://images.unsplash.com/photo-1584697964358-3e14ca57658b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Maya Patel">
                        </div>
                        <div class="member-info">
                            <h3>Maya Patel</h3>
                            <div class="member-role">Skincare Specialist</div>
                            <p>Certified esthetician with expertise in advanced facial treatments.</p>
                        </div>
                    </div>
                    
                    <div class="team-member">
                        <div class="member-img">
                            <img src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="David Chen">
                        </div>
                        <div class="member-info">
                            <h3>David Chen</h3>
                            <div class="member-role">Ayurveda Practitioner</div>
                            <p>Traditional Ayurvedic therapist trained in Kerala, India.</p>
                        </div>
                    </div>
                    
                    <div class="team-member">
                        <div class="member-img">
                            <img src="../spa-website/photos/elena-rodriguez_da39.webp" alt="Elena Rodriguez">
                        </div>
                        <div class="member-info">
                            <h3>Elena Rodriguez</h3>
                            <div class="member-role">Beauty Therapist</div>
                            <p>Specialized in holistic beauty treatments and wellness therapies.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Services Page -->
    <div id="services" class="page">
        <section class="services-header">
            <div class="container">
                <h1>Our Services</h1>
                <p>Discover our range of premium treatments designed for your complete wellness</p>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="section-title">
                    <h2>Service Categories</h2>
                    <p>Explore our comprehensive range of wellness treatments</p>
                </div>
                
                <div class="service-categories">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-spa"></i>
                        </div>
                        <h3>Massage Therapies</h3>
                        <p>From relaxing Swedish to therapeutic deep tissue, our massages relieve tension and promote healing.</p>
                        <a href="#" class="btn" data-page="service-detail">View Services</a>
                    </div>
                    
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-hand-sparkles"></i>
                        </div>
                        <h3>Skin Care</h3>
                        <p>Revitalize your skin with our customized facials, peels, and advanced skincare treatments.</p>
                        <a href="#" class="btn" data-page="service-detail">View Services</a>
                    </div>
                    
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h3>Ayurveda</h3>
                        <p>Ancient healing traditions to restore balance and promote holistic wellness.</p>
                        <a href="#" class="btn" data-page="service-detail">View Services</a>
                    </div>
                    
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h3>Beauty Treatments</h3>
                        <p>Enhance your natural beauty with our specialized grooming and beauty services.</p>
                        <a href="#" class="btn" data-page="service-detail">View Services</a>
                    </div>
                </div>
                
                <div class="section-title">
                    <h2>All Services</h2>
                    <p>Browse our complete menu of treatments</p>
                </div>
                
                <div class="filter-buttons">
                    <button class="filter-btn active" data-filter="all">All Services</button>
                    <button class="filter-btn" data-filter="massage">Massage</button>
                    <button class="filter-btn" data-filter="skincare">Skin Care</button>
                    <button class="filter-btn" data-filter="ayurveda">Ayurveda</button>
                    <button class="filter-btn" data-filter="therapy">Therapy</button>
                </div>
                
                <div class="services-grid">
                    <div class="service-card" data-category="massage">
                        <div class="service-img">
                            <img src="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Swedish Massage">
                        </div>
                        <div class="service-content">
                            <h3>Swedish Massage</h3>
                            <p>Relaxing full-body massage using long, flowing strokes to ease muscle tension.</p>
                            <div class="service-price">$85 - 60 min</div>
                            <a href="#" class="btn" data-page="service-detail">Book Now</a>
                        </div>
                    </div>
                    
                    <div class="service-card" data-category="massage">
                        <div class="service-img">
                            <img src="https://images.unsplash.com/photo-1600334129128-685c5582fd35?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Deep Tissue Massage">
                        </div>
                        <div class="service-content">
                            <h3>Deep Tissue Massage</h3>
                            <p>Targeted pressure to relieve chronic muscle tension and pain.</p>
                            <div class="service-price">$95 - 60 min</div>
                            <a href="#" class="btn" data-page="service-detail">Book Now</a>
                        </div>
                    </div>
                    
                    <div class="service-card" data-category="skincare">
                        <div class="service-img">
                            <img src="https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Hydrating Facial">
                        </div>
                        <div class="service-content">
                            <h3>Hydrating Facial</h3>
                            <p>Intensive moisture treatment to replenish and plump the skin.</p>
                            <div class="service-price">$75 - 50 min</div>
                            <a href="#" class="btn" data-page="service-detail">Book Now</a>
                        </div>
                    </div>
                    
                    <div class="service-card" data-category="skincare">
                        <div class="service-img">
                            <img src="https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Anti-Aging Facial">
                        </div>
                        <div class="service-content">
                            <h3>Anti-Aging Facial</h3>
                            <p>Targeted treatment to reduce fine lines and improve skin elasticity.</p>
                            <div class="service-price">$90 - 60 min</div>
                            <a href="#" class="btn" data-page="service-detail">Book Now</a>
                        </div>
                    </div>
                    
                    <div class="service-card" data-category="ayurveda">
                        <div class="service-img">
                            <img src="https://images.unsplash.com/photo-1591228127791-8e2eaef098d3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Abhyanga Massage">
                        </div>
                        <div class="service-content">
                            <h3>Abhyanga Massage</h3>
                            <p>Traditional Ayurvedic full-body massage with warm herbal oils.</p>
                            <div class="service-price">$95 - 75 min</div>
                            <a href="#" class="btn" data-page="service-detail">Book Now</a>
                        </div>
                    </div>
                    
                    <div class="service-card" data-category="therapy">
                        <div class="service-img">
                            <img src="https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2120&q=80" alt="Hot Stone Therapy">
                        </div>
                        <div class="service-content">
                            <h3>Hot Stone Therapy</h3>
                            <p>Deep muscle relaxation using smooth, heated stones.</p>
                            <div class="service-price">$110 - 75 min</div>
                            <a href="#" class="btn" data-page="service-detail">Book Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Service Detail Page -->
    <div id="service-detail" class="page">
        <section class="service-detail">
            <div class="container">
                <div class="service-detail-content">
                    <div class="service-detail-img">
                        <img src="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Swedish Massage">
                    </div>
                    <div class="service-info">
                        <h1>Swedish Massage</h1>
                        <div class="service-meta">
                            <div class="service-price">$85</div>
                            <div class="service-duration">
                                <i class="far fa-clock"></i>
                                <span>60 minutes</span>
                            </div>
                        </div>
                        <div class="rating">
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span>4.5 (128 reviews)</span>
                        </div>
                        <p>Our signature Swedish massage is the perfect way to melt away stress and tension. Using long, flowing strokes, kneading, and circular movements, this treatment improves circulation, eases muscle aches, and promotes deep relaxation.</p>
                        
                        <h3>Benefits</h3>
                        <ul class="benefits-list">
                            <li><i class="fas fa-check"></i> Relieves muscle tension and stiffness</li>
                            <li><i class="fas fa-check"></i> Improves blood circulation</li>
                            <li><i class="fas fa-check"></i> Reduces stress and anxiety</li>
                            <li><i class="fas fa-check"></i> Enhances flexibility and range of motion</li>
                            <li><i class="fas fa-check"></i> Promotes better sleep</li>
                        </ul>
                        
                        <a href="#" class="btn" data-page="booking">Book This Service</a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Booking Page -->
    <div id="booking" class="page">
        <section class="booking-page">
            <div class="container">
                <div class="section-title">
                    <h2>Book Your Appointment</h2>
                    <p>Schedule your wellness journey with us</p>
                </div>
                
                <div class="booking-form">
                    <?php if (isset($success_message)): ?>
                        <div style="background-color: #d4edda; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                            <?php echo $success_message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($error_message)): ?>
                        <div style="background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form id="appointment-form" method="POST" action="">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="service">Select Service</label>
                                <select id="service" name="service" class="form-control" required>
                                    <option value="">Choose a service</option>
                                    <option value="swedish-massage">Swedish Massage - $85</option>
                                    <option value="deep-tissue">Deep Tissue Massage - $95</option>
                                    <option value="hydrating-facial">Hydrating Facial - $75</option>
                                    <option value="anti-aging-facial">Anti-Aging Facial - $90</option>
                                    <option value="abhyanga">Abhyanga Massage - $95</option>
                                    <option value="hot-stone">Hot Stone Therapy - $110</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="date">Preferred Date</label>
                                <input type="date" name="date" id="date" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="time">Preferred Time</label>
                                <select id="time" name="time" class="form-control" required>
                                    <option value="">Select a time</option>
                                    <option value="09:00">9:00 AM</option>
                                    <option value="10:00">10:00 AM</option>
                                    <option value="11:00">11:00 AM</option>
                                    <option value="12:00">12:00 PM</option>
                                    <option value="13:00">1:00 PM</option>
                                    <option value="14:00">2:00 PM</option>
                                    <option value="15:00">3:00 PM</option>
                                    <option value="16:00">4:00 PM</option>
                                    <option value="17:00">5:00 PM</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="therapist">Preferred Therapist (Optional)</label>
                            <select id="therapist" name="therapist" class="form-control">
                                <option value="">No preference</option>
                                <option value="sarah">Sarah Johnson</option>
                                <option value="maya">Maya Patel</option>
                                <option value="david">David Chen</option>
                                <option value="elena">Elena Rodriguez</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="notes">Special Requests or Notes</label>
                            <textarea id="notes" name="notes" class="form-control" rows="4"></textarea>
                        </div>
                        
                        <?php if ($currentUser): ?>
    <!-- User is logged in - show booking button -->
    <button type="submit" class="btn" style="width: 100%;">Book Appointment</button>
<?php else: ?>
    <!-- User not logged in - show login button -->
    <a href="login_singin.php" class="btn" style="width: 100%; display: block; text-align: center;">
        Login to Book Appointment
    </a>
<?php endif; ?>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <!-- Packages Page -->
    <div id="packages" class="page">
        <section class="packages-header">
            <div class="container">
                <h1>Membership & Packages</h1>
                <p>Discover our special offers and membership plans for regular wellness</p>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="section-title">
                    <h2>Wellness Packages</h2>
                    <p>Our curated packages offer the perfect combination of treatments for complete rejuvenation</p>
                </div>
                
                <div class="packages-grid">
                    <div class="package-card">
                        <div class="package-header">
                            <h3>Relaxation Package</h3>
                            <div class="package-price">$199</div>
                        </div>
                        <div class="package-body">
                            <p>Perfect for stress relief and deep relaxation</p>
                            <ul class="package-features">
                                <li><i class="fas fa-check"></i> 60-min Swedish Massage</li>
                                <li><i class="fas fa-check"></i> 30-min Foot Reflexology</li>
                                <li><i class="fas fa-check"></i> Aromatherapy Session</li>
                                <li><i class="fas fa-check"></i> Herbal Tea Service</li>
                            </ul>
                            <a href="#" class="btn" data-page="booking">Book Package</a>
                        </div>
                    </div>
                    
                    <div class="package-card featured">
                        <div class="package-header">
                            <h3>Ultimate Rejuvenation</h3>
                            <div class="package-price">$349</div>
                        </div>
                        <div class="package-body">
                            <p>Our most popular package for complete mind-body renewal</p>
                            <ul class="package-features">
                                <li><i class="fas fa-check"></i> 90-min Hot Stone Therapy</li>
                                <li><i class="fas fa-check"></i> 60-min Hydrating Facial</li>
                                <li><i class="fas fa-check"></i> 30-min Scalp Treatment</li>
                                <li><i class="fas fa-check"></i> Complimentary Lunch</li>
                                <li><i class="fas fa-check"></i> Gift Voucher ($50 value)</li>
                            </ul>
                            <a href="#" class="btn" data-page="booking">Book Package</a>
                        </div>
                    </div>
                    
                    <div class="package-card">
                        <div class="package-header">
                            <h3>Couples Retreat</h3>
                            <div class="package-price">$449</div>
                        </div>
                        <div class="package-body">
                            <p>Share the wellness experience with your loved one</p>
                            <ul class="package-features">
                                <li><i class="fas fa-check"></i> Side-by-Side Massages (60 min)</li>
                                <li><i class="fas fa-check"></i> Couples Aromatherapy Session</li>
                                <li><i class="fas fa-check"></i> Private Relaxation Suite</li>
                                <li><i class="fas fa-check"></i> Champagne & Chocolate</li>
                            </ul>
                            <a href="#" class="btn" data-page="booking">Book Package</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="testimonials">
            <div class="container">
                <div class="section-title">
                    <h2>Membership Plans</h2>
                    <p>Join our wellness community with exclusive benefits</p>
                </div>
                
                <div class="packages-grid">
                    <div class="package-card">
                        <div class="package-header">
                            <h3>Basic Membership</h3>
                            <div class="package-price">$79<span>/month</span></div>
                        </div>
                        <div class="package-body">
                            <ul class="package-features">
                                <li><i class="fas fa-check"></i> 1 Treatment per month</li>
                                <li><i class="fas fa-check"></i> 15% off additional services</li>
                                <li><i class="fas fa-check"></i> Priority booking</li>
                                <li><i class="fas fa-check"></i> Wellness newsletter</li>
                            </ul>
                            <a href="#" class="btn">Join Now</a>
                        </div>
                    </div>
                    
                    <div class="package-card featured">
                        <div class="package-header">
                            <h3>Premium Membership</h3>
                            <div class="package-price">$149<span>/month</span></div>
                        </div>
                        <div class="package-body">
                            <ul class="package-features">
                                <li><i class="fas fa-check"></i> 2 Treatments per month</li>
                                <li><i class="fas fa-check"></i> 25% off additional services</li>
                                <li><i class="fas fa-check"></i> Unlimited sauna access</li>
                                <li><i class="fas fa-check"></i> Complimentary products</li>
                                <li><i class="fas fa-check"></i> Guest passes (2 per year)</li>
                            </ul>
                            <a href="#" class="btn">Join Now</a>
                        </div>
                    </div>
                    
                    <div class="package-card">
                        <div class="package-header">
                            <h3>Elite Membership</h3>
                            <div class="package-price">$249<span>/month</span></div>
                        </div>
                        <div class="package-body">
                            <ul class="package-features">
                                <li><i class="fas fa-check"></i> 4 Treatments per month</li>
                                <li><i class="fas fa-check"></i> 30% off additional services</li>
                                <li><i class="fas fa-check"></i> Personal wellness consultant</li>
                                <li><i class="fas fa-check"></i> Exclusive event invitations</li>
                                <li><i class="fas fa-check"></i> Quarterly gift packages</li>
                            </ul>
                            <a href="#" class="btn">Join Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Blog Page -->
    <div id="blog" class="page">
        <section class="blog-header">
            <div class="container">
                <h1>Wellness Blog</h1>
                <p>Tips, insights, and inspiration for your wellness journey</p>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="blog-grid">
                    <div class="blog-card">
                        <div class="blog-img">
                            <img src="https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2120&q=80" alt="Benefits of Regular Massage">
                        </div>
                        <div class="blog-content">
                            <div class="blog-meta">
                                <span class="blog-date"><i class="far fa-calendar"></i> June 15, 2023</span>
                                <span class="blog-category">Wellness</span>
                            </div>
                            <h3>5 Benefits of Regular Massage Therapy</h3>
                            <p class="blog-excerpt">Discover how incorporating regular massage into your wellness routine can transform your physical and mental health.</p>
                            <a href="#" class="btn btn-secondary">Read More</a>
                        </div>
                    </div>
                    
                    <div class="blog-card">
                        <div class="blog-img">
                            <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Skincare Routine">
                        </div>
                        <div class="blog-content">
                            <div class="blog-meta">
                                <span class="blog-date"><i class="far fa-calendar"></i> May 28, 2023</span>
                                <span class="blog-category">Skincare</span>
                            </div>
                            <h3>Building the Perfect Skincare Routine for Your Skin Type</h3>
                            <p class="blog-excerpt">Learn how to customize your skincare regimen based on your unique skin needs for a radiant complexion.</p>
                            <a href="#" class="btn btn-secondary">Read More</a>
                        </div>
                    </div>
                    
                    <div class="blog-card">
                        <div class="blog-img">
                            <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Meditation Techniques">
                        </div>
                        <div class="blog-content">
                            <div class="blog-meta">
                                <span class="blog-date"><i class="far fa-calendar"></i> May 12, 2023</span>
                                <span class="blog-category">Mindfulness</span>
                            </div>
                            <h3>5 Meditation Techniques to Reduce Stress</h3>
                            <p class="blog-excerpt">Simple yet powerful meditation practices you can incorporate into your daily routine for mental clarity and peace.</p>
                            <a href="#" class="btn btn-secondary">Read More</a>
                        </div>
                    </div>
                    
                    <div class="blog-card">
                        <div class="blog-img">
                            <img src="https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2120&q=80" alt="Ayurvedic Principles">
                        </div>
                        <div class="blog-content">
                            <div class="blog-meta">
                                <span class="blog-date"><i class="far fa-calendar"></i> April 30, 2023</span>
                                <span class="blog-category">Ayurveda</span>
                            </div>
                            <h3>Understanding Ayurvedic Principles for Modern Living</h3>
                            <p class="blog-excerpt">How ancient Ayurvedic wisdom can help balance your mind and body in today's fast-paced world.</p>
                            <a href="#" class="btn btn-secondary">Read More</a>
                        </div>
                    </div>
                    
                    <div class="blog-card">
                        <div class="blog-img">
                            <img src="https://images.unsplash.com/photo-1515377905703-c4788e51af15?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Aromatherapy Benefits">
                        </div>
                        <div class="blog-content">
                            <div class="blog-meta">
                                <span class="blog-date"><i class="far fa-calendar"></i> April 18, 2023</span>
                                <span class="blog-category">Aromatherapy</span>
                            </div>
                            <h3>The Healing Power of Essential Oils</h3>
                            <p class="blog-excerpt">Explore how different essential oils can support emotional wellbeing and physical health.</p>
                            <a href="#" class="btn btn-secondary">Read More</a>
                        </div>
                    </div>
                    
                    <div class="blog-card">
                        <div class="blog-img">
                            <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Self-Care Practices">
                        </div>
                        <div class="blog-content">
                            <div class="blog-meta">
                                <span class="blog-date"><i class="far fa-calendar"></i> April 5, 2023</span>
                                <span class="blog-category">Self-Care</span>
                            </div>
                            <h3>10 Self-Care Practices for Busy Professionals</h3>
                            <p class="blog-excerpt">Simple ways to incorporate self-care into your hectic schedule without adding more stress.</p>
                            <a href="#" class="btn btn-secondary">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Contact Page -->
    <div id="contact" class="page">
        <section class="contact-page">
            <div class="container">
                <div class="section-title">
                    <h2>Contact Us</h2>
                    <p>Get in touch to schedule your appointment or ask any questions</p>
                </div>
                
                <div class="contact-content">
                    <div class="contact-info">
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-text">
                                <h4>Our Location</h4>
                                <p>123 Wellness Street, Serenity City, SC 12345</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-text">
                                <h4>Phone Number</h4>
                                <p>+1 (555) 123-4567</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-text">
                                <h4>Email Address</h4>
                                <p>info@relaxospa.com</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="contact-text">
                                <h4>Working Hours</h4>
                                <p>Monday - Sunday: 9:00 AM - 8:00 PM</p>
                            </div>
                        </div>
                        
                        <div class="social-links">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-pinterest"></i></a>
                        </div>
                    </div>
                    
                    <div class="contact-form">
                        <form id="contact-form">
                            <div class="form-group">
                                <label for="contact-name">Your Name</label>
                                <input type="text" id="contact-name" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="contact-email">Email Address</label>
                                <input type="email" id="contact-email" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="contact-subject">Subject</label>
                                <input type="text" id="contact-subject" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="contact-message">Message</label>
                                <textarea id="contact-message" class="form-control" rows="5" required></textarea>
                            </div>
                            
                            <button type="submit" class="btn">Send Message</button>
                        </form>
                    </div>
                </div>
                
                <div class="map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3023.9503398796587!2d-73.9940307!3d40.7191097!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a27e2f24131%3A0x64ffc98d24069f02!2sCANARY!5e0!3m2!1sen!2sus!4v1629787671333!5m2!1sen!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-col">
                    <h3>Relaxo Spa</h3>
                    <p>Your sanctuary for wellness and rejuvenation. Experience the perfect balance of traditional and modern spa treatments.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
                
                <div class="footer-col">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="#" data-page="home">Home</a></li>
                        <li><a href="#" data-page="about">About Us</a></li>
                        <li><a href="#" data-page="services">Services</a></li>
                        <li><a href="#" data-page="packages">Packages</a></li>
                        <li><a href="#" data-page="blog">Blog</a></li>
                        <li><a href="#" data-page="contact">Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h3>Services</h3>
                    <ul>
                        <li><a href="#" data-page="service-detail">Massage Therapy</a></li>
                        <li><a href="#" data-page="service-detail">Facial Treatment</a></li>
                        <li><a href="#" data-page="service-detail">Ayurveda Therapy</a></li>
                        <li><a href="#" data-page="service-detail">Beauty Treatment</a></li>
                        <li><a href="#" data-page="service-detail">Couple Packages</a></li>
                        <li><a href="#" data-page="service-detail">Corporate Wellness</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h3>Contact Info</h3>
                    <ul>
                        <li><i class="fas fa-map-marker-alt"></i> 123 Wellness Street, Serenity City</li>
                        <li><i class="fas fa-phone"></i> +1 (555) 123-4567</li>
                        <li><i class="fas fa-envelope"></i> info@relaxospa.com</li>
                        <li><i class="fas fa-clock"></i> Mon-Sun: 9:00 AM - 8:00 PM</li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                <p>&copy; 2023 Relaxo Spa. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Page Navigation
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Menu Toggle
            const menuToggle = document.getElementById('menuToggle');
            const mobileNav = document.getElementById('mobileNav');
            const mobileNavClose = document.getElementById('mobileNavClose');
            
            menuToggle.addEventListener('click', function() {
                mobileNav.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
            
            mobileNavClose.addEventListener('click', function() {
                mobileNav.classList.remove('active');
                document.body.style.overflow = 'auto';
            });
            
            // Close mobile nav when clicking on a link
            document.querySelectorAll('.mobile-nav-links a, .mobile-nav-btns a').forEach(link => {
                link.addEventListener('click', function() {
                    mobileNav.classList.remove('active');
                    document.body.style.overflow = 'auto';
                });
            });

            // Page Navigation
            const pageLinks = document.querySelectorAll('a[data-page]');
            pageLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetPage = this.getAttribute('data-page');
                    
                    // Hide all pages
                    document.querySelectorAll('.page').forEach(page => {
                        page.classList.remove('active');
                    });
                    
                    // Show target page
                    document.getElementById(targetPage).classList.add('active');
                    
                    // Scroll to top
                    window.scrollTo(0, 0);
                });
            });

            // Testimonial Slider
            const dots = document.querySelectorAll('.dot');
            const slides = document.querySelectorAll('.testimonial-slide');
            
            dots.forEach(dot => {
                dot.addEventListener('click', function() {
                    const slideIndex = this.getAttribute('data-slide');
                    
                    // Remove active class from all dots and slides
                    dots.forEach(d => d.classList.remove('active'));
                    slides.forEach(s => s.classList.remove('active'));
                    
                    // Add active class to current dot and slide
                    this.classList.add('active');
                    slides[slideIndex].classList.add('active');
                });
            });

            // Auto slide testimonials
            let currentSlide = 0;
            function autoSlide() {
                currentSlide = (currentSlide + 1) % slides.length;
                
                dots.forEach(d => d.classList.remove('active'));
                slides.forEach(s => s.classList.remove('active'));
                
                dots[currentSlide].classList.add('active');
                slides[currentSlide].classList.add('active');
            }
            
            setInterval(autoSlide, 5000);

            // Service Filtering
            const filterBtns = document.querySelectorAll('.filter-btn');
            const serviceCards = document.querySelectorAll('.service-card');
            
            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterBtns.forEach(b => b.classList.remove('active'));
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    const filterValue = this.getAttribute('data-filter');
                    
                    // Show/hide service cards based on filter
                    serviceCards.forEach(card => {
                        if (filterValue === 'all' || card.getAttribute('data-category') === filterValue) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });

            // Form Submissions
            document.getElementById('contact-form').addEventListener('submit', function(e) {
                e.preventDefault();
                alert('Thank you for your message! We will get back to you soon.');
                this.reset();
            });

            // Animated Stats Counter
            const statNumbers = document.querySelectorAll('.stat-number');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const target = entry.target;
                        const count = parseInt(target.getAttribute('data-count'));
                        const duration = 2000; // 2 seconds
                        const step = count / (duration / 16); // 60fps
                        let current = 0;
                        
                        const timer = setInterval(() => {
                            current += step;
                            if (current >= count) {
                                current = count;
                                clearInterval(timer);
                            }
                            target.textContent = Math.floor(current);
                        }, 16);
                        
                        observer.unobserve(target);
                    }
                });
            }, { threshold: 0.5 });
            
            statNumbers.forEach(stat => {
                observer.observe(stat);
            });

            // Scroll animations
            window.addEventListener('scroll', function() {
                const nav = document.querySelector('header');
                
                if (window.scrollY > 50) {
                    nav.style.padding = '10px 0';
                    nav.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
                } else {
                    nav.style.padding = '20px 0';
                    nav.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
                }
            });

            // Set minimum date for booking to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date').setAttribute('min', today);

            // User Authentication System
            // User profile elements
            const userProfile = document.getElementById('userProfile');
            const userAvatar = document.getElementById('userAvatar');
            const userName = document.getElementById('userName');
            const userNameMobile = document.getElementById('userNameMobile');
            const userDropdown = document.getElementById('userDropdown');
            const authLinks = document.getElementById('authLinks');
            const logoutBtn = document.getElementById('logoutBtn');
            
            const mobileUserProfile = document.getElementById('mobileUserProfile');
            const mobileUserAvatar = document.getElementById('mobileUserAvatar');
            const mobileUserName = document.getElementById('mobileUserName');
            const mobileLoginLink = document.getElementById('mobileLoginLink');
            const mobileLogoutBtn = document.getElementById('mobileLogoutBtn');
            
            // Check if user is logged in from PHP session
            let isLoggedIn = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;
            let userData = <?php echo isset($_SESSION['user']) ? json_encode($_SESSION['user']) : '{}'; ?>;
            
            // Initialize user state
            if (isLoggedIn && userData.email) {
                showUserProfile(userData);
            } else {
                hideUserProfile();
            }
            
            // Logout functionality
            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                simulateLogout();
            });
            
            mobileLogoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                simulateLogout();
            });
            
            function simulateLogout() {
                isLoggedIn = false;
                userData = {};
                hideUserProfile();
                
                // Redirect to logout
                window.location.href = '?logout=true';
            }
            
            // Show user profile with email-based avatar
            function showUserProfile(user) {
                // Extract first letter from email for avatar
                const emailFirstLetter = user.email.charAt(0).toUpperCase();
                
                // Update user profile elements
                userAvatar.textContent = emailFirstLetter;
                userName.textContent = user.name || user.email.split('@')[0];
                userNameMobile.textContent = emailFirstLetter;
                
                // Show user profile, hide auth links
                userProfile.style.display = 'flex';
                authLinks.style.display = 'none';
                
                // Update mobile view - mobile only shows avatar, no name
                mobileUserProfile.style.display = 'block';
                mobileLoginLink.style.display = 'none';
                mobileUserAvatar.textContent = emailFirstLetter;
                
                // Handle responsive user name display
                handleResponsiveUserDisplay();
            }
            
            function hideUserProfile() {
                userProfile.style.display = 'none';
                authLinks.style.display = 'flex';
                
                mobileUserProfile.style.display = 'none';
                mobileLoginLink.style.display = 'block';
            }
            
            // Handle responsive user display
            function handleResponsiveUserDisplay() {
                const isMobile = window.innerWidth <= 768;
                
                if (isMobile) {
                    // On mobile - hide full name, show only avatar
                    userName.style.display = 'none';
                    userNameMobile.style.display = 'none';
                    userAvatar.style.marginRight = '0';
                } else {
                    // On desktop - show full name
                    userName.style.display = 'block';
                    userNameMobile.style.display = 'none';
                    userAvatar.style.marginRight = '10px';
                }
            }
            
            // Listen for window resize to update user display
            window.addEventListener('resize', handleResponsiveUserDisplay);
            
            // Initial call to set correct display
            handleResponsiveUserDisplay();
            
            // Toggle user dropdown
            userProfile.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('active');
            });
            
            // Close dropdown when clicking elsewhere
            document.addEventListener('click', function() {
                userDropdown.classList.remove('active');
            });
        });
    </script>
</body>
</html>
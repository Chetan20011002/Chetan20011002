<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Village Development System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --primary-color: #2e7d32;
            --primary-dark: #1b5e20;
            --secondary-color: #81c784;
            --text-light: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: #f8f9fa;
        }

        /* Navbar Styles */
        .navbar {
            background: linear-gradient(to right, var(--primary-dark), var(--primary-color));
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            background: rgba(27, 94, 32, 0.95);
            backdrop-filter: blur(10px);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--text-light) !important;
        }

        .nav-link {
            color: var(--text-light) !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: var(--secondary-color) !important;
            transform: translateY(-2px);
        }

        .navbar .btn {
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .navbar .btn-light {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }

        .navbar .btn-light:hover {
            background: white;
            color: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            background: linear-gradient(rgba(78, 238, 88, 0.8), rgba(6, 11, 6, 0.9)), 
                        url('https://source.unsplash.com/1600x900/?village,green') center/cover no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 150px;
            background: linear-gradient(to top, #f8f9fa, transparent);
        }

        .hero-content {
            max-width: 800px;
            padding: 2rem;
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .btn-green {
            background: linear-gradient(45deg, var(--primary-dark), var(--primary-color));
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-green:hover {
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        /* Section Styles */
        .section {
            padding: 100px 0;
            position: relative;
        }

        .section h2 {
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 3rem;
            position: relative;
            display: inline-block;
        }

        .section h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        /* Feature Cards */
        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .feature-card i {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }

        .feature-card h4 {
            color: var(--primary-dark);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        /* Contact Section */
        .contact-section {
            background: linear-gradient(45deg, var(--primary-dark), var(--primary-color));
            color: white;
            padding: 80px 0;
        }

        .contact-section h2::after {
            background: white;
        }

        /* Footer */
        .footer {
            background: var(--primary-dark);
            color: white;
            padding: 30px 0;
            text-align: center;
        }

        .footer p {
            margin: 0;
            opacity: 0.9;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            .section {
                padding: 60px 0;
            }
            .feature-card {
                margin-bottom: 2rem;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">VDM System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                <li class="nav-item"><a class="btn btn-light ms-2" href="admin_login.php">Admin Login</a></li>
                <li class="nav-item"><a class="btn btn-light ms-2" href="user_login.php">User Login</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<div class="hero">
    <div class="hero-content">
        <h1>Empowering Villages with Technology</h1>
        <p>Building a connected and efficient community management system for sustainable development and growth.</p>
        <a href="#about" class="btn btn-green">Discover More</a>
    </div>
</div>

<!-- About Section -->
<section id="about" class="section">
    <div class="container">
        <h2 class="text-center">About The Project</h2>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <p class="text-center lead">
                    The Village Development System (VDM) is an innovative platform designed to streamline village administration, 
                    enhance community communication, and foster volunteer coordination for sustainable rural development.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="section bg-light">
    <div class="container">
        <h2 class="text-center">Key Features</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="feature-card">
                    <i class="fas fa-users"></i>
                    <h4>Community Management</h4>
                    <p>Efficiently manage community resources and coordinate activities for better governance.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <i class="fas fa-bullhorn"></i>
                    <h4>Announcements</h4>
                    <p>Keep the community informed with real-time updates and important notifications.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <i class="fas fa-tools"></i>
                    <h4>Problem Reporting</h4>
                    <p>Streamlined system for reporting and tracking community issues for quick resolution.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="contact-section">
    <div class="container">
        <h2 class="text-center text-white">Contact Us</h2>
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <p class="lead mb-4">Have questions? We're here to help!</p>
                <p class="mb-4">For inquiries, reach out at <strong>support@vdm.com</strong></p>
                <a href="mailto:support@vdm.com" class="btn btn-light btn-lg">Send us an email</a>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <p>&copy; 2025 Village Development System. All Rights Reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Navbar scroll effect
window.addEventListener('scroll', function() {
    if (window.scrollY > 50) {
        document.querySelector('.navbar').classList.add('scrolled');
    } else {
        document.querySelector('.navbar').classList.remove('scrolled');
    }
});
</script>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/6818cc3fd37b0a190da1f9ec/1iqgdro53';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
</body>
</html>
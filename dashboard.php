<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JAPHETH RALPH NYADWERA - Portfolio</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
      @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');

:root {
  --bg-color: #1f242d;
  --text-color: #fff;
  --accent-color: #7cf03d;
  --nav-padding: 25px 9%;
}

/* Global Styles */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

body {
  background: var(--bg-color);
  color: var(--text-color);
  line-height: 1.6;
}

a {
  color: var(--text-color);
  text-decoration: none;
  transition: all 0.3s ease;
}

.container {
  width: 100%;
  max-width: 1000px;
  margin: 0 auto;
  padding: 20px;
}

/* Header & Navigation */
header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 0;
  border-bottom: 1px solid #444;
  margin-bottom: 30px;
}

.logo {
  font-weight: 800;
  font-size: 28px;
  color: #ddd;
  position: relative;
  padding: 5px 0;
}

.logo::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 2px;
  background: linear-gradient(90deg, blue, #00b509);
  transform: scaleX(0);
  transform-origin: right;
  transition: transform 0.4s ease;
}

.logo:hover::after {
  transform: scaleX(1);
  transform-origin: left;
}

.nav-links {
  display: flex;
  list-style: none;
}

.nav-links li {
  margin-left: 20px;
}

.nav-links a {
  text-decoration: none;
  color: blue;
  font-weight: 600;
  transition: all 0.3s;
  position: relative;
  padding: 5px 0;
}

.nav-links a::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 0;
  height: 2px;
  background-color: #00b509;
  transition: width 0.3s ease;
}

.nav-links a:hover {
  color: #00b509;
}

.nav-links a:hover::after {
  width: 100%;
}

.hamburger {
  display: none;
  cursor: pointer;
}

.hamburger span {
  display: block;
  width: 25px;
  height: 3px;
  background-color: #ddd;
  margin: 5px 0;
  transition: all 0.3s ease;
}

/* Hero Section */
.hero {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 50px;
  margin-bottom: 50px;
}

.hero-content {
  flex: 1;
  min-width: 300px;
}

.profile-image-container {
  flex: 0 0 300px;
  position: relative;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0, 181, 9, 0.2);
}

.profile-image {
  width: 100%;
  height: auto;
  display: block;
  transition: all 0.5s ease;
  border-radius: 10px;
}

.profile-image-container::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border: 2px solid blue;
  border-radius: 10px;
  z-index: -1;
  transform: translate(10px, 10px);
  opacity: 0.7;
  transition: all 0.5s ease;
}

.profile-image-container:hover::before {
  transform: translate(5px, 5px);
  opacity: 1;
}

.profile-image-container:hover .profile-image {
  transform: scale(1.02);
}

.name {
  font-size: 2.5rem;
  font-weight: 800;
  margin-bottom: 10px;
  line-height: 1.2;
  color: #fff;
  background: linear-gradient(90deg, blue, #00b509);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
  display: inline-block;
}

.title {
  font-size: 1.5rem;
  color: #aaa;
  margin-bottom: 30px;
  font-weight: 600;
}

.bio {
  max-width: 500px;
  margin-bottom: 30px;
  font-size: 1rem;
  line-height: 1.7;
  color: #aaa;
}

/* Buttons & Social Icons */
.action-buttons {
  margin-bottom: 30px;
}

.cv-button {
  background: linear-gradient(90deg, blue, #00b509);
  color: white;
  padding: 12px 30px;
  border: none;
  border-radius: 30px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  text-decoration: none;
  display: inline-block;
  transition: all 0.3s ease;
  box-shadow: 0 5px 15px rgba(0, 181, 9, 0.3);
}

.cv-button:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(0, 181, 9, 0.4);
}

.social-media {
  display: flex;
  justify-content: flex-start;
  gap: 20px;
}

.social-media a {
  color: #555;
  font-size: 2rem;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.05);
}

.social-media a:hover {
  transform: translateY(-5px);
  background: rgba(255, 255, 255, 0.1);
}

.social-media .bxl-linkedin:hover {
  color: #0077b5;
}

.social-media .bxl-twitter:hover {
  color: #1da1f2;
}

.social-media .bxl-instagram:hover {
  color: #e1306c;
}

.social-media .bxl-facebook-circle:hover {
  color: #1877f2;
}

.social-media .bxl-youtube:hover {
  color: #ff0000;
}

/* Sections Common Styles */
.section {
  padding: 80px 0;
}

.section h2 {
  text-align: center;
  margin-bottom: 40px;
  font-size: 2.5rem;
}

/* Technical Expertise Section */
.technical-expertise {
  background-color: #1f242d;
}

.skills-list {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: center;
}

.skill-item {
  background-color: #2a303a;
  border: 1px solid #3a404a;
  border-radius: 8px;
  padding: 20px;
  width: calc(33.333% - 20px);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.skill-item:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.skill-item h3 {
  font-size: 1.3rem;
  margin-bottom: 10px;
  color: var(--accent-color);
}

.skill-item p {
  color: #aaa;
  margin: 0;
}

/* Education Section */
.education {
  background-color: #1f242d;
}

.education-list {
  list-style-type: none;
  padding: 0;
  max-width: 800px;
  margin: 0 auto;
}

.education-list li {
  background-color: #2a303a;
  margin-bottom: 15px;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  border-left: 4px solid var(--accent-color);
}

.education-list li strong {
  color: #fff;
}

.education-list li p {
  margin: 5px 0 0;
  color: #aaa;
}

/* Projects Section */
.projects {
  background-color: #1f242d;
}

.section-subtitle {
  text-align: center;
  margin-bottom: 50px;
  color: #aaa;
  font-size: 1.1rem;
}

.project-card {
  background: #2a303a;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
  margin-bottom: 30px;
  overflow: hidden;
  transition: transform 0.3s ease;
  border: 1px solid #3a404a;
}

.project-card:hover {
  transform: translateY(-5px);
}

.project-content {
  padding: 30px;
}

.project-content h3 {
  margin-top: 0;
  color: var(--accent-color);
  font-size: 1.5rem;
}

.project-description {
  color: #ccc;
  font-size: 1.1rem;
  margin-bottom: 15px;
}

.project-details {
  color: #aaa;
  margin-bottom: 20px;
  line-height: 1.6;
}

.tech-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 20px;
}

.tech-tags span {
  background: #3a404a;
  padding: 5px 12px;
  border-radius: 20px;
  font-size: 0.85rem;
  color: var(--accent-color);
}

.project-links {
  display: flex;
  gap: 15px;
}

.btn {
  display: inline-block;
  padding: 10px 20px;
  background: var(--accent-color);
  color: #1f242d;
  border-radius: 5px;
  text-decoration: none;
  font-weight: 500;
  transition: all 0.3s ease;
}

.btn:hover {
  background: #6ad82c;
}

.btn-outline {
  background: transparent;
  border: 1px solid var(--accent-color);
  color: var(--accent-color);
}

.btn-outline:hover {
  background: rgba(124, 240, 61, 0.1);
}

/* Project Features */
.features {
  margin: 20px 0;
  padding: 15px;
  background: rgba(124, 240, 61, 0.05);
  border-radius: 8px;
  border-left: 3px solid var(--accent-color);
}

.features h4 {
  margin-top: 0;
  color: var(--accent-color);
  font-size: 1.1rem;
}

.features ul {
  padding-left: 20px;
  margin-bottom: 0;
}

.features li {
  margin-bottom: 8px;
  color: #ccc;
}

/* Achievements */
.achievements {
  display: flex;
  gap: 15px;
  margin: 20px 0;
  flex-wrap: wrap;
}

.achievement-badge {
  background: rgba(124, 240, 61, 0.1);
  padding: 8px 15px;
  border-radius: 20px;
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.9rem;
  color: #fff;
}

/* Services Section */
.services-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 30px;
}

.service-box {
  background: #2a303a;
  padding: 30px;
  border-radius: 20px;
  border: 1px solid rgba(124, 240, 61, 0.2);
  box-shadow: 0 0 20px rgba(124, 240, 61, 0.1);
  transition: transform 0.3s, box-shadow 0.3s;
  text-align: center;
}

.service-box:hover {
  transform: translateY(-10px);
  box-shadow: 0 15px 30px rgba(124, 240, 61, 0.2);
}

.service-box i {
  font-size: 50px;
  color: var(--accent-color);
  margin-bottom: 20px;
}

.service-box h3 {
  font-size: 22px;
  margin-bottom: 15px;
  color: #fff;
}

.service-box p {
  font-size: 16px;
  line-height: 1.6;
  color: #aaa;
}

/* Contact Section */
.contact-container {
  display: flex;
  flex-wrap: wrap;
  gap: 30px;
}

.contact-info, .contact-form {
  flex: 1;
  min-width: 300px;
  background: #2a303a;
  padding: 30px;
  border-radius: 20px;
  border: 1px solid rgba(124, 240, 61, 0.2);
  box-shadow: 0 0 20px rgba(124, 240, 61, 0.1);
}

.contact-info h3 {
  font-size: 25px;
  margin-bottom: 20px;
  color: var(--accent-color);
}

.detail-item {
  display: flex;
  align-items: flex-start;
  margin-bottom: 20px;
}

.detail-item i {
  font-size: 22px;
  color: var(--accent-color);
  margin-right: 15px;
  margin-top: 5px;
}

.detail-item h4 {
  font-size: 18px;
  margin-bottom: 5px;
  color: #fff;
}

.detail-item a {
  display: block;
  color: #aaa;
  margin-bottom: 5px;
  transition: color 0.3s;
}

.detail-item a:hover {
  color: var(--accent-color);
}

.sms-btn {
  display: inline-block;
  padding: 8px 15px;
  background: var(--accent-color);
  color: #1f242d;
  border-radius: 30px;
  font-weight: 600;
  margin-top: 10px;
  transition: all 0.3s;
}

.sms-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 5px 15px rgba(124, 240, 61, 0.4);
}

.contact-form input,
.contact-form textarea {
  width: 100%;
  padding: 12px 15px;
  background: #1f242d;
  border: 1px solid rgba(124, 240, 61, 0.3);
  border-radius: 10px;
  color: #fff;
  font-size: 16px;
  transition: all 0.3s;
}

.contact-form input:focus,
.contact-form textarea:focus {
  border-color: var(--accent-color);
  box-shadow: 0 0 10px rgba(124, 240, 61, 0.3);
  outline: none;
}

.contact-form textarea {
  height: 150px;
  resize: none;
}

/* Responsive Design */
@media (max-width: 900px) {
  .hero {
    gap: 30px;
  }
  
  .profile-image-container {
    flex: 0 0 250px;
  }
}

@media (max-width: 768px) {
  .nav-links {
    position: fixed;
    top: 80px;
    left: -100%;
    width: 100%;
    height: calc(100vh - 80px);
    background-color: #111;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    transition: left 0.5s ease;
    z-index: 100;
  }

  .nav-links.active {
    left: 0;
  }

  .nav-links li {
    margin: 15px 0;
  }

  .hamburger {
    display: block;
  }

  .hamburger.active span:nth-child(1) {
    transform: rotate(45deg) translate(5px, 5px);
  }

  .hamburger.active span:nth-child(2) {
    opacity: 0;
  }

  .hamburger.active span:nth-child(3) {
    transform: rotate(-45deg) translate(7px, -6px);
  }

  .name {
    font-size: 2rem;
  }

  .title {
    font-size: 1.2rem;
  }

  .skill-item {
    width: calc(50% - 20px);
  }
}

@media (max-width: 600px) {
  .hero {
    flex-direction: column;
  }
  
  .profile-image-container {
    align-self: center;
    margin-top: 30px;
    flex: 0 0 200px;
  }
  
  .name {
    font-size: 1.8rem;
  }
  
  .title {
    font-size: 1.1rem;
  }
  
  .bio {
    font-size: 0.9rem;
  }

  .skill-item {
    width: 100%;
  }

  .project-links {
    flex-direction: column;
  }
  
  .btn {
    width: 100%;
    text-align: center;
  }
}

@media (max-width: 480px) {
  .logo {
    font-size: 1.5rem;
  }
  
  .cv-button {
    padding: 10px 20px;
    font-size: 0.9rem;
  }
  
  .social-media a {
    font-size: 1.5rem;
    width: 40px;
    height: 40px;
  }
}  
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <span class="logo-highlight">JRN</span> PORTFOLIO
            </div>
            <nav>
                <ul class="nav-links">
                    <li><a href="#home">HOME</a></li>
                    <li><a href="#about">ABOUT</a></li>
                    <li><a href="#portfolio">PORTFOLIO</a></li>
                    <li><a href="#services">SERVICES</a></li>
                    <li><a href="#contact">CONTACT</a></li>
                </ul>
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </nav>
        </header>

        <main>
            <section id="home" class="section active-section">
                <div class="hero">
                    <div class="hero-content">
                        <h1 class="name">JAPHETH RALPH NYADWERA</h1>
                        <h2 class="title">Front-end Developer</h2>
                        <p class="bio">
                            Enthusiastic and detailed-oriented software and web Developer currently
                            pursuing a degree in Information and Communication Technology at
                            Maseno University. Skilled in front-end and back-end Technologies
                            such as HTML, CSS, JavaScript, Java, Python, PHP, and MYSQL. Experienced in
                            building responsive websites and dynamic web applications with a
                            focus on clean code and user-friendly design.
                        </p>
                        <div class="action-buttons">
                            <a href="JAPHETH RALPH. CV.pdf" download="JAPHETH RALPH. CV.pdf" class="cv-button">
                                Download CV <i class='bx bx-download'></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="profile-image-container">
                        <div class="image-wrapper">
                            <img src="image of japheth ralph.jpg" alt="Japheth Ralph Nyadwera" class="profile-image">
                            <div class="image-border"></div>
                        </div>
                    </div>
                </div>
                
                <div class="social-media">
                    <a href="https://x.com/nyadwera_ralph?t=6qBoSEmMitjlYWrtJizzsA&s=09" target="_blank" class="social-icon"><i class="bx bxl-twitter"></i></a>
                    <a href="https://youtube.com/@japhethralph?si=a212tD4l-zY_Uwuu" target="_blank" class="social-icon"><i class="bx bxl-youtube"></i></a>
                    <a href="https://www.instagram.com/ralph.japheth?igsh=YzljYTk1ODg3Zg==" target="_blank" class="social-icon"><i class="bx bxl-instagram"></i></a>
                    <a href="https://www.linkedin.com/in/japheth-ralph-nyadwera-23406a2b5" target="_blank" class="social-icon"><i class="bx bxl-linkedin"></i></a>
                    <a href="https://www.facebook.com/japheth.ralp.3" target="_blank" class="social-icon"><i class="bx bxl-facebook-circle"></i></a>
                </div>
            </section>
            
            <!-- About Section -->
            <section id="about" class="section">
                <div class="about-section">
                    <div class="container">
                        <h2>About Me</h2>
                        <div class="about-content">
                            <p>Enthusiastic and detailed-oriented software and web Developer currently
                            pursuing a degree in Information and Communication Technology at
                            Maseno University. Skilled in front-end and back-end Technologies
                            such as HTML, CSS, JavaScript, Java, Python, PHP, and MYSQL. Experienced in
                            building responsive websites and dynamic web applications with a
                            focus on clean code and user-friendly design.</p>
                        </div>
                    </div>
                </div>

                <!-- Technical Expertise Section -->
                <section class="technical-expertise">
                    <div class="container">
                        <h2>Technical Expertise</h2>
                        <div class="skills-list">
                            <div class="skill-item">
                                <h3>Frontend Development</h3>
                                <p>(React, JavaScript)</p>
                            </div>
                            <div class="skill-item">
                                <h3>Cisco Networking & Security</h3>
                            </div>
                            <div class="skill-item">
                                <h3>ICT Systems Management</h3>
                            </div>
                            <div class="skill-item">
                                <h3>Data Security & Troubleshooting</h3>
                            </div>
                            <div class="skill-item">
                                <h3>IT Training & Capacity Building</h3>
                            </div>
                            <div class="skill-item">
                                <h3>Hardware/Software Maintenance</h3>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Education Section -->
                <section class="education">
                    <div class="container">
                        <h2>Education</h2>
                        <ul class="education-list">
                            <li>
                                <strong>Bachelor of Science in ICT</strong>
                                <p>Maseno University - Kisumu (2022–Ongoing)</p>
                            </li>
                            <li>
                                <strong>Certificate in Network Security (Cisco)</strong>
                                <p>Maseno University - Kisumu (2024–2025)</p>
                            </li>
                            <li>
                                <strong>Certificate in Networking (Cisco)</strong>
                                <p>Maseno University - Kisumu (2023–2024)</p>
                            </li>
                            <li>
                                <strong>Certification in switching and routing</strong>
                                <p>Maseno University - Kisumu (2022 – 2023)</p>
                            </li>
                            <li>
                                <strong>Kenya Certificate of Secondary Education (K.C.S.E)</strong>
                                <p>Nyagowa Mixed Secondary School - Homa-Bay (2018–2022)</p>
                            </li>
                            <li>
                                <strong>Kenya Certificate of Primary Education (K.C.P.E)</strong>
                                <p>Nyimbi Integrated Primary School - Homa-Bay (2018–2022)</p>
                            </li>
                        </ul>
                    </div>
                </section>
            </section>
            
            <!-- Portfolio Section -->
            <section id="portfolio" class="section">
                <div class="projects">
                    <div class="container">
                        <h2>My Projects</h2>
                        <p class="section-subtitle">A showcase of my best work and contributions</p>
                        
                        <!-- Project 1: Biometric Attendance System -->
                        <div class="project-card">
                            <div class="project-content">
                                <h3>Student Biometric Attendance System</h3>
                                <p class="project-description">
                                    Automated attendance tracking using fingerprint recognition for educational institutions.
                                </p>
                                <p class="project-details">
                                    Developed a comprehensive biometric solution that replaces manual attendance registers with fingerprint authentication. The system integrates with school databases, provides real-time attendance reports to administrators, and sends automated notifications to parents about student presence. Built with Python, Django, and MySQL, it supports RFID card backup authentication and generates detailed analytics on attendance patterns.
                                </p>
                                <div class="tech-tags">
                                    <span>Python</span>
                                    <span>Django</span>
                                    <span>MySQL</span>
                                    <span>Biometric API</span>
                                    <span>JavaScript</span>
                                    <span>Bootstrap</span>
                                </div>
                                <div class="features">
                                    <h4>Key Features:</h4>
                                    <ul>
                                        <li>Fingerprint-based student identification</li>
                                        <li>Real-time attendance dashboard</li>
                                        <li>Automated parent notifications (SMS/Email)</li>
                                        <li>RFID card backup system</li>
                                        <li>Analytics and reporting module</li>
                                    </ul>
                                </div>
                                <div class="project-links">
                                    <a href="#" class="btn">Live Demo</a>
                                    <a href="https://github.com/38780431/PORTFOLIO_UPDATED" class="btn btn-outline" target="_blank" rel="noopener noreferrer">View Code</a>
                                    <a href="#" class="btn btn-outline">Case Study</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Project 2: My Project Portfolio -->
                        <div class="project-card">
                            <div class="project-content">
                                <h3>My Project Portfolio</h3>
                                <p class="project-description">
                                    Responsive portfolio template with dark/light mode toggle.
                                </p>
                                <p class="project-details">
                                    My portfolio website showcases my skills and projects through a sleek, responsive design built with HTML, CSS, and JavaScript. It highlights my work, experience, and creativity while offering a smooth user experience. Whether exploring my projects or getting in touch, the site reflects my passion for clean code and modern web development.
                                </p>
                                <div class="tech-tags">
                                    <span>HTML5</span>
                                    <span>CSS3</span>
                                    <span>JavaScript</span>
                                </div>
                                <div class="features">
                                    <h4>Key Features:</h4>
                                    <ul>
                                        <li>Responsive layout for desktop and mobile screens</li>
                                        <li>Portfolio sections for projects, skills, and background</li>
                                        <li>Navigation links to explore the portfolio</li>
                                        <li>Contact section for visitor inquiries</li>
                                    </ul>
                                </div>
                                <div class="project-links">
                                    <a href="#" class="btn">Live Demo</a>
                                    <a href="https://github.com/38780431/PORTFOLIO_UPDATED" class="btn btn-outline" target="_blank" rel="noopener noreferrer">View Code</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Rental House Management System -->
                        <div class="project-card">
                            <div class="project-content">
                                <h3>Rental House Management System</h3>
                                <p class="project-description">
                                    A property management system concept for keeping rental house, tenant, and payment information organized in one place.
                                </p>
                                <p class="project-details">
                                    The system is designed to help property managers maintain property and tenant records, track rent payments, and keep up with maintenance requests. Bringing these tasks together can make routine rental administration easier to follow.
                                </p>
                                <div class="tech-tags">
                                    <span>Property Management</span>
                                    <span>Tenant Records</span>
                                    <span>Rent Tracking</span>
                                    <span>Maintenance</span>
                                </div>
                                <div class="features">
                                    <h4>Key Features:</h4>
                                    <ul>
                                        <li>Rental property and unit records</li>
                                        <li>Tenant information management</li>
                                        <li>Rent payment tracking</li>
                                        <li>Maintenance request records</li>
                                        <li>Overview of property and payment status</li>
                                    </ul>
                                </div>
                                <div class="project-links">
                                    <a href="#" class="btn">Live Demo</a>
                                    <a href="https://github.com/38780431/PORTFOLIO_UPDATED" class="btn btn-outline" target="_blank" rel="noopener noreferrer">View Code</a>
                                    <a href="#" class="btn btn-outline">Case Study</a>
                                </div>
                            </div>
                        </div>

                        <!-- Student Alert System -->
                        <div class="project-card">
                            <div class="project-content">
                                <h3>Student Alert System</h3>
                                <p class="project-description">
                                    A school communication system concept for sharing important student updates with students and their parents or guardians.
                                </p>
                                <p class="project-details">
                                    The system is designed to help schools prepare and send timely alerts about attendance, announcements, and other student-related updates, while keeping a record of the information shared.
                                </p>
                                <div class="tech-tags">
                                    <span>Student Updates</span>
                                    <span>School Communication</span>
                                    <span>Parent Notifications</span>
                                    <span>Alert Records</span>
                                </div>
                                <div class="features">
                                    <h4>Key Features:</h4>
                                    <ul>
                                        <li>Student and parent or guardian contact records</li>
                                        <li>Alerts for attendance and school announcements</li>
                                        <li>Timely notification of important updates</li>
                                        <li>History of sent alerts</li>
                                    </ul>
                                </div>
                                <div class="project-links">
                                    <a href="#" class="btn">Live Demo</a>
                                    <a href="https://github.com/38780431/PORTFOLIO_UPDATED" class="btn btn-outline" target="_blank" rel="noopener noreferrer">View Code</a>
                                    <a href="#" class="btn btn-outline">Case Study</a>
                                </div>
                            </div>
                        </div>

                        <!-- Project 3: E-Learning Platform -->
                        <div class="project-card">
                            <div class="project-content">
                                <h3>EduSmart AI Learning Platform</h3>
                                <p class="project-description">
                                    Adaptive e-learning system with personalized course recommendations using machine learning.
                                </p>
                                <p class="project-details">
                                    Developed a full-stack e-learning platform that analyzes student performance to recommend tailored learning paths. The system incorporates video lessons, interactive quizzes, and a virtual lab environment. The AI engine tracks user progress and adjusts content difficulty dynamically, improving knowledge retention by 40% compared to traditional platforms.
                                </p>
                                <div class="tech-tags">
                                    <span>React</span>
                                    <span>Node.js</span>
                                    <span>Python</span>
                                    <span>TensorFlow</span>
                                    <span>MongoDB</span>
                                    <span>AWS</span>
                                </div>
                                <div class="features">
                                    <h4>Core Innovations:</h4>
                                    <ul>
                                        <li>Personalized course recommendations engine</li>
                                        <li>Real-time progress analytics dashboard</li>
                                        <li>Interactive coding environment</li>
                                        <li>Automated difficulty adjustment</li>
                                        <li>Multi-device synchronization</li>
                                    </ul>
                                </div>
                                <div class="achievements">
                                    <div class="achievement-badge">
                                        <span>🏆</span> 95% User Satisfaction
                                    </div>
                                    <div class="achievement-badge">
                                        <span>📈</span> 40% Improved Retention
                                    </div>
                                </div>
                                <div class="project-links">
                                    <a href="#" class="btn">Platform Demo</a>
                                    <a href="https://github.com/38780431/PORTFOLIO_UPDATED" class="btn btn-outline" target="_blank" rel="noopener noreferrer">GitHub Repo</a>
                                    <a href="#" class="btn btn-outline">Technical White Paper</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Services Section -->
            <section id="services" class="section">
                <h2 class="heading">My <span>Services</span></h2>
                <div class="services-container">
                    <div class="service-box">
                        <i class='bx bx-code-alt'></i>
                        <h3>Frontend Development</h3>
                        <p>Building responsive and interactive UIs using React, HTML5, CSS3, JavaScript with modern frameworks and best practices.</p>
                    </div>
                    <div class="service-box">
                        <i class='bx bx-globe'></i>
                        <h3>Web Development</h3>
                        <p>Full-stack web development solutions including CMS integration, e-commerce platforms, and custom web applications.</p>
                    </div>
                    <div class="service-box">
                        <i class='bx bx-bar-chart-alt'></i>
                        <h3>Data Analysis</h3>
                        <p>Transforming raw data into meaningful insights using Python, SQL, and visualization tools like Tableau and Power BI.</p>
                    </div>
                    <div class="service-box">
                        <i class='bx bx-server'></i>
                        <h3>System Administration</h3>
                        <p>IT infrastructure management, network administration, and cloud solutions deployment on AWS and Azure platforms.</p>
                    </div>
                    <div class="service-box">
                        <i class='bx bx-palette'></i>
                        <h3>Graphic Design</h3>
                        <p>Creating visually appealing designs, logos, and branding materials using Adobe Creative Suite and Figma.</p>
                    </div>
                </div>
            </section>
            
            <!-- Contact Section -->
            <section id="contact" class="section">
                <h2 class="heading">Contact <span>Me!</span></h2>
                <div class="contact-container">
                    <div class="contact-info">
                        <h3>Get in Touch</h3>
                        <div class="contact-details">
                            <div class="detail-item">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <h4>Call or SMS</h4>
                                    <a href="tel:+254758946742">+254 758 946 742</a>
                                    <a href="sms:+254758946742" class="sms-btn">Send SMS</a>
                                </div>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-envelope"></i>
                                <div>
                                    <h4>Email</h4>
                                    <a href="mailto:jnyadwera@yahoo.com">jnyadwera@yahoo.com</a>
                                </div>
                            </div>
                        </div>
                        <div class="social-media">
                            <h4>Follow Me</h4>
                            <div class="social-links">
                                <a href="https://x.com/nyadwera_ralph?t=6qBoSEmMitjlYWrtJizzsA&s=09" target="_blank" class="social-icon"><i class="bx bxl-twitter"></i></a>
                                <a href="https://youtube.com/@japhethralph?si=a212tD4l-zY_Uwuu" target="_blank" class="social-icon"><i class="bx bxl-youtube"></i></a>
                                <a href="https://www.instagram.com/ralph.japheth?igsh=YzljYTk1ODg3Zg==" target="_blank" class="social-icon"><i class="bx bxl-instagram"></i></a>
                                <a href="https://www.linkedin.com/in/japheth-ralph-nyadwera-23406a2b5" target="_blank" class="social-icon"><i class="bx bxl-linkedin"></i></a>
                                <a href="https://www.facebook.com/japheth.ralp.3" target="_blank" class="social-icon"><i class="bx bxl-facebook-circle"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="contact-form">
                        <form action="https://formspree.io/f/opiyodavid948@gmail.com" method="POST">
                            <div class="form-group">
                                <input type="text" name="name" placeholder="Your Name" required>
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" placeholder="Your Email" required>
                            </div>
                            <div class="form-group">
                                <input type="text" name="subject" placeholder="Subject">
                            </div>
                            <div class="form-group">
                                <textarea name="message" placeholder="Your Message" required></textarea>
                            </div>
                            <button type="submit" class="btn">Send Message</button>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            // Show home section by default
            showSection('home');
            
            // Set click handlers for all nav links
            const navLinks = document.querySelectorAll('.nav-links a');
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const sectionId = this.getAttribute('href').substring(1);
                    showSection(sectionId);
                    
                    // Close mobile menu if open
                    document.querySelector('nav').classList.remove('mobile-menu');
                });
            });
            
            // Mobile menu toggle
            const hamburger = document.querySelector('.hamburger');
            hamburger.addEventListener('click', function() {
                document.querySelector('nav').classList.toggle('mobile-menu');
            });
        });

        function showSection(id) {
            // Hide all sections
            document.querySelectorAll('.section').forEach(section => {
                section.style.display = 'none';
                section.classList.remove('active-section');
            });
            
            // Show the selected section
            const section = document.getElementById(id);
            if (section) {
                section.style.display = 'block';
                section.classList.add('active-section');
            }
            
            // Update active nav link
            document.querySelectorAll('.nav-links li').forEach(item => {
                item.classList.remove('active');
            });
            const activeLink = document.querySelector(`.nav-links a[href="#${id}"]`);
            if (activeLink) {
                activeLink.parentElement.classList.add('active');
            }
            
            // Scroll to top
            window.scrollTo(0, 0);
        }
    </script>
</body>
</html>
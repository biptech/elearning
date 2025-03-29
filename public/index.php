<?php 
   session_start();
   include('../includes/config.php');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>eLearning Navbar</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    />
    <link rel="stylesheet" href="../css/index.css">
  </head>
  <?php include '../includes/header.php'; ?>
  <body>
    <section class="hero">
      <div class="hero-content">
        <h1>
          Education is the most powerful weapon which you can use to change the
          world.
        </h1>
        <p>
          Start, switch, or advance your career with more than 10,000 courses,
          Professional Certificates, and degrees from world-class universities
          and companies.
        </p>
        <div class="cta-buttons">
          <a href="#" class="btn primary">Join For Free</a>
          <a href="#" class="btn secondary">Try eLearning for Business</a>
        </div>
      </div>
      <div class="hero-image">
        <div class="circle">
          <img src="../images/study-girl1.jpg" alt="Woman smiling" />
        </div>
      </div>
    </section>
    <section class="hero-section">
      <h2>
        Thousands of students achieved their
        <span class="highlight">dream job</span> at
      </h2>

      <div class="logo-grid">
        <img src="../images/Samsung_Logo.svg.png" alt="Amazon" />
        <img src="../images/Samsung_Logo.svg.png" alt="Google" />
        <img src="../images/Samsung_Logo.svg.png" alt="Microsoft" />
        <img src="../images/Samsung_Logo.svg.png" alt="Goldman Sachs" />
        <img src="../images/Samsung_Logo.svg.png" alt="PayPal" />
        <img src="../images/Samsung_Logo.svg.png" alt="Samsung" />
        <img src="../images/Samsung_Logo.svg.png" alt="Salesforce" />
        <img src="../images/Samsung_Logo.svg.png" alt="NetApp" />
        <img src="../images/Samsung_Logo.svg.png" alt="Hitachi" />
        <img src="../images/Samsung_Logo.svg.png" alt="JPMorgan" />
        <img src="../images/Samsung_Logo.svg.png" alt="IBM" />
        <img src="../images/Samsung_Logo.svg.png" alt="Dell" />
        <img src="../images/Samsung_Logo.svg.png" alt="Deloitte" />
        <img src="../images/Samsung_Logo.svg.png" alt="KPMG" />
        <img src="../images/Samsung_Logo.svg.png" alt="ISRO" />
        <img src="../images/Samsung_Logo.svg.png" alt="Mercedes-Benz" />
        <img src="../images/Samsung_Logo.svg.png" alt="EY" />
        <img src="../images/Samsung_Logo.svg.png" alt="Airtel" />
      </div>
      <p class="more-text">+ many more</p>
    </section>

    <section class="course-section">
      <h2>
        Because you searched for
        <span class="highlight">"Web Development"</span>
      </h2>

      <div class="carousel-container">
        <button class="prev-btn">&#10094;</button>
        <div class="carousel">
          <div class="course">
            <img src="../images/study-girl.jpg" alt="Full-Stack Bootcamp" />
            <h3>The Complete Full-Stack Web Development Bootcamp</h3>
            <p class="author">Dr. Angela Yu</p>
            <p class="rating">⭐ 4.7 (425,438)</p>
            <p class="price">$74.99</p>
          </div>
          <div class="course">
            <img src="../images/study-girl.jpg" alt="Web Developer Bootcamp" />
            <h3>The Web Developer Bootcamp 2025</h3>
            <p class="author">Colt Steele</p>
            <p class="rating">⭐ 4.7 (278,949)</p>
            <p class="price">$74.99</p>
          </div>
          <div class="course">
            <img src="../images/study-girl.jpg" alt="Full-Stack Bootcamp" />
            <h3>The Complete Full-Stack Web Development Bootcamp</h3>
            <p class="author">Dr. Angela Yu</p>
            <p class="rating">⭐ 4.7 (425,438)</p>
            <p class="price">$74.99</p>
          </div>
          <div class="course">
            <img
              src="../images/study-girl.jpg"
              alt="Web Development Masterclass"
            />
            <h3>Web Development Masterclass - Online Certification</h3>
            <p class="author">YouAccel Training</p>
            <p class="rating">⭐ 4.5 (10,102)</p>
            <p class="price">$74.99</p>
          </div>
          <div class="course">
            <img src="../images/study-girl.jpg" alt="Full-Stack Bootcamp" />
            <h3>The Complete Full-Stack Web Development Bootcamp</h3>
            <p class="author">Dr. Angela Yu</p>
            <p class="rating">⭐ 4.7 (425,438)</p>
            <p class="price">$74.99</p>
          </div>
          <div class="course">
            <img
              src="../images/study-girl.jpg"
              alt="Complete Web Developer Course"
            />
            <h3>The Complete Web Developer Course 3.0</h3>
            <p class="author">Rob Percival</p>
            <p class="rating">⭐ 4.2 (72,408)</p>
            <p class="price">$74.99</p>
          </div>
          <div class="course">
            <img
              src="../images/study-girl.jpg"
              alt="Internet & Web Development"
            />
            <h3>Internet and Web Development Fundamentals</h3>
            <p class="author">YouAccel Training</p>
            <p class="rating">⭐ 4.3 (1,778)</p>
            <p class="price">$39.99</p>
          </div>
        </div>
        <button class="next-btn">&#10095;</button>
      </div>
    </section>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const carousel = document.querySelector(".carousel");
        const prevBtn = document.querySelector(".prev-btn");
        const nextBtn = document.querySelector(".next-btn");

        prevBtn.addEventListener("click", () => {
          carousel.scrollBy({ left: -250, behavior: "smooth" });
        });

        nextBtn.addEventListener("click", () => {
          carousel.scrollBy({ left: 250, behavior: "smooth" });
        });
      });
    </script>
        <?php include '../includes/footer.php'; ?>
  </body>
</html>
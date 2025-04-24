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
    <?php include 'view_logos.php';?>

    <?php include 'viewed.php'; ?>

    <?php include 'search_display.php'; ?>
        <?php include '../includes/footer.php'; ?>
  </body>
</html>
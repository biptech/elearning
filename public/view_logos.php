<?php include('../includes/config.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Our Placements</title>
    <style>

.heading {
    font-size: 24px;
    margin: 30px 0;
}

.highlight {
    color: orange;
}

.logo-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    padding: 20px;
}

.company-logo {
    width: 200px;
    height: auto;
    object-fit: contain;
    background-color: transparent;
    padding: 10px;
    border-radius: 15px;
    transition: transform 0.3s;
}

.company-logo:hover {
    transform: scale(1.1);
}

    </style>
</head>
<body>
    <h2 class="heading">Thousands of students achieved their <span class="highlight">dream job</span> at</h2>

    <div class="logo-container">
        <?php
$result = $con->query("SELECT * FROM company_logos ORDER BY uploaded_at DESC");
while ($row = $result->fetch_assoc()) {
    echo "<img src='../admin/{$row['logo_path']}' alt='Company Logo' class='company-logo'>";
}
        ?>
    </div>
</body>
</html>

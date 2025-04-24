<?php include('includes/config.php'); ?>
<?php include('includes/topheader.php'); ?>
<?php include('includes/leftsidebar.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload Company Logo</title>
    <style>
        .logo-upload-page {
            margin-left: 250px; /* Adjust for sidebar */
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            max-width: 900px;
            font-family: 'Segoe UI', sans-serif;
        }

        .logo-upload-page h3 {
            color: #333;
            margin-bottom: 20px;
        }

        .logo-upload-page form {
            margin-bottom: 30px;
        }

        .logo-upload-page label {
            font-weight: bold;
        }

        .logo-upload-page input[type="file"] {
            padding: 8px;
            margin: 10px 0;
        }

        .logo-upload-page button {
            background-color: #0057A0;
            color: #fff;
            padding: 10px 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .logo-upload-page button:hover {
            background-color: #004080;
        }

        .logo-upload-page ul {
            list-style: none;
            padding-left: 0;
        }

        .logo-upload-page li {
            background-color: #fafafa;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 8px;
        }

        .logo-upload-page img {
            max-height: 60px;
            max-width: 120px;
            object-fit: contain;
        }

        .logo-upload-page .delete-link {
            background-color: #e74c3c;
            color: white;
            padding: 5px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
        }

        .logo-upload-page .delete-link:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
<div class="logo-upload-page">
    <h3>Upload Company Logo</h3>
    <form method="POST" enctype="multipart/form-data">
        <label>Select Logo:</label><br>
        <input type="file" name="logo" required>
        <button type="submit" name="upload">Upload</button>
    </form>

    <?php
    if (isset($_POST['upload'])) {
        // Check if directory exists, if not, create it
        $targetDir = "uploaded_files/logos/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // File info
        $file = $_FILES['logo'];
        $fileName = basename($file["name"]);
        $targetFile = $targetDir . time() . "_" . $fileName;

        // Allowed file types
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($file['type'], $allowedTypes)) {
            // Upload the file
            if (move_uploaded_file($file["tmp_name"], $targetFile)) {
                $logoPath = $targetFile;
                $stmt = $con->prepare("INSERT INTO company_logos (logo_path) VALUES (?)");
                $stmt->bind_param("s", $logoPath);
                $stmt->execute();
                echo "<p style='color:green;'>Logo uploaded successfully!</p>";
            } else {
                echo "<p style='color:red;'>Upload failed.</p>";
            }
        } else {
            echo "<p style='color:red;'>Invalid file type. Only JPG, PNG, and GIF are allowed.</p>";
        }
    }

    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $stmt = $con->prepare("SELECT logo_path FROM company_logos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($logoPath);
        $stmt->fetch();
        $stmt->close();

        // Delete the logo file from the server
        if (file_exists($logoPath)) {
            unlink($logoPath);
        }

        // Remove from database
        $stmt = $con->prepare("DELETE FROM company_logos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo "<p style='color:red;'>Logo deleted!</p>";
    }

    // Fetch and display all uploaded logos
    $result = $con->query("SELECT * FROM company_logos ORDER BY uploaded_at DESC");
    echo "<h3>Uploaded Logos:</h3><ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>
                <img src='{$row['logo_path']}' alt='Logo'>
                <a href='upload-logo.php?delete={$row['id']}' class='delete-link' onclick='return confirm(\"Are you sure you want to delete this logo?\")'>Delete</a>
              </li>";
    }
    echo "</ul>";
    ?>
</div>
<?php include('includes/footer.php'); ?>
</body>
</html>

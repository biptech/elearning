<?php
session_start();
include('includes/config.php');
error_reporting(0);

if(strlen($_SESSION['login']) == 0) { 
    header('location:index.php');
} else {
    if(isset($_POST['submit'])) {
        $category = $_POST['category'];
        $description = $_POST['description'];
        $status = 1;
        $query = mysqli_query($con, "INSERT INTO tblcategory(CategoryName,Description,Is_Active) VALUES('$category','$description','$status')");
        if($query) {
            $msg = "Category created ";
        } else {
            $error = "Something went wrong. Please try again.";    
        } 
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Category</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
        }

        .content-page {
            margin-left: 260px;
            padding: 30px;
            margin-top: 70px;
        }

        .container {
            max-width: 700px;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        h2, h3 {
            color: #333;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 6px;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
            margin-bottom: 20px;
        }

        button[type="submit"] {
            padding: 10px 18px;
            background-color: #007bff;
            color: #fff;
            border: none;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .success-alert, .error-alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 16px;
        }

        .success-alert {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error-alert {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

<?php include('includes/topheader.php'); ?>
<?php include('includes/leftsidebar.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">
            <h2>Add Category</h2>
            <p><a href="#">Admin</a> > <a href="#">Category</a> > Add Category</p>
            <hr>

            <?php if($msg): ?>
                <div class="success-alert">
                    <strong>Well done!</strong> <?php echo htmlentities($msg); ?>
                </div>
            <?php endif; ?>

            <?php if($error): ?>
                <div class="error-alert">
                    <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                </div>
            <?php endif; ?>

            <form name="category" method="post">
                <div>
                    <label for="category">Category</label>
                    <input type="text" name="category" required>
                </div>

                <div>
                    <label for="description">Category Description</label>
                    <textarea name="description" rows="5" required></textarea>
                </div>

                <div>
                    <button type="submit" name="submit">Submit</button>
                </div>
            </form>

        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
</body>
</html>

<?php } ?>
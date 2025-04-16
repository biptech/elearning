<?php
session_start();
include('includes/config.php');
error_reporting(0);

// Check if the user is logged in
if (strlen($_SESSION['login']) == 0) {
    // If not logged in, redirect to the login page
    header('location:index.php');
} else {
    // If the form is submitted
    if (isset($_POST['submit'])) {
        // Retrieve form values and sanitize them
        $category = $_POST['category'];
        $description = $_POST['description'];
        $status = 1; // Status is set to active (1)

        // Insert category into the database
        $query = mysqli_query($con, "INSERT INTO tblcategory (CategoryName, Description, Is_Active) VALUES ('$category', '$description', '$status')");

        // If insertion is successful, display success message
        if ($query) {
            $msg = "Category created successfully!";
        } else {
            // If insertion fails, display error message
            $error = "Something went wrong. Please try again.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
   <style>
    /* General Styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

/* Main container */
.content-page {
    max-width: 900px;
    margin: 50px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Page Heading */
h2 {
    font-size: 2em;
    margin-bottom: 20px;
}

/* Breadcrumbs */
p {
    font-size: 1.1em;
    margin-bottom: 10px;
}

/* Horizontal line */
hr {
    margin-bottom: 20px;
}

/* Success and Error Alerts */
.alert {
    padding: 15px;
    margin: 10px 0;
    border-radius: 4px;
    font-size: 1.1em;
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

/* Form Styling */
form {
    display: flex;
    flex-direction: column;
}

.form-group {
    margin-bottom: 20px;
}

label {
    font-size: 1.1em;
    margin-bottom: 5px;
    color: #333;
}

.form-input {
    padding: 10px;
    font-size: 1em;
    border: 1px solid #ddd;
    border-radius: 4px;
    width: 100%;
    box-sizing: border-box;
}

.form-input:focus {
    border-color: #0056b3;
    outline: none;
}

/* Button Styling */
.form-buttons {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

.btn {
    padding: 10px 20px;
    font-size: 1.1em;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-submit {
    background-color: #28a745;
    color: white;
    flex: 1;
    margin-right: 10px;
}

.btn-submit:hover {
    background-color: #218838;
}

.btn-reset {
    background-color: #dc3545;
    color: white;
    flex: 1;
}

.btn-reset:hover {
    background-color: #c82333;
}

/* Sidebar and Footer Elements */
.sidebar, .footer {
    padding: 20px;
    background-color: #f8f9fa;
}

/* Add some padding and margin around the page */
.container {
    margin: 0 20px;
}

/* Media Queries for responsiveness */
@media (max-width: 768px) {
    .form-buttons {
        flex-direction: column;
    }
    
    .btn-submit, .btn-reset {
        width: 100%;
        margin: 10px 0;
    }
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

            <!-- Success or error message -->
            <?php if($msg): ?>
                <div class="alert success-alert">
                    <strong>Success!</strong> <?php echo htmlentities($msg); ?>
                </div>
            <?php endif; ?>

            <?php if($error): ?>
                <div class="alert error-alert">
                    <strong>Error!</strong> <?php echo htmlentities($error); ?>
                </div>
            <?php endif; ?>

            <!-- Category form -->
            <form name="category" method="post">
                <div class="form-group">
                    <label for="category">Category Name</label>
                    <input type="text" name="category" id="category" required class="form-input">
                </div>

                <div class="form-group">
                    <label for="description">Category Description</label>
                    <textarea name="description" id="description" rows="5" required class="form-input"></textarea>
                </div>

                <div class="form-buttons">
                    <button type="submit" name="submit" class="btn btn-submit">Submit</button>
                    <button type="reset" class="btn btn-reset">Discard</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

</body>
</html>

<?php } ?>

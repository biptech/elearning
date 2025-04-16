<?php
session_start();
include('includes/config.php');
error_reporting(0);

if(strlen($_SESSION['login']) == 0) {
    header('location:index.php');
} else {
    if(isset($_POST['submitsubcat'])) {
        $categoryid = $_POST['category'];
        $subcatname = $_POST['subcategory'];
        $subcatdescription = $_POST['sucatdescription'];
        $status = 1;
        $query = mysqli_query($con, "INSERT INTO tblsubcategory(CategoryId,Subcategory,SubCatDescription,Is_Active) VALUES('$categoryid','$subcatname','$subcatdescription','$status')");
        if($query) {
            $msg = "Sub-Category created";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
?>
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
.p.breadcrumb {
    font-size: 1.1em;
    margin-bottom: 10px;
}

/* Horizontal line */
hr {
    margin-bottom: 20px;
}

/* Success and Error Alerts */
.msg-success, .msg-error {
    padding: 15px;
    margin: 10px 0;
    border-radius: 4px;
    font-size: 1.1em;
}

.msg-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.msg-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* Form Styling */
form {
    display: flex;
    flex-direction: column;
}

label {
    font-size: 1.1em;
    margin-bottom: 5px;
    color: #333;
    margin-top: 20px;
}

select, input, textarea {
    padding: 10px;
    font-size: 1em;
    border: 1px solid #ddd;
    border-radius: 4px;
    width: 100%;
    box-sizing: border-box;
}

textarea {
    resize: vertical;
}

/* Input focus effect */
select:focus, input:focus, textarea:focus {
    border-color: #0056b3;
    outline: none;
}

/* Submit Button */
button {
    padding: 10px 20px;
    font-size: 1.1em;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 20px;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #0056b3;
}

/* Footer and Sidebar Elements */
.sidebar, .footer {
    padding: 20px;
    background-color: #f8f9fa;
}

/* Responsive Design */
@media (max-width: 768px) {
    .content-page {
        margin: 20px;
        padding: 15px;
    }

    form {
        margin-top: 20px;
    }
}

</style>
<?php include('includes/topheader.php'); ?>
<?php include('includes/leftsidebar.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

            <div>
                <h2>Add Sub-Category</h2>
                <p class="breadcrumb">
                    <a href="#">Admin</a> > <a href="#">Category</a> > Add Sub-Category
                </p>
                <hr>
            </div>

            <?php if($msg || $error) { ?>
                <?php if($msg) { ?>
                    <div class="msg-success">
                        <strong>Well done!</strong> <?php echo htmlentities($msg); ?>
                    </div>
                <?php } ?>
                <?php if($error) { ?>
                    <div class="msg-error">
                        <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                    </div>
                <?php } ?>
            <?php } ?>

            <div>
                <h3>Add Sub-Category</h3>
                <form name="category" method="post">
                    <label>Category</label>
                    <select name="category" required>
                        <option value="">Select Category</option>
                        <?php
                            $ret = mysqli_query($con, "SELECT id, CategoryName FROM tblcategory WHERE Is_Active=1");
                            while($result = mysqli_fetch_array($ret)) {
                        ?>
                            <option value="<?php echo htmlentities($result['id']); ?>">
                                <?php echo htmlentities($result['CategoryName']); ?>
                            </option>
                        <?php } ?>
                    </select>

                    <label>Sub-Category</label>
                    <input type="text" name="subcategory" required>

                    <label>Sub-Category Description</label>
                    <textarea name="sucatdescription" rows="5" required></textarea>

                    <button type="submit" name="submitsubcat">Submit</button>
                </form>
            </div>

        </div>
    </div>

    <?php include('includes/footer.php'); ?>
</div>

<?php } ?>

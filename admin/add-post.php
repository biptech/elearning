<?php
session_start();
include('includes/config.php');
error_reporting(0);

// Session timeout handling
if (!isset($_SESSION['last_activity'])) {
    $_SESSION['last_activity'] = time();
} elseif (time() - $_SESSION['last_activity'] > 900) {
    session_unset();
    session_destroy();
    header('location:index.php?timeout=true');
    exit;
}
$_SESSION['last_activity'] = time();

if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
    exit;
}

$msg = "";
$error = "";

if (isset($_POST['submit'])) {
    // Form processing logic remains unchanged
    // ... [original PHP code here] ...
}
?>

<?php include('includes/topheader.php'); ?>
<?php include('includes/leftsidebar.php'); ?>

<style>
.content-page {
    margin-left: 250px;
    padding: 30px;
    min-height: calc(100vh - 70px);
    margin-top: 70px;

}
.container-custom {
    max-width: 800px;
    margin: 0 auto;
    background: #ffffff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
}
.page-title-custom {
    font-size: 22px;
    color: #2c3e50;
    margin-bottom: 25px;
}

.breadcrumb-custom {
        list-style: none;
        padding: 0;
        display: flex;
        gap: 5px;
        font-size: 14px;
    }

    .breadcrumb-custom li::after {
        content: "/";
        margin: 0 5px;
    }

    .breadcrumb-custom li:last-child::after {
        content: "";
    }

.form-message-box {
    margin-bottom: 25px;
}
.alert-success-custom {
    background: #d4edda;
    color: #155724;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}
.alert-error-custom {
    background: #f8d7da;
    color: #721c24;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}
.form-add-post .form-group-custom {
    margin-bottom: 25px;
}
.form-add-post label {
    display: block;
    margin-bottom: 8px;
    color: #2c3e50;
    font-weight: 500;
}
.input-custom {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid #e3e6f0;
    border-radius: 5px;
    transition: border-color 0.3s;
}
.input-custom:focus {
    border-color: #727cf5;
    outline: none;
}
.select-custom {
    width: 100%;
    padding: 10px 15px;
    background: #ffffff;
    border: 1px solid #e3e6f0;
    border-radius: 5px;
}
.textarea-custom {
    width: 100%;
    height: 300px;
    padding: 15px;
    border: 1px solid #e3e6f0;
    border-radius: 5px;
    resize: vertical;
}
.form-actions-custom {
    margin-top: 30px;
    display: flex;
    gap: 15px;
}
.btn-submit-custom {
    background: #727cf5;
    color: #ffffff;
    padding: 10px 25px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s;
}
.btn-submit-custom:hover {
    background: #5b66e0;
}
.btn-discard-custom {
    background: #e3e6f0;
    color: #2c3e50;
    padding: 10px 25px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s;
}
.btn-discard-custom:hover {
    background: #d4d8e3;
}
</style>

<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize CKEditor
    ClassicEditor
        .create(document.querySelector('.textarea-custom'))
        .catch(error => {
            console.error(error);
        });

    // Category Change Handler
    document.querySelector('#category').addEventListener('change', function() {
        const catId = this.value;
        if(catId) {
            fetch(`get_subcategory.php?catid=${catId}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById("subcategory").innerHTML = data;
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading subcategories');
                });
        }
    });

    // Form Validation
    document.querySelector('.form-add-post').addEventListener('submit', function(e) {
        const title = document.getElementById('posttitle');
        const category = document.getElementById('category');
        const image = document.getElementById('postimage');
        
        if(title.value.trim() === '') {
            e.preventDefault();
            alert('Please enter a post title');
            title.focus();
            return false;
        }
        
        if(category.value === '') {
            e.preventDefault();
            alert('Please select a category');
            category.focus();
            return false;
        }
        
        if(image.files.length === 0) {
            e.preventDefault();
            alert('Please select an image');
            image.focus();
            return false;
        }
    });
});
</script>

<div class="content-page">
    <div class="content-area">
        <div class="container-custom">
            <div class="page-header-custom">
                <h4 class="page-title-custom">Add Post</h4>
                <ul class="breadcrumb-custom">
                    <li><a href="#">Post</a></li>
                    <!-- <li><a href="#">Add Post</a></li> -->
                    <li class="active">Add Post</li>
                </ul>
            </div>

            <div class="form-message-box">
                <?php if (!empty($msg)) { ?>
                    <div class="alert-success-custom">
                        <strong>Success:</strong> <?php echo htmlentities($msg); ?>
                    </div>
                <?php } ?>
                <?php if (!empty($error)) { ?>
                    <div class="alert-error-custom">
                        <strong>Error:</strong> <?php echo htmlentities($error); ?>
                    </div>
                <?php } ?>
            </div>

            <form name="addpost" method="post" class="form-add-post" enctype="multipart/form-data">
                <div class="form-group-custom">
                    <label for="posttitle">Post Title</label>
                    <input type="text" id="posttitle" name="posttitle" class="input-custom" placeholder="Enter title" required>
                </div>

                <div class="form-group-custom">
                    <label for="category">Category</label>
                    <select name="category" id="category" class="select-custom" onchange="getSubCat(this.value);" required>
                        <option value="">Select Category</option>
                        <?php
                        $ret = mysqli_query($con, "SELECT id, CategoryName FROM tblcategory WHERE Is_Active = 1");
                        while ($result = mysqli_fetch_array($ret)) {
                        ?>
                        <option value="<?php echo htmlentities($result['id']); ?>"><?php echo htmlentities($result['CategoryName']); ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group-custom">
                    <label for="subcategory">Sub Category</label>
                    <select name="subcategory" id="subcategory" class="select-custom"></select>
                </div>

                <div class="form-group-custom">
                    <label for="postdescription">Post Details</label>
                    <textarea name="postdescription" class="textarea-custom" required></textarea>
                </div>

                <div class="form-group-custom">
                    <label for="postimage">Feature Image</label>
                    <input type="file" id="postimage" name="postimage" class="input-file-custom" required>
                </div>

                <div class="form-actions-custom">
                    <button type="submit" name="submit" class="btn-submit-custom">Save and Post</button>
                    <button type="button" class="btn-discard-custom" onclick="window.location.href='discard.php';">Discard</button>
                </div>
            </form>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>
</div>
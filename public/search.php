<?php 
include '../includes/config.php';

$query = isset($_GET['query']) ? trim($_GET['query']) : '';

if ($query == '') {
    echo "<script>alert('Please enter a search query!'); window.history.back();</script>";
    exit;
}

// Sanitize search query to prevent SQL injection
$searchQuery = mysqli_real_escape_string($con, $query);

// Fetch posts from tblposts
$postQuery = "SELECT id, PostTitle AS title, PostDetails AS details, PostImage AS image, 'post' AS type 
              FROM tblposts 
              WHERE PostTitle LIKE '%$searchQuery%' OR PostDetails LIKE '%$searchQuery%'";

$postResults = mysqli_query($con, $postQuery);

// Fetch products from products table
$productQuery = "SELECT id, name AS title, details, image, price, 'product' AS type 
                 FROM products 
                 WHERE name LIKE '%$searchQuery%' OR details LIKE '%$searchQuery%'";

$productResults = mysqli_query($con, $productQuery);

// Combine results
$results = [];
while ($row = mysqli_fetch_assoc($postResults)) {
    $results[] = $row;
}
while ($row = mysqli_fetch_assoc($productResults)) {
    $results[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <style>
        body {
            background-color: #000;
            font-family: Arial, sans-serif;
        }

        .search-text {
            text-align: center;
            padding-top: 40px;
            color: #fff;
        }

        .search-text span {
            color: rgb(248, 189, 51);
        }

        .search-results {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 40px 20px;
        }

        .card-link {
            text-decoration: none;
            color: inherit;
        }

        .card {
            background-color: #111;
            border-radius: 10px;
            padding: 15px;
            width: 250px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin: 10px;
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4);
        }

        .card img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .card-body {
            padding: 15px 0;
        }

        .card h3 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #fff;
        }

        .price {
            font-size: 20px;
            color: #ffc107;
            font-weight: bold;
        }

        .no-results {
            text-align: center;
            color: #fff;
            padding: 50px;
        }

        .no-results a {
            color: #f8bd33;
        }

        @media (max-width: 600px) {
            .card {
                width: 90%;
            }
        }
    </style>
</head>

<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="secontainer container">
        <h2 class="search-text">Search Results for "<span><?php echo htmlspecialchars($query); ?></span>"</h2>
        
        <div class="search-results">
            <?php if (count($results) > 0) { ?>
                <?php foreach ($results as $row) { 
                    $link = ($row['type'] == 'post') 
                            ? "post-details.php?nid=" . $row['id'] 
                            : "product_detail.php?id=" . $row['id'];
                    $imagePath = ($row['type'] == 'post') 
                                 ? "../admin/postimages/" . $row['image'] 
                                 : "../admin/uploaded_files/" . $row['image'];
                ?>
                    <a href="<?php echo $link; ?>" class="card-link">
                        <div class="card">
                            <img src="<?php echo $imagePath; ?>" onerror="this.src='../images/default.jpg';" alt="<?php echo htmlspecialchars($row['title']); ?>">
                            <div class="card-body">
                                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                                <?php if ($row['type'] != 'post') { ?>
                                    <p class="price">Rs <?php echo htmlspecialchars($row['price']); ?></p>
                                <?php } ?>
                            </div>
                        </div>
                    </a>
                <?php } ?>
            <?php } else { ?>
                <div class="no-results">
                    <h4>No results found for "<?php echo htmlspecialchars($query); ?>"</h4>
                    <a href="index.php">Back to Home</a>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>

</html>
<?php 
include '../includes/config.php';

$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

if ($query == '') {
    echo "<script>alert('Please enter a search query!'); window.history.back();</script>";
    exit;
}

$searchQuery = mysqli_real_escape_string($con, $query);

// Fetch posts
$postQuery = "SELECT id, PostTitle AS title, PostDetails AS details, PostImage AS image, 'post' AS type 
              FROM tblposts 
              WHERE PostTitle LIKE '%$searchQuery%' OR PostDetails LIKE '%$searchQuery%'";

// Fetch products
$productQuery = "SELECT id, name AS title, details, image, price, 'product' AS type 
                 FROM products 
                 WHERE name LIKE '%$searchQuery%' OR details LIKE '%$searchQuery%'";

$results = [];

if ($filter == 'all' || $filter == 'post') {
    $postResults = mysqli_query($con, $postQuery);
    if (!$postResults) {
        echo "<script>alert('Error fetching posts.'); window.history.back();</script>";
        exit;
    }
    while ($row = mysqli_fetch_assoc($postResults)) {
        $results[] = $row;
    }
}

if ($filter == 'all' || $filter == 'product') {
    $productResults = mysqli_query($con, $productQuery);
    if (!$productResults) {
        echo "<script>alert('Error fetching products.'); window.history.back();</script>";
        exit;
    }
    while ($row = mysqli_fetch_assoc($productResults)) {
        $results[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background-color: #000;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .main-container {
            padding: 20px;
        }

        .search-text {
            text-align: center;
            padding-top: 20px;
            color: #fff;
        }

        .search-text span {
            color: rgb(248, 189, 51);
        }

        .content-wrapper {
            display: flex;
            gap: 30px;
            padding: 20px;
        }

        .filter-sidebar {
            background-color: #111;
            padding: 20px;
            border-radius: 10px;
            min-width: 200px;
            height: fit-content;
            color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .filter-sidebar h3 {
            margin-bottom: 15px;
            color: rgb(248, 189, 51);
        }

        .filter-sidebar label {
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .filter-select {
            width: 100%;
            padding: 8px;
            margin-top: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: none;
        }

        .filter-btn {
            padding: 8px 16px;
            background-color: #f8bd33;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: #000;
            font-weight: bold;
        }

        .filter-btn:hover {
            background-color: #e6a700;
        }

        .search-results {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
        }

        .card-link {
            text-decoration: none;
            color: inherit;
        }

        .card {
            background-color: #111;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4);
        }

        .horizontal-card {
            display: flex;
            align-items: center;
            gap: 20px;
            width: 100%;
            max-width: 800px;
        }

        .card-image {
            width: 200px;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
            flex-shrink: 0;
        }

        .card-body {
            padding: 0;
            color: #fff;
        }

        .card-body h3 {
            font-size: 18px;
            margin-bottom: 10px;
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
            width: 100%;
        }

        .no-results a {
            color: #f8bd33;
        }

        @media (max-width: 768px) {
            .content-wrapper {
                flex-direction: column;
                align-items: center;
            }

            .filter-sidebar {
                width: 100%;
                margin-bottom: 20px;
            }

            .horizontal-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .card-image {
                width: 100%;
                height: auto;
            }
        }
    </style>
</head>

<body>
<?php include '../includes/header.php'; ?>

<div class="main-container">
    <h2 class="search-text">Search Results for "<span><?php echo htmlspecialchars($query); ?></span>"</h2>

    <div class="content-wrapper">
        <!-- Filter Sidebar -->
        <div class="filter-sidebar">
            <form method="GET" action="search.php">
                <input type="hidden" name="query" value="<?php echo htmlspecialchars($query); ?>">
                <h3>Filter By:</h3>
                <label for="filterSelect">Choose Category:</label>
                <select id="filterSelect" name="filter" class="filter-select" onchange="this.form.submit()">
                    <option value="all" <?php if ($filter == 'all') echo 'selected'; ?>>All</option>
                    <option value="post" <?php if ($filter == 'post') echo 'selected'; ?>>Posts</option>
                    <option value="product" <?php if ($filter == 'product') echo 'selected'; ?>>Products</option>
                </select>
            </form>
        </div>

        <!-- Search Results -->
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
                        <div class="card horizontal-card">
                            <img class="card-image" src="<?php echo $imagePath; ?>" onerror="this.src='../images/default.jpg';" alt="<?php echo htmlspecialchars($row['title']); ?>">
                            <div class="card-body">
                                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                                <?php if ($row['type'] == 'product') { ?>
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
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>

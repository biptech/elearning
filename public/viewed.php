<?php
include '../includes/config.php';

$items = [];

if (!empty($_SESSION['viewed_items'])) {
    $unique = [];
    $count = 0;

    foreach (array_reverse($_SESSION['viewed_items']) as $viewed) {
        $id = isset($viewed['id']) ? (int)$viewed['id'] : 0;
        $type = $viewed['type'] ?? '';

        if ($id <= 0 || !in_array($type, ['post', 'product'])) {
            continue;
        }

        $key = $type . '_' . $id;
        if (isset($unique[$key])) continue;
        $unique[$key] = true;

        if ($type === 'post') {
            $query = "SELECT id, PostTitle AS title, PostImage AS image, PostDetails AS description, 'post' AS type 
                      FROM tblposts WHERE id = $id";
        } else {
            $query = "SELECT id, name AS title, image, price, 'product' AS type 
                      FROM products WHERE id = $id";
        }

        $res = mysqli_query($con, $query);

        if ($res && $data = mysqli_fetch_assoc($res)) {
            $items[] = $data;
            $count++;
        }

        if ($count >= 10) {
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recently Viewed Items</title>
    <style>
        body {
            background: #0a0a0a;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            padding: 20px 10px 10px;
            font-size: 26px;
            color: #f4f4f4;
        }

        .carousel-container {
            display: flex;
            overflow-x: auto;
            scroll-behavior: smooth;
            padding: 20px;
            gap: 20px;
        }

        .carousel-container::-webkit-scrollbar {
            display: none;
        }

        .carousel-item {
            flex: 0 0 auto;
            background: #1a1a1a;
            width: 240px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.6);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .carousel-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 25px rgba(255, 255, 255, 0.2);
        }

        .carousel-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-bottom: 2px solid #333;
        }

        .carousel-item h4 {
            padding: 10px 12px 0;
            font-size: 16px;
            margin: 0;
            color: #fff;
        }

        .carousel-item p {
            padding: 4px 12px 12px;
            margin: 0;
            font-size: 14px;
            color: #ccc;
        }

        .carousel-link {
            text-decoration: none;
            color: white;
        }

        @media (max-width: 600px) {
            .carousel-item {
                width: 180px;
            }

            .carousel-item h4 {
                font-size: 14px;
            }

            .carousel-item p {
                font-size: 13px;
            }

            h2 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>

<h2>👀 Recently Viewed Items</h2>

<div class="carousel-container">
    <?php if (!empty($items)): ?>
        <?php foreach ($items as $item): 
            $link = ($item['type'] == 'post') 
                ? "post-details.php?nid=" . $item['id'] 
                : "product_detail.php?id=" . $item['id'];

            $imagePath = ($item['type'] == 'post') 
                ? "../admin/postimages/" . $item['image'] 
                : "../admin/uploaded_files/" . $item['image'];

            $description = '';
            if ($item['type'] == 'post' && isset($item['description'])) {
                $descText = strip_tags($item['description']);
                $description = mb_substr($descText, 0, 80) . '...';
            }
        ?>
            <a href="<?php echo $link; ?>" class="carousel-link">
                <div class="carousel-item">
                    <img src="<?php echo $imagePath; ?>" onerror="this.src='../images/default.jpg';" alt="Item Image">
                    <h4 title="<?php echo htmlspecialchars($item['title']); ?>" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        <?php echo htmlspecialchars($item['title']); ?>
                    </h4>
                    <?php if ($item['type'] == 'post'): ?>
                        <p><?php echo htmlspecialchars($description); ?></p>
                    <?php elseif ($item['type'] == 'product' && isset($item['price'])): ?>
                        <p>Price: रु <?php echo htmlspecialchars($item['price']); ?></p>
                    <?php endif; ?>
                </div>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align:center; padding: 20px;">No recently viewed items.</p>
    <?php endif; ?>
</div>

</body>
</html>

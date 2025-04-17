<style>

    .custom-sidebar-container {
        width: 250px;
        background: #222d32;
        color: #b8c7ce;
        height: 100vh;
        padding: 20px 0;
        position: fixed;
        left: 0;
        top: 70px; /* Match header height */
        overflow-y: auto;
    }
    .custom-menu-list {
        list-style: none;
        padding: 0;
    }

    .custom-menu-item {
        margin: 0;
    }

    .custom-menu-link {
        display: block;
        padding: 12px 20px;
        color: #b8c7ce;
        text-decoration: none;
    }

    .custom-menu-link:hover {
        background: #1e282c;
        color: #fff;
    }

    .custom-submenu {
        list-style: none;
        padding-left: 20px;
        display: none;
    }

    .has-submenu:hover .custom-submenu {
        display: block;
    }

    .custom-submenu-link {
        padding: 8px 20px;
        display: block;
        color: #b8c7ce;
        text-decoration: none;
    }

    .custom-submenu-link:hover {
        color: #fff;
        background-color: #1a2226;
    }

    .custom-menu-arrow {
        float: right;
    }
</style>

<div class="custom-sidebar-container">
    <ul class="custom-menu-list">
        <li class="custom-menu-item"><a href="dashboard.php" class="custom-menu-link"><i class="mdi mdi-view-dashboard"></i> Dashboard</a></li>
        
        <li class="custom-menu-item has-submenu">
            <a href="#" class="custom-menu-link"><i class="mdi mdi-format-list-bulleted"></i> Category <span class="custom-menu-arrow">▼</span></a>
            <ul class="custom-submenu">
                <li><a href="add-category.php" class="custom-submenu-link">Add Category</a></li>
                <li><a href="manage-categories.php" class="custom-submenu-link">Manage Category</a></li>
            </ul>
        </li>

        <!-- <li class="custom-menu-item has-submenu">
            <a href="#" class="custom-menu-link"><i class="ti ti-layout-list-thumb"></i> Sub Category <span class="custom-menu-arrow">▼</span></a>
            <ul class="custom-submenu">
                <li><a href="add-subcategory.php" class="custom-submenu-link">Add Sub Category</a></li>
                <li><a href="manage-subcategories.php" class="custom-submenu-link">Manage Sub Category</a></li>
            </ul>
        </li> -->

        <li class="custom-menu-item has-submenu">
            <a href="#" class="custom-menu-link"><i class="mdi mdi-newspaper"></i> Posts <span class="custom-menu-arrow">▼</span></a>
            <ul class="custom-submenu">
                <li><a href="add-post.php" class="custom-submenu-link">Add Posts</a></li>
                <li><a href="manage-posts.php" class="custom-submenu-link">Manage Posts</a></li>
                <li><a href="trash-posts.php" class="custom-submenu-link">Trash Posts</a></li>
            </ul>
        </li>

        <li class="custom-menu-item has-submenu">
            <a href="#" class="custom-menu-link"><i class="mdi mdi-cart"></i> Cart <span class="custom-menu-arrow">▼</span></a>
            <ul class="custom-submenu">
                <li><a href="add-cart.php" class="custom-submenu-link">Add Cart</a></li>
                <li><a href="manage-carts.php" class="custom-submenu-link">Manage Cart</a></li>
                <li><a href="trash-carts.php" class="custom-submenu-link">Trash Cart</a></li>
            </ul>
        </li>

        <li class="custom-menu-item has-submenu">
            <a href="#" class="custom-menu-link"><i class="mdi mdi-comment-account-outline"></i> Orders <span class="custom-menu-arrow">▼</span></a>
            <ul class="custom-submenu">
                <li><a href="unapprove-orders.php" class="custom-submenu-link">Waiting Orders</a></li>
                <li><a href="manage-orders.php" class="custom-submenu-link">Approved Orders</a></li>
            </ul>
        </li>

        <li class="custom-menu-item has-submenu">
            <a href="#" class="custom-menu-link"><i class="ti ti-files"></i> CMS <span class="custom-menu-arrow">▼</span></a>
            <ul class="custom-submenu">
                <li><a href="aboutus.php" class="custom-submenu-link">About Us</a></li>
                <li><a href="contactus.php" class="custom-submenu-link">Contact Us</a></li>
            </ul>
        </li>

        <li class="custom-menu-item has-submenu">
            <a href="#" class="custom-menu-link"><i class="mdi mdi-comment-account-outline"></i> Comments <span class="custom-menu-arrow">▼</span></a>
            <ul class="custom-submenu">
                <li><a href="unapprove-comment.php" class="custom-submenu-link">Waiting for Approval</a></li>
                <li><a href="manage-comments.php" class="custom-submenu-link">Approved Comments</a></li>
            </ul>
        </li>

        <li class="custom-menu-item">
            <a href="https://www.youtube.com/@BipTechOfficial" class="custom-menu-link" target="_blank"><i class="ti ti-info-alt"></i> Watch Video</a>
        </li>

        <li class="custom-menu-item">
            <a href="#" class="custom-menu-link"><i class="fa fa-eye"></i> Website Preview</a>
        </li>
    </ul>
</div>

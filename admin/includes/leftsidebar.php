<style>
    .custom-sidebar-container {
        width: 250px;
        background: #1f2a36;
        color: #e0e0e0;
        height: 100vh;
        position: fixed;
        top: 70px;
        left: 0;
        overflow-y: auto;
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        z-index: 100;
    }

    .custom-menu-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .custom-menu-item {
        transition: background 0.2s ease;
    }

    .custom-menu-link {
        display: block;
        padding: 14px 20px;
        color: #e0e0e0;
        text-decoration: none;
        font-weight: 500;
        font-size: 15px;
        transition: all 0.3s ease;
    }

    .custom-menu-link i {
        margin-right: 10px;
    }

    .custom-menu-link:hover {
        background: #263544;
        color: #ffffff;
    }

    .custom-submenu {
        list-style: none;
        padding-left: 20px;
        display: none;
        background: #1b252f;
    }

    .has-submenu:hover .custom-submenu {
        display: block;
    }

    .custom-submenu-link {
        display: block;
        padding: 10px 25px;
        color: #b8c7ce;
        text-decoration: none;
        font-size: 14px;
        transition: background 0.2s ease;
    }

    .custom-submenu-link:hover {
        background: #2c3b4a;
        color: #fff;
    }

    .custom-menu-arrow {
        float: right;
        font-size: 12px;
        opacity: 0.6;
    }

    /* Optional scrollbar styling for long sidebar content */
    .custom-sidebar-container::-webkit-scrollbar {
        width: 6px;
    }
    .custom-sidebar-container::-webkit-scrollbar-thumb {
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
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

        <li class="custom-menu-item has-submenu">
            <a href="#" class="custom-menu-link"><i class="ti ti-layout-list-thumb"></i> Logo <span class="custom-menu-arrow">▼</span></a>
            <ul class="custom-submenu">
                <li><a href="upload-logo.php" class="custom-submenu-link">Add Logo</a></li>
            </ul>
       </li>

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

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/config.php';

if (isset($_SESSION['username'])) {
    $u_username = $_SESSION['username'];
    $qry = "SELECT * FROM user_signup WHERE u_email = '$u_username'";
    $result = mysqli_query($con, $qry);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $u_name = $row['u_name'] ?? 'Guest';
        $u_id = $row['u_id'] ?? 0;
        $parts = explode(' ', $u_name);
        $firstName = $parts[0] ?? 'Guest';
    } else {
        // If no user is found, assign default values
        $u_name = 'Guest';
        $u_id = 0;
        $firstName = 'Guest';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery for AJAX -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    />
    <link rel="stylesheet" href="../css/header.css">

</head>
<body>
    <nav class="navbar">
        <div><a class="logo" href="index.php">eLearning</a></div>
        
        <!-- Search Bar -->
        <form class="search-bar" action="search.php" method="GET" onsubmit="return validateSearch()">
          <i class="fa fa-search"></i>
            <input type="text" id="search-box" name="query" placeholder="Search for anything" onkeyup="toggleSearchButton()" autocomplete="off">
            <div id="suggestions"></div>
        </form>

        <div class="right-icons">
            <a href="cources.php">Our Courses</a>
            <a href="view_products.php">E-Book</a>
            <a href="#">My Learning</a>

            <!-- Profile Dropdown -->
            <div class="profile-section">
                <?php if (isset($_SESSION['username'])) { ?>
                    <div class="dropdown">
                        <a href="#" id="profileDropdownMenu"><i class="fa fa-user-circle-o"></i> <?php echo $firstName; ?></a>
                        <div class="dropdown-content" id="profileDropdown">
                            <a href="user-profile.php?u_id=<?php echo $u_id; ?>"><i class="fa fa-user"></i> Profile</a>
                            <a href="log-out.php" onclick="return confirm('Are you sure to log out?')"><i class="fa fa-sign-out"></i> LOG OUT</a>
                        </div>
                    </div>
                <?php } else { ?>
                    <div>
                        <a href="login.php"><i class="fa fa-sign-in"></i> LOG IN</a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </nav>

    <nav class="navbar">
        <div class="categories">
            <a href="#">Development</a>
            <a href="#">Business</a>
            <a href="#">IT & Software</a>
            <a href="#">Design</a>
            <a href="#">Marketing</a>
        </div>
    </nav>

    <script>
        // Fetch search suggestions
        function fetchSuggestions() {
            let query = document.getElementById("search-box").value;
            let suggestionsBox = document.getElementById("suggestions");

            if (query.length < 1) {
                suggestionsBox.style.display = "none";
                return;
            }

            $.ajax({
                url: "search_suggest.php",
                method: "GET",
                data: { query: query },
                success: function(response) {
                    let suggestions = JSON.parse(response);
                    let output = "";

                    if (suggestions.length > 0) {
                        suggestions.forEach(function(item) {
                            output += `<div onclick="selectSuggestion('${item.title}')">
                                        <strong>${item.type}:</strong> ${item.title}
                                       </div>`;
                        });
                    } else {
                        output = "<div>No results found</div>";
                    }

                    suggestionsBox.innerHTML = output;
                    suggestionsBox.style.display = "block";
                }
            });
        }

        // Select search suggestion
        function selectSuggestion(value) {
            document.getElementById("search-box").value = value;
            document.getElementById("suggestions").style.display = "none";
        }

        // Enable search button when text is entered
        function toggleSearchButton() {
            let searchBox = document.getElementById("search-box");
            let searchBtn = document.getElementById("search-btn");
            searchBtn.style.display = searchBox.value.trim() === "" ? "none" : "inline-block";
        }

        // Validate search input before submission
        function validateSearch() {
            return document.getElementById("search-box").value.trim() !== "";
        }

        // Profile Dropdown Toggle
        document.addEventListener("DOMContentLoaded", function () {
            const dropdownMenu = document.getElementById("profileDropdownMenu");
            const dropdownContent = document.getElementById("profileDropdown");

            dropdownMenu.addEventListener("click", function (event) {
                event.preventDefault();
                dropdownContent.classList.toggle("show");
            });

            // Close dropdown when clicking outside
            document.addEventListener("click", function (event) {
                if (!dropdownMenu.contains(event.target) && !dropdownContent.contains(event.target)) {
                    dropdownContent.classList.remove("show");
                }
            });
        });
    </script>
</body>
</html>
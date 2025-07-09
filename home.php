<?php
session_start();
if (!isset($_SESSION['user_id'])) 
{
    header("Location: index.html"); 
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'filmforge');
if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

// Pagination setup
$limit = 20; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
$offset = ($page - 1) * $limit; 

// Fetch total number of movies for pagination
$total_sql = "SELECT COUNT(*) AS total_movies FROM Movies";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_movies = $total_row['total_movies'];
$total_pages = ceil($total_movies / $limit);

// Fetch movies for the current page
$sql = "SELECT * FROM Movies LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FilmForge - Home</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function showLoader() 
	{
            document.getElementById("loader").style.display = "block";
        }
        window.onload = function() 
	{
            showLoader();
            setTimeout(function() 
	    {
                document.getElementById("loader").style.display = "none";
            }, 1000);
        };
    </script>
</head>
<body class="home-page">
    <header>
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="my_reviews.php">My Reviews</a></li>
                <li><a href="favorites.php">Favorites</a></li>
                <li><a href="watchlist.php">Watchlist</a></li>
                <li><a href="search.php">Search</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Welcome to FilmForge!</h1>
        
                
        <div class="movies-container">
            <?php while ($movie = $result->fetch_assoc()): ?>
                <div class="movie-card">
                    <img src="<?php echo $movie['image_url']; ?>" alt="<?php echo $movie['title']; ?>">
                    <h2><?php echo $movie['title']; ?></h2>
                    <p><?php echo $movie['release_year']; ?></p>
                    <a href="movie_details.php?movie_id=<?php echo $movie['movie_id']; ?>">View Details</a>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="home.php?page=<?php echo $page - 1; ?>">Previous</a>
            <?php endif; ?>
            <?php if ($page < $total_pages): ?>
                <a href="home.php?page=<?php echo $page + 1; ?>">Next</a>
            <?php endif; ?>
        </div>
	

    </main>
<footer class="site-footer">
    <p>FilmForge © All Rights Reserved</p>
</footer>
</body>
</html>

<?php
$conn->close();
?>







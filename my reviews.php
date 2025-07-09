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

$user_id = $_SESSION['user_id'];
$reviews = [];

$sql = "SELECT Movies.movie_id, Movies.title, Movies.image_url, Reviews.rating, Reviews.review_text 
        FROM Reviews 
        JOIN Movies ON Reviews.movie_id = Movies.movie_id 
        WHERE Reviews.user_id = ?";
$stmt = $conn->prepare($sql);
if ($stmt) 
{
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) 
    {
        $reviews[] = $row;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FilmForge - My Reviews</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="my-reviews-page">
    <header>
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="my_reviews.php" class="active">My Reviews</a></li>
                <li><a href="favorites.php">Favorites</a></li>
                <li><a href="watchlist.php">Watchlist</a></li>
                <li><a href="search.php">Search</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>My Reviews</h1>
        <div class="movies-container">
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="movie-card">
                        <img src="<?php echo $review['image_url']; ?>" alt="<?php echo $review['title']; ?>">
                        <h2><?php echo $review['title']; ?></h2>
                        <p>Rating: <?php echo $review['rating']; ?>/5</p>
                        <p>Review: <?php echo $review['review_text']; ?></p>
                        <a href="movie_details.php?movie_id=<?php echo $review['movie_id']; ?>">View Movie</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>You haven't reviewed any movies yet.</p>
            <?php endif; ?>
        </div>
    </main>
	<footer class="site-footer">
    <p>FilmForge © All Rights Reserved</p>
</footer>
</body>
</html>

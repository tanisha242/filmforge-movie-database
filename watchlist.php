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
$watchlist = [];

$sql = "SELECT Movies.movie_id, Movies.title, Movies.release_year, Movies.genre, Movies.image_url 
        FROM Watchlist 
        JOIN Movies ON Watchlist.movie_id = Movies.movie_id 
        WHERE Watchlist.user_id = ?";
$stmt = $conn->prepare($sql);
if ($stmt) 
{
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) 
    {
        $watchlist[] = $row;
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
    <title>FilmForge - Watchlist</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="watchlist-page">
    <header>
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="my_reviews.php">My Reviews</a></li>
                <li><a href="favorites.php">Favorites</a></li>
                <li><a href="watchlist.php" class="active">Watchlist</a></li>
                <li><a href="search.php">Search</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>My Watchlist</h1>
        <div class="movies-container">
            <?php if (!empty($watchlist)): ?>
                <?php foreach ($watchlist as $movie): ?>
                    <div class="movie-card">
                        <img src="<?php echo $movie['image_url']; ?>" alt="<?php echo $movie['title']; ?>">
                        <h2><?php echo $movie['title']; ?></h2>
                        <p><?php echo $movie['release_year']; ?> | <?php echo $movie['genre']; ?></p>
                        <a href="movie_details.php?movie_id=<?php echo $movie['movie_id']; ?>">View Details</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Your watchlist is currently empty.</p>
            <?php endif; ?>
        </div>
    </main>
	<footer class="site-footer">
    <p>FilmForge © All Rights Reserved</p>
</footer>
</body>
</html>

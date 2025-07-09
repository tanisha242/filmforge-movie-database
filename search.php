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

$search_query = "";
$genre_filter = "";
$movies = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $search_query = $_POST['search_query'];
    $genre_filter = $_POST['genre_filter'];

    $sql = "SELECT * FROM Movies WHERE title LIKE ?";
    if (!empty($genre_filter)) 
    {
        $sql .= " AND genre = ?";
    }

    $stmt = $conn->prepare($sql);
    if ($stmt) 
    {
        if (!empty($genre_filter)) 
	{
            $search_query = "%$search_query%";  
            $stmt->bind_param("ss", $search_query, $genre_filter);
        } 
	else 
	{
            $search_query = "%$search_query%";
            $stmt->bind_param("s", $search_query);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) 
	{
            $movies[] = $row;
        }
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FilmForge - Search</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="search-page">
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
        <h1>Search Movies</h1>
        <form method="POST" action="search.php" class="search-form">
            <label for="search_query">Search by Title:</label>
            <input type="text" name="search_query" id="search_query" placeholder="Search by title..." value="<?php echo htmlspecialchars($search_query); ?>">
            <label for="genre_filter">Genre:</label>
            <select name="genre_filter" id="genre_filter">
                <option value="">All Genres</option>
                <option value="Action" <?php if ($genre_filter == "Action") echo "selected"; ?>>Action</option>
                <option value="Suspense" <?php if ($genre_filter == "Suspense") echo "selected"; ?>>Suspense</option>
                <option value="Thriller" <?php if ($genre_filter == "Thriller") echo "selected"; ?>>Thriller</option>
                <option value="Romantic" <?php if ($genre_filter == "Romantic") echo "selected"; ?>>Romantic</option>
                <option value="Horror" <?php if ($genre_filter == "Horror") echo "selected"; ?>>Horror</option>
                <option value="SciFi" <?php if ($genre_filter == "SciFi") echo "selected"; ?>>SciFi</option>
            </select>
            <button type="submit">Search</button>
        </form>

        <div class="movies-container">
            <?php if (!empty($movies)): ?>
                <?php foreach ($movies as $movie): ?>
                    <div class="movie-card">
                        <img src="<?php echo $movie['image_url']; ?>" alt="<?php echo $movie['title']; ?>">
                        <h2><?php echo $movie['title']; ?></h2>
                        <p><?php echo $movie['release_year']; ?></p>
                        <a href="movie_details.php?movie_id=<?php echo $movie['movie_id']; ?>">View Details</a>
                    </div>
                <?php endforeach; ?>
            <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                <p>No movies found matching your search criteria. Try refining your search.</p>
            <?php endif; ?>
        </div>
    </main>
	<footer class="site-footer">
    <p>FilmForge © All Rights Reserved</p>
</footer>
</body>
</html>


















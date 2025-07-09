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

$movie_id = $_GET['movie_id'] ?? 0; 

$sql = "SELECT * FROM Movies WHERE movie_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_assoc();

if (!$movie) 
{
    header("Location: home.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$review_check_sql = "SELECT * FROM Reviews WHERE movie_id = ? AND user_id = ?";
$review_stmt = $conn->prepare($review_check_sql);
$review_stmt->bind_param("ii", $movie_id, $user_id);
$review_stmt->execute();
$review_check_result = $review_stmt->get_result();
$existing_review = $review_check_result->fetch_assoc();

$reviews_sql = "SELECT reviews.*, users.username 
                FROM Reviews reviews 
                JOIN Users users ON reviews.user_id = users.user_id 
                WHERE reviews.movie_id = ?";
$reviews_stmt = $conn->prepare($reviews_sql);
$reviews_stmt->bind_param("i", $movie_id);
$reviews_stmt->execute();
$reviews_result = $reviews_stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($movie['title']); ?> - FilmForge</title>
    <link rel="stylesheet" href="moviedetails.css">
    <script>
            function setRating(rating) 
	    {
            document.getElementById('rating').value = rating;
            for (let i = 1; i <= 5; i++) 
	    {
                document.getElementById('star' + i).classList.remove('filled');
                if (i <= rating) 
	        {
                    document.getElementById('star' + i).classList.add('filled');
                }
            }
        }
    </script>
</head>
<body class="movie-detail-page" style="background-image: url('images/new.jpeg')">


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
        <div class="container">
                <div class="movie-image">
                <img src="<?php echo htmlspecialchars($movie['image_url']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
            </div>

                <div class="movie-description">
                <h1><?php echo htmlspecialchars($movie['title']); ?></h1>
                <p><?php echo htmlspecialchars($movie['description']); ?></p>
                <p><strong>Release Year:</strong> <?php echo htmlspecialchars($movie['release_year']); ?></p>
                <p><strong>Genre:</strong> <?php echo htmlspecialchars($movie['genre']); ?></p>
                <a href="<?php echo htmlspecialchars($movie['trailer_url']); ?>" target="_blank">Watch Trailer</a>
            </div>

                <div class="review-form">
                <?php if (!$existing_review): ?>
                    <h3>Your Review:</h3>
                    <form action="movie_actions.php" method="POST">
                        <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>">
                        <input type="hidden" name="rating" id="rating" value="0">
                        <label for="review">Your Review:</label>
                        <textarea name="review" rows="4" required></textarea>
                        <div class="star-rating">
                            <span id="star1" class="star" onclick="setRating(1)">&#9733;</span>
                            <span id="star2" class="star" onclick="setRating(2)">&#9733;</span>
                            <span id="star3" class="star" onclick="setRating(3)">&#9733;</span>
                            <span id="star4" class="star" onclick="setRating(4)">&#9733;</span>
                            <span id="star5" class="star" onclick="setRating(5)">&#9733;</span>
                        </div>
                       <br><button type="submit" name="action" value="rate_review">Submit Review</button>
                    </form>
                <?php else: ?>
                    <h3>Your Review:</h3>
                    <p>Rating: <?php echo str_repeat('&#9733;', $existing_review['rating']); ?></p>
                    <p><?php echo htmlspecialchars($existing_review['review_text']); ?></p>
                <?php endif; ?>

                    <form action="movie_actions.php" method="POST">
                    <br><input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>">
                    <button type="submit" name="action" value="add_to_favorites">Add to Favorites</button><br><br>
                    <button type="submit" name="action" value="add_to_watchlist">Add to Watchlist</button><br>
                </form>
            </div>
        </div>

            <div class="reviews">
            <h3>All Reviews:</h3>
            <?php while ($review = $reviews_result->fetch_assoc()): ?>
                <div class="review">
                    <p><strong><?php echo htmlspecialchars($review['username']); ?></strong></p>
                    <p>Rating: <?php echo str_repeat('&#9733;', $review['rating']); ?></p>
                    <p><?php echo htmlspecialchars($review['review_text']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </main>
	
</body>
</html>

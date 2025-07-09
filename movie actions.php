<?php
session_start();
if (!isset($_SESSION['user_id']))
{
    header("Location: index.html");
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'filmforge');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$movie_id = $_POST['movie_id'];
$action = $_POST['action'];

$redirect_url = "movie_details.php?movie_id=" . $movie_id;

if ($action == "rate_review" && isset($_POST['rating'])) 
{
    $rating = (int)$_POST['rating'];
    if ($rating < 1 || $rating > 5) 
    {
        header("Location: $redirect_url&error=Invalid rating value");
        exit;
    }

    $review = $conn->real_escape_string($_POST['review']);

    $check_sql = "SELECT * FROM Reviews WHERE user_id = $user_id AND movie_id = $movie_id";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) 
    {
        $sql = "UPDATE Reviews SET rating = $rating, review_text = '$review' WHERE user_id = $user_id AND movie_id = $movie_id";
    } 
    else 
    {
        $sql = "INSERT INTO Reviews (user_id, movie_id, rating, review_text) VALUES ($user_id, $movie_id, $rating, '$review')";
    }

    if ($conn->query($sql) === TRUE) 
    {
        header("Location: $redirect_url&success=Review saved successfully");
    } 
    else 
    {
        header("Location: $redirect_url&error=" . urlencode($conn->error));
    }
} 
elseif ($action == "add_to_favorites") 
{
    $check_sql = "SELECT * FROM Favorites WHERE user_id = $user_id AND movie_id = $movie_id";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows == 0) 
    {
        $sql = "INSERT INTO Favorites (user_id, movie_id) VALUES ($user_id, $movie_id)";
        if ($conn->query($sql) === TRUE) 
        {
            header("Location: $redirect_url&success=Movie added to favorites");
        } 
        else 
        {
            header("Location: $redirect_url&error=" . urlencode($conn->error));
        }
    } 
    else 
    {
        header("Location: $redirect_url&info=Movie already in favorites");
    }
} 
elseif ($action == "add_to_watchlist") 
{
    $check_sql = "SELECT * FROM Watchlist WHERE user_id = $user_id AND movie_id = $movie_id";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows == 0) 
    {
        $sql = "INSERT INTO Watchlist (user_id, movie_id) VALUES ($user_id, $movie_id)";
        if ($conn->query($sql) === TRUE) 
        {
            header("Location: $redirect_url&success=Movie added to watchlist");
        } 
        else 
        {
            header("Location: $redirect_url&error=" . urlencode($conn->error));
        }
    } 
    else 
    {
        header("Location: $redirect_url&info=Movie already in watchlist");
    }
}

$conn->close();
?>

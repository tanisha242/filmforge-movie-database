<?php
$conn = new mysqli('localhost', 'root', '', 'filmforge');

if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM Users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) 
    {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) 
	{
            session_start();
            $_SESSION['user_id'] = $row['user_id'];
            header("Location: home.php");  
            exit;
        }
       else 
       {
            echo "<script>alert('Invalid password. Please try again.'); window.history.back();</script>";
        }
    } 
   else 
   {
        echo "<script>alert('User not found. Please check your username or sign up.'); window.history.back();</script>";
   }

    $stmt->close();
}

$conn->close();
?>







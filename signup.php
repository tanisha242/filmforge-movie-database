<?php
$conn = new mysqli('localhost', 'root', '', 'filmforge');

if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM Users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) 
    {
        echo "<script>alert('Username or email already exists. Please try a different one.'); window.history.back();</script>";
    } 
    else 
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO Users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) 
        {
            echo "<script>alert('Signup successful! You can now login.'); window.location.href = 'index.html';</script>";
        } 
	else 
	{
            echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
        }

        $stmt->close();
    }
}

$conn->close();
?>





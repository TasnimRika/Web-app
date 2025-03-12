<?php
// Database Configuration
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $role = $_POST['role']; 

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if ($stmt->execute()) {
        echo "<script>alert('Registration Successful'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Unable to register);</script>";
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center min-h-screen bg-gradient-to-r from-gray-100 to-gray-300">
    <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-4">Register</h1>
        <form method="POST" action="">
            <div class="mb-4">
                <input type="text" name="name" placeholder="Name" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" />
            </div>
            <div class="mb-4">
                <input type="email" name="email" placeholder="Email" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" />
            </div>
            <div class="mb-4">
                <input type="password" name="password" placeholder="Password" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" />
            </div>
            <div class="mb-4">
                <select name="role" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    <option value="" disabled selected>Select Role</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit"
                class="w-full px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition">Register</button>
        </form>
        <div class="mt-4 text-center">
            <button onclick="location.href='login.php'"
                class="w-full px-4 py-2 text-blue-500 border border-blue-500 rounded-lg hover:bg-blue-100 transition">Login</button>
        </div>
    </div>
</body>

</html>
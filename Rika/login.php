<?php

include 'db_config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password']; 
    $role = $_POST['role'];

    $sql = "SELECT * FROM users WHERE email='$email' AND role='$role'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $role;
            $_SESSION['user_name'] = $user['name'];

            header("Location: " . ($role === 'admin' ? 'dashboard.php' : 'dashboard.php'));
            exit();
        } else {
            echo "<script>alert('Invalid password. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('No user found with this email and role.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center min-h-screen bg-gradient-to-r from-gray-100 to-gray-300">
    <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-4">Login</h1>
        <form method="POST" action="">
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
                class="w-full px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition">Login</button>
            <button type="button" onclick="location.href='register.php'"
                class="w-full mt-2 px-4 py-2 text-blue-500 border border-blue-500 rounded-lg hover:bg-blue-100 transition">Register</button>
        </form>
    </div>
</body>

</html>
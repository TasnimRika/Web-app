<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

$stmt = $conn->prepare("SELECT name, email, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_profile'])) {
        if (!empty($_FILES['profile_picture']['name'])) {
            $target_dir = "pictures/";  // Change folder to 'pictures'
            $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
            
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
                $stmt->bind_param("si", $target_file, $user_id);
                if ($stmt->execute()) {
                    $message = "picture updated!";
                }
            } else {
                $message = "Error uploading picture.";
            }
        }
        
    }

    if (isset($_POST['change_password'])) {
        $new_password = htmlspecialchars($_POST['new_password']);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $new_password, $user_id);
        if ($stmt->execute()) {
            $message = "Password changed!";
        }
    }

    if (isset($_POST['delete_account'])) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            session_destroy();
            header("Location: register.php");
            exit();
        }
    }

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex flex-col items-center">
    <nav class="w-full bg-blue-600 text-white p-4 flex justify-between items-center shadow-md">
        <div class="text-xl font-bold"> login</div>
        <button onclick="location.href='logout.php'"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Logout</button>
    </nav>

    <div class="bg-white p-8 mt-6 rounded-lg shadow-lg w-full max-w-md text-center">
        <h1 class="text-2xl font-bold text-gray-800">Hello, <?php echo $user['name']; ?>!</h1>
        <img src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture"
            class="w-24 h-24 rounded-full mx-auto my-4 border">
        <p class="text-green-500"> <?php echo $message; ?> </p>

        <h2 class="text-xl font-semibold mt-4">Upload Photo</h2>
        <form method="POST" action="" enctype="multipart/form-data" class="space-y-3">
            <input type="file" name="profile_picture" class="w-full p-2 border rounded">
            <button type="submit" name="update_profile"
                class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update
                Profile Picture</button>
        </form>

        <h2 class="text-xl font-semibold mt-4">Change Password</h2>
        <form method="POST" action="" class="space-y-3">
            <input type="password" name="new_password" placeholder="New Password" required
                class="w-full p-2 border rounded">
            <button type="submit" name="change_password"
                class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Change
                Password</button>
        </form>

        <h2 class="text-xl font-semibold mt-4">Delete Account</h2>
        <form method="POST" action="">
            <button type="submit" name="delete_account"
                class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                onclick="return confirm('Are you sure? This action cannot be undone!')">Delete Account</button>
        </form>
    </div>
</body>

</html>
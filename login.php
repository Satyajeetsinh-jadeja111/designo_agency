<?php
session_start();
include 'config.php';
if (isset($_POST['login'])) {
    if ($_POST['user'] == 'admin' && $_POST['pass'] == 'admin123') {
        $_SESSION['admin'] = true;
        header('Location: admin.php');
    } else {
        $error = "Invalid Credentials";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Admin Login</title>
</head>

<body class="bg-[#050810] flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md bg-white/5 border border-white/10 p-10 rounded-[2rem] backdrop-blur-xl">
        <h2 class="text-white text-3xl font-serif italic mb-8 text-center">Designo Admin</h2>
        <?php if (isset($error)) echo "<p class='text-red-400 text-sm mb-4'>$error</p>"; ?>
        <form method="POST" class="space-y-4">
            <input type="text" name="user" placeholder="Username" class="w-full bg-white/5 border border-white/10 p-4 rounded-xl text-white outline-none focus:border-blue-500 transition" required>
            <input type="password" name="pass" placeholder="Password" class="w-full bg-white/5 border border-white/10 p-4 rounded-xl text-white outline-none focus:border-blue-500 transition" required>
            <button name="login" class="w-full bg-white text-black font-bold py-4 rounded-xl hover:bg-blue-500 hover:text-white transition">Enter Dashboard</button>
        </form>
    </div>
</body>

</html>
<?php
include 'config.php';
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// --- DELETE PROJECT ---
if (isset($_GET['delete_project'])) {
    $id = (int)$_GET['delete_project'];
    $conn->query("DELETE FROM portfolio WHERE id = $id");
    header("Location: admin.php?tab=portfolio");
}

// --- UPLOAD LOGIC (With Video Detection) ---
if (isset($_POST['upload'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $file_name = $_FILES['image']['name'];
    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $target = "assets/uploads/" . basename($file_name);

    // Detect file type
    $video_exts = ['mp4', 'webm', 'mov'];
    $file_type = in_array($ext, $video_exts) ? 'video' : 'image';

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $sql = "INSERT INTO portfolio (title, service_type, image_path, file_type) VALUES ('$title', '$type', '$file_name', '$file_type')";
        $conn->query($sql);
        header("Location: admin.php?tab=portfolio&success=1");
    }
}

// --- DELETE ENQUIRY ---
if (isset($_GET['delete_enquiry'])) {
    $id = (int)$_GET['delete_enquiry'];
    $conn->query("DELETE FROM enquiries WHERE id = $id");
    header("Location: admin.php?tab=inbox");
}

$portfolio = $conn->query("SELECT * FROM portfolio ORDER BY id DESC");
$enquiries = $conn->query("SELECT * FROM enquiries ORDER BY created_at DESC");
$active_tab = $_GET['tab'] ?? 'portfolio';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Designo Admin | Studio Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: rgb(15, 23, 42);
            color: #f1f5f9;
            font-family: sans-serif;
        }

        .glass-card {
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
        }

        input,
        select,
        textarea {
            background: rgb(30, 41, 59) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: white !important;
        }

        .tab-active {
            color: #38bdf8;
            border-bottom: 2px solid #38bdf8;
        }
    </style>
</head>

<body class="min-h-screen">
    <nav class="sticky top-0 z-50 bg-slate-900/80 backdrop-blur-md border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-10">
                <span class="font-bold tracking-tighter text-xl text-white">DESIGNO<span class="text-sky-400">.</span></span>
                <div class="flex gap-8 text-[11px] uppercase tracking-[0.2em] font-bold">
                    <a href="?tab=portfolio" class="py-2 <?php echo $active_tab == 'portfolio' ? 'tab-active' : 'opacity-40'; ?>">Portfolio</a>
                    <a href="?tab=inbox" class="py-2 <?php echo $active_tab == 'inbox' ? 'tab-active' : 'opacity-40'; ?>">Inbox</a>
                </div>
            </div>
            <a href="logout.php" class="bg-red-500/20 text-red-400 text-[10px] uppercase px-5 py-2.5 rounded-xl font-bold hover:bg-red-500 hover:text-white transition-all">Logout</a>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-6 md:p-10">
        <?php if ($active_tab == 'portfolio'): ?>
            <div class="grid lg:grid-cols-12 gap-10">
                <div class="lg:col-span-4">
                    <div class="glass-card p-8 rounded-[2.5rem]">
                        <h2 class="text-xl font-bold mb-6 text-sky-400">Add Content</h2>
                        <form action="" method="POST" enctype="multipart/form-data" class="space-y-5">
                            <input type="text" name="title" required class="w-full p-4 rounded-2xl text-sm" placeholder="Project Name">
                            <select name="type" class="w-full p-4 rounded-2xl text-sm">
                                <option>Digital Marketing</option>
                                <option>Event Coverage</option>
                                <option>E-Commerce Shoot</option>
                                <option>Graphic Design</option>
                                <option>Post Process</option>
                            </select>
                            <label class="block text-[10px] text-slate-500 ml-2 mb-1 uppercase font-bold">Upload MP4 or Image</label>
                            <input type="file" name="image" required class="w-full p-4 rounded-2xl text-xs">
                            <button name="upload" class="w-full bg-sky-500 text-slate-900 py-4 rounded-2xl font-bold uppercase tracking-widest text-xs">Publish Content</button>
                        </form>
                    </div>
                </div>
                <div class="lg:col-span-8">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-6">
                        <?php while ($row = $portfolio->fetch_assoc()): ?>
                            <div class="glass-card rounded-[2rem] overflow-hidden group relative">
                                <?php if ($row['file_type'] == 'video'): ?>
                                    <video src="assets/uploads/<?php echo $row['image_path']; ?>" muted class="w-full h-44 object-cover"></video>
                                    <div class="absolute top-2 left-2 bg-sky-500 text-[8px] px-2 py-1 rounded-full font-bold">VIDEO</div>
                                <?php else: ?>
                                    <img src="assets/uploads/<?php echo $row['image_path']; ?>" class="w-full h-44 object-cover">
                                <?php endif; ?>
                                <div class="p-5">
                                    <p class="text-[9px] uppercase text-sky-400 font-bold"><?php echo $row['service_type']; ?></p>
                                    <h3 class="text-sm font-semibold truncate"><?php echo $row['title']; ?></h3>
                                    <a href="?delete_project=<?php echo $row['id']; ?>" class="text-red-500 text-[10px] font-bold uppercase mt-3 inline-block">Delete</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="max-w-4xl mx-auto space-y-5">
                <?php while ($row = $enquiries->fetch_assoc()): ?>
                    <div class="glass-card p-8 rounded-[2.5rem] border-l-4 border-sky-500">
                        <span class="text-[10px] bg-sky-500/10 text-sky-400 px-4 py-1 rounded-full font-bold"><?php echo $row['service']; ?></span>
                        <h3 class="text-2xl font-bold mt-4"><?php echo $row['name']; ?></h3>
                        <p class="text-slate-400 mb-6 italic">"<?php echo $row['message']; ?>"</p>
                        <div class="flex gap-4">
                            <a href="mailto:<?php echo $row['email']; ?>" class="text-xs font-bold text-sky-400">REPLY</a>
                            <a href="?delete_enquiry=<?php echo $row['id']; ?>" class="text-xs font-bold text-red-400">DELETE</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>

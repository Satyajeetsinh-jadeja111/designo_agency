<?php
include 'config.php';
session_start();

// Security Check
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// --- LOGIC: DELETE PORTFOLIO ITEM ---
if (isset($_GET['delete_project'])) {
    $id = (int)$_GET['delete_project'];
    $conn->query("DELETE FROM portfolio WHERE id = $id");
    header("Location: admin.php?tab=portfolio");
}

// --- LOGIC: HANDLE PORTFOLIO UPLOAD ---
if (isset($_POST['upload'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $image = $_FILES['image']['name'];
    $target = "assets/uploads/" . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $sql = "INSERT INTO portfolio (title, service_type, image_path) VALUES ('$title', '$type', '$image')";
        $conn->query($sql);
        header("Location: admin.php?tab=portfolio&success=1");
    }
}

// --- LOGIC: HANDLE ENQUIRY DELETION ---
if (isset($_GET['delete_enquiry'])) {
    $id = (int)$_GET['delete_enquiry'];
    $conn->query("DELETE FROM enquiries WHERE id = $id");
    header("Location: admin.php?tab=inbox");
}

// Fetch Data
$portfolio = $conn->query("SELECT * FROM portfolio ORDER BY id DESC");
$enquiries = $conn->query("SELECT * FROM enquiries ORDER BY created_at DESC");
$active_tab = $_GET['tab'] ?? 'portfolio';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Designo Admin | Slate Edition</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: rgb(15, 23, 42);
            /* Your requested theme */
            color: #f1f5f9;
        }

        .glass-card {
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
        }

        .nav-link {
            transition: all 0.3s ease;
        }

        .tab-active {
            color: #38bdf8;
            border-bottom: 2px solid #38bdf8;
        }

        /* Modern Inputs */
        input,
        select,
        textarea {
            background: rgb(30, 41, 59) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: white !important;
        }

        input:focus {
            border-color: #38bdf8 !important;
            outline: none;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
    </style>
</head>

<body class="min-h-screen custom-scrollbar">

    <nav class="sticky top-0 z-50 bg-slate-900/80 backdrop-blur-md border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-10">
                <span class="font-bold tracking-tighter text-xl text-white">DESIGNO<span class="text-sky-400">.</span></span>
                <div class="flex gap-8 text-[11px] uppercase tracking-[0.2em] font-bold">
                    <a href="?tab=portfolio" class="nav-link py-2 <?php echo $active_tab == 'portfolio' ? 'tab-active' : 'opacity-40 hover:opacity-100'; ?>">Portfolio</a>
                    <a href="?tab=inbox" class="nav-link py-2 <?php echo $active_tab == 'inbox' ? 'tab-active' : 'opacity-40 hover:opacity-100'; ?>">
                        Inbox
                        <?php
                        $count = $conn->query("SELECT id FROM enquiries")->num_rows;
                        if ($count > 0) echo "<span class='ml-2 bg-sky-500 text-slate-900 px-2 py-0.5 rounded-full text-[9px]'>$count</span>";
                        ?>
                    </a>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <a href="index.php" target="_blank" class="text-[10px] uppercase font-bold text-slate-400 hover:text-white transition-colors">View Site</a>
                <a href="logout.php" class="bg-red-500/20 text-red-400 text-[10px] uppercase px-5 py-2.5 rounded-xl font-bold hover:bg-red-500 hover:text-white transition-all">Logout</a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-6 md:p-10">

        <?php if ($active_tab == 'portfolio'): ?>
            <div class="grid lg:grid-cols-12 gap-10">

                <div class="lg:col-span-4">
                    <div class="glass-card p-8 rounded-[2.5rem]">
                        <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Work
                        </h2>
                        <form action="" method="POST" enctype="multipart/form-data" class="space-y-5">
                            <div>
                                <label class="text-[10px] uppercase font-bold text-slate-400 mb-2 block">Project Title</label>
                                <input type="text" name="title" required class="w-full p-4 rounded-2xl text-sm" placeholder="e.g. Summer Campaign 2026">
                            </div>
                            <div>
                                <label class="text-[10px] uppercase font-bold text-slate-400 mb-2 block">Category</label>
                                <select name="type" class="w-full p-4 rounded-2xl text-sm outline-none">
                                    <option>Digital Marketing</option>
                                    <option>Event Coverage</option>
                                    <option>E-Commerce Shoot</option>
                                    <option>Graphic Design</option>
                                    <option>Post Process</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] uppercase font-bold text-slate-400 mb-2 block">Upload Image</label>
                                <input type="file" name="image" required class="w-full p-4 rounded-2xl text-xs file:hidden">
                            </div>
                            <button name="upload" class="w-full bg-sky-500 text-slate-900 py-4 rounded-2xl font-bold hover:bg-sky-400 transition-all shadow-lg shadow-sky-500/10">Publish Project</button>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-8">
                    <h2 class="text-xl font-bold mb-6">Gallery Management</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-6">
                        <?php while ($row = $portfolio->fetch_assoc()): ?>
                            <div class="glass-card rounded-[2rem] overflow-hidden group">
                                <div class="relative h-44">
                                    <img src="assets/uploads/<?php echo $row['image_path']; ?>" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                                        <a href="?delete_project=<?php echo $row['id']; ?>" onclick="return confirm('Delete this work?')" class="bg-red-500 p-3 rounded-full hover:scale-110 transition-transform">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v2m3 3h.01" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                <div class="p-5">
                                    <p class="text-[9px] uppercase text-sky-400 font-bold mb-1"><?php echo $row['service_type']; ?></p>
                                    <h3 class="text-sm font-semibold truncate"><?php echo $row['title']; ?></h3>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <div class="max-w-4xl mx-auto">
                <div class="flex justify-between items-center mb-10">
                    <h2 class="text-3xl font-bold tracking-tight">Enquiries Inbox</h2>
                    <p class="text-xs text-slate-500"><?php echo $enquiries->num_rows; ?> total messages</p>
                </div>

                <div class="space-y-5">
                    <?php if ($enquiries->num_rows > 0): ?>
                        <?php while ($row = $enquiries->fetch_assoc()): ?>
                            <div class="glass-card p-8 rounded-[2.5rem] border-l-4 border-l-sky-500">
                                <div class="flex flex-col md:flex-row justify-between items-start gap-6">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-4 mb-4">
                                            <span class="text-[10px] bg-sky-500/10 text-sky-400 px-4 py-1.5 rounded-full uppercase font-bold tracking-widest"><?php echo $row['service']; ?></span>
                                            <span class="text-[10px] text-slate-500"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></span>
                                        </div>
                                        <h3 class="text-2xl font-bold mb-1"><?php echo $row['name']; ?></h3>
                                        <p class="text-sky-400 text-sm mb-6"><?php echo $row['email']; ?></p>
                                        <div class="bg-slate-800/50 p-6 rounded-2xl border border-white/5 italic text-slate-300 text-sm leading-relaxed">
                                            "<?php echo nl2br($row['message']); ?>"
                                        </div>
                                    </div>
                                    <div class="flex md:flex-col gap-3 w-full md:w-32">
                                        <a href="mailto:<?php echo $row['email']; ?>" class="bg-white text-slate-900 text-center py-4 rounded-2xl text-[10px] font-bold uppercase tracking-widest hover:bg-sky-400 transition-colors">Reply</a>
                                        <a href="?delete_enquiry=<?php echo $row['id']; ?>" onclick="return confirm('Archive this message?')" class="text-red-400 text-center py-4 rounded-2xl text-[10px] font-bold uppercase tracking-widest border border-red-500/20 hover:bg-red-500/10 transition-all">Delete</a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="glass-card py-20 rounded-[3rem] text-center">
                            <p class="opacity-20 italic text-lg">No new messages in your inbox.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

    </main>
</body>

</html>
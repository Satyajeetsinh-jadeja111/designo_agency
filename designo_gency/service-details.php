<?php
include 'config.php';
$type = $_GET['type'] ?? 'marketing'; // Gets the 'type' from the URL

// Map the slugs to nice titles
$titles = [
    "marketing" => "Digital Marketing",
    "event" => "Event Coverage",
    "shoot" => "E-Commerce Shoot",
    "graphic" => "Graphic Design",
    "post" => "Post Process"
];
$display_title = $titles[$type] ?? "Creative Work";

// Fetch all projects for THIS specific service
$stmt = $conn->prepare("SELECT * FROM portfolio WHERE service_type = ? ORDER BY id DESC");
$stmt->bind_param("s", $display_title);
$stmt->execute();
$res = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo $display_title; ?> | Designo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital@1&family=Plus+Jakarta+Sans:wght@300;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: #0A0A0C;
            color: white;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .font-serif {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>

<body class="p-6 md:p-12">
    <a href="index.php" class="text-[10px] uppercase tracking-widest opacity-50 hover:opacity-100">← Back to Studio</a>

    <header class="mt-12 mb-20">
        <h1 class="text-5xl md:text-8xl font-serif italic"><?php echo $display_title; ?>.</h1>
        <div class="w-20 h-1 bg-blue-500 mt-6"></div>
    </header>

    <div class="columns-1 md:columns-2 lg:columns-3 gap-8 space-y-8">
        <?php while ($row = $res->fetch_assoc()): ?>
            <div class="break-inside-avoid rounded-3xl overflow-hidden border border-white/5">
                <img src="assets/uploads/<?php echo $row['image_path']; ?>" class="w-full">
                <div class="p-6 bg-white/5">
                    <h3 class="text-xl font-bold"><?php echo $row['title']; ?></h3>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>

</html>
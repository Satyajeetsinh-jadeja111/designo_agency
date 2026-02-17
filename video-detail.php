<?php
include 'config.php';
$id = (int)$_GET['id'];
$res = $conn->query("SELECT * FROM portfolio WHERE id = $id");
$video = $res->fetch_assoc();

if (!$video) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $video['title']; ?> | Designo Cinema</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: #000;
            color: white;
            font-family: sans-serif;
            overflow: hidden;
        }

        .theater-container {
            height: 100vh;
            width: 100vw;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        video {
            max-height: 85vh;
            width: auto;
            max-width: 95%;
            box-shadow: 0 0 100px rgba(56, 189, 248, 0.2);
            border-radius: 1rem;
        }

        .ui-overlay {
            position: absolute;
            top: 40px;
            left: 40px;
            right: 40px;
            display: flex;
            justify-content: space-between;
            z-index: 10;
        }
    </style>
</head>

<body>

    <div class="theater-container">
        <div class="ui-overlay">
            <div>
                <p class="text-[10px] uppercase tracking-[0.4em] text-sky-400 font-bold mb-1">Now Playing</p>
                <h1 class="text-2xl md:text-4xl font-light tracking-tighter uppercase"><?php echo $video['title']; ?></h1>
            </div>
            <a href="javascript:history.back()" class="w-12 h-12 rounded-full border border-white/20 flex items-center justify-center hover:bg-white hover:text-black transition-all">✕</a>
        </div>

        <video controls autoplay playsinline class="bg-black shadow-2xl">
            <source src="assets/uploads/<?php echo $video['image_path']; ?>" type="video/mp4">
            Your browser does not support the video tag.
        </video>

        <div class="absolute bottom-10 text-center opacity-30 text-[9px] uppercase tracking-[1em]">
            Designo Agency Cinematic Experience
        </div>
    </div>

</body>

</html>
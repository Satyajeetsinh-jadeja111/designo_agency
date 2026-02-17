<?php
include 'config.php';
$type = $_GET['type'] ?? 'marketing';
$titles = ["marketing" => "Digital Marketing", "event" => "Event Coverage", "shoot" => "E-Commerce Shoot", "graphic" => "Graphic Design", "post" => "Post Process"];
$display_name = $titles[$type] ?? "Work";

// Separate Queries - We use $res_vids and $res_imgs to avoid confusion
$res_vids = $conn->query("SELECT * FROM portfolio WHERE service_type = '$display_name' AND file_type = 'video' ORDER BY id DESC");
$res_imgs = $conn->query("SELECT * FROM portfolio WHERE service_type = '$display_name' AND file_type = 'image' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $display_name; ?> | Designo Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital@1&display=swap" rel="stylesheet">
    <style>
        body {
            background: rgb(15, 23, 42);
            color: white;
            font-family: sans-serif;
        }

        .font-serif {
            font-family: 'Playfair Display', serif;
        }

        /* Tab Transitions */
        .tab-btn {
            transition: all 0.3s ease;
            opacity: 0.4;
            border-bottom: 2px solid transparent;
            cursor: pointer;
        }

        .tab-btn.active {
            opacity: 1;
            color: #38bdf8;
            border-bottom: 2px solid #38bdf8;
        }

        .section-content {
            display: none;
        }

        .section-content.active {
            display: block;
            animation: fadeIn 0.4s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="p-6 md:p-20">

    <a href="index.php" class="text-[10px] uppercase tracking-[0.3em] opacity-50 hover:text-sky-400 transition-all font-bold">
        ← Back to Studio
    </a>

    <header class="my-10">
        <h1 class="text-6xl md:text-9xl font-serif italic"><?php echo $display_name; ?>.</h1>
    </header>

    <div class="flex gap-10 border-b border-white/10 mb-12">
        <button onclick="switchTab('imgs')" id="btn-imgs" class="tab-btn active pb-4 text-[10px] uppercase tracking-widest font-bold">
            Photography (<?php echo $res_imgs->num_rows; ?>)
        </button>
        <button onclick="switchTab('vids')" id="btn-vids" class="tab-btn pb-4 text-[10px] uppercase tracking-widest font-bold">
            Cinematic Videos (<?php echo $res_vids->num_rows; ?>)
        </button>
    </div>

    <section id="section-imgs" class="section-content active">
        <?php if ($res_imgs->num_rows > 0): ?>
            <div class="columns-1 md:columns-3 gap-8 space-y-8">
                <?php while ($img = $res_imgs->fetch_assoc()): ?>
                    <div class="break-inside-avoid rounded-3xl overflow-hidden border border-white/10 bg-slate-900 group">
                        <img src="assets/uploads/<?php echo $img['image_path']; ?>"
                            class="w-full grayscale-[20%] group-hover:grayscale-0 transition-all duration-700"
                            alt="Designo Showcase">
                        <div class="p-4 bg-slate-800/50">
                            <p class="text-[10px] uppercase tracking-widest opacity-60 font-bold"><?php echo $img['title']; ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="py-20 text-center opacity-30 italic">No photography projects found.</div>
        <?php endif; ?>
    </section>

    <section id="section-vids" class="section-content">
        <?php if ($res_vids->num_rows > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <?php while ($vid = $res_vids->fetch_assoc()): ?>
                    <a href="video-detail.php?id=<?php echo $vid['id']; ?>" class="group block rounded-[2.5rem] overflow-hidden bg-black border border-white/5 relative aspect-video">
                        <video src="assets/uploads/<?php echo $vid['image_path']; ?>#t=0.5" class="w-full h-full object-cover opacity-60 group-hover:opacity-100 transition-all"></video>

                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-16 h-16 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center group-hover:bg-sky-500 group-hover:scale-110 transition-all">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="white">
                                    <path d="M8 5v14l11-7z" />
                                </svg>
                            </div>
                        </div>

                        <div class="absolute bottom-8 left-8">
                            <h3 class="font-bold text-xl text-white"><?php echo $vid['title']; ?></h3>
                            <p class="text-[9px] uppercase tracking-widest text-sky-400 font-bold mt-1">Watch Experience →</p>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="py-20 text-center opacity-30 italic">No cinematic motion found.</div>
        <?php endif; ?>
    </section>

    <script>
        function switchTab(type) {
            // 1. Hide both sections
            document.getElementById('section-imgs').classList.remove('active');
            document.getElementById('section-vids').classList.remove('active');

            // 2. Reset both buttons
            document.getElementById('btn-imgs').classList.remove('active');
            document.getElementById('btn-vids').classList.remove('active');

            // 3. Activate the chosen one
            if (type === 'imgs') {
                document.getElementById('section-imgs').classList.add('active');
                document.getElementById('btn-imgs').classList.add('active');
            } else {
                document.getElementById('section-vids').classList.add('active');
                document.getElementById('btn-vids').classList.add('active');
            }
        }
    </script>
</body>

</html>
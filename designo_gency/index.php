<?php
include 'config.php';

/** * SMART GALLERY LOGIC: 
 * Selects only the LATEST entry for each unique service_type.
 */
$gallery_query = "SELECT p1.* FROM portfolio p1 
                  INNER JOIN (SELECT service_type, MAX(id) as max_id FROM portfolio GROUP BY service_type) p2 
                  ON p1.id = p2.max_id 
                  ORDER BY p1.id DESC";
$gallery_res = $conn->query($gallery_query);
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Designo | Creative Production Studio</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@200;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --ink: rgb(15, 23, 42);
            --accent: #3B82F6;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--ink);
            color: #F8FAFC;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        body.loading {
            overflow: hidden;
            height: 100vh;
        }

        .font-serif {
            font-family: 'Playfair Display', serif;
        }

        /* Navigation glass effect */
        .glass-nav {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Hero Title Fluid */
        .hero-title {
            font-size: clamp(3rem, 12vw, 10rem);
            line-height: 0.9;
        }

        /* Floating WhatsApp Pulse */
        .wa-float {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 55px;
            height: 55px;
            background-color: #25d366;
            color: #FFF;
            border-radius: 50px;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(37, 211, 102, 0.3);
            animation: wa-pulse 2s infinite;
        }

        @keyframes wa-pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
            }

            70% {
                box-shadow: 0 0 0 15px rgba(37, 211, 102, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
            }
        }

        /* Elegant Service Cards */
        .glass {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
        }

        @media (min-width: 1024px) {
            .glass:hover {
                background: #fff;
                color: #000;
                transform: translateY(-12px);
            }
        }

        /* Form Styling */
        input,
        textarea,
        select {
            background: #969696 !important;
            /*rgba(255, 255, 255, 0.03)*/
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: white !important;
        }

        input:focus {
            border-color: var(--accent) !important;
            outline: none;
        }
    </style>
</head>

<body class="loading">

    <div id="loader" class="fixed inset-0 bg-[#0A0A0C] z-[9999] flex flex-col items-center justify-center">
        <h1 class="text-4xl md:text-5xl font-serif italic tracking-tighter loader-text text-white">Designo.</h1>
        <div class="w-48 md:w-64 h-[1px] bg-white/10 mt-8 relative overflow-hidden">
            <div id="loadbar" class="absolute inset-0 bg-blue-500 w-0"></div>
        </div>
        <div id="counter" class="mt-4 text-[9px] tracking-[0.4em] opacity-30 uppercase text-white">0% Initialized</div>
    </div>

    <a href="https://wa.me/917228024562" class="wa-float md:bottom-10 md:right-10" target="_blank">
        <svg fill="#fff" width="28px" height="28px" viewBox="0 0 24 24">
            <path d="M12.031 6.172c-2.203 0-4.391.583-6.328 1.691L2.109 6.844l1.011 3.511c-1.181 1.901-1.801 4.102-1.801 6.354 0 6.63 5.39 12.02 12.022 12.02 3.212 0 6.231-1.251 8.502-3.522a11.917 11.917 0 0 0 3.522-8.498c0-6.63-5.39-12.02-12.022-12.02z" />
        </svg>
    </a>

    <nav class="fixed w-full z-50 px-6 md:px-12 py-5 flex justify-between items-center glass-nav">
        <h1 class="text-lg md:text-xl font-serif italic tracking-tighter">Designo Agency.</h1>
        <div class="hidden md:flex gap-8 text-[10px] uppercase tracking-widest font-bold">
            <a href="#about" class="hover:text-blue-500">About</a>
            <a href="#services" class="hover:text-blue-500">Services</a>
            <a href="#work" class="hover:text-blue-500">Gallery</a>
            <a href="#contact" class="hover:text-blue-500">Contact</a>
        </div>
        <a href="login.php" class="text-[9px] md:text-[10px] uppercase tracking-widest bg-white text-black px-5 md:px-7 py-2.5 rounded-full font-bold">Studio Login</a>
    </nav>

    <section class="min-h-screen flex items-center justify-center relative px-6">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-[800px] h-[400px] bg-blue-600/10 blur-[120px] rounded-full pointer-events-none"></div>
        <div class="text-center z-10 w-full">
            <h1 id="heroTitle" class="hero-title font-serif italic opacity-0 translate-y-10">
                Design <br class="md:hidden"> & <span class="text-blue-500">Motion.</span>
            </h1>
            <p class="mt-8 md:mt-12 uppercase tracking-[0.4em] md:tracking-[1.2em] text-[8px] md:text-xs opacity-0 heroSub">
                Cinematic Content • Marketing • Brand Identity
            </p>
        </div>
    </section>

    <section id="about" class="py-24 md:py-40 px-6 max-w-5xl mx-auto text-center border-t border-white/5">
        <h2 class="text-[10px] uppercase tracking-[0.5em] text-blue-500 mb-10">The Agency</h2>
        <p class="text-2xl md:text-5xl font-serif leading-tight">
            Designo is a high-fidelity creative house. We combine <span class="italic text-gray-400">visual storytelling</span> with data-driven growth to build modern brands.
        </p>
    </section>

    <section id="services" class="py-24 md:py-40 px-6 max-w-7xl mx-auto">
        <div class="mb-16 md:mb-32">
            <h2 class="text-4xl md:text-8xl font-serif italic tracking-tighter">Expertise.</h2>
            <div class="w-16 h-[1px] bg-blue-500 mt-4"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-10">
            <?php
            $my_services = [
                "marketing" => ["Digital Marketing", "Growth & High-End Strategy"],
                "event" => ["Event Coverage", "Cinematic Memories Captured"],
                "shoot" => ["E-Commerce Shoot", "Product Photography Specialists"],
                "graphic" => ["Graphic Design", "Premium Brand Identity"],
                "post" => ["Post Process", "Expert Video & Retouching"]
            ];
            foreach ($my_services as $slug => $s): ?>
                <a href="service-details.php?type=<?php echo $slug; ?>" class="service-card p-10 glass group rounded-[2rem] block">
                    <h3 class="text-2xl font-bold uppercase tracking-tighter mb-2"><?php echo $s[0]; ?></h3>
                    <p class="text-xs opacity-50"><?php echo $s[1]; ?></p>
                    <div class="mt-6 w-10 h-1 bg-blue-500 group-hover:w-full transition-all duration-500"></div>
                    <span class="text-[10px] uppercase mt-6 block opacity-0 group-hover:opacity-100 transition-all text-blue-400 font-bold">Explore Category →</span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="work" class="py-24 md:py-40 bg-slate-900/50 text-white rounded-t-[3rem] md:rounded-t-[8rem] relative border-t border-white/5">
        <section id="work" class="py-24 md:py-40 bg-slate-900/50 text-white rounded-t-[3rem] md:rounded-t-[8rem] relative border-t border-white/5">
            <div class="max-w-7xl mx-auto px-6">
                <h2 class="text-5xl md:text-9xl font-serif italic mb-20 tracking-tighter leading-none">Latest Highlights.</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12">
                    <?php while ($row = $gallery_res->fetch_assoc()):
                        // 1. Get the exact name from your database
                        $db_category = trim($row['service_type']);

                        /**
                         * 2. STRICTOR MAPPING LOGIC
                         * This ensures "E-Commerce Shoot" goes to "shoot" 
                         * and "Digital Marketing" goes to "marketing"
                         */
                        $slug = "marketing"; // Default fallback

                        if (stripos($db_category, 'Marketing') !== false) {
                            $slug = "marketing";
                        } elseif (stripos($db_category, 'Event') !== false) {
                            $slug = "event";
                        } elseif (stripos($db_category, 'Commerce') !== false || stripos($db_category, 'Shoot') !== false) {
                            $slug = "shoot";
                        } elseif (stripos($db_category, 'Graphic') !== false) {
                            $slug = "graphic";
                        } elseif (stripos($db_category, 'Post') !== false || stripos($db_category, 'Process') !== false) {
                            $slug = "post";
                        }
                    ?>
                        <a href="service-details.php?type=<?php echo $slug; ?>" class="group block relative overflow-hidden rounded-[2.5rem] bg-black/40 aspect-[4/5] border border-white/5">
                            <img src="assets/uploads/<?php echo $row['image_path']; ?>"
                                class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 opacity-70 group-hover:opacity-100">

                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent opacity-90"></div>

                            <div class="absolute inset-0 p-10 flex flex-col justify-end translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                                <p class="text-[9px] uppercase tracking-widest text-blue-400 font-bold mb-2">
                                    <?php echo $db_category; ?>
                                </p>
                                <h4 class="text-3xl font-serif italic mb-6"><?php echo $row['title']; ?></h4>

                                <div class="flex items-center gap-3 opacity-0 group-hover:opacity-100 transition-all">
                                    <span class="text-[10px] uppercase font-bold tracking-widest">View <?php echo $slug; ?> Projects</span>
                                    <div class="w-8 h-8 rounded-full border border-white/20 flex items-center justify-center group-hover:bg-blue-600 transition-all">
                                        <svg width="10" height="10" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 11L11 1M11 1H1M11 1V11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
    </section>

    <section id="contact" class="py-24 md:py-40 px-6 max-w-7xl mx-auto grid md:grid-cols-2 gap-20">
        <div>
            <h2 class="text-5xl md:text-8xl font-serif italic mb-8 tracking-tighter leading-none">Let's <br>Talk.</h2>
            <p class="opacity-40 mb-12 max-w-sm">Ready to elevate your brand? Send us an enquiry or contact us directly.</p>
            <div class="glass p-8 rounded-[2rem] inline-block">
                <p class="text-[10px] uppercase tracking-widest text-blue-500 mb-4 font-bold">Direct Line</p>
                <p class="text-2xl font-serif italic">+91 72280 24562</p>
            </div>
        </div>

        <div class="relative group">
            <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-sky-400 rounded-[3.5rem] blur opacity-20 group-hover:opacity-40 transition duration-1000"></div>

            <div class="relative">
                <div class="absolute -inset-2 bg-blue-600/20 rounded-[3.5rem] blur-2xl opacity-0 group-hover:opacity-100 transition duration-700"></div>

                <div class="relative glass p-8 md:p-12 rounded-[3rem] border border-white/10 bg-slate-900 shadow-2xl">
                    <form action="send_enquiry.php" method="POST" class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div class="space-y-2">
                                <label class="text-[10px] uppercase tracking-[0.2em] font-bold text-sky-400 ml-1">Full Name</label>
                                <input type="text" name="name" required placeholder="John Doe"
                                    class="w-full bg-slate-950 border border-white/20 p-4 rounded-2xl text-sm text-white focus:border-sky-500 focus:bg-black transition-all placeholder:text-slate-600 outline-none hover:border-white/40">
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] uppercase tracking-[0.2em] font-bold text-sky-400 ml-1">Email Address</label>
                                <input type="email" name="email" required placeholder="john@example.com"
                                    class="w-full bg-slate-950 border border-white/20 p-4 rounded-2xl text-sm text-white focus:border-sky-500 focus:bg-black transition-all placeholder:text-slate-600 outline-none hover:border-white/40">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] uppercase tracking-[0.2em] font-bold text-sky-400 ml-1">Select Service</label>
                            <div class="relative">
                                <select name="service" class="w-full bg-slate-950 border border-white/20 p-4 rounded-2xl text-sm text-white appearance-none focus:border-sky-500 focus:bg-black transition-all outline-none cursor-pointer hover:border-white/40">
                                    <option value="marketing" class="bg-slate-900">Digital Marketing</option>
                                    <option value="event" class="bg-slate-900">Event Coverage</option>
                                    <option value="shoot" class="bg-slate-900">E-Commerce Shoot</option>
                                    <option value="graphic" class="bg-slate-900">Graphic Design</option>
                                    <option value="post" class="bg-slate-900">Post Process</option>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none opacity-40">
                                    <svg width="12" height="8" viewBox="0 0 12 8" fill="none">
                                        <path d="M1 1L6 6L11 1" stroke="white" stroke-width="2" stroke-linecap="round" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] uppercase tracking-[0.2em] font-bold text-sky-400 ml-1">Project Brief</label>
                            <textarea name="message" rows="4" placeholder="Tell us about your vision..."
                                class="w-full bg-slate-950 border border-white/20 p-4 rounded-2xl text-sm text-white focus:border-sky-500 focus:bg-black transition-all placeholder:text-slate-600 outline-none resize-none hover:border-white/40"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-sky-500 text-slate-950 py-5 rounded-2xl font-bold uppercase tracking-widest text-xs transition-all hover:bg-white hover:shadow-[0_0_30px_rgba(56,189,248,0.4)] active:scale-[0.98]">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-16 text-center border-t border-white/5 opacity-30 text-[9px] uppercase tracking-[1em]">
        Designo Agency • 2026 • Ronak & Darshit
    </footer>

    <script>
        gsap.registerPlugin(ScrollTrigger);

        // Preloader Logic
        const loaderTl = gsap.timeline();
        let obj = {
            val: 0
        };
        loaderTl.to(obj, {
            val: 100,
            duration: 2.2,
            ease: "power2.inOut",
            onUpdate: () => document.getElementById("counter").innerHTML = Math.floor(obj.val) + "% Initialized"
        });
        loaderTl.to("#loadbar", {
            width: "100%",
            duration: 2.2,
            ease: "power2.inOut"
        }, 0);
        loaderTl.to("#loader", {
            yPercent: -100,
            duration: 1.2,
            ease: "expo.inOut"
        });
        loaderTl.call(() => document.body.classList.remove('loading'));

        // Entrance
        loaderTl.to("#heroTitle", {
            opacity: 1,
            y: 0,
            duration: 1.5,
            ease: "power4.out"
        }, "-=0.2");
        loaderTl.to(".heroSub", {
            opacity: 0.4,
            duration: 1
        }, "-=1");

        // Scroll Reveal
        gsap.utils.toArray(".service-card, .glass, #work a").forEach(el => {
            gsap.from(el, {
                scrollTrigger: {
                    trigger: el,
                    start: "top 92%"
                },
                opacity: 0,
                y: 50,
                duration: 1.2,
                ease: "power2.out"
            });
        });
    </script>
</body>

</html>
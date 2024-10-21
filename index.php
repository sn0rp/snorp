<?php
require_once 'vendor/autoload.php';

function getRecentPosts($limit = 4) {
    $posts = glob('posts/*.md');
    usort($posts, function($a, $b) {
        return filemtime($b) - filemtime($a);
    });
    $recentPosts = array_slice($posts, 0, $limit);
    return array_map(function($post) {
        return [
            'file' => $post,
            'title' => getPostTitle($post)
        ];
    }, $recentPosts);
}

function getPostTitle($filePath) {
    $content = file_get_contents($filePath);
    if (preg_match('/^#\s*(.+)$/m', $content, $matches)) {
        return trim($matches[1]);
    }
    return basename($filePath, '.md');
}

class ProjectCard {
    public $image;
    public $title;
    public $description;
    public $link;

    public function __construct($image, $title, $description, $link) {
        $this->image = $image;
        $this->title = $title;
        $this->description = $description;
        $this->link = $link;
    }

    public function render() {
        return "
            <div class=\"project-card\">
                <img src=\"{$this->image}\" alt=\"{$this->title}\" style=\"max-height:200px;\">
                <h3><a href=\"{$this->link}\" class=\"catscape-link\"><i class=\"fas fa-link\"></i></a> {$this->title}</h3>
                <p>{$this->description}</p>
            </div>
        ";
    }
}

$projects = [
    new ProjectCard("/images/projects/jobertdemo.png", "Jobert", "Web application for tracking job applications, providing insights through data visualization and automating job search tasks", "https://github.com/sn0rp/jobert"),
    new ProjectCard("/images/projects/quotes.png", "Inspirational Quotes", "Web app that creates humorous, AI-generated inspirational quotes on demand", "https://github.com/sn0rp/quotes"),
    //new ProjectCard("https://placewaifu.com/image/200/150", "Project 3", "Description of Project 3", "#"),
];

$recentPosts = getRecentPosts();

function incrementVisitCount() {
    $countFile = 'visit_count.txt';
    if (file_exists($countFile)) {
        $count = (int)file_get_contents($countFile);
        $count++;
    } else {
        $count = 1;
    }
    file_put_contents($countFile, $count);
    return $count;
}

$visitCount = incrementVisitCount();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>&#9732;Snorp</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://unpkg.com/webamp@1.4.2/built/webamp.bundle.min.js"></script>
    <script src="js/catscape.js" defer></script>
    <link rel="stylesheet" href="css/prism-dracula.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-php.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <script src="js/mouse-follower.js" defer></script>
    <script src="js/dvd-screensaver.js" defer></script>
    <script src="js/badge-scroll.js" defer></script>
</head>
<body>
    <div class="page-wrapper">
        <div class="container">
            <header>
                <h1>Snorp</h1>
                <nav>
                    <a href="index.php" class="home-link"><i class="fas fa-globe"></i></a>
                    <a href="https://github.com/sn0rp" class="external-link"><i class="fab fa-github"></i></a>
                    <a href="https://x.com/_snorp" class="external-link"><i class="fab fa-x-twitter"></i></a>
                </nav>
                <img src="images/under-construction.gif" alt="under construction" style="width: 210px;"/>
                <div class="toggle-buttons">
                    <button id="toggle-dvd" class="toggle-button">Toggle DVD</button>
                    <button id="toggle-follower" class="toggle-button">Toggle Follower</button>
                </div>
            </header>
            
            <main>
                <section id="about">
                    <h2>About Me</h2>
                    <p>I'm a software engineer specializing in cloud computing and automation. In my free time, I'm diving into machine learning and embedded systems. When I'm not coding, you might find me optimizing my health, exploring nature, or playing piano. I'm driven by curiosity and a determination to turn "impossible" into "done." Always up for a challenge.</p>
                </section>
                
                <section id="recent-posts">
                    <h2>Recent Posts</h2>
                    <ul>
                        <?php foreach ($recentPosts as $post): ?>
                            <li><a href="blog.php?post=<?= basename($post['file'], '.md') ?>" class="catscape-link"><?= $post['title'] ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php if (count(glob('posts/*.md')) > 10): ?>
                        <a href="blog.php" class="catscape-link">More posts...</a>
                    <?php endif; ?>
                </section>
                
                <section id="projects">
                    <h2>Creations</h2>
                    <div class="project-grid">
                        <?php foreach ($projects as $project): ?>
                            <?= $project->render() ?>
                        <?php endforeach; ?>
                    </div>
                </section>
                
                <section id="badges">
                    <br>
                    <div class="badge-container">
                        <a href="https://snorp.dev"><img src="/images/badges/snorpBadge.png" alt="Badge"></a>
                    </div>
                    <div class="badge-scroll-container">
                        <div class="badge-scroll">
                            <img src="images/badges/badgemath.png" alt="Badge">
                            <img src="images/badges/badgephysics.png" alt="Badge">
                            <img src="images/badges/Archlinux_80x15.png" alt="Badge">
                            <img src="images/badges/antwerp.png" alt="Badge">
                            <img src="images/badges/boeken.gif" alt="Badge">
                            <img src="images/badges/f-22_6.gif" alt="Badge">
                            <img src="images/badges/fl_isr.gif" alt="Badge">
                            <img src="images/badges/loveanime.gif" alt="Badge">
                            <img src="images/badges/mensa1.gif" alt="Badge">
                            <img src="images/badges/nosmoking.gif" alt="Badge">
                            <img src="images/badges/nvidia.gif" alt="Badge">
                            <img src="images/badges/piano.gif" alt="Badge">
                            <img src="images/badges/texan.gif" alt="Badge">
                            <img src="images/badges/us_open.gif" alt="Badge">
                            <img src="images/badges/vim_the_editor.png" alt="Badge">
                            <img src="images/badges/xp.gif" alt="Badge">
                        </div>
                    </div>
                    <hr class="rainbow-hr">
                </section>
            </main>
            
            <footer>
                <div id="hit-counter">
                    <span>Visits</span>
                    <div class="counter">
                        <?php 
                            $paddedCount = str_pad($visitCount, 8, '0', STR_PAD_LEFT);
                            for ($i = 0; $i < strlen($paddedCount); $i++) {
                                echo '<span class="digit">' . $paddedCount[$i] . '</span>';
                            }
                        ?>
                    </div>
                </div>
                <p>&copy; <?php echo date("Y"); ?> Snorp. All rights reserved.</p>
                <img src="/images/datboi.gif" alt="datboi" id="datboi">
            </footer>
        </div>
        
        <div id="catscape-container" class="hidden">
            <div id="catscape-titlebar">
                <span>Snorpscape Sailor 1.0</span>
                <button id="catscape-open-main">Open in Main Window</button>
                <button id="catscape-close">&times;</button>
            </div>
            <div id="catscape-addressbar">
                <input type="text" id="catscape-url" readonly>
            </div>
            <iframe id="catscape-frame"></iframe>
            <div id="catscape-statusbar">
                <span id="catscape-status"></span>
                <span id="catscape-meta-title"></span>
            </div>
        </div>
        
        <div id="webamp-container"></div>
        
        <script>
            const Webamp = window.Webamp;
            if (window.innerWidth > 1024 && window.self === window.top) {
                new Webamp({
                    initialTracks: [
                        {
                            metaData: {
                                artist: "DJ Mike Llama",
                                title: "Llama Whippin' Intro",
                            },
                            url: "https://cdn.jsdelivr.net/gh/captbaritone/webamp@43434d82/mp3/llama-2.91.mp3",
                        },
                        {
                            metaData: {
                                artist: "Streambeats",
                                title: "Rooftops",
                            },
                            url: "/music/rooftops.wav",
                        },
                        {
                            metaData: {
                                artist: "Streambeats",
                                title: "Manhattan Project",
                            },
                            url: "/music/manhattan-project.mp3",
                        },
                        {
                            metaData: {
                                artist: "Streambeats",
                                title: "Electrolyte",
                            },
                            url: "/music/electrolyte.wav",
                        },
                        {
                            metaData: {
                                artist: "Streambeats",
                                title: "Stuck in Wonderland",
                            },
                            url: "/music/stuck-in-wonderland.wav",
                        },
                        {
                            metaData: {
                                artist: "Squishyboi",
                                title: "Voyager",
                            },
                            url: "/music/voyager-battle.mp3",
                        },
                        {
                            metaData: {
                                artist: "Squishyboi",
                                title: "Augmented",
                            },
                            url: "/music/augmented-battle.mp3",
                        },
                    ],
                }).renderWhenReady(document.getElementById('webamp-container')).then(() => {
                    // Find the main Webamp element
                    const webampElement = document.querySelector('#webamp');
                    if (webampElement) {
                        // Apply styles directly to the Webamp element
                        webampElement.style.position = 'fixed';
                        webampElement.style.top = '200px';
                        webampElement.style.right = '20px';
                        webampElement.style.zIndex = '9999';
                    }
                });
            }
        </script>
    </div>
    <div id="mouse-follower"></div>
    <div id="dvd-logo">DVD</div>
    <img src="/images/howard.gif" alt="howard" id="howard">
    <img src="/images/jupiter.gif" alt="jupiter" id="jupiter">
    <img src="/images/sasha.gif" alt="sasha" id="sasha">
</body>
</html>

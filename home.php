<?php
require_once 'vendor/autoload.php';

ini_set('display_errors', 0);

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
    if (!is_file($filePath) || !preg_match('/^posts\/[a-zA-Z0-9-_]+\.md$/', $filePath)) {
        return 'Invalid file';
    }
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

    public function __construct($image, $title, $description, $link = null) {
        $this->image = $image;
        $this->title = $title;
        $this->description = $description;
        $this->link = $link;
    }

    public function render() {
        $wrapperStart = $this->link ? "<a href=\"{$this->link}\"" : "<div";
        $wrapperEnd = $this->link ? "</a>" : "</div>";
        
        return "
            {$wrapperStart} class=\"project-card\">
                <div class=\"project-card-image\">
                    <img src=\"{$this->image}\" alt=\"{$this->title}\">
                </div>
                <div class=\"project-card-content\">
                    <h3>
                        {$this->title}
                        " . "
                    </h3>
                    <p>{$this->description}</p>
                </div>
            {$wrapperEnd}
        ";
    }
}

$projects = [
    new ProjectCard("/images/projects/jobertdemo.png", "Jobert", "Web app for tracking, automating, and visualizing job applications", "https://github.com/sn0rp/jobert"),
    new ProjectCard("/images/projects/weather.png", "Weather", "Most rapidly useful weather app, live at weather.snorp.dev", "https://github.com/sn0rp/weather"),
    //new ProjectCard("/images/projects/printerFrustrationMin.png", "Arduino Uno R4 USB Host Library", "Why doesn't this already exist??? WIP!"),
    //new ProjectCard("https://placewaifu.com/image/200/150", "Project 3", "Description of Project 3", "#"),
];

$recentPosts = getRecentPosts();

function incrementVisitCount() {
    $countFile = __DIR__ . '/visit_count.txt';
    if (file_exists($countFile)) {
        $count = (int)file_get_contents($countFile);
        $count++;
    } else {
        $count = 1;
    }
    file_put_contents($countFile, $count, LOCK_EX);
    return $count;
}

$visitCount = incrementVisitCount();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:image" content="" />
    <meta name="twitter:image" content="" />
    <title>&#9732;Snorp</title>
    <link rel="icon" href="/images/snorp-sprite.png" type="image/png">
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://unpkg.com/webamp@1.4.2/built/webamp.bundle.min.js"></script>
    <script src="/js/catscape.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:ital@0;1&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&display=swap" rel="stylesheet">
    <script src="/js/mouse-follower.js" defer></script>
    <script src="/js/dvd-screensaver.js" defer></script>
    <script src="/js/badge-scroll.js" defer></script>
    <script src="/js/wobbly-text.js" defer></script>
    <script src="/js/virus-attack.js" defer></script>
    <script src="/js/click-explosion.js" defer></script>
</head>
<body>
    <svg style="display: none;">
        <symbol id="icon-house" viewBox="0 0 576 512">
            <path d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/>
        </symbol>
        <symbol id="icon-github" viewBox="0 0 496 512">
            <path d="M165.9 397.4c0 2-2.3 3.6-5.2 3.6-3.3 .3-5.6-1.3-5.6-3.6 0-2 2.3-3.6 5.2-3.6 3-.3 5.6 1.3 5.6 3.6zm-31.1-4.5c-.7 2 1.3 4.3 4.3 4.9 2.6 1 5.6 0 6.2-2s-1.3-4.3-4.3-5.2c-2.6-.7-5.5 .3-6.2 2.3zm44.2-1.7c-2.9 .7-4.9 2.6-4.6 4.9 .3 2 2.9 3.3 5.9 2.6 2.9-.7 4.9-2.6 4.6-4.6-.3-1.9-3-3.2-5.9-2.9zM244.8 8C106.1 8 0 113.3 0 252c0 110.9 69.8 205.8 169.5 239.2 12.8 2.3 17.3-5.6 17.3-12.1 0-6.2-.3-40.4-.3-61.4 0 0-70 15-84.7-29.8 0 0-11.4-29.1-27.8-36.6 0 0-22.9-15.7 1.6-15.4 0 0 24.9 2 38.6 25.8 21.9 38.6 58.6 27.5 72.9 20.9 2.3-16 8.8-27.1 16-33.7-55.9-6.2-112.3-14.3-112.3-110.5 0-27.5 7.6-41.3 23.6-58.9-2.6-6.5-11.1-33.3 2.6-67.9 20.9-6.5 69 27 69 27 20-5.6 41.5-8.5 62.8-8.5s42.8 2.9 62.8 8.5c0 0 48.1-33.6 69-27 13.7 34.7 5.2 61.4 2.6 67.9 16 17.7 25.8 31.5 25.8 58.9 0 96.5-58.9 104.2-114.8 110.5 9.2 7.9 17 22.9 17 46.4 0 33.7-.3 75.4-.3 83.6 0 6.5 4.6 14.4 17.3 12.1C428.2 457.8 496 362.9 496 252 496 113.3 383.5 8 244.8 8zM97.2 352.9c-1.3 1-1 3.3 .7 5.2 1.6 1.6 3.9 2.3 5.2 1 1.3-1 1-3.3-.7-5.2-1.6-1.6-3.9-2.3-5.2-1zm-10.8-8.1c-.7 1.3 .3 2.9 2.3 3.9 1.6 1 3.6 .7 4.3-.7 .7-1.3-.3-2.9-2.3-3.9-2-.6-3.6-.3-4.3 .7zm32.4 35.6c-1.6 1.3-1 4.3 1.3 6.2 2.3 2.3 5.2 2.6 6.5 1 1.3-1.3 .7-4.3-1.3-6.2-2.2-2.3-5.2-2.6-6.5-1zm-11.4-14.7c-1.6 1-1.6 3.6 0 5.9 1.6 2.3 4.3 3.3 5.6 2.3 1.6-1.3 1.6-3.9 0-6.2-1.4-2.3-4-3.3-5.6-2z"/>
        </symbol>
        <symbol id="icon-x-twitter" viewBox="0 0 512 512">
            <path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/>
        </symbol>
        <symbol id="icon-link" viewBox="0 0 640 512">
            <path d="M579.8 267.7c56.5-56.5 56.5-148 0-204.5c-50-50-128.8-56.5-186.3-15.4l-1.6 1.1c-14.4 10.3-17.7 30.3-7.4 44.6s30.3 17.7 44.6 7.4l1.6-1.1c32.1-22.9 76-19.3 103.8 8.6c31.5 31.5 31.5 82.5 0 114L422.3 334.8c-31.5 31.5-82.5 31.5-114 0c-27.9-27.9-31.5-71.8-8.6-103.8l1.1-1.6c10.3-14.4 6.9-34.4-7.4-44.6s-34.4-6.9-44.6 7.4l-1.1 1.6C206.5 251.2 213 330 263 380c56.5 56.5 148 56.5 204.5 0L579.8 267.7zM60.2 244.3c-56.5 56.5-56.5 148 0 204.5c50 50 128.8 56.5 186.3 15.4l1.6-1.1c14.4-10.3 17.7-30.3 7.4-44.6s-30.3-17.7-44.6-7.4l-1.6 1.1c-32.1 22.9-76 19.3-103.8-8.6C74 372 74 321 105.5 289.5L217.7 177.2c31.5-31.5 82.5-31.5 114 0c27.9 27.9 31.5 71.8 8.6 103.9l-1.1 1.6c-10.3 14.4-6.9 34.4 7.4 44.6s34.4 6.9 44.6-7.4l1.1-1.6C433.5 260.8 427 182 377 132c-56.5-56.5-148-56.5-204.5 0L60.2 244.3z"/>
        </symbol>
    </svg>
    <div class="page-wrapper">
        <div class="container">
            <header>
                <h1 id="wobble">Snorp</h1>
                <nav>
                    <a href="/" class="home-link">
                        <svg class="icon"><use xlink:href="#icon-house"></use></svg>
                    </a>
                    <a href="https://github.com/sn0rp" class="external-link">
                        <svg class="icon"><use xlink:href="#icon-github"></use></svg>
                    </a>
                    <a href="https://x.com/_snorp" class="external-link">
                        <svg class="icon"><use xlink:href="#icon-x-twitter"></use></svg>
                    </a>
                </nav>
                <img src="/images/under-construction.gif" alt="under construction" style="width: 210px;"/>
                <div class="toggle-buttons">
                    <button id="toggle-dvd" class="toggle-button">Toggle DVD</button>
                    <button id="toggle-follower" class="toggle-button">Toggle Follower</button>
                    <button id="virus-attack" class="toggle-button">DON'T CLICK</button>
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
                            <li><a href="/blog/<?= basename($post['file'], '.md') ?>" class="catscape-link"><?= $post['title'] ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php if (count(glob('posts/*.md')) > 10): ?>
                        <a href="/blog" class="catscape-link">More posts...</a>
                    <?php endif; ?>
                </section>
                
                <section id="projects">
                    <h2>Incredible Stuff</h2>
                    <div class="project-grid">
                        <?php foreach ($projects as $project): ?>
                            <?= $project->render() ?>
                        <?php endforeach; ?>
                    </div>
                </section>
                
                <section id="badges">
                    <br>
                    <div class="badge-container">
                        <img src="/images/badges/snorpBadge.png" alt="Badge">
                    </div>
                    <div class="badge-scroll-container">
                        <div class="badge-scroll">
                            <img src="/images/badges/badgemath.png" alt="Badge">
                            <img src="/images/badges/badgephysics.png" alt="Badge">
                            <img src="/images/badges/Archlinux_80x15.png" alt="Badge">
                            <img src="/images/badges/f-22_6.gif" alt="Badge">
                            <img src="/images/badges/fl_isr.gif" alt="Badge">
                            <img src="/images/badges/loveanime.gif" alt="Badge">
                            <img src="/images/badges/nosmoking.gif" alt="Badge">
                            <img src="/images/badges/piano.gif" alt="Badge">
                            <img src="/images/badges/vim_the_editor.png" alt="Badge">
                            <img src="/images/badges/xp.gif" alt="Badge">
                            <!--loop-->
                            <img src="/images/badges/nosmoking.gif" alt="Badge">
                            <img src="/images/badges/piano.gif" alt="Badge">
                            <img src="/images/badges/vim_the_editor.png" alt="Badge">
                            <img src="/images/badges/xp.gif" alt="Badge">
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
                                artist: "Streambeats",
                                title: "Rooftops",
                            },
                            url: "/music/rooftops.mp3",
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
                                title: "Stuck in Wonderland",
                            },
                            url: "/music/stuck-in-wonderland.mp3",
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

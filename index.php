<?php
require_once 'vendor/autoload.php';

function getRecentPosts($limit = 4) {
    $posts = glob('posts/*.md');
    usort($posts, function($a, $b) {
        return filemtime($b) - filemtime($a);
    });
    return array_slice($posts, 0, $limit);
}

$recentPosts = getRecentPosts();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Snorp - Personal Website</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/webamp@1.4.2/built/webamp.bundle.min.js"></script>
    <script src="js/catscape.js" defer></script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Snorp</h1>
            <nav>
                <a href="#" class="catscape-link">Home</a>
                <a href="blog.php" class="catscape-link">Blog</a>
                <a href="https://github.com/yourusername" class="catscape-link"><i class="fab fa-github"></i></a>
                <a href="https://twitter.com/yourusername" class="catscape-link"><i class="fab fa-x-twitter"></i></a>
            </nav>
        </header>
        
        <main>
            <section id="about">
                <h2>About Me</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
            </section>
            
            <section id="recent-posts">
                <h2>Recent Posts</h2>
                <ul>
                    <?php foreach ($recentPosts as $post): ?>
                        <li><a href="blog.php?post=<?= basename($post, '.md') ?>" class="catscape-link"><?= ucfirst(str_replace('-', ' ', basename($post, '.md'))) ?></a></li>
                    <?php endforeach; ?>
                </ul>
                <?php if (count(glob('posts/*.md')) > 4): ?>
                    <a href="blog.php" class="catscape-link">More posts...</a>
                <?php endif; ?>
            </section>
            
            <section id="projects">
                <h2>Projects</h2>
                <div class="project-grid">
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <div class="project-card">
                            <img src="https://placewaifu.com/image/200/150" alt="Project <?= $i ?>">
                            <h3>Project <?= $i ?></h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                            <a href="#" class="catscape-link"><i class="fas fa-link"></i></a>
                        </div>
                    <?php endfor; ?>
                </div>
            </section>
            
            <section id="badges">
                <h2>Badges</h2>
                <div class="badge-container">
                    <?php for ($i = 1; $i <= 10; $i++): ?>
                        <img src="images/badges/badge<?= $i ?>.gif" alt="Badge <?= $i ?>">
                    <?php endfor; ?>
                </div>
            </section>
        </main>
        
        <footer>
            <p>&copy; 2023 Snorp. All rights reserved.</p>
        </footer>
    </div>
    
    <div id="catscape-container" class="hidden">
        <div id="catscape-titlebar">
            <span>Snorpscape Sailor</span>
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
        new Webamp({
            initialTracks: [
                {
                    metaData: {
                        artist: "DJ Mike Llama",
                        title: "Llama Whippin' Intro",
                    },
                    url: "https://cdn.jsdelivr.net/gh/captbaritone/webamp@43434d82/mp3/llama-2.91.mp3",
                },
            ],
        }).renderWhenReady(document.getElementById('webamp-container'));
    </script>
</body>
</html>

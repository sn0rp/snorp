<?php
require_once 'vendor/autoload.php';

function getPostList() {
    $posts = glob('posts/*.md');
    usort($posts, function($a, $b) {
        return filemtime($b) - filemtime($a);
    });
    return array_map(function($post) {
        return [
            'file' => $post,
            'title' => getPostTitle($post)
        ];
    }, $posts);
}
$parsedown = new Parsedown();
$parsedown->setMarkupEscaped(false);
$posts = getPostList();

function getPostTitle($filePath) {
    $content = file_get_contents($filePath);
    if (preg_match('/^#\s*(.+)$/m', $content, $matches)) {
        return trim($matches[1]);
    }
    return basename($filePath, '.md');
}

if (isset($_GET['post'])) {
    $postFile = 'posts/' . $_GET['post'] . '.md';
    if (file_exists($postFile)) {
        $postContent = file_get_contents($postFile);
        $postTitle = getPostTitle($postFile);
        $postHtml = $parsedown->text($postContent);
        $postHtml = preg_replace('/<pre><code class="language-([^"]+)">/', '<div class="centered-content"><pre><code class="language-$1">', $postHtml);
        $postHtml = str_replace('</code></pre>', '</code></pre></div>', $postHtml);
    } else {
        $postHtml = '<p>Post not found.</p>';
        $postTitle = 'Post Not Found';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Snorp Thoughts</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="js/catscape.js" defer></script>
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism-okaidia.min.css">-->
    <link rel="stylesheet" href="css/prism-dracula.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/prism.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs/plugins/autoloader/prism-autoloader.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-cpp.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
</head>
<body class="blog-page">
    <div class="container">
        <header>
            <h1>Snorp Thoughts</h1>
            <nav>
                <a href="index.php" class="home-link"><i class="fas fa-globe"></i></a>
                <a href="https://github.com/sn0rp" class="external-link"><i class="fab fa-github"></i></a>
                <a href="https://twitter.com/_snorp" class="external-link"><i class="fab fa-x-twitter"></i></a>
            </nav>
        </header>
        
        <main>
            <?php if (isset($postHtml)): ?>
                <article>
                    <?= $postHtml ?>
                </article>
            <?php else: ?>
                <h2>Blog Posts</h2>
                <ul>
                    <?php foreach ($posts as $post): ?>
                        <li>
                            <a href="?post=<?= basename($post['file'], '.md') ?>" class="catscape-link">
                                <?= $post['title'] ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </main>
        
        <footer>
            <p>&copy; <?php echo date("Y"); ?> Snorp. All rights reserved.</p>
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
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            Prism.highlightAll();
        });
    </script>
</body>
</html>

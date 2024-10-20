<?php
require_once 'vendor/autoload.php';

function getPostList() {
    $posts = glob('posts/*.md');
    usort($posts, function($a, $b) {
        return filemtime($b) - filemtime($a);
    });
    return $posts;
}

$parsedown = new Parsedown();
$posts = getPostList();

if (isset($_GET['post'])) {
    $postFile = 'posts/' . $_GET['post'] . '.md';
    if (file_exists($postFile)) {
        $postContent = file_get_contents($postFile);
        $postHtml = $parsedown->text($postContent);
    } else {
        $postHtml = '<p>Post not found.</p>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Snorp - Blog</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/catscape.js" defer></script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Snorp's Blog</h1>
            <nav>
                <a href="index.php" class="catscape-link">Home</a>
                <a href="blog.php" class="catscape-link">Blog</a>
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
                            <a href="?post=<?= basename($post, '.md') ?>" class="catscape-link">
                                <?= ucfirst(str_replace('-', ' ', basename($post, '.md'))) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
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
</body>
</html>

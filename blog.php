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
$posts = getPostList();

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

if (isset($_GET['post'])) {
    $postName = preg_replace('/[^a-zA-Z0-9-_]/', '', $_GET['post']);
    $postFile = 'posts/' . $postName . '.md';
    if (file_exists($postFile) && is_file($postFile)) {
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
    <link rel="icon" href="/images/snorp-sprite.png" type="image/png">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/catscape.js" defer></script>
    <link rel="stylesheet" href="css/prism-dracula.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/prism.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs/plugins/autoloader/prism-autoloader.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-cpp.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:ital@0;1&display=swap" rel="stylesheet">
</head>
<body class="blog-page">
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
    <div class="container">
        <header>
            <h1>Snorp Thoughts</h1>
            <nav>
                <a href="index.php" class="home-link">
                    <svg class="icon"><use xlink:href="#icon-house"></use></svg>
                </a>
                <a href="https://github.com/sn0rp" class="external-link">
                    <svg class="icon"><use xlink:href="#icon-github"></use></svg>
                </a>
                <a href="https://x.com/_snorp" class="external-link">
                    <svg class="icon"><use xlink:href="#icon-x-twitter"></use></svg>
                </a>
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

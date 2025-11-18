<?php
require_once 'includes/PostManager.php';

$postManager = new PostManager();

// Handle post view vs list view
if (isset($_GET['post'])) {
    $post = $postManager->getPost($_GET['post']);
    $postHtml = $postManager->renderPost($post);
    $postTitle = $post ? $post['title'] : 'Post Not Found';
} else {
    // Get filter parameters
    $category = isset($_GET['category']) ? $_GET['category'] : null;
    $tag = isset($_GET['tag']) ? $_GET['tag'] : null;
    
    // Get filtered or all posts
    if ($category) {
        $posts = $postManager->getPostsByCategory($category);
        $pageTitle = "Posts in: $category";
    } elseif ($tag) {
        $posts = $postManager->getPostsByTag($tag);
        $pageTitle = "Posts tagged: $tag";
    } else {
        $posts = $postManager->getAllPosts();
        $pageTitle = "All Posts";
    }
    
    $allCategories = $postManager->getAllCategories();
    $allTags = $postManager->getAllTags();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($postTitle) ? $postTitle : 'Snorp Thoughts' ?></title>
    <link rel="icon" href="/images/snorp-sprite.png" type="image/png">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/prism-dracula.css">
    <script src="/js/catscape.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/plugins/autoloader/prism-autoloader.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:ital@0;1&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&display=swap" rel="stylesheet">
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
    </svg>
    <div class="container">
        <header>
            <h1>Snorp Thoughts</h1>
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
        </header>
        
        <main class="blog-main">
            <?php if (isset($postHtml)): ?>
                <!-- Single Post View -->
                <article>
                    <?= $postHtml ?>
                </article>
                <div style="margin: 20px; 0">
                    <a href="/blog">&larr; Back to all posts</a>
                </div>
            <?php else: ?>
                <!-- Blog Index View -->
                <h2><?= $pageTitle ?></h2>
                
                <!-- Filter Section -->
                <div class="blog-filters" style="margin-bottom: 20px; padding: 15px; background: rgba(0,0,0,0.3); border-radius: 8px;">
                    <?php if (!empty($allCategories)): ?>
                        <div style="margin-bottom: 10px;">
                            <strong>Categories:</strong>
                            <?php if ($category): ?>
                                <a href="/blog" style="margin-left: 10px;">Clear filter</a>
                            <?php endif; ?>
                            <div style="margin-top: 5px;">
                                <?php foreach ($allCategories as $cat): ?>
                                    <a href="/blog?category=<?= urlencode($cat) ?>" 
                                       class="filter-tag <?= $category === $cat ? 'active' : '' ?>"
                                       style="display: inline-block; margin: 3px; padding: 5px 10px; background: rgba(138, 43, 226, 0.3); border-radius: 4px; font-size: 14px;">
                                        <?= htmlspecialchars($cat) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (false): ?> <!--if (!empty($allTags))-->
                        <div>
                            <strong>Tags:</strong>
                            <?php if ($tag): ?>
                                <a href="/blog" style="margin-left: 10px;">Clear filter</a>
                            <?php endif; ?>
                            <div style="margin-top: 5px;">
                                <?php foreach ($allTags as $t): ?>
                                    <a href="/blog?tag=<?= urlencode($t) ?>" 
                                       class="filter-tag <?= $tag === $t ? 'active' : '' ?>"
                                       style="display: inline-block; margin: 3px; padding: 5px 10px; background: rgba(0, 119, 190, 0.3); border-radius: 4px; font-size: 12px;">
                                        #<?= htmlspecialchars($t) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Posts List -->
                <?php if (empty($posts)): ?>
                    <p>No posts found.</p>
                <?php else: ?>
                    <div class="posts-list">
                        <?php foreach ($posts as $post): ?>
                            <article class="post-preview" style="margin-bottom: 30px; padding: 20px; background: rgba(0,0,0,0.3); border-radius: 8px;">
                                <h3 style="margin-top: 0;">
                                    <a href="/blog/<?= $post['slug'] ?>" class="catscape-link">
                                        <?= htmlspecialchars($post['title']) ?>
                                    </a>
                                </h3>
                                <div class="post-meta" style="font-size: 14px; opacity: 0.8; margin-bottom: 10px;">
                                    <span><?= date('F j, Y', $post['timestamp']) ?></span>
                                    <?php if (!empty($post['categories'])): ?>
                                        <span style="margin-left: 15px;">
                                            <?php foreach ($post['categories'] as $cat): ?>
                                                <a href="/blog?category=<?= urlencode($cat) ?>" style="margin-right: 5px;">
                                                    <?= htmlspecialchars($cat) ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <p><?= htmlspecialchars($post['excerpt']) ?></p>
                                <a href="/blog/<?= $post['slug'] ?>" class="catscape-link">Read more &rarr;</a>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </main>
    </div><br>
    
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
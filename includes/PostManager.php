<?php
require_once __DIR__ . '/../vendor/autoload.php';

class PostManager {
    private $postsDir;
    private $parsedown;
    
    public function __construct($postsDir = 'posts') {
        $this->postsDir = $postsDir;
        $this->parsedown = new Parsedown();
    }
    
    /**
     * Parse YAML frontmatter and content from markdown file
     */
    private function parsePost($filePath) {
        if (!file_exists($filePath)) {
            return null;
        }
        
        $content = file_get_contents($filePath);
        
        // Extract frontmatter
        $frontmatter = [];
        if (preg_match('/^---\s*\n(.*?)\n---\s*\n(.*)$/s', $content, $matches)) {
            $yamlContent = $matches[1];
            $markdownContent = $matches[2];
            
            // Parse YAML manually (simple key: value pairs)
            foreach (explode("\n", $yamlContent) as $line) {
                if (preg_match('/^(\w+):\s*(.+)$/', trim($line), $lineMatch)) {
                    $key = $lineMatch[1];
                    $value = trim($lineMatch[2], '"\'');
                    
                    // Handle arrays like [tag1, tag2]
                    if (preg_match('/^\[(.*)\]$/', $value, $arrayMatch)) {
                        $value = array_map('trim', explode(',', $arrayMatch[1]));
                    }
                    
                    $frontmatter[$key] = $value;
                }
            }
        } else {
            $markdownContent = $content;
        }
        
        // Get title from frontmatter or first H1
        $title = $frontmatter['title'] ?? $this->extractTitle($markdownContent);
        
        return [
            'file' => basename($filePath),
            'slug' => basename($filePath, '.md'),
            'title' => $title,
            'date' => $frontmatter['date'] ?? date('Y-m-d', filemtime($filePath)),
            'categories' => $frontmatter['categories'] ?? [],
            'tags' => $frontmatter['tags'] ?? [],
            'excerpt' => $frontmatter['excerpt'] ?? $this->generateExcerpt($markdownContent),
            'content' => $markdownContent,
            'timestamp' => strtotime($frontmatter['date'] ?? date('Y-m-d', filemtime($filePath)))
        ];
    }
    
    /**
     * Extract title from markdown content
     */
    private function extractTitle($content) {
        if (preg_match('/^#\s*(.+)$/m', $content, $matches)) {
            return trim($matches[1]);
        }
        return 'Untitled';
    }
    
    /**
     * Generate excerpt from content
     */
    private function generateExcerpt($content, $length = 150) {
        // Remove markdown formatting
        $text = preg_replace('/^#.*$/m', '', $content);
        $text = preg_replace('/\*\*(.*?)\*\*/', '$1', $text);
        $text = preg_replace('/\*(.*?)\*/', '$1', $text);
        $text = strip_tags($text);
        $text = trim(preg_replace('/\s+/', ' ', $text));
        
        if (strlen($text) > $length) {
            return substr($text, 0, $length) . '...';
        }
        return $text;
    }
    
    /**
     * Get all posts sorted by date (newest first)
     */
    public function getAllPosts() {
        $posts = glob($this->postsDir . '/*.md');
        $parsedPosts = [];
        
        foreach ($posts as $postFile) {
            $post = $this->parsePost($postFile);
            if ($post) {
                $parsedPosts[] = $post;
            }
        }
        
        // Sort by timestamp descending
        usort($parsedPosts, function($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });
        
        return $parsedPosts;
    }
    
    /**
     * Get recent posts
     */
    public function getRecentPosts($limit = 4) {
        return array_slice($this->getAllPosts(), 0, $limit);
    }
    
    /**
     * Get single post by slug
     */
    public function getPost($slug) {
        $postFile = $this->postsDir . '/' . preg_replace('/[^a-zA-Z0-9-_]/', '', $slug) . '.md';
        return $this->parsePost($postFile);
    }
    
    /**
     * Render post content as HTML
     */
    public function renderPost($post) {
        if (!$post) {
            return '<p>Post not found.</p>';
        }
        
        $html = $this->parsedown->text($post['content']);
        
        // Format code blocks for Prism
        $html = preg_replace('/<pre><code class="language-([^"]+)">/', '<div class="centered-content"><pre><code class="language-$1">', $html);
        $html = str_replace('</code></pre>', '</code></pre></div>', $html);
        
        return $html;
    }
    
    /**
     * Get all unique categories
     */
    public function getAllCategories() {
        $categories = [];
        foreach ($this->getAllPosts() as $post) {
            if (!empty($post['categories'])) {
                $categories = array_merge($categories, $post['categories']);
            }
        }
        return array_unique($categories);
    }
    
    /**
     * Get all unique tags
     */
    public function getAllTags() {
        $tags = [];
        foreach ($this->getAllPosts() as $post) {
            if (!empty($post['tags'])) {
                $tags = array_merge($tags, $post['tags']);
            }
        }
        return array_unique($tags);
    }
    
    /**
     * Filter posts by category
     */
    public function getPostsByCategory($category) {
        return array_filter($this->getAllPosts(), function($post) use ($category) {
            return in_array($category, $post['categories']);
        });
    }
    
    /**
     * Filter posts by tag
     */
    public function getPostsByTag($tag) {
        return array_filter($this->getAllPosts(), function($post) use ($tag) {
            return in_array($tag, $post['tags']);
        });
    }
}
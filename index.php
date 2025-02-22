<?php
require_once 'vendor/autoload.php';

ini_set('display_errors', 0);

$request_uri = $_SERVER['REQUEST_URI'];
$path = trim(parse_url($request_uri, PHP_URL_PATH), '/');

switch ($path) {
    case '':
        // Home page
        include 'home.php';
        break;
    case 'blog':
        // Blog listing page
        include 'blog.php';
        break;
    case 'biznus':
        include 'biznus.php';
        break;
    default:
        // Check if it's a blog post
        if (preg_match('/^blog\/(.+)$/', $path, $matches)) {
            $_GET['post'] = $matches[1];
            include 'blog.php';
        } else {
            // 404 page
            header("HTTP/1.0 404 Not Found");
            include '404.php';
        }
        break;
}

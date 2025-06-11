<?php
require 'config.php';
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $posts = json_decode(file_get_contents('posts.json'), true);
    $posts = array_filter($posts, fn($post) => $post['id'] !== $id);
    file_put_contents('posts.json', json_encode(array_values($posts), JSON_PRETTY_PRINT));
}
header('Location: index.php');
?>
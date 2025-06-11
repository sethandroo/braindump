<?php
session_start();
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $text = trim($_POST['text'] ?? '');
    $link = trim($_POST['link'] ?? '');
    $tagsRaw = trim($_POST['tags'] ?? '');
    $tags = array_filter(array_map('trim', explode(',', $tagsRaw)));

    if (!$title) {
        $error = 'Title is required.';
    } else {
        $posts = file_exists('posts.json') ? json_decode(file_get_contents('posts.json'), true) : [];

        $imagePath = '';
        if (!empty($_FILES['image']['name'])) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed)) {
                $newName = 'uploads/' . uniqid() . '.' . $ext;
                if (!is_dir('uploads')) {
                    mkdir('uploads', 0755, true);
                }
                if (move_uploaded_file($_FILES['image']['tmp_name'], $newName)) {
                    $imagePath = $newName;
                } else {
                    $error = 'Failed to upload image.';
                }
            } else {
                $error = 'Invalid image format.';
            }
        }

        if (!$error) {
            $post = [
                'id' => uniqid(),
                'title' => htmlspecialchars($title),
                'text' => htmlspecialchars($text),
                'link' => htmlspecialchars($link),
                'tags' => $tags,
                'image' => $imagePath,
                'time' => date('Y-m-d H:i:s'),
            ];
            $posts[] = $post;
            file_put_contents('posts.json', json_encode($posts, JSON_PRETTY_PRINT));
            header('Location: index.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>New Post</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        body {
            max-width: 600px;
        }
    </style>
</head>
<body>
    <h1>Create New Post</h1>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input name="title" type="text" id="title" required value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">

        <label for="text">Text:</label>
        <textarea name="text" id="text"><?= htmlspecialchars($_POST['text'] ?? '') ?></textarea>

        <label for="link">Link:</label>
        <input name="link" type="text" id="link" value="<?= htmlspecialchars($_POST['link'] ?? '') ?>">

        <label for="tags">Tags:</label>
        <input name="tags" type="text" id="tags" value="<?= htmlspecialchars($_POST['tags'] ?? '') ?>">

        <label for="image">Image:</label>
        <input type="file" name="image" id="image" accept="image/*">
        <br>

        <input type="submit" value="Post">
    </form>
    <br>
    <a href="index.php">← Back to feed</a>
</body>
</html>
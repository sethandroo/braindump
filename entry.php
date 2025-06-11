<?php
$posts = json_decode(file_get_contents('posts.json'), true);
$postId = $_GET['id'] ?? null;
$foundPost = null;

foreach ($posts as $post) {
    if ($post['id'] == $postId) {
        $foundPost = $post;
        break;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $foundPost ? htmlspecialchars($foundPost['title']) : "Post Not Found"; ?></title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); min-width:300px; min-height:500px;">
    <div class="post">
    <?php if ($foundPost): ?>
        <h3><?php echo htmlspecialchars($foundPost['title']); ?></h3>

            <p><?= nl2br(htmlspecialchars($post['text'])) ?></p>

            <?php if (!empty($post['link'])): ?>
                <p><a href="<?= htmlspecialchars($post['link']) ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($post['link']) ?></a></p>
            <?php endif; ?>

            <?php if (!empty($post['image'])): ?>
                <a href="<?= htmlspecialchars($post['image']) ?>" class="lightbox-trigger" title="<?= htmlspecialchars($post['title']) ?>">
                    <img src="<?= htmlspecialchars($post['image']) ?>" alt="Post image" />
                </a>
            <?php endif; ?>

            <?php if (!empty($post['tags'])): ?>
                <p class="tags">
                    <?php foreach ($post['tags'] as $tag): ?>
                        <a href="index.php?tag=<?= urlencode($tag) ?>"><?= htmlspecialchars($tag) ?></a>
                    <?php endforeach; ?>
                </p>
            <?php endif; ?>

            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                <div class="actions">
                    <a href="edit.php?id=<?= urlencode($post['id']) ?>">Edit</a>
                    <a href="delete.php?id=<?= urlencode($post['id']) ?>" onclick="return confirm('Delete this post?');">Delete</a>
                </div>
            <?php endif; ?>
            <br>
        <p><a href="index.php">← Back to all posts</a></p>
    <?php else: ?>
        <h2>Post not found.</h2>
        <p><a href="index.php">← Back to all posts</a></p>
    <?php endif; ?>
        </div>
    </div>
    <script src="/scripts/masonry.pkgd.min.js"></script>
    <link rel="stylesheet" href="/scripts/basicLightbox.min.css" />
    <script src="/scripts/basicLightbox.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var grid = document.querySelector('.posts-grid');
            if(grid) {
                new Masonry(grid, {
                    itemSelector: '.post',
                    gutter: 15,
                    fitWidth: true
                });
            }

            document.querySelectorAll('.lightbox-trigger').forEach(function(el) {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    const instance = basicLightbox.create(`
                        <img src="${el.getAttribute('href')}" style="max-width: 90vw; max-height: 90vh;" alt="${el.getAttribute('title') || 'Image'}">
                    `)
                    instance.show()
                });
            });
        });
    </script>
</body>
</html>
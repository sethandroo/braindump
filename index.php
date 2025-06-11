<?php
function getAllTagsFromPosts($posts) {
    $tagSet = [];
    foreach ($posts as $post) {
        if (!empty($post['tags']) && is_array($post['tags'])) {
            foreach ($post['tags'] as $tag) {
                $tag = trim($tag);
                if ($tag !== '') {
                    $tagSet[$tag] = true;
                }
            }
        }
    }
    $tags = array_keys($tagSet);
    sort($tags);
    return $tags;
}
?>

<?php
session_start();
$posts = file_exists('posts.json') ? json_decode(file_get_contents('posts.json'), true) : [];
$selectedTag = $_GET['tag'] ?? null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>braindump</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div style="display: flex;">
    <!-- Sidebar -->
    <div style="width: auto; padding: 25px; padding-right: 25px; padding-top: 20px; font-size: small; line-height: 30px;">
        <?php
        $tags = getAllTagsFromPosts($posts);
        foreach ($tags as $tag) {
            echo "<a href='?tag=" . urlencode($tag) . "'># $tag</a><br>";
        }
        ?>
    </div>

    <!-- Main Content -->
    <div style="flex-grow: 1; padding: 50px; padding-top: 10px;">

    <div class="nav">
        <?php if ($selectedTag): ?>
            <p>Filtering by tag: <strong><?= htmlspecialchars($selectedTag) ?></strong>
            <a href="index.php">(clear)</a></p>
        <?php endif; ?>
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
            <p><a href="post.php">+ New Post</a> | <a href="logout.php">Logout</a></p>
        <?php else: ?>
        <?php endif; ?>
    </div>

    <div class="posts-grid">
    <?php foreach ($posts as $post): ?>
        <?php if ($selectedTag && (!isset($post['tags']) || !in_array($selectedTag, $post['tags']))) continue; ?>
        <div class="post">
            <h3><a href="entry.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h3>
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
        </div>
    <?php endforeach; ?>
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
    </div>
</div>
</body>
</html>​
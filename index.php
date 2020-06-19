<?php 
    require_once 'functions.php';
    $link = getDbConnection();
    $query = 'SELECT posts.*, COUNT(comments.id) AS comment_count, AVG(comments.rate) AS avg_rate FROM posts LEFT JOIN comments ON posts.id = comments.article_id GROUP BY posts.id';
    $result = mysqli_query($link, $query);
    if (!$result)
    {
        http_response_code(500);
        exit('Database query error.');
    }

    $currentPage = 'home';
    $pageTitle = 'Домашня сторінка';
?>

<?php require 'header.php'; ?>
<main>
<?php while ($row = mysqli_fetch_array($result)) { ?>
    <article class="articleInMain">
        <img class="articleImage" src="Pictures/<?=$row['image']; ?>" alt="<?= htmlentities($row['title']); ?>">
        <header class="headArt">
            <a class="headA" href="article.php?article=<?= $row['id']; ?>"><?= htmlentities($row['theme']); ?></a>
            <h2><?= htmlentities($row['title']); ?></h2>
            Published: <?= htmlentities($row['author']); ?> <time datetime="<?= $row['created']; ?>"><?= $row['created']; ?></time>
        </header>
        <div class="articleContainer"><?= $row['content']; ?></div>
        <footer>
            <div class="article-stats">
                Comments count: <?= $row['comment_count']; ?>.
                Average rate: <?= null === $row['avg_rate'] ? 'N/A' : number_format($row['avg_rate'], 1);?>
            </div>
        </footer>
    </article>
<?php } ?>
</main>
<?php require 'footer.php'; ?>
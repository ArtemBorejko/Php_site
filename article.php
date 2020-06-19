<?php require_once 'functions.php';

    if (empty($_GET['article'])) {
        header('Location: index.php');
        die;
    }

    $link = getDbConnection();
    $id = mysqli_real_escape_string($link, $_GET['article']); 
    $query = "SELECT * FROM posts WHERE id='{$id}'";
    $result = mysqli_query($link, $query);
    if (!$result)
    {
        http_response_code(500);
        exit('Database query error.');
    }

    $row = mysqli_fetch_array($result);
    if (!$row)
    {
        http_response_code(404);
        exit('Article not found.');
    }

    $currentPage = 'article';
    $pageTitle = htmlentities($row['title']);
?>
<?php
    $commentErrors = [];
    $commentAuthor = '';
    $commentRate = 5;
    $commentText = '';
    if (isset($_POST['action']) && 'add-comment' === $_POST['action']) {
    $commentAuthor = trim((string)$_POST['author']);
    $commentRate = (int)$_POST['rate'];
    $commentText = trim((string)$_POST['comment']);
    $commentDate = date('Y-m-d H:i:s');
if ('' === $commentAuthor) {
        $commentErrors['author'] = 'Author can not be empty';
    } elseif (mb_strlen($commentAuthor) > 50) {
        $commentErrors['author'] = 'Author can not be more than 50 characters';
    }

    if ($commentRate < 1 || $commentRate > 5) {
        $commentErrors['rate'] = 'Rate is invalid';
    }

    if ('' === $commentText) {
        $commentErrors['comment'] = 'Comment can not be empty';
    } elseif (mb_strlen($commentText) < 3) {
        $commentErrors['comment'] = 'Comment can not be less than 3 characters';
    } elseif (mb_strlen($commentText) > 200) {
        $commentErrors['comment'] = 'Comment can not be more than 200 characters';
    }

    if (0 === count($commentErrors)) {
    $result2 = $link->execute(sprintf("INSERT INTO comments (article_id, author, rate, text, created) VALUES ('%d', '%s', '%s', '%s', '%s')",
    $id, mysqli_real_escape_string($link, $commentAuthor), mysqli_real_escape_string($link, $commentRate), mysqli_real_escape_string($link, $commentText), $commentDate));
    if (!$result2){
    http_response_code(500);
    exit('Database query error');
    }

    header("Location: article.php?article={$id}");
    exit();
    }
}
?>
<?php
    $result1 = $link->query("SELECT * FROM comments WHERE article_id='{$id}'");
    if (!$result1)
    {
        http_response_code(500);
        exit('Database query error.');
    }
?>
<?php require 'header.php'; ?>
<main>
    <article class="articleInMain">
        <img class="articleImage" src="Pictures/<?=$row['image']; ?>" alt="<?= htmlentities($row['title']); ?>">
        <header class="headArt">
            <a class="headA" href="article.php?article=<?= $row['id']; ?>"><?= htmlentities($row['theme']); ?></a>
            <h1><?= htmlentities($row['title']); ?></h1>
            Published: <?= htmlentities($row['author']); ?> <time datetime="<?= $row['created']; ?>"><?= $row['created']; ?></time>
        </header>
        <div class="articleContainer"><?= $row['content']; ?></div>
    </article>
    
    <div class="article-comment">
    <form action="" method="post">
    <input type="hidden" name="action" value="add-comment">
    <div>
        <label>Your name: <input type="text" name="author" value="<?= htmlspecialchars($commentAuthor); ?>"></label>
        <?php if (isset($commentErrors['author'])) { ?>
    <div class="comment-error"><?= $commentErrors['author']; ?></div>
    <?php } ?>
    </div>
    <div>
        <label>Rate article:
            <select name="rate">
                <?php for ($i=5; $i>0; $i--) { ?>
                <option value="<?= $i; ?>"
                    <?php if ($i === $commentRate) { ?>selected<?php } ?>
        >Rate <?= $i; ?></option>
        <?php } ?>
        </select>
    </label>
    <?php if (isset($commentErrors['rate'])) { ?>
        <div class="comment-error"><?= $commentErrors['rate']; ?></div>
    <?php } ?>
    </div>
    <div>
        <label>Comment:
            <textarea name="comment" cols="30" rows="5"><?= htmlspecialchars($commentText); ?></textarea>
        </label>
        <?php if (isset($commentErrors['comment'])) { ?>
            <div class="comment-error"><?= $commentErrors['comment']; ?></div>
        <?php } ?>
    </div>
    <div><input type="submit" value="Send"></div>
    </form>
    </div>
    
    <div class="article-comments">
        <?php while ($row1 = $result1->fetch_array(MYSQLI_ASSOC)) { ?>
            <article class="article-comment">
                <header class="comment-header">
                    <div class="comment-author">Author: <?= htmlspecialchars($row1['author']); ?></div>
                    <div class="comment-rate">Rate of article: <?= $row1['rate']; ?></div>
                    <div>
                        Published on
                        <time datetime="<?= $row1['created']; ?>">
                            <?= date('D, d M Y', strtotime($row1['created'])); ?>
                        </time>
                    </div>
                </header>
              <div class="comment-content"><?= nl2br(htmlspecialchars($row1['text'])); ?></div>
            </article>
        <?php } ?>
    </div>
</main>
<?php require 'footer.php'; ?>
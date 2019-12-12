<?php

$info = NULL;

$error = false;
$blog_name = $_GET['blog'];
$article_name = $_GET['article'];

function blogPath($blog_name)
{
    return realpath('./data') . '/' . $blog_name;
}

function write_file($path, $lines)
{
    $success = false;
    $pointer = fopen($path, 'a+');

    if (flock($pointer, LOCK_SH)) {
        foreach ($lines as $line) {
            fwrite($pointer, $line . PHP_EOL);
        }
        flock($pointer, LOCK_UN);
        $success = true;
    }

    fclose($pointer);
    return $success;
}

function ChmodFunc($path)
{
    chmod($path, 0777);
}

function brGen($text)
{
    return str_replace(["\r\n", "\r", "\n"], '<br/>', $text);
}

if (empty($blog_name) || empty($article_name)) {
    header('Location: blog.php');
}

if (!file_exists(blogPath($blog_name)) || !file_exists(blogPath($blog_name) . '/' . $article_name)) {
    $error = true;
}

if (!empty($_POST)) {
    $username = $_POST['username'];
    $type = $_POST['type'];
    $comment = brGen($_POST['comment']);
    $comments_dir = blogPath($blog_name) . '/' . $article_name . '.k';

    if (!file_exists($comments_dir)) {
        mkdir($comments_dir);
        ChmodFunc($comments_dir);
    }

    $comment_data = [
        $type,
        (date('Y-m-d') . ', ' . date('H:i:s')),
        $username,
        $comment
    ];

    // -1 bo komentarze indexujemy od 0
    $file_count = count(array_diff(scandir($comments_dir), ['.', '..'])) - 1;
    write_file($comments_dir . '/' . ($file_count + 1), $comment_data);
    header("Location: blog.php?nazwa=${blog_name}");
}

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" media="screen" type="text/css" href="./style.css"/>
    <title>Blog</title>
</head>
<body>
<?php include 'menu.php'; ?>
<?php if ($error): ?>
    <h1>Nie odnaleziono podanego bloga lub artykułu</h1>
<?php else: ?>
    <h1>Dodaj komentarz</h1>
    <?php if (!is_null($info)): ?>
        <div class="info">
            <?php echo $info; ?>
        </div>
    <?php endif; ?>
    <form class="form" action="#" method="post">
        <div class="form__group">
            <label for="type">Typ komentarza:</label>
            <select name="type" id="type">
                <option value="Pozytywny">Pozytywny</option>
                <option value="Negatywny">Negatywny</option>
                <option value="Neutralny">Neutralny</option>
            </select>
        </div>
        <div class="form__group">
            <label for="username">Twój pseudonim:</label>
            <input type="text" id="username" name="username"/>
        </div>
        <div class="form__group">
            <label for="comment">Komentarz:</label>
            <textarea name="comment" id="comment" cols="30" rows="10"></textarea>
        </div>
        <div class="form__group form__group--horizontal">
            <input type="reset" value="Wyczyść formularz"/>
            <input type="submit" value="Dodaj komentarz!"/>
        </div>
    </form>
<?php endif; ?>
</body>
</html>
<?php

$list = false;
$info = NULL;

function blogPath($blog_name)
{
    return realpath('./data') . '/' . $blog_name;
}

function read_file($path)
{
    $content = false;
    $pointer = fopen($path, 'r');

    if (flock($pointer, LOCK_SH)) {
        $content = explode(PHP_EOL, fread($pointer, filesize($path)));
        flock($pointer, LOCK_UN);
    }

    fclose($pointer);
    return $content;
}

function errGen($info)
{
    return "<span id=\"error\">${info}</span>";
}

function getMedia($blog_name, $article_name)
{
    $files = array_diff(scandir(blogPath($blog_name)), ['.', '..', 'info']);

    return array_filter($files, function ($file) use ($article_name) {
        return ($file !== $article_name . '.k') && strpos($file, $article_name) !== false && strpos($file, '.') !== false;
    });
}


function getComments($blog_name, $article_name)
{
    if (!file_exists(blogPath($blog_name) . '/' . $article_name . '.k')) {
        return [];
    }

    $comments = [];
    $comments_dir = blogPath($blog_name) . '/' . $article_name . '.k';
    $comment_count = count(array_diff(scandir($comments_dir), ['.', '..']));

    for ($i = 0; $i < $comment_count; $i++) {
        $comments[$i] = read_file($comments_dir . '/' . $i);
    }

    return $comments;
}

function checkExt($filename)
{
    return strpos($filename, '.') === false;
}

function getArtArr($blog_name)
{
    $files = array_diff(scandir(blogPath($blog_name)), ['.', '..', 'info']);
    return array_filter($files, 'checkExt');
}

if (!isset($_GET['nazwa']) || empty($_GET['nazwa'])) {
    $list = true;
    $blog_list = array_diff(scandir('./data'), ['.', '..']);

} else {
    $blog_name = $_GET['nazwa'];

    if (!file_exists(blogPath($blog_name))) {
        $info = errGen("Blog o nazwie \"${blog_name}\" nie istnieje!");

    } else {
        $blog_dir = realpath('./data') . '/' . $blog_name;
        //get_blog_dir($blog_name);
        $blog_info = read_file($blog_dir . '/info');
        $article_list = getArtArr($blog_name);
    }
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
<?php if ($list): ?>
    <h1>Wyszukiwanie Błogu</h1>
    <ul id="mainBlog">
        <?php
        if (empty($blog_list)) {
            echo '<li>Brak blogów do wyświetlenia</li>';
        } else {
            foreach ($blog_list as $blog_name) {
                echo '<li>';
                echo "<a href=\"?nazwa=${blog_name}\">${blog_name}</a>";
                echo '</li>';
            }
        }
        ?>
    </ul>
<?php else: ?>
    <h1>Blog <?php echo $blog_name; ?></h1>
    <?php if (!is_null($info)): ?>
        <div class="info">
            <?php echo $info; ?>
        </div>
    <?php endif; ?>
    <h4>Opis:</h4>
    <p>

        <?php echo $blog_info[2]; ?>
    </p>

    <h2>Wpisy:</h2>

    <?php

    if (empty($article_list)) {
        echo '<h4>Brak wpisów do wyświetlenia</h4>';
    }

    foreach ($article_list as $article_name) {
        $article = read_file(blogPath($blog_name) . '/' . $article_name);
        $comments = getComments($blog_name, $article_name);
        $media = getMedia($blog_name, $article_name);

        echo '<div class="article">';
        echo '<b>' . $article[0] . '</b>';
        echo '<p>' . $article[1] . '</p>';

        echo '<h4>Załączniki</h4>';
        echo '<ul id=\"zal\">';
        if (empty($media)) {
            echo '<li>Brak załączników</li>';
        } else {
            foreach ($media as $m) {
                $ext = pathinfo($m, PATHINFO_EXTENSION);

                if ($ext == "jpg" || $ext == "png") {
                    echo "<li><a href=\"data/${blog_name}/${m}\"><img id=\"picture\" src=\"data/$blog_name/$m\" alt=\"\" height=\"120\" width=\"180\"/></a></li>";
                } else {
                    echo "<li><a target=\"_blank\" href=\"data/${blog_name}/${m}\">${m}</a></li>";
                }

            }
        }
        echo '</ul>';

        echo '<h4>Komentarze:</h4>';

        if (count($comments) === 0) {
            echo '<i>Brak komentarzy</i><br/>';
        } else {
            foreach ($comments as $comment) {
                echo '<div class="comment">';
                echo '<b>' . $comment[2] . '</b> <br/>';
                echo '<i>' . $comment[0] . '</i> <br/>';
                echo '<span>' . $comment[1] . '</span> <br/>';
                echo '<p>' . $comment[3] . '</p>';
                echo '</div>';
            }
        }

        echo "<a href=\"koment.php?blog=${blog_name}&article=${article_name}\">Dodaj komentarz</a>";
        echo '</div>';
    }
    ?>

<?php endif; ?>
</body>
</html>
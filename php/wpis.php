<?php


$info = NULL;
$current_date = date('Y-m-d');
$current_time = date('H:i');

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

function brGen($text)
{
    return str_replace(["\r\n", "\r", "\n"], '<br/>', $text);
}

function sucGen($info)
{
    return "<span id=\"success\">${info}</span>";
}

function ChmodFunc($path)
{
    chmod($path, 0777);
}

function fileNameGen($date, $hour, $uid)
{
    return str_replace('-', '', $date) . str_replace(':', '', $hour) . date('s') . ($uid < 10 ? "0${uid}" : "${uid}");
}


if (!empty($_POST)) {
    if (!($blog_name = getName($_POST['username'], $_POST['password']))) {
        $info = "<span id=\"error\">nieprawidłowy login lub hasło</span>";

    } else {
        $date = $_POST['date'];
        $time = $_POST['time'];
        $content = brGen($_POST['content']);

        $article_name = getArt($blog_name, $date, $time);
        $article_path = blogPath($blog_name) . '/' . $article_name;

        write_file($article_path, [
            ($date . ', ' . $time),
            $content
        ]);

        ChmodFunc($article_path);
        uploadF($blog_name, $article_name);
        $info = sucGen("dodano \"${blog_name}\"");
    }
}

function getName($username, $password)
{
    $blog_names = array_diff(scandir('./data'), ['.', '..']);

    foreach ($blog_names as $bs) {

        $file_content = file_get_contents("./data/${bs}/info");
        $lines = explode(PHP_EOL, $file_content);
        if ($lines[0] == $username && $lines[1] == md5($password)) {
            return $bs;
        }
    }

    return false;
}

function getArt($blog_name, $date, $hour)
{
    $uId = 0;
    $blog_path = blogPath($blog_name);

    while (file_exists($blog_path . '/' . fileNameGen($date, $hour, $uId))) {
        $uId++;
    }

    return fileNameGen($date, $hour, $uId);
}

function uploadF($blog_name, $article_name)
{
    $blog_path = blogPath($blog_name);

    for ($i = 1; $i <= 3; $i++) {
        if (empty($_FILES["file${i}"]['name']) || $_FILES["file${i}"]['error']) {
            continue;
        }

        $extension = strtolower(pathinfo($_FILES["file${i}"]["name"], PATHINFO_EXTENSION));
        $new_path = $blog_path . '/' . $article_name . $i . '.' . $extension;

        if (!move_uploaded_file($_FILES["file${i}"]["tmp_name"], $new_path)) {
            $info = 'wystąpił błąd';
        }

        ChmodFunc($new_path);
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
<h1>Nowy Wpis</h1>
<?php if (!is_null($info)): ?>

    <?php echo $info; ?>

<?php endif; ?>
<form action="#" method="post" enctype="multipart/form-data">
    <label id="wpis" for="content">Wpis</label><br>
    <textarea name="content" id="content" cols="30" rows="10"></textarea>

    <label for="username">Nazwa użytkownika:</label>
    <input type="text" id="username" name="username"/>

    <label for="password">Hasło:</label>
    <input type="password" id="password" name="password"/>


    <label for="date">Data (RRRR-MM-DD):</label>
    <input type="text" id="date" name="date" value="<?php echo $current_date; ?>"/>

    <label for="time">Czas (GG:MM):</label>
    <input type="text" id="time" name="time" value="<?php echo $current_time; ?>"/>

    <label for="file1">Załącznik 1:</label>
    <input type="file" name="file1"/>

    <label for="file2">Załącznik 2:</label>
    <input type="file" name="file2"/>

    <label for="file3">Załącznik 3:</label>
    <input type="file" name="file3"/>


    <br><br><br>
    <input type="reset" value="Wyczyść formularz"/>
    <input type="submit" value="Dodaj"/>

</form>
</body>
</html>
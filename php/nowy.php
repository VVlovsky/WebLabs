<?php

$info = NULL;

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

function ChmodFunc($path)
{
    chmod($path, 0777);
}

function errGen($info)
{
    return "<span id=\"error\">${info}</span>";
}

function sucGen($info)
{
    return "<span id=\"success\">${info}</span>";
}

if (!empty($_POST)) {
    $blog_name = $_POST['name'];
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $description = brGen($_POST['description']);

    $parent_path = realpath('./data');
    $blog_dir_path = "${parent_path}/${blog_name}";

    if (empty($blog_name) || empty($username) || empty($password) || empty($description)) {
        $info = errGen('Uzupełnij wszystkie pola!');

    } else if (file_exists(blogPath($blog_name))) {
        $info = errGen("Blog o nazwie \"${blog_name}\" już istnieje!");

    } else if (!mkdir($blog_dir_path)) {
        $info = errGen('Wystąpił bląd 1');

    } else {
        ChmodFunc($blog_dir_path);

        $info = [
            $username,
            $password,
            $description
        ];

        if (!write_file("${blog_dir_path}/info", $info)) {
            $info = errGen('Wystąpił bląd 2');
        } else {
            ChmodFunc("${blog_dir_path}/info");
            $info = sucGen("Blog \"${blog_name}\" utworzony");
        }
    }
}
//else{
//    echo "<b>NIE DZIAŁA A MUSI!!!!!!!1111!</b>";
//}

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
<h1>Nowy Błog</h1>
<?php if (!is_null($info)): ?>
    <div>
        <?php echo $info; ?>
    </div>
<?php endif; ?>
<form action="#" method="post">
    <label id="wpis" for="description">Opis bloga:</label><br>
    <textarea name="description" id="description" cols="30" rows="10"></textarea>

    <label for="name">Nazwa bloga:</label>
    <input type="text" id="name" name="name"/>

    <label for="username">Nazwa użytkownika:</label>
    <input type="text" id="username" name="username"/>

    <label for="password">Hasło:</label>
    <input type="password" id="password" name="password"/>

    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

    <input type="reset" value="Wyczyść formularz"/>
    <input type="submit" value="Stwórz"/>

</form>
</body>
</html>
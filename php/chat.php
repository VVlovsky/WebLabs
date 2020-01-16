<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" media="screen" type="text/css" href="./style.css"/>
    <title>Blog - chat</title>
</head>
<body>
<?php include 'menu.php'; ?>

<div class="form chat">
    <div class="chat__group">

        <label for="chat__activate">Online</label>
        <input type="checkbox" id="chat__activate">
    </div>

    <form class="chat__form">
        <div class="form__group">
            <label for="username">Nazwa użytkownika:</label>
            <input id="username" name="username" type="text" disabled>
        </div>
        <div class="form__group">
            <textarea id="message" name="message" class="chat__message" disabled></textarea>
            <label for="message">Wiadomość:</label>

        </div>
        <div class="form__group">
            <button role="submit" class="chat__send" disabled>Wyślij</button>
        </div>
    </form>
    <div class="form__group">
        <textarea class="chat__room" disabled></textarea>
    </div>
</div>

<script src="../js/chat.js"></script>
</body>
</html>
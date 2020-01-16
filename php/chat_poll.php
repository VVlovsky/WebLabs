<?php

session_write_close();
ignore_user_abort(false);

set_time_limit(0);

$file_path = realpath('./chat.txt');
$file_modification_time = filemtime($file_path);
$file_modification_time_current = $file_modification_time;

while ($file_modification_time == $file_modification_time_current && !isset($_GET['fetch'])) {

    memcache_delete();

    $file_modification_time_current = filemtime($file_path);
}

$messages = '';
$pointer = fopen($file_path, 'r');

if (flock($pointer, LOCK_SH)) {
    $messages = fread($pointer, filesize($file_path));
    flock($pointer, LOCK_UN);
}

fclose($pointer);
echo $messages;

?>
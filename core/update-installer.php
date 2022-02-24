<?php

ini_set('max_execution_time', '3000');

$dirs = scandir(__DIR__ . '/tmp');
if (count($dirs) > 2) {
    $src = __DIR__ . '/tmp/' . $dirs[2];
    $dst = __DIR__;
}

function delete_dir($dirPath)
{
    if (file_exists($dirPath)) {
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '{,.}[!.,!..]*', GLOB_MARK | GLOB_BRACE);

        foreach ($files as $file) {
            if (is_dir($file)) {
                delete_dir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }
}

function recurse_copy($src, $dst)
{
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                recurse_copy($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

rename(__DIR__ . '/core/config.php', __DIR__ . '/config.php');
delete_dir(__DIR__ . '/core/app');
delete_dir(__DIR__ . '/core/bootstrap');
delete_dir(__DIR__ . '/core/config');
delete_dir(__DIR__ . '/core/database');
delete_dir(__DIR__ . '/core/resources');
delete_dir(__DIR__ . '/core/tests');
delete_dir(__DIR__ . '/core/vendor');
recurse_copy($src, $dst);
rename(__DIR__ . '/config.php', __DIR__ . '/core/config.php');

if ($_GET['finishUrl']) {
    header('location: ' . $_GET['finishUrl']);
} else {
    header('location: ' . '/admin/update/finish');
}

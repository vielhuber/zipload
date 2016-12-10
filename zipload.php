<?php
// get args
$file = realpath(dirname(__FILE__)) . "/zip.zip";
if (isset($_GET["file"]) && $_GET["file"] != "")
{
    $file = realpath(dirname(__FILE__)) . "/" . strip_tags($_GET["file"]);
}
$folder = realpath(dirname(__FILE__));
if (isset($_GET["folder"]) && $_GET["folder"] != "")
{
    $folder = realpath(dirname(__FILE__)) . "/" . strip_tags($_GET["folder"]);
}


if (isset($_GET["cmd"]) && $_GET["cmd"] == "unzip")
{

    if (!file_exists($file))
    {
        die('file does not exist.');
    }
    if (!is_dir($folder))
    {
        die('folder does not exist.');
    }

    if (file_exists($file))
    {
        $zip = new ZipArchive;
        if ($zip->open($file) === TRUE)
        {
            $zip->extractTo($folder);
            $zip->close();
            die('OK');
        }
    }
}


if (isset($_GET["cmd"]) && $_GET["cmd"] == "zip")
{

    if (!is_dir($folder))
    {
        die('folder does not exist.');
    }
    if (file_exists($file))
    {
        unlink($file);
    }

    $rootPath = realpath($folder);
    $zip = new ZipArchive();
    $zip->open($file, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    foreach ($files as $name => $file)
    {
        if ($name == $_SERVER["SCRIPT_FILENAME"])
        {
            continue;
        }
        if (!$file->isDir())
        {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);
            $zip->addFile($filePath, $relativePath);
        }
    }
    $zip->close();
    die('OK');

}

if (isset($_GET["cmd"]) && $_GET["cmd"] == "delete")
{
    if (!is_dir($folder))
    {
        die('folder does not exist.');
    }
    rrmdir($folder, true);
    die('OK');

}

function rrmdir($dir, $init)
{
    global $file;

    if (is_dir($dir))
    {
        $objects = scandir($dir);
        foreach ($objects as $object)
        {
            if ($init === true && $object == basename($_SERVER["SCRIPT_FILENAME"]))
            {
                continue;
            }
            if ($init === true && $object == substr($file, strrpos($file, "/") + 1))
            {
                continue;
            }
            if ($object != "." && $object != "..")
            {
                if (filetype($dir . "/" . $object) == "dir")
                {
                    rrmdir($dir . "/" . $object, false);
                }
                else
                {
                    unlink($dir . "/" . $object);
                }
            }
        }
        reset($objects);
        // don't delete main dir
        if ($init === false)
        {
            rmdir($dir);
        }
    }
}
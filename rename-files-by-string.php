<?php

$arguments = getopt('d:s:t:ev');

$dir = isset($arguments['d']) ? $arguments['d'] : __DIR__;
$search = $arguments['s'] ?? '';
$to = $arguments['t'] ?? '';
$toIsEmpty = isset($arguments['e']);
$verbose = isset($arguments['v']);
$files = [];
$slash = strstr(__DIR__, '\\') ? '\\' : '/';

if (empty($arguments)) {
    echo help(); die;
}

if ($search === '') {
    echo "Provide parameter -s(string to rename)\n"; die;
} elseif (!$to && !$toIsEmpty) {
    echo "Provide parameter -t(new string)\n"; die;
}

$dirResource = opendir($dir);

while (($file = readdir($dirResource)) !== false) {
    $files[] = $file;
}

foreach ($files as $file) {
    if (strstr($file, $search) !== false) {
        $newName = str_replace($search, $to, $file);

        if (!array_search($newName, $files)) {
            rename($dir . $slash . $file, $dir . $slash . $newName);

            if ($verbose) {
                echo "File $file has been renamed to $newName\n";
            }
        } else {
            echo "File $file can not be renamed to $newName because file with this name already exists\n";
        }
    }
}

closedir($dirResource);

function help(): string
{
    return "This script searches for a given string in filenames of a given directory and changes it to another string.
    \nAvailable parameters:\n-d - directory where the target files are\n-s - string to be changed\n-t - string to be changed to\n-e - if this argument is present, -t can be omitted and new string will be ''\n-v - verbose - every operation is reported\n";
}
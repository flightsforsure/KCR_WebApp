<?php
/**
 * Simple activity logging to a text file.
 * Each entry stored as timestamp|user|action
 */
function log_activity($user, $action)
{
    $line = date('c') . "|$user|$action\n";
    file_put_contents(__DIR__ . '/activity.log', $line, FILE_APPEND);
}

function read_activity()
{
    $file = __DIR__ . '/activity.log';
    if (!file_exists($file)) {
        return [];
    }
    $rows = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $entries = [];
    foreach ($rows as $row) {
        list($time, $user, $action) = explode('|', $row);
        $entries[] = ['time' => $time, 'user' => $user, 'action' => $action];
    }
    return $entries;
}
?>

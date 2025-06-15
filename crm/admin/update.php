<?php
require_once __DIR__ . '/../../google_api/sheet_handler.php';
require_once __DIR__ . '/../../activity_log.php';
require_once __DIR__ . '/../../send_mail.php';
require_once __DIR__ . '/../../config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rowNumber = intval($_POST['row']);
    $status = $_POST['status'] ?? 'New';

    $rows = sheet_get_all();
    if (!isset($rows[$rowNumber-1])) {
        die('Invalid row');
    }
    $rowData = $rows[$rowNumber-1];
    $assignedEmail = $rowData[array_search('assigned_to', $rows[0])];

    // Update status column
    $statusIndex = array_search('lead_status', $rows[0]);
    $rowData[$statusIndex] = $status;
    sheet_update_row($rowNumber, $rowData);

    log_activity($_SESSION['user']['email'], "Updated row $rowNumber status to $status");

    // Send notification to broker
    if ($assignedEmail) {
        send_notification($assignedEmail, '', 'Lead Updated', 'Lead status changed to ' . $status);
    }
}
header('Location: dashboard.php');
exit;
?>

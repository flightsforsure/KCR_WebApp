<?php
require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../../google_api/sheet_handler.php';
require_once __DIR__ . '/../../activity_log.php';

if (!isset($_SESSION['user'])) {
    header('Location: /auth/login.php');
    exit;
}

$userEmail = $_SESSION['user']['email'];
$rows = sheet_get_all();
$headerRow = $rows[0];
$assignedIndex = array_search('assigned_to', $headerRow);
?>
<h1 class="mb-4">My Leads</h1>
<div class="table-responsive">
<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <?php foreach ($headerRow as $col): ?>
            <th><?php echo htmlspecialchars($col); ?></th>
        <?php endforeach; ?>
    </tr>
    </thead>
    <tbody>
    <?php for ($i=1;$i<count($rows);$i++): $row=$rows[$i]; if(($row[$assignedIndex]??'')!==$userEmail) continue; ?>
        <tr>
            <?php foreach ($row as $cell): ?>
                <td><?php echo htmlspecialchars($cell); ?></td>
            <?php endforeach; ?>
        </tr>
    <?php endfor; ?>
    </tbody>
</table>
</div>
<?php require_once __DIR__ . '/../footer.php'; ?>

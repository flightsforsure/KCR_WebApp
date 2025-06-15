<?php
require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../..//google_api/sheet_handler.php';
require_once __DIR__ . '/../../activity_log.php';
require_once __DIR__ . '/../../send_mail.php';

// Only proceed if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: /auth/login.php');
    exit;
}

$rows = sheet_get_all();
?>
<h1 class="mb-4">Admin Dashboard</h1>
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-light">
        <tr>
            <?php foreach ($rows[0] as $col): ?>
                <th><?php echo htmlspecialchars($col); ?></th>
            <?php endforeach; ?>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php for ($i = 1; $i < count($rows); $i++): $row = $rows[$i]; ?>
            <tr>
                <?php foreach ($row as $cell): ?>
                    <td><?php echo htmlspecialchars($cell); ?></td>
                <?php endforeach; ?>
                <td>
                    <form method="post" action="update.php" class="d-flex gap-1">
                        <input type="hidden" name="row" value="<?php echo $i+1; ?>">
                        <select name="status" class="form-select form-select-sm">
                            <?php foreach ($LEAD_STATUSES as $status): ?>
                                <option value="<?php echo $status; ?>"><?php echo $status; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                    </form>
                </td>
            </tr>
        <?php endfor; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/../footer.php'; ?>

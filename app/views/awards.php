<?php
function formatDateForInput($date)
{
    if (!$date) return "";
    $timestamp = strtotime($date);
    if ($timestamp === false) return "";
    return date("Y-m-d", $timestamp);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Awards - ICMS</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>

<body>

    <div class="app-shell">
        <?php $activeMenu = 'awards'; include __DIR__ . '/partials/sidebar.php'; ?>
        <div class="main">
        <?php include __DIR__ . '/partials/topbar.php'; ?>

    <div class="container">

        <div class="header">
            <h1>Awards</h1>
        </div>

        <?php if ($message !== ""): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if ($error !== ""): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="card">

            <?php if ($editAward): ?>
                <h2>Update Award</h2>
                <form method="POST" action="AwardController.php">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="award_id" value="<?php echo $editAward['AWARD_ID']; ?>">
            <?php else: ?>
                <h2>Create New Award</h2>
                <form method="POST" action="AwardController.php">
                    <input type="hidden" name="action" value="create">
            <?php endif; ?>

                <div class="form-grid">

                    <div class="form-group">
                        <label>Award Title</label>
                        <input type="text" name="award_title" required
                               value="<?php echo $editAward ? htmlspecialchars($editAward['AWARD_TITLE']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Prize Amount</label>
                        <input type="number" name="prize_amount" min="0" step="0.01" required
                               value="<?php echo $editAward ? htmlspecialchars($editAward['PRIZE_AMOUNT']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Certificate Number</label>
                        <input type="text" name="certificate_number" required
                               value="<?php echo $editAward ? htmlspecialchars($editAward['CERTIFICATE_NUMBER']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Award Date</label>
                        <input type="date" name="award_date" required
                               value="<?php echo $editAward ? formatDateForInput($editAward['AWARD_DATE']) : ''; ?>">
                    </div>

                    <div class="form-group full">
                        <label>Evaluation</label>
                        <select name="evaluation_id" required>
                            <option value="">-- Select Evaluation --</option>
                            <?php foreach ($evaluations as $ev): ?>
                                <?php $sel = $editAward && (int)$editAward['EVALUATION_ID'] === (int)$ev['EVALUATION_ID']; ?>
                                <option value="<?php echo $ev['EVALUATION_ID']; ?>" <?php echo $sel ? 'selected' : ''; ?>>
                                    Evaluation #<?php echo $ev['EVALUATION_ID']; ?> — Score: <?php echo $ev['SCORE']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>

                <div class="actions">
                    <?php if ($editAward): ?>
                        <button type="submit" class="btn-update">Update Award</button>
                        <a href="AwardController.php" class="btn-cancel">Cancel</a>
                    <?php else: ?>
                        <button type="submit" class="btn-primary">Create Award</button>
                    <?php endif; ?>
                </div>

            </form>

        </div>

        <div class="card">

            <h2>All Awards</h2>

            <?php if (count($awards) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Project</th>
                            <th>Team</th>
                            <th>Prize</th>
                            <th>Certificate No.</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($awards as $a): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($a['AWARD_ID']); ?></td>
                                <td><?php echo htmlspecialchars($a['AWARD_TITLE']); ?></td>
                                <td><?php echo htmlspecialchars($a['PROJECT_TITLE']); ?></td>
                                <td><?php echo htmlspecialchars($a['TEAM_NAME']); ?></td>
                                <td><?php echo number_format($a['PRIZE_AMOUNT'], 2); ?></td>
                                <td><?php echo htmlspecialchars($a['CERTIFICATE_NUMBER']); ?></td>
                                <td><?php echo date('d-m-Y', strtotime($a['AWARD_DATE'])); ?></td>
                                <td>
                                    <div class="table-actions">
                                        <a href="AwardController.php?edit=<?php echo $a['AWARD_ID']; ?>">
                                            <button type="button" class="btn-update">Edit</button>
                                        </a>
                                        <form method="POST" action="AwardController.php" class="inline-form"
                                              onsubmit="return confirm('Delete this award?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="award_id" value="<?php echo $a['AWARD_ID']; ?>">
                                            <button type="submit" class="btn-delete">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No awards found.</p>
            <?php endif; ?>

        </div>

    </div>

        </div>
    </div>

</body>

</html>

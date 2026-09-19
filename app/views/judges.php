<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Judges - ICMS</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>

<body>

    <div class="app-shell">
        <?php $activeMenu = 'judges'; include __DIR__ . '/partials/sidebar.php'; ?>
        <div class="main">
        <?php include __DIR__ . '/partials/topbar.php'; ?>

    <div class="container">

        <div class="header">
            <h1>Manage Judges</h1>
        </div>

        <?php if ($message !== ""): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if ($error !== ""): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="card">

            <?php if ($editJudge): ?>
                <h2>Update Judge</h2>
                <form method="POST" action="JudgeController.php">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="judge_id" value="<?php echo $editJudge['JUDGE_ID']; ?>">
            <?php else: ?>
                <h2>Add New Judge</h2>
                <form method="POST" action="JudgeController.php">
                    <input type="hidden" name="action" value="create">
            <?php endif; ?>

                <div class="form-grid">

                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" required
                               value="<?php echo $editJudge ? htmlspecialchars($editJudge['NAME']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Designation</label>
                        <input type="text" name="designation" required
                               value="<?php echo $editJudge ? htmlspecialchars($editJudge['DESIGNATION']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Organization</label>
                        <input type="text" name="organization" required
                               value="<?php echo $editJudge ? htmlspecialchars($editJudge['ORGANIZATION']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Specialization</label>
                        <input type="text" name="specialization" required
                               value="<?php echo $editJudge ? htmlspecialchars($editJudge['SPECIALIZATION']) : ''; ?>">
                    </div>

                </div>

                <div class="actions">
                    <?php if ($editJudge): ?>
                        <button type="submit" class="btn-update">Update Judge</button>
                        <a href="JudgeController.php" class="btn-cancel">Cancel</a>
                    <?php else: ?>
                        <button type="submit" class="btn-primary">Add Judge</button>
                    <?php endif; ?>
                </div>

            </form>

        </div>

        <?php if ($editJudge): ?>

            <div class="card">
                <h2>Contact Info — <?php echo htmlspecialchars($editJudge['NAME']); ?></h2>

                <h3>Emails</h3>
                <div class="chip-list">
                    <?php foreach ($judgeEmails as $e): ?>
                        <div class="chip">
                            <?php echo htmlspecialchars($e['EMAIL']); ?>
                            <form method="POST" action="ContactController.php"
                                  onsubmit="return confirm('Remove this email?');">
                                <input type="hidden" name="type" value="judge">
                                <input type="hidden" name="entity_id" value="<?php echo $editJudge['JUDGE_ID']; ?>">
                                <input type="hidden" name="action" value="delete_email">
                                <input type="hidden" name="email_id" value="<?php echo $e['EMAIL_ID']; ?>">
                                <button type="submit">&times;</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                    <?php if (count($judgeEmails) === 0): ?>
                        <span class="muted">No emails added yet.</span>
                    <?php endif; ?>
                </div>
                <form method="POST" action="ContactController.php" class="form-grid" style="grid-template-columns: 2fr 1fr;">
                    <input type="hidden" name="type" value="judge">
                    <input type="hidden" name="entity_id" value="<?php echo $editJudge['JUDGE_ID']; ?>">
                    <input type="hidden" name="action" value="add_email">
                    <div class="form-group">
                        <input type="email" name="email" placeholder="new.email@example.com" required>
                    </div>
                    <button type="submit" class="btn-primary">Add Email</button>
                </form>

                <h3 style="margin-top: 25px;">Phone Numbers</h3>
                <div class="chip-list">
                    <?php foreach ($judgePhones as $p): ?>
                        <div class="chip">
                            <?php echo htmlspecialchars($p['PHONE']); ?>
                            <form method="POST" action="ContactController.php"
                                  onsubmit="return confirm('Remove this phone number?');">
                                <input type="hidden" name="type" value="judge">
                                <input type="hidden" name="entity_id" value="<?php echo $editJudge['JUDGE_ID']; ?>">
                                <input type="hidden" name="action" value="delete_phone">
                                <input type="hidden" name="phone_id" value="<?php echo $p['PHONE_ID']; ?>">
                                <button type="submit">&times;</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                    <?php if (count($judgePhones) === 0): ?>
                        <span class="muted">No phone numbers added yet.</span>
                    <?php endif; ?>
                </div>
                <form method="POST" action="ContactController.php" class="form-grid" style="grid-template-columns: 2fr 1fr;">
                    <input type="hidden" name="type" value="judge">
                    <input type="hidden" name="entity_id" value="<?php echo $editJudge['JUDGE_ID']; ?>">
                    <input type="hidden" name="action" value="add_phone">
                    <div class="form-group">
                        <input type="text" name="phone" placeholder="+880 1XXXXXXXXX" required>
                    </div>
                    <button type="submit" class="btn-primary">Add Phone</button>
                </form>

            </div>

        <?php endif; ?>

        <div class="card">

            <h2>All Judges</h2>

            <?php if (count($judges) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Organization</th>
                            <th>Specialization</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($judges as $j): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($j['JUDGE_ID']); ?></td>
                                <td><?php echo htmlspecialchars($j['NAME']); ?></td>
                                <td><?php echo htmlspecialchars($j['DESIGNATION']); ?></td>
                                <td><?php echo htmlspecialchars($j['ORGANIZATION']); ?></td>
                                <td><?php echo htmlspecialchars($j['SPECIALIZATION']); ?></td>
                                <td>
                                    <div class="table-actions">
                                        <a href="JudgeController.php?edit=<?php echo $j['JUDGE_ID']; ?>">
                                            <button type="button" class="btn-update">Edit</button>
                                        </a>
                                        <form method="POST" action="JudgeController.php" class="inline-form"
                                              onsubmit="return confirm('Delete this judge?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="judge_id" value="<?php echo $j['JUDGE_ID']; ?>">
                                            <button type="submit" class="btn-delete">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No judges found.</p>
            <?php endif; ?>

        </div>

    </div>

        </div>
    </div>

</body>

</html>

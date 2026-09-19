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
    <title>Project Submissions - ICMS</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>

<body>

    <div class="app-shell">
        <?php $activeMenu = 'submissions'; include __DIR__ . '/partials/sidebar.php'; ?>
        <div class="main">
        <?php include __DIR__ . '/partials/topbar.php'; ?>

    <div class="container">

        <div class="header">
            <h1>Project Submissions</h1>
        </div>

        <?php if ($message !== ""): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if ($error !== ""): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="card">

            <?php if ($editSubmission): ?>
                <h2>Update Submission</h2>
                <form method="POST" action="ProjectSubmissionController.php">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="submission_id" value="<?php echo $editSubmission['SUBMISSION_ID']; ?>">
            <?php else: ?>
                <h2>New Project Submission</h2>
                <form method="POST" action="ProjectSubmissionController.php">
                    <input type="hidden" name="action" value="create">
            <?php endif; ?>

                <div class="form-grid">

                    <div class="form-group">
                        <label>Project Title</label>
                        <input type="text" name="project_title" required
                               value="<?php echo $editSubmission ? htmlspecialchars($editSubmission['PROJECT_TITLE']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Category</label>
                        <input type="text" name="project_category" required
                               value="<?php echo $editSubmission ? htmlspecialchars($editSubmission['PROJECT_CATEGORY']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Submission Date</label>
                        <input type="date" name="submission_date" required
                               value="<?php echo $editSubmission ? formatDateForInput($editSubmission['SUBMISSION_DATE']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <?php $st = $editSubmission ? $editSubmission['SUBMISSION_STATUS'] : 'Pending'; ?>
                        <select name="submission_status" required>
                            <?php foreach ($statuses as $s): ?>
                                <option value="<?php echo $s; ?>" <?php echo $st === $s ? 'selected' : ''; ?>><?php echo $s; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Team</label>
                        <select name="team_id" required>
                            <option value="">-- Select Team --</option>
                            <?php foreach ($teams as $team): ?>
                                <?php $sel = $editSubmission && (int)$editSubmission['TEAM_ID'] === (int)$team['TEAM_ID']; ?>
                                <option value="<?php echo $team['TEAM_ID']; ?>" <?php echo $sel ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($team['TEAM_NAME']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Challenge</label>
                        <select name="challenge_id" required>
                            <option value="">-- Select Challenge --</option>
                            <?php foreach ($challenges as $challenge): ?>
                                <?php $sel = $editSubmission && (int)$editSubmission['CHALLENGE_ID'] === (int)$challenge['CHALLENGE_ID']; ?>
                                <option value="<?php echo $challenge['CHALLENGE_ID']; ?>" <?php echo $sel ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($challenge['TITLE']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group full">
                        <label>Repository Link</label>
                        <input type="url" name="repository_link" placeholder="https://github.com/..."
                               value="<?php echo $editSubmission ? htmlspecialchars($editSubmission['REPOSITORY_LINK']) : ''; ?>">
                    </div>

                    <div class="form-group full">
                        <label>Project Description</label>
                        <textarea name="project_description" rows="4" required><?php
                            echo $editSubmission ? htmlspecialchars($editSubmission['PROJECT_DESCRIPTION']) : '';
                        ?></textarea>
                    </div>

                </div>

                <div class="actions">
                    <?php if ($editSubmission): ?>
                        <button type="submit" class="btn-update">Update Submission</button>
                        <a href="ProjectSubmissionController.php" class="btn-cancel">Cancel</a>
                    <?php else: ?>
                        <button type="submit" class="btn-primary">Submit Project</button>
                    <?php endif; ?>
                </div>

            </form>

        </div>

        <div class="card">

            <h2>All Submissions</h2>

            <?php if (count($submissions) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Team</th>
                            <th>Challenge</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($submissions as $s): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($s['SUBMISSION_ID']); ?></td>
                                <td><?php echo htmlspecialchars($s['PROJECT_TITLE']); ?></td>
                                <td><?php echo htmlspecialchars($s['PROJECT_CATEGORY']); ?></td>
                                <td><?php echo htmlspecialchars($s['TEAM_NAME']); ?></td>
                                <td><?php echo htmlspecialchars($s['CHALLENGE_TITLE']); ?></td>
                                <td><?php echo date('d-m-Y', strtotime($s['SUBMISSION_DATE'])); ?></td>
                                <td class="status"><?php echo htmlspecialchars($s['SUBMISSION_STATUS']); ?></td>
                                <td>
                                    <div class="table-actions">
                                        <a href="ProjectSubmissionController.php?edit=<?php echo $s['SUBMISSION_ID']; ?>">
                                            <button type="button" class="btn-update">Edit</button>
                                        </a>
                                        <form method="POST" action="ProjectSubmissionController.php" class="inline-form"
                                              onsubmit="return confirm('Delete this submission?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="submission_id" value="<?php echo $s['SUBMISSION_ID']; ?>">
                                            <button type="submit" class="btn-delete">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No submissions found.</p>
            <?php endif; ?>

        </div>

    </div>

        </div>
    </div>

</body>

</html>

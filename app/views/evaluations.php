<?php
function formatDateForInput($date)
{
    if (!$date) return "";
    $timestamp = strtotime($date);
    if ($timestamp === false) return "";
    return date("Y-m-d", $timestamp);
}

$recommendations = array('Strongly Recommend', 'Recommend', 'Neutral', 'Not Recommended');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluations - ICMS</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>

<body>

    <div class="app-shell">
        <?php $activeMenu = 'evaluations'; include __DIR__ . '/partials/sidebar.php'; ?>
        <div class="main">
        <?php include __DIR__ . '/partials/topbar.php'; ?>

    <div class="container">

        <div class="header">
            <h1>Evaluations</h1>
        </div>

        <?php if ($message !== ""): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if ($error !== ""): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="card">

            <?php if ($editEvaluation): ?>
                <h2>Update Evaluation</h2>
                <form method="POST" action="EvaluationController.php">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="evaluation_id" value="<?php echo $editEvaluation['EVALUATION_ID']; ?>">
            <?php else: ?>
                <h2>Record New Evaluation</h2>
                <form method="POST" action="EvaluationController.php">
                    <input type="hidden" name="action" value="create">
            <?php endif; ?>

                <div class="form-grid">

                    <div class="form-group">
                        <label>Submission</label>
                        <select name="submission_id" required>
                            <option value="">-- Select Submission --</option>
                            <?php foreach ($submissions as $sub): ?>
                                <?php $sel = $editEvaluation && (int)$editEvaluation['SUBMISSION_ID'] === (int)$sub['SUBMISSION_ID']; ?>
                                <option value="<?php echo $sub['SUBMISSION_ID']; ?>" <?php echo $sel ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($sub['PROJECT_TITLE']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Judge</label>
                        <select name="judge_id" required>
                            <option value="">-- Select Judge --</option>
                            <?php foreach ($judges as $judge): ?>
                                <?php $sel = $editEvaluation && (int)$editEvaluation['JUDGE_ID'] === (int)$judge['JUDGE_ID']; ?>
                                <option value="<?php echo $judge['JUDGE_ID']; ?>" <?php echo $sel ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($judge['NAME']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Score (0-100)</label>
                        <input type="number" name="score" min="0" max="100" step="0.01" required
                               value="<?php echo $editEvaluation ? htmlspecialchars($editEvaluation['SCORE']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Evaluation Date</label>
                        <input type="date" name="evaluation_date" required
                               value="<?php echo $editEvaluation ? formatDateForInput($editEvaluation['EVALUATION_DATE']) : ''; ?>">
                    </div>

                    <div class="form-group full">
                        <label>Recommendation</label>
                        <?php $rec = $editEvaluation ? $editEvaluation['RECOMMENDATIONS'] : ''; ?>
                        <select name="recommendations">
                            <option value="">-- Select Recommendation --</option>
                            <?php foreach ($recommendations as $r): ?>
                                <option value="<?php echo $r; ?>" <?php echo $rec === $r ? 'selected' : ''; ?>><?php echo $r; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group full">
                        <label>Comments</label>
                        <textarea name="comments" rows="3"><?php
                            echo $editEvaluation ? htmlspecialchars($editEvaluation['COMMENTS']) : '';
                        ?></textarea>
                    </div>

                </div>

                <div class="actions">
                    <?php if ($editEvaluation): ?>
                        <button type="submit" class="btn-update">Update Evaluation</button>
                        <a href="EvaluationController.php" class="btn-cancel">Cancel</a>
                    <?php else: ?>
                        <button type="submit" class="btn-primary">Submit Evaluation</button>
                    <?php endif; ?>
                </div>

            </form>

        </div>

        <div class="card">

            <h2>All Evaluations</h2>

            <?php if (count($evaluations) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Project</th>
                            <th>Judge</th>
                            <th>Score</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($evaluations as $e): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($e['EVALUATION_ID']); ?></td>
                                <td><?php echo htmlspecialchars($e['PROJECT_TITLE']); ?></td>
                                <td><?php echo htmlspecialchars($e['JUDGE_NAME']); ?></td>
                                <td><?php echo htmlspecialchars($e['SCORE']); ?> / 100</td>
                                <td><?php echo date('d-m-Y', strtotime($e['EVALUATION_DATE'])); ?></td>
                                <td>
                                    <div class="table-actions">
                                        <a href="EvaluationController.php?edit=<?php echo $e['EVALUATION_ID']; ?>">
                                            <button type="button" class="btn-update">Edit</button>
                                        </a>
                                        <form method="POST" action="EvaluationController.php" class="inline-form"
                                              onsubmit="return confirm('Delete this evaluation?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="evaluation_id" value="<?php echo $e['EVALUATION_ID']; ?>">
                                            <button type="submit" class="btn-delete">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No evaluations found.</p>
            <?php endif; ?>

        </div>

    </div>

        </div>
    </div>

</body>

</html>

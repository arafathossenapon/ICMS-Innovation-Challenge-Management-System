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
    <title>Manage Teams - ICMS</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>

<body>

    <div class="app-shell">
        <?php $activeMenu = 'teams'; include __DIR__ . '/partials/sidebar.php'; ?>
        <div class="main">
        <?php include __DIR__ . '/partials/topbar.php'; ?>

    <div class="container">

        <div class="header">
            <h1>Manage Teams</h1>
        </div>

        <?php if ($message !== ""): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if ($error !== ""): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="card">

            <?php if ($editTeam): ?>
                <h2>Update Team</h2>
                <form method="POST" action="TeamController.php">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="team_id" value="<?php echo $editTeam['TEAM_ID']; ?>">
            <?php else: ?>
                <h2>Register New Team</h2>
                <form method="POST" action="TeamController.php">
                    <input type="hidden" name="action" value="create">
            <?php endif; ?>

                <div class="form-grid">

                    <div class="form-group">
                        <label>Team Name</label>
                        <input type="text" name="team_name" required
                               value="<?php echo $editTeam ? htmlspecialchars($editTeam['TEAM_NAME']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Creation Date</label>
                        <input type="date" name="creation_date" required
                               value="<?php echo $editTeam ? formatDateForInput($editTeam['CREATION_DATE']) : ''; ?>">
                    </div>

                    <div class="form-group full">
                        <label>University</label>
                        <input type="text" name="university" required
                               value="<?php echo $editTeam ? htmlspecialchars($editTeam['UNIVERSITY']) : ''; ?>">
                    </div>

                </div>

                <div class="actions">
                    <?php if ($editTeam): ?>
                        <button type="submit" class="btn-update">Update Team</button>
                        <a href="TeamController.php" class="btn-cancel">Cancel</a>
                    <?php else: ?>
                        <button type="submit" class="btn-primary">Create Team</button>
                    <?php endif; ?>
                </div>

            </form>

        </div>

        <div class="card">

            <h2>All Teams</h2>

            <?php if (count($teams) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Team Name</th>
                            <th>Creation Date</th>
                            <th>University</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($teams as $team): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($team['TEAM_ID']); ?></td>
                                <td><?php echo htmlspecialchars($team['TEAM_NAME']); ?></td>
                                <td><?php echo date('d-m-Y', strtotime($team['CREATION_DATE'])); ?></td>
                                <td><?php echo htmlspecialchars($team['UNIVERSITY']); ?></td>
                                <td>
                                    <div class="table-actions">

                                        <a href="../controllers/ParticipantController.php?team_id=<?php echo $team['TEAM_ID']; ?>">
                                            <button type="button" class="btn-secondary">Members</button>
                                        </a>

                                        <a href="TeamController.php?edit=<?php echo $team['TEAM_ID']; ?>">
                                            <button type="button" class="btn-update">Edit</button>
                                        </a>

                                        <form method="POST" action="TeamController.php" class="inline-form"
                                              onsubmit="return confirm('Delete this team? This will fail if the team still has participants, submissions or participation records.');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="team_id" value="<?php echo $team['TEAM_ID']; ?>">
                                            <button type="submit" class="btn-delete">Delete</button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No teams found.</p>
            <?php endif; ?>

        </div>

    </div>

        </div>
    </div>

</body>

</html>

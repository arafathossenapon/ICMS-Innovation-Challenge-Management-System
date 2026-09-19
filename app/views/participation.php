<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Participation - ICMS</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>

<body>

    <div class="app-shell">
        <?php $activeMenu = 'participation'; include __DIR__ . '/partials/sidebar.php'; ?>
        <div class="main">
        <?php include __DIR__ . '/partials/topbar.php'; ?>

    <div class="container">

        <div class="header">
            <h1>Manage Participation</h1>
        </div>

        <p class="muted">Assign a team to a challenge. A team can join multiple challenges.</p>

        <?php if ($message !== ""): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if ($error !== ""): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="card">

            <h2>Register Team for a Challenge</h2>

            <form method="POST" action="ParticipationController.php">
                <input type="hidden" name="action" value="create">

                <div class="form-grid">

                    <div class="form-group">
                        <label>Team</label>
                        <select name="team_id" required>
                            <option value="">-- Select Team --</option>
                            <?php foreach ($teams as $team): ?>
                                <option value="<?php echo $team['TEAM_ID']; ?>">
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
                                <option value="<?php echo $challenge['CHALLENGE_ID']; ?>">
                                    <?php echo htmlspecialchars($challenge['TITLE']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>

                <div class="actions">
                    <button type="submit" class="btn-primary">Register</button>
                </div>

            </form>

        </div>

        <div class="card">

            <h2>All Participations</h2>

            <?php if (count($participations) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Team</th>
                            <th>Challenge</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($participations as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['TEAM_NAME']); ?></td>
                                <td><?php echo htmlspecialchars($row['CHALLENGE_TITLE']); ?></td>
                                <td>
                                    <form method="POST" action="ParticipationController.php" class="inline-form"
                                          onsubmit="return confirm('Remove this team from the challenge?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="team_id" value="<?php echo $row['TEAM_ID']; ?>">
                                        <input type="hidden" name="challenge_id" value="<?php echo $row['CHALLENGE_ID']; ?>">
                                        <button type="submit" class="btn-delete">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No participation records found.</p>
            <?php endif; ?>

        </div>

    </div>

        </div>
    </div>

</body>

</html>

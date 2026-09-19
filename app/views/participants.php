<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Participants - ICMS</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>

<body>

    <div class="app-shell">
        <?php $activeMenu = 'participants'; include __DIR__ . '/partials/sidebar.php'; ?>
        <div class="main">
        <?php include __DIR__ . '/partials/topbar.php'; ?>

    <div class="container">

        <div class="header">
            <h1>
                Manage Participants
                <?php if ($filterTeamId): ?>
                    <span class="badge">Filtered by team</span>
                <?php endif; ?>
            </h1>
            <?php if ($filterTeamId): ?>
                <a href="ParticipantController.php" class="btn-cancel">Clear filter</a>
            <?php endif; ?>
        </div>

        <?php if ($message !== ""): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if ($error !== ""): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="card">

            <?php if ($editParticipant): ?>
                <h2>Update Participant</h2>
                <form method="POST" action="ParticipantController.php">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="participant_id" value="<?php echo $editParticipant['PARTICIPANT_ID']; ?>">
            <?php else: ?>
                <h2>Add New Participant</h2>
                <form method="POST" action="ParticipantController.php">
                    <input type="hidden" name="action" value="create">
            <?php endif; ?>

                <div class="form-grid">

                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" required
                               value="<?php echo $editParticipant ? htmlspecialchars($editParticipant['NAME']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Department</label>
                        <input type="text" name="department" required
                               value="<?php echo $editParticipant ? htmlspecialchars($editParticipant['DEPARTMENT']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>University ID</label>
                        <input type="text" name="university_id" required
                               value="<?php echo $editParticipant ? htmlspecialchars($editParticipant['UNIVERSITY_ID']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Gender</label>
                        <?php $g = $editParticipant ? $editParticipant['GENDER'] : 'Male'; ?>
                        <select name="gender" required>
                            <option value="Male" <?php echo $g === 'Male' ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo $g === 'Female' ? 'selected' : ''; ?>>Female</option>
                            <option value="Other" <?php echo $g === 'Other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>

                    <div class="form-group full">
                        <label>Team</label>
                        <select name="team_id" required>
                            <option value="">-- Select Team --</option>
                            <?php foreach ($teams as $team): ?>
                                <?php
                                $selected = $editParticipant && (int)$editParticipant['TEAM_ID'] === (int)$team['TEAM_ID'];
                                ?>
                                <option value="<?php echo $team['TEAM_ID']; ?>" <?php echo $selected ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($team['TEAM_NAME']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>

                <div class="actions">
                    <?php if ($editParticipant): ?>
                        <button type="submit" class="btn-update">Update Participant</button>
                        <a href="ParticipantController.php" class="btn-cancel">Cancel</a>
                    <?php else: ?>
                        <button type="submit" class="btn-primary">Add Participant</button>
                    <?php endif; ?>
                </div>

            </form>

        </div>

        <?php if ($editParticipant): ?>

            <div class="card">
                <h2>Contact Info — <?php echo htmlspecialchars($editParticipant['NAME']); ?></h2>

                <h3>Emails</h3>
                <div class="chip-list">
                    <?php foreach ($participantEmails as $e): ?>
                        <div class="chip">
                            <?php echo htmlspecialchars($e['EMAIL']); ?>
                            <form method="POST" action="ContactController.php"
                                  onsubmit="return confirm('Remove this email?');">
                                <input type="hidden" name="type" value="participant">
                                <input type="hidden" name="entity_id" value="<?php echo $editParticipant['PARTICIPANT_ID']; ?>">
                                <input type="hidden" name="action" value="delete_email">
                                <input type="hidden" name="email_id" value="<?php echo $e['EMAIL_ID']; ?>">
                                <button type="submit">&times;</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                    <?php if (count($participantEmails) === 0): ?>
                        <span class="muted">No emails added yet.</span>
                    <?php endif; ?>
                </div>
                <form method="POST" action="ContactController.php" class="form-grid" style="grid-template-columns: 2fr 1fr;">
                    <input type="hidden" name="type" value="participant">
                    <input type="hidden" name="entity_id" value="<?php echo $editParticipant['PARTICIPANT_ID']; ?>">
                    <input type="hidden" name="action" value="add_email">
                    <div class="form-group">
                        <input type="email" name="email" placeholder="new.email@example.com" required>
                    </div>
                    <button type="submit" class="btn-primary">Add Email</button>
                </form>

                <h3 style="margin-top: 25px;">Phone Numbers</h3>
                <div class="chip-list">
                    <?php foreach ($participantPhones as $p): ?>
                        <div class="chip">
                            <?php echo htmlspecialchars($p['PHONE']); ?>
                            <form method="POST" action="ContactController.php"
                                  onsubmit="return confirm('Remove this phone number?');">
                                <input type="hidden" name="type" value="participant">
                                <input type="hidden" name="entity_id" value="<?php echo $editParticipant['PARTICIPANT_ID']; ?>">
                                <input type="hidden" name="action" value="delete_phone">
                                <input type="hidden" name="phone_id" value="<?php echo $p['PHONE_ID']; ?>">
                                <button type="submit">&times;</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                    <?php if (count($participantPhones) === 0): ?>
                        <span class="muted">No phone numbers added yet.</span>
                    <?php endif; ?>
                </div>
                <form method="POST" action="ContactController.php" class="form-grid" style="grid-template-columns: 2fr 1fr;">
                    <input type="hidden" name="type" value="participant">
                    <input type="hidden" name="entity_id" value="<?php echo $editParticipant['PARTICIPANT_ID']; ?>">
                    <input type="hidden" name="action" value="add_phone">
                    <div class="form-group">
                        <input type="text" name="phone" placeholder="+880 1XXXXXXXXX" required>
                    </div>
                    <button type="submit" class="btn-primary">Add Phone</button>
                </form>

            </div>

        <?php endif; ?>

        <div class="card">

            <h2>All Participants</h2>

            <?php if (count($participants) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>University ID</th>
                            <th>Gender</th>
                            <th>Team</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($participants as $p): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($p['PARTICIPANT_ID']); ?></td>
                                <td><?php echo htmlspecialchars($p['NAME']); ?></td>
                                <td><?php echo htmlspecialchars($p['DEPARTMENT']); ?></td>
                                <td><?php echo htmlspecialchars($p['UNIVERSITY_ID']); ?></td>
                                <td><?php echo htmlspecialchars($p['GENDER']); ?></td>
                                <td><?php echo htmlspecialchars($p['TEAM_NAME']); ?></td>
                                <td>
                                    <div class="table-actions">
                                        <a href="ParticipantController.php?edit=<?php echo $p['PARTICIPANT_ID']; ?>">
                                            <button type="button" class="btn-update">Edit</button>
                                        </a>
                                        <form method="POST" action="ParticipantController.php" class="inline-form"
                                              onsubmit="return confirm('Delete this participant?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="participant_id" value="<?php echo $p['PARTICIPANT_ID']; ?>">
                                            <button type="submit" class="btn-delete">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No participants found.</p>
            <?php endif; ?>

        </div>

    </div>

        </div>
    </div>

</body>

</html>

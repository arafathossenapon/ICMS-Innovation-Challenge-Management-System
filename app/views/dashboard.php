<?php

require_once __DIR__ . '/../core/Auth.php';

Auth::requireLogin();

$user = Auth::user();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ICMS</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>

<body>

    <div class="app-shell">
        <?php $activeMenu = 'dashboard'; include __DIR__ . '/partials/sidebar.php'; ?>
        <div class="main">
        <?php include __DIR__ . '/partials/topbar.php'; ?>


    <div class="container">

        <!-- ================================
             WELCOME
        ================================= -->

        <div class="welcome">

            <h1>Innovation Challenge Management System</h1>

            <p>
                Welcome, <strong><?php echo htmlspecialchars($user['username']); ?></strong>
            </p>

            <p>User ID: <?php echo htmlspecialchars($user['user_id']); ?></p>

            <p>
                Role: <span class="role"><?php echo htmlspecialchars($user['role']); ?></span>
            </p>

        </div>


        <!-- ================================
             ORGANIZER
        ================================= -->

        <?php if ($user['role'] === 'Organizer'): ?>

            <h2>Organizer Dashboard</h2>

            <div class="cards">

                <div class="card">
                    <h3>Challenges</h3>
                    <p>Create, update, delete and manage innovation challenges.</p>
                    <a href="../controllers/ChallengeController.php">Manage Challenges</a>
                </div>

                <div class="card">
                    <h3>Teams</h3>
                    <p>Register and manage participating teams.</p>
                    <a href="../controllers/TeamController.php">Manage Teams</a>
                </div>

                <div class="card">
                    <h3>Participants</h3>
                    <p>Manage team members and their contact details.</p>
                    <a href="../controllers/ParticipantController.php">Manage Participants</a>
                </div>

                <div class="card">
                    <h3>Participation</h3>
                    <p>Assign teams to challenges.</p>
                    <a href="../controllers/ParticipationController.php">Manage Participation</a>
                </div>

                <div class="card">
                    <h3>Project Submissions</h3>
                    <p>View and manage submitted projects.</p>
                    <a href="../controllers/ProjectSubmissionController.php">Manage Submissions</a>
                </div>

                <div class="card">
                    <h3>Judges</h3>
                    <p>Manage judges and their contact details.</p>
                    <a href="../controllers/JudgeController.php">Manage Judges</a>
                </div>

                <div class="card">
                    <h3>Evaluations</h3>
                    <p>Record and manage project evaluations.</p>
                    <a href="../controllers/EvaluationController.php">Manage Evaluations</a>
                </div>

                <div class="card">
                    <h3>Awards</h3>
                    <p>Manage challenge awards and certificates.</p>
                    <a href="../controllers/AwardController.php">Manage Awards</a>
                </div>

            </div>

        <?php else: ?>

            <p class="muted">No dashboard modules are configured for this role yet.</p>

        <?php endif; ?>

    </div>
        </div>
    </div>

</body>

</html>

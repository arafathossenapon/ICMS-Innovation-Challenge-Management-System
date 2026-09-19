<?php

// Expects $activeMenu to be set by the including view
// Example: 'dashboard', 'teams', 'awards', etc.

if (!isset($activeMenu)) {
    $activeMenu = '';
}

$menuItems = array(

    'dashboard' => array(
        'label' => 'Dashboard',
        'icon'  => '🏠',
        'href'  => '../views/dashboard.php'
    ),

    'challenges' => array(
        'label' => 'Challenges',
        'icon'  => '🚀',
        'href'  => '../controllers/ChallengeController.php'
    ),

    'teams' => array(
        'label' => 'Teams',
        'icon'  => '👥',
        'href'  => '../controllers/TeamController.php'
    ),

    'participants' => array(
        'label' => 'Participants',
        'icon'  => '👤',
        'href'  => '../controllers/ParticipantController.php'
    ),

    'participation' => array(
        'label' => 'Participation',
        'icon'  => '🔗',
        'href'  => '../controllers/ParticipationController.php'
    ),

    'submissions' => array(
        'label' => 'Project Submissions',
        'icon'  => '📄',
        'href'  => '../controllers/ProjectSubmissionController.php'
    ),

    'judges' => array(
        'label' => 'Judges',
        'icon'  => '⚖️',
        'href'  => '../controllers/JudgeController.php'
    ),

    'evaluations' => array(
        'label' => 'Evaluations',
        'icon'  => '📝',
        'href'  => '../controllers/EvaluationController.php'
    ),

    'awards' => array(
        'label' => 'Awards',
        'icon'  => '🏆',
        'href'  => '../controllers/AwardController.php'
    )

);

?>

<div class="sidebar">

    <!-- Sidebar Brand -->
    <div class="sidebar-brand">

        <div class="logo-badge">
            💡
        </div>

        <div class="brand-text">

            <h2>ICMS</h2>

            <span>Admin Portal</span>

        </div>

    </div>


    <!-- Sidebar Navigation -->
    <div class="sidebar-nav">

        <?php foreach ($menuItems as $key => $item): ?>

            <a
                href="<?php echo $item['href']; ?>"
                class="<?php echo $activeMenu === $key ? 'active' : ''; ?>"
            >

                <span class="nav-icon">
                    <?php echo $item['icon']; ?>
                </span>

                <span>
                    <?php echo $item['label']; ?>
                </span>

            </a>

        <?php endforeach; ?>

    </div>


    <!-- New Challenge Button -->
    <a
        href="../controllers/ChallengeController.php"
        class="sidebar-cta"
    >
        + New Challenge
    </a>


    <!-- Sidebar Footer -->
    <div class="sidebar-footer">

        <div class="avatar small">

            <?php
            echo strtoupper(
                substr($_SESSION['username'], 0, 1)
            );
            ?>

        </div>


        <span style="font-size: 13px; font-weight: bold;">

            <?php
            echo htmlspecialchars($_SESSION['username']);
            ?>

        </span>


        <a
            href="../controllers/LogoutController.php"
            class="logout-link"
        >
            Logout
        </a>

    </div>

</div>
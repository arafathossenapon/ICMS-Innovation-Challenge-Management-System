<?php
if (!isset($pageTitle)) {
    $pageTitle = "";
}
?>
<div class="topbar">

    <div class="topbar-search">🔍 Search challenges, teams, or participants...</div>

    <div class="topbar-right">
        <span class="topbar-icon">🔔</span>
        <span class="topbar-icon">❓</span>
        <div class="topbar-user">
            <div class="user-text">
                <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                <span><?php echo htmlspecialchars($_SESSION['role']); ?></span>
            </div>
            <div class="avatar">
                <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
            </div>
        </div>
    </div>

</div>

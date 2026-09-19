<?php

function formatDateForInput($date)
{
    if (!$date) {
        return "";
    }

    $timestamp = strtotime($date);

    if ($timestamp === false) {
        return "";
    }

    return date("Y-m-d", $timestamp);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Manage Challenges - ICMS</title>

    <link rel="stylesheet" href="../../public/css/style.css">

</head>


<body>


<!-- ================================
     NAVBAR
================================ -->

<div class="app-shell">
<?php $activeMenu = 'challenges'; include __DIR__ . '/partials/sidebar.php'; ?>
<div class="main">
<?php include __DIR__ . '/partials/topbar.php'; ?>

<div class="container">


    <!-- ================================
         HEADER
    ================================= -->

    <div class="header">

        <h1>Manage Challenges</h1>

    </div>


    <!-- ================================
         MESSAGES
    ================================= -->

    <?php if ($message !== ""): ?>

        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php endif; ?>


    <?php if ($error !== ""): ?>

        <div class="error">
            <?php echo htmlspecialchars($error); ?>
        </div>

    <?php endif; ?>


    <!-- ================================
         CREATE / UPDATE FORM
    ================================= -->

    <div class="card">

        <?php if ($editChallenge): ?>

            <h2>Update Challenge</h2>

            <form method="POST"
                  action="ChallengeController.php">

                <input type="hidden"
                       name="action"
                       value="update">

                <input type="hidden"
                       name="challenge_id"
                       value="<?php echo $editChallenge['CHALLENGE_ID']; ?>">


        <?php else: ?>

            <h2>Create New Challenge</h2>

            <form method="POST"
                  action="ChallengeController.php">

                <input type="hidden"
                       name="action"
                       value="create">

        <?php endif; ?>


            <div class="form-grid">


                <!-- Title -->

                <div class="form-group">

                    <label>
                        Challenge Title
                    </label>

                    <input
                        type="text"
                        name="title"
                        required
                        value="<?php
                        echo $editChallenge
                            ? htmlspecialchars($editChallenge['TITLE'])
                            : '';
                        ?>"
                    >

                </div>


                <!-- Theme -->

                <div class="form-group">

                    <label>
                        Theme
                    </label>

                    <input
                        type="text"
                        name="theme"
                        required
                        value="<?php
                        echo $editChallenge
                            ? htmlspecialchars($editChallenge['THEME'])
                            : '';
                        ?>"
                    >

                </div>


                <!-- Start Date -->

                <div class="form-group">

                    <label>
                        Start Date
                    </label>

                    <input
                        type="date"
                        name="start_date"
                        required
                        value="<?php
                        echo $editChallenge
                            ? formatDateForInput(
                                $editChallenge['START_DATE']
                            )
                            : '';
                        ?>"
                    >

                </div>


                <!-- End Date -->

                <div class="form-group">

                    <label>
                        End Date
                    </label>

                    <input
                        type="date"
                        name="end_date"
                        required
                        value="<?php
                        echo $editChallenge
                            ? formatDateForInput(
                                $editChallenge['END_DATE']
                            )
                            : '';
                        ?>"
                    >

                </div>


                <!-- Venue -->

                <div class="form-group">

                    <label>
                        Venue
                    </label>

                    <input
                        type="text"
                        name="venue"
                        required
                        value="<?php
                        echo $editChallenge
                            ? htmlspecialchars($editChallenge['VENUE'])
                            : '';
                        ?>"
                    >

                </div>


                <!-- Status -->

                <div class="form-group">

                    <label>
                        Status
                    </label>

                    <select name="status" required>

                        <?php
                        $currentStatus =
                            $editChallenge
                                ? $editChallenge['STATUS']
                                : 'Upcoming';
                        ?>

                        <option value="Upcoming"
                            <?php
                            echo $currentStatus === 'Upcoming'
                                ? 'selected'
                                : '';
                            ?>>
                            Upcoming
                        </option>

                        <option value="Ongoing"
                            <?php
                            echo $currentStatus === 'Ongoing'
                                ? 'selected'
                                : '';
                            ?>>
                            Ongoing
                        </option>

                        <option value="Completed"
                            <?php
                            echo $currentStatus === 'Completed'
                                ? 'selected'
                                : '';
                            ?>>
                            Completed
                        </option>

                    </select>

                </div>

            </div>


            <div class="actions">

                <?php if ($editChallenge): ?>

                    <button
                        type="submit"
                        class="btn-update">
                        Update Challenge
                    </button>

                    <a
                        href="ChallengeController.php"
                        class="btn-cancel">
                        Cancel
                    </a>

                <?php else: ?>

                    <button
                        type="submit"
                        class="btn-primary">
                        Create Challenge
                    </button>

                <?php endif; ?>

            </div>


        </form>

    </div>


    <!-- ================================
         CHALLENGE LIST
    ================================= -->

    <div class="card">

        <h2>My Challenges</h2>


        <?php if (count($challenges) > 0): ?>

            <table>

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Title</th>

                        <th>Theme</th>

                        <th>Start Date</th>

                        <th>End Date</th>

                        <th>Venue</th>

                        <th>Status</th>

                        <th>Actions</th>

                    </tr>

                </thead>


                <tbody>

                    <?php foreach ($challenges as $challenge): ?>

                        <tr>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $challenge['CHALLENGE_ID']
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $challenge['TITLE']
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $challenge['THEME']
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo date(
                                    'd-m-Y',
                                    strtotime(
                                        $challenge['START_DATE']
                                    )
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo date(
                                    'd-m-Y',
                                    strtotime(
                                        $challenge['END_DATE']
                                    )
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $challenge['VENUE']
                                );
                                ?>
                            </td>

                            <td class="status">

                                <?php
                                echo htmlspecialchars(
                                    $challenge['STATUS']
                                );
                                ?>

                            </td>


                            <td>

                                <div class="table-actions">


                                    <!-- Edit -->

                                    <a
                                        href="ChallengeController.php?edit=<?php
                                        echo $challenge['CHALLENGE_ID'];
                                        ?>"
                                    >

                                        <button
                                            type="button"
                                            class="btn-update">
                                            Edit
                                        </button>

                                    </a>


                                    <!-- Delete -->

                                    <form
                                        method="POST"
                                        action="ChallengeController.php"
                                        class="inline-form"
                                        onsubmit="return confirm(
                                            'Are you sure you want to delete this challenge?'
                                        );"
                                    >

                                        <input
                                            type="hidden"
                                            name="action"
                                            value="delete"
                                        >

                                        <input
                                            type="hidden"
                                            name="challenge_id"
                                            value="<?php
                                            echo $challenge['CHALLENGE_ID'];
                                            ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="btn-delete">
                                            Delete
                                        </button>

                                    </form>


                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        <?php else: ?>

            <p>
                No challenges found.
            </p>

        <?php endif; ?>

    </div>


</div>


</div>
</div>

</body>

</html>
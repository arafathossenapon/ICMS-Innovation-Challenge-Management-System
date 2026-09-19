<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/ChallengeModel.php';

Auth::requireLogin('Organizer');


$challengeModel = new ChallengeModel();

$organizerId = $_SESSION['user_id'];

$message = "";
$error = "";


// ================================
// CREATE / UPDATE / DELETE
// ================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = isset($_POST['action'])
        ? $_POST['action']
        : "";


    try {

        // ================================
        // CREATE
        // ================================

        if ($action === 'create') {

            $title = trim($_POST['title']);
            $theme = trim($_POST['theme']);
            $startDate = $_POST['start_date'];
            $endDate = $_POST['end_date'];
            $venue = trim($_POST['venue']);
            $status = $_POST['status'];


            if (
                $title === "" ||
                $theme === "" ||
                $startDate === "" ||
                $endDate === "" ||
                $venue === ""
            ) {
                throw new Exception("All fields are required.");
            }


            if ($startDate > $endDate) {
                throw new Exception(
                    "Start date cannot be after end date."
                );
            }


            $challengeModel->createChallenge(
                $title,
                $theme,
                $startDate,
                $endDate,
                $venue,
                $status,
                $organizerId
            );


            $message = "Challenge created successfully!";
        }


        // ================================
        // UPDATE
        // ================================

        elseif ($action === 'update') {

            $challengeId = $_POST['challenge_id'];

            $title = trim($_POST['title']);
            $theme = trim($_POST['theme']);
            $startDate = $_POST['start_date'];
            $endDate = $_POST['end_date'];
            $venue = trim($_POST['venue']);
            $status = $_POST['status'];


            if (
                $challengeId === "" ||
                $title === "" ||
                $theme === "" ||
                $startDate === "" ||
                $endDate === "" ||
                $venue === ""
            ) {
                throw new Exception("All fields are required.");
            }


            if ($startDate > $endDate) {
                throw new Exception(
                    "Start date cannot be after end date."
                );
            }


            $challengeModel->updateChallenge(
                $challengeId,
                $title,
                $theme,
                $startDate,
                $endDate,
                $venue,
                $status,
                $organizerId
            );


            $message = "Challenge updated successfully!";
        }


        // ================================
        // DELETE
        // ================================

        elseif ($action === 'delete') {

            $challengeId = $_POST['challenge_id'];


            if ($challengeId === "") {
                throw new Exception("Invalid challenge ID.");
            }


            $challengeModel->deleteChallenge(
                $challengeId,
                $organizerId
            );


            $message = "Challenge deleted successfully!";
        }

    } catch (Exception $e) {

        $error = $e->getMessage();
    }
}


// Get all challenges
$challenges = $challengeModel->getChallenges($organizerId);


// Edit mode
$editChallenge = null;

if (
    isset($_GET['edit']) &&
    $_GET['edit'] !== ""
) {

    $editChallenge = $challengeModel->getChallenge(
        $_GET['edit'],
        $organizerId
    );
}


// Load view
require_once __DIR__ . '/../views/challenges.php';

?>
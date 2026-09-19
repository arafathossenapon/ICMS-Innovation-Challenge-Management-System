<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/ProjectSubmissionModel.php';
require_once __DIR__ . '/../models/TeamModel.php';
require_once __DIR__ . '/../models/ChallengeModel.php';

Auth::requireLogin('Organizer');

$submissionModel = new ProjectSubmissionModel();
$teamModel = new TeamModel();
$challengeModel = new ChallengeModel();

$message = "";
$error = "";

$statuses = array('Pending', 'Submitted', 'Approved', 'Rejected');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = isset($_POST['action']) ? $_POST['action'] : "";

    try {

        $title = trim($_POST['project_title']);
        $category = trim($_POST['project_category']);
        $date = $_POST['submission_date'];
        $description = trim($_POST['project_description']);
        $repoLink = trim($_POST['repository_link']);
        $status = $_POST['submission_status'];
        $teamId = $_POST['team_id'];
        $challengeId = $_POST['challenge_id'];

        if ($action === 'create') {

            if ($title === "" || $category === "" || $date === "" || $description === "" || $teamId === "" || $challengeId === "") {
                throw new Exception("Please fill in all required fields.");
            }

            $submissionModel->createSubmission($title, $category, $date, $description, $repoLink, $status, $teamId, $challengeId);
            $message = "Project submitted successfully!";

        } elseif ($action === 'update') {

            $submissionId = $_POST['submission_id'];

            if ($submissionId === "" || $title === "" || $category === "" || $date === "" || $description === "" || $teamId === "" || $challengeId === "") {
                throw new Exception("Please fill in all required fields.");
            }

            $submissionModel->updateSubmission($submissionId, $title, $category, $date, $description, $repoLink, $status, $teamId, $challengeId);
            $message = "Submission updated successfully!";

        } elseif ($action === 'delete') {

            $submissionId = $_POST['submission_id'];

            if ($submissionId === "") {
                throw new Exception("Invalid submission ID.");
            }

            $submissionModel->deleteSubmission($submissionId);
            $message = "Submission deleted successfully!";
        }

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$submissions = $submissionModel->getSubmissions();
$teams = $teamModel->getTeamsList();
$challenges = $challengeModel->getAllChallengesList();

$editSubmission = null;

if (isset($_GET['edit']) && $_GET['edit'] !== "") {
    $editSubmission = $submissionModel->getSubmission($_GET['edit']);
}

require_once __DIR__ . '/../views/submissions.php';

?>

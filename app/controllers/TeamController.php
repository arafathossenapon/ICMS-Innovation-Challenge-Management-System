<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/TeamModel.php';

Auth::requireLogin('Organizer');

$teamModel = new TeamModel();

$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = isset($_POST['action']) ? $_POST['action'] : "";

    try {

        if ($action === 'create') {

            $teamName = trim($_POST['team_name']);
            $creationDate = $_POST['creation_date'];
            $university = trim($_POST['university']);

            if ($teamName === "" || $creationDate === "" || $university === "") {
                throw new Exception("All fields are required.");
            }

            $teamModel->createTeam($teamName, $creationDate, $university);
            $message = "Team created successfully!";

        } elseif ($action === 'update') {

            $teamId = $_POST['team_id'];
            $teamName = trim($_POST['team_name']);
            $creationDate = $_POST['creation_date'];
            $university = trim($_POST['university']);

            if ($teamId === "" || $teamName === "" || $creationDate === "" || $university === "") {
                throw new Exception("All fields are required.");
            }

            $teamModel->updateTeam($teamId, $teamName, $creationDate, $university);
            $message = "Team updated successfully!";

        } elseif ($action === 'delete') {

            $teamId = $_POST['team_id'];

            if ($teamId === "") {
                throw new Exception("Invalid team ID.");
            }

            $teamModel->deleteTeam($teamId);
            $message = "Team deleted successfully!";
        }

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$teams = $teamModel->getTeams();

$editTeam = null;

if (isset($_GET['edit']) && $_GET['edit'] !== "") {
    $editTeam = $teamModel->getTeam($_GET['edit']);
}

require_once __DIR__ . '/../views/teams.php';

?>

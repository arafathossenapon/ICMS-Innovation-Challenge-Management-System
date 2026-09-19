<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/ParticipationModel.php';
require_once __DIR__ . '/../models/TeamModel.php';
require_once __DIR__ . '/../models/ChallengeModel.php';

Auth::requireLogin('Organizer');

$participationModel = new ParticipationModel();
$teamModel = new TeamModel();
$challengeModel = new ChallengeModel();

$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = isset($_POST['action']) ? $_POST['action'] : "";

    try {

        if ($action === 'create') {

            $teamId = $_POST['team_id'];
            $challengeId = $_POST['challenge_id'];

            if ($teamId === "" || $challengeId === "") {
                throw new Exception("Please select both a team and a challenge.");
            }

            $participationModel->addParticipation($teamId, $challengeId);
            $message = "Team registered for the challenge successfully!";

        } elseif ($action === 'delete') {

            $teamId = $_POST['team_id'];
            $challengeId = $_POST['challenge_id'];

            $participationModel->deleteParticipation($teamId, $challengeId);
            $message = "Participation removed successfully!";
        }

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$participations = $participationModel->getParticipations();
$teams = $teamModel->getTeamsList();
$challenges = $challengeModel->getAllChallengesList();

require_once __DIR__ . '/../views/participation.php';

?>

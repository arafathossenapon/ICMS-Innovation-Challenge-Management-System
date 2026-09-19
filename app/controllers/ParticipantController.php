<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/ParticipantModel.php';
require_once __DIR__ . '/../models/TeamModel.php';
require_once __DIR__ . '/../models/ContactModel.php';

Auth::requireLogin('Organizer');

$participantModel = new ParticipantModel();
$teamModel = new TeamModel();
$contactModel = new ContactModel();

$message = isset($_GET['contact_msg']) ? $_GET['contact_msg'] : "";
$error = isset($_GET['contact_err']) ? $_GET['contact_err'] : "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = isset($_POST['action']) ? $_POST['action'] : "";

    try {

        if ($action === 'create') {

            $name = trim($_POST['name']);
            $department = trim($_POST['department']);
            $universityId = trim($_POST['university_id']);
            $gender = $_POST['gender'];
            $teamId = $_POST['team_id'];

            if ($name === "" || $department === "" || $universityId === "" || $teamId === "") {
                throw new Exception("All fields are required.");
            }

            $participantModel->createParticipant($name, $department, $universityId, $gender, $teamId);
            $message = "Participant added successfully!";

        } elseif ($action === 'update') {

            $participantId = $_POST['participant_id'];
            $name = trim($_POST['name']);
            $department = trim($_POST['department']);
            $universityId = trim($_POST['university_id']);
            $gender = $_POST['gender'];
            $teamId = $_POST['team_id'];

            if ($participantId === "" || $name === "" || $department === "" || $universityId === "" || $teamId === "") {
                throw new Exception("All fields are required.");
            }

            $participantModel->updateParticipant($participantId, $name, $department, $universityId, $gender, $teamId);
            $message = "Participant updated successfully!";

        } elseif ($action === 'delete') {

            $participantId = $_POST['participant_id'];

            if ($participantId === "") {
                throw new Exception("Invalid participant ID.");
            }

            $participantModel->deleteParticipant($participantId);
            $message = "Participant deleted successfully!";
        }

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Optional filter: ?team_id=5 (linked from the Teams "Members" button)
$filterTeamId = isset($_GET['team_id']) && $_GET['team_id'] !== "" ? $_GET['team_id'] : null;

$participants = $participantModel->getParticipants($filterTeamId);
$teams = $teamModel->getTeamsList();

$editParticipant = null;
$participantEmails = array();
$participantPhones = array();

if (isset($_GET['edit']) && $_GET['edit'] !== "") {
    $editParticipant = $participantModel->getParticipant($_GET['edit']);

    if ($editParticipant) {
        $participantEmails = $contactModel->getEmails('participant', $editParticipant['PARTICIPANT_ID']);
        $participantPhones = $contactModel->getPhones('participant', $editParticipant['PARTICIPANT_ID']);
    }
}

require_once __DIR__ . '/../views/participants.php';

?>

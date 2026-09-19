<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/AwardModel.php';
require_once __DIR__ . '/../models/EvaluationModel.php';

Auth::requireLogin('Organizer');

$awardModel = new AwardModel();
$evaluationModel = new EvaluationModel();

$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = isset($_POST['action']) ? $_POST['action'] : "";

    try {

        $title = trim($_POST['award_title']);
        $prizeAmount = trim($_POST['prize_amount']);
        $certificateNumber = trim($_POST['certificate_number']);
        $date = $_POST['award_date'];
        $evaluationId = $_POST['evaluation_id'];

        if ($title === "" || $prizeAmount === "" || $certificateNumber === "" || $date === "" || $evaluationId === "") {
            throw new Exception("All fields are required.");
        }

        if (!is_numeric($prizeAmount) || $prizeAmount < 0) {
            throw new Exception("Prize amount must be a positive number.");
        }

        if ($action === 'create') {

            $awardModel->createAward($title, $prizeAmount, $certificateNumber, $date, $evaluationId);
            $message = "Award created successfully!";

        } elseif ($action === 'update') {

            $awardId = $_POST['award_id'];

            if ($awardId === "") {
                throw new Exception("Invalid award ID.");
            }

            $awardModel->updateAward($awardId, $title, $prizeAmount, $certificateNumber, $date, $evaluationId);
            $message = "Award updated successfully!";

        } elseif ($action === 'delete') {

            $awardId = $_POST['award_id'];
            $awardModel->deleteAward($awardId);
            $message = "Award deleted successfully!";
        }

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$awards = $awardModel->getAwards();
$evaluations = $evaluationModel->getEvaluationsList();

$editAward = null;

if (isset($_GET['edit']) && $_GET['edit'] !== "") {
    $editAward = $awardModel->getAward($_GET['edit']);
}

require_once __DIR__ . '/../views/awards.php';

?>

<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/EvaluationModel.php';
require_once __DIR__ . '/../models/ProjectSubmissionModel.php';
require_once __DIR__ . '/../models/JudgeModel.php';

Auth::requireLogin('Organizer');

$evaluationModel = new EvaluationModel();
$submissionModel = new ProjectSubmissionModel();
$judgeModel = new JudgeModel();

$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = isset($_POST['action']) ? $_POST['action'] : "";

    try {

        $score = trim($_POST['score']);
        $comments = trim($_POST['comments']);
        $date = $_POST['evaluation_date'];
        $recommendations = trim($_POST['recommendations']);
        $submissionId = $_POST['submission_id'];
        $judgeId = $_POST['judge_id'];

        if ($score === "" || $date === "" || $submissionId === "" || $judgeId === "") {
            throw new Exception("Score, date, submission and judge are required.");
        }

        if (!is_numeric($score) || $score < 0 || $score > 100) {
            throw new Exception("Score must be a number between 0 and 100.");
        }

        if ($action === 'create') {

            $evaluationModel->createEvaluation($score, $comments, $date, $recommendations, $submissionId, $judgeId);
            $message = "Evaluation recorded successfully!";

        } elseif ($action === 'update') {

            $evaluationId = $_POST['evaluation_id'];

            if ($evaluationId === "") {
                throw new Exception("Invalid evaluation ID.");
            }

            $evaluationModel->updateEvaluation($evaluationId, $score, $comments, $date, $recommendations, $submissionId, $judgeId);
            $message = "Evaluation updated successfully!";

        } elseif ($action === 'delete') {

            $evaluationId = $_POST['evaluation_id'];
            $evaluationModel->deleteEvaluation($evaluationId);
            $message = "Evaluation deleted successfully!";
        }

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$evaluations = $evaluationModel->getEvaluations();
$submissions = $submissionModel->getSubmissionsList();
$judges = $judgeModel->getJudgesList();

$editEvaluation = null;

if (isset($_GET['edit']) && $_GET['edit'] !== "") {
    $editEvaluation = $evaluationModel->getEvaluation($_GET['edit']);
}

require_once __DIR__ . '/../views/evaluations.php';

?>

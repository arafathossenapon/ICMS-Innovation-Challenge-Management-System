<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/JudgeModel.php';
require_once __DIR__ . '/../models/ContactModel.php';

Auth::requireLogin('Organizer');

$judgeModel = new JudgeModel();
$contactModel = new ContactModel();

$message = isset($_GET['contact_msg']) ? $_GET['contact_msg'] : "";
$error = isset($_GET['contact_err']) ? $_GET['contact_err'] : "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = isset($_POST['action']) ? $_POST['action'] : "";

    try {

        if ($action === 'create') {

            $name = trim($_POST['name']);
            $designation = trim($_POST['designation']);
            $organization = trim($_POST['organization']);
            $specialization = trim($_POST['specialization']);

            if ($name === "" || $designation === "" || $organization === "" || $specialization === "") {
                throw new Exception("All fields are required.");
            }

            $judgeModel->createJudge($name, $designation, $organization, $specialization);
            $message = "Judge added successfully!";

        } elseif ($action === 'update') {

            $judgeId = $_POST['judge_id'];
            $name = trim($_POST['name']);
            $designation = trim($_POST['designation']);
            $organization = trim($_POST['organization']);
            $specialization = trim($_POST['specialization']);

            if ($judgeId === "" || $name === "" || $designation === "" || $organization === "" || $specialization === "") {
                throw new Exception("All fields are required.");
            }

            $judgeModel->updateJudge($judgeId, $name, $designation, $organization, $specialization);
            $message = "Judge updated successfully!";

        } elseif ($action === 'delete') {

            $judgeId = $_POST['judge_id'];

            if ($judgeId === "") {
                throw new Exception("Invalid judge ID.");
            }

            $judgeModel->deleteJudge($judgeId);
            $message = "Judge deleted successfully!";
        }

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$judges = $judgeModel->getJudges();

$editJudge = null;
$judgeEmails = array();
$judgePhones = array();

if (isset($_GET['edit']) && $_GET['edit'] !== "") {
    $editJudge = $judgeModel->getJudge($_GET['edit']);

    if ($editJudge) {
        $judgeEmails = $contactModel->getEmails('judge', $editJudge['JUDGE_ID']);
        $judgePhones = $contactModel->getPhones('judge', $editJudge['JUDGE_ID']);
    }
}

require_once __DIR__ . '/../views/judges.php';

?>

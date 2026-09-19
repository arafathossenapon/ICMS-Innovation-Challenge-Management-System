<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/ContactModel.php';

Auth::requireLogin('Organizer');

// Where to send the user back to after add/delete (e.g. ParticipantController.php?edit=5)
$redirectMap = array(
    'participant' => 'ParticipantController.php',
    'judge'       => 'JudgeController.php',
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $type = isset($_POST['type']) ? $_POST['type'] : "";
    $entityId = isset($_POST['entity_id']) ? $_POST['entity_id'] : "";
    $action = isset($_POST['action']) ? $_POST['action'] : "";

    if (!ContactModel::isValidType($type) || !isset($redirectMap[$type])) {
        die("Invalid contact request.");
    }

    $contactModel = new ContactModel();
    $backTo = $redirectMap[$type] . '?edit=' . urlencode($entityId);

    try {

        if ($action === 'add_email') {

            $email = trim($_POST['email']);
            if ($email === "") {
                throw new Exception("Email cannot be empty.");
            }
            $contactModel->addEmail($type, $entityId, $email);

        } elseif ($action === 'add_phone') {

            $phone = trim($_POST['phone']);
            if ($phone === "") {
                throw new Exception("Phone cannot be empty.");
            }
            $contactModel->addPhone($type, $entityId, $phone);

        } elseif ($action === 'delete_email') {

            $contactModel->deleteEmail($type, $_POST['email_id']);

        } elseif ($action === 'delete_phone') {

            $contactModel->deletePhone($type, $_POST['phone_id']);
        }

        header("Location: " . $backTo . "&contact_msg=" . urlencode("Contact info updated."));
        exit();

    } catch (Exception $e) {

        header("Location: " . $backTo . "&contact_err=" . urlencode($e->getMessage()));
        exit();
    }
}

// No direct GET access.
header("Location: ../views/dashboard.php");
exit();

?>

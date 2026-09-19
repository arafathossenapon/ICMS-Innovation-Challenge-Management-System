<?php

require_once __DIR__ . '/../../config/database.php';

class ParticipantModel
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getParticipants($teamId = null)
    {
        $sql = "SELECT p.PARTICIPANT_ID, p.NAME, p.DEPARTMENT, p.UNIVERSITY_ID,
                       p.GENDER, p.TEAM_ID, t.TEAM_NAME
                FROM PARTICIPANT p
                JOIN TEAM t ON t.TEAM_ID = p.TEAM_ID";

        if ($teamId !== null) {
            $sql .= " WHERE p.TEAM_ID = :team_id";
        }

        $sql .= " ORDER BY p.PARTICIPANT_ID DESC";

        $statement = oci_parse($this->db, $sql);

        if ($teamId !== null) {
            oci_bind_by_name($statement, ":team_id", $teamId);
        }

        oci_execute($statement);

        $participants = array();
        while ($row = oci_fetch_assoc($statement)) {
            $participants[] = $row;
        }

        oci_free_statement($statement);
        return $participants;
    }

    public function getParticipant($participantId)
    {
        $sql = "SELECT PARTICIPANT_ID, NAME, DEPARTMENT, UNIVERSITY_ID, GENDER, TEAM_ID
                FROM PARTICIPANT
                WHERE PARTICIPANT_ID = :participant_id";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":participant_id", $participantId);
        oci_execute($statement);

        $participant = oci_fetch_assoc($statement);
        oci_free_statement($statement);
        return $participant;
    }

    public function createParticipant($name, $department, $universityId, $gender, $teamId)
    {
        $sql = "INSERT INTO PARTICIPANT (PARTICIPANT_ID, NAME, DEPARTMENT, UNIVERSITY_ID, GENDER, TEAM_ID)
                VALUES (PARTICIPANT_SEQ.NEXTVAL, :name, :department, :university_id, :gender, :team_id)";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":name", $name);
        oci_bind_by_name($statement, ":department", $department);
        oci_bind_by_name($statement, ":university_id", $universityId);
        oci_bind_by_name($statement, ":gender", $gender);
        oci_bind_by_name($statement, ":team_id", $teamId);

        $result = oci_execute($statement, OCI_NO_AUTO_COMMIT);

        if (!$result) {
            $error = oci_error($statement);
            oci_rollback($this->db);
            oci_free_statement($statement);
            throw new Exception($error['message']);
        }

        oci_commit($this->db);
        oci_free_statement($statement);
        return true;
    }

    public function updateParticipant($participantId, $name, $department, $universityId, $gender, $teamId)
    {
        $sql = "UPDATE PARTICIPANT
                SET NAME = :name,
                    DEPARTMENT = :department,
                    UNIVERSITY_ID = :university_id,
                    GENDER = :gender,
                    TEAM_ID = :team_id
                WHERE PARTICIPANT_ID = :participant_id";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":name", $name);
        oci_bind_by_name($statement, ":department", $department);
        oci_bind_by_name($statement, ":university_id", $universityId);
        oci_bind_by_name($statement, ":gender", $gender);
        oci_bind_by_name($statement, ":team_id", $teamId);
        oci_bind_by_name($statement, ":participant_id", $participantId);

        $result = oci_execute($statement, OCI_NO_AUTO_COMMIT);

        if (!$result) {
            $error = oci_error($statement);
            oci_rollback($this->db);
            oci_free_statement($statement);
            throw new Exception($error['message']);
        }

        oci_commit($this->db);
        oci_free_statement($statement);
        return true;
    }

    public function deleteParticipant($participantId)
    {
        $sql = "DELETE FROM PARTICIPANT WHERE PARTICIPANT_ID = :participant_id";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":participant_id", $participantId);

        $result = oci_execute($statement, OCI_NO_AUTO_COMMIT);

        if (!$result) {
            $error = oci_error($statement);
            oci_rollback($this->db);
            oci_free_statement($statement);
            throw new Exception("Could not delete participant: " . $error['message']);
        }

        oci_commit($this->db);
        oci_free_statement($statement);
        return true;
    }
}

?>

<?php

require_once __DIR__ . '/../../config/database.php';

class ParticipationModel
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getParticipations()
    {
        $sql = "SELECT p.TEAM_ID, p.CHALLENGE_ID, t.TEAM_NAME, c.TITLE AS CHALLENGE_TITLE
                FROM PARTICIPATION p
                JOIN TEAM t ON t.TEAM_ID = p.TEAM_ID
                JOIN CHALLENGE c ON c.CHALLENGE_ID = p.CHALLENGE_ID
                ORDER BY p.CHALLENGE_ID DESC, p.TEAM_ID DESC";

        $statement = oci_parse($this->db, $sql);
        oci_execute($statement);

        $rows = array();
        while ($row = oci_fetch_assoc($statement)) {
            $rows[] = $row;
        }

        oci_free_statement($statement);
        return $rows;
    }

    public function addParticipation($teamId, $challengeId)
    {
        $sql = "INSERT INTO PARTICIPATION (TEAM_ID, CHALLENGE_ID)
                VALUES (:team_id, :challenge_id)";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":team_id", $teamId);
        oci_bind_by_name($statement, ":challenge_id", $challengeId);

        $result = oci_execute($statement, OCI_NO_AUTO_COMMIT);

        if (!$result) {
            $error = oci_error($statement);
            oci_rollback($this->db);
            oci_free_statement($statement);
            // ORA-00001 (unique constraint) means this team is already in this challenge.
            throw new Exception($error['message']);
        }

        oci_commit($this->db);
        oci_free_statement($statement);
        return true;
    }

    public function deleteParticipation($teamId, $challengeId)
    {
        $sql = "DELETE FROM PARTICIPATION
                WHERE TEAM_ID = :team_id AND CHALLENGE_ID = :challenge_id";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":team_id", $teamId);
        oci_bind_by_name($statement, ":challenge_id", $challengeId);

        $result = oci_execute($statement, OCI_NO_AUTO_COMMIT);

        if (!$result) {
            $error = oci_error($statement);
            oci_rollback($this->db);
            oci_free_statement($statement);
            throw new Exception("Could not remove participation: " . $error['message']);
        }

        oci_commit($this->db);
        oci_free_statement($statement);
        return true;
    }
}

?>

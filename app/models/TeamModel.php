<?php

require_once __DIR__ . '/../../config/database.php';

class TeamModel
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getTeams()
    {
        $sql = "SELECT TEAM_ID, TEAM_NAME, CREATION_DATE, UNIVERSITY
                FROM TEAM
                ORDER BY TEAM_ID DESC";

        $statement = oci_parse($this->db, $sql);
        oci_execute($statement);

        $teams = array();
        while ($row = oci_fetch_assoc($statement)) {
            $teams[] = $row;
        }

        oci_free_statement($statement);
        return $teams;
    }

    // Lightweight list for populating <select> dropdowns elsewhere.
    public function getTeamsList()
    {
        $sql = "SELECT TEAM_ID, TEAM_NAME FROM TEAM ORDER BY TEAM_NAME";

        $statement = oci_parse($this->db, $sql);
        oci_execute($statement);

        $teams = array();
        while ($row = oci_fetch_assoc($statement)) {
            $teams[] = $row;
        }

        oci_free_statement($statement);
        return $teams;
    }

    public function getTeam($teamId)
    {
        $sql = "SELECT TEAM_ID, TEAM_NAME, CREATION_DATE, UNIVERSITY
                FROM TEAM
                WHERE TEAM_ID = :team_id";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":team_id", $teamId);
        oci_execute($statement);

        $team = oci_fetch_assoc($statement);
        oci_free_statement($statement);
        return $team;
    }

    public function createTeam($teamName, $creationDate, $university)
    {
        $sql = "INSERT INTO TEAM (TEAM_ID, TEAM_NAME, CREATION_DATE, UNIVERSITY)
                VALUES (TEAM_SEQ.NEXTVAL, :team_name, TO_DATE(:creation_date, 'YYYY-MM-DD'), :university)";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":team_name", $teamName);
        oci_bind_by_name($statement, ":creation_date", $creationDate);
        oci_bind_by_name($statement, ":university", $university);

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

    public function updateTeam($teamId, $teamName, $creationDate, $university)
    {
        $sql = "UPDATE TEAM
                SET TEAM_NAME = :team_name,
                    CREATION_DATE = TO_DATE(:creation_date, 'YYYY-MM-DD'),
                    UNIVERSITY = :university
                WHERE TEAM_ID = :team_id";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":team_name", $teamName);
        oci_bind_by_name($statement, ":creation_date", $creationDate);
        oci_bind_by_name($statement, ":university", $university);
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

    public function deleteTeam($teamId)
    {
        $sql = "DELETE FROM TEAM WHERE TEAM_ID = :team_id";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":team_id", $teamId);

        $result = oci_execute($statement, OCI_NO_AUTO_COMMIT);

        if (!$result) {
            $error = oci_error($statement);
            oci_rollback($this->db);
            oci_free_statement($statement);
            // Most likely a foreign key violation (team has participants/submissions).
            throw new Exception("Could not delete team: " . $error['message']);
        }

        oci_commit($this->db);
        oci_free_statement($statement);
        return true;
    }
}

?>

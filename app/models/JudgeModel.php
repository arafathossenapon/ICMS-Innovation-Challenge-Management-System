<?php

require_once __DIR__ . '/../../config/database.php';

class JudgeModel
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getJudges()
    {
        $sql = "SELECT JUDGE_ID, NAME, DESIGNATION, ORGANIZATION, SPECIALIZATION
                FROM JUDGE
                ORDER BY JUDGE_ID DESC";

        $statement = oci_parse($this->db, $sql);
        oci_execute($statement);

        $judges = array();
        while ($row = oci_fetch_assoc($statement)) {
            $judges[] = $row;
        }

        oci_free_statement($statement);
        return $judges;
    }

    public function getJudgesList()
    {
        $sql = "SELECT JUDGE_ID, NAME FROM JUDGE ORDER BY NAME";

        $statement = oci_parse($this->db, $sql);
        oci_execute($statement);

        $judges = array();
        while ($row = oci_fetch_assoc($statement)) {
            $judges[] = $row;
        }

        oci_free_statement($statement);
        return $judges;
    }

    public function getJudge($judgeId)
    {
        $sql = "SELECT JUDGE_ID, NAME, DESIGNATION, ORGANIZATION, SPECIALIZATION
                FROM JUDGE
                WHERE JUDGE_ID = :judge_id";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":judge_id", $judgeId);
        oci_execute($statement);

        $judge = oci_fetch_assoc($statement);
        oci_free_statement($statement);
        return $judge;
    }

    public function createJudge($name, $designation, $organization, $specialization)
    {
        $sql = "INSERT INTO JUDGE (JUDGE_ID, NAME, DESIGNATION, ORGANIZATION, SPECIALIZATION)
                VALUES (JUDGE_SEQ.NEXTVAL, :name, :designation, :organization, :specialization)";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":name", $name);
        oci_bind_by_name($statement, ":designation", $designation);
        oci_bind_by_name($statement, ":organization", $organization);
        oci_bind_by_name($statement, ":specialization", $specialization);

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

    public function updateJudge($judgeId, $name, $designation, $organization, $specialization)
    {
        $sql = "UPDATE JUDGE
                SET NAME = :name,
                    DESIGNATION = :designation,
                    ORGANIZATION = :organization,
                    SPECIALIZATION = :specialization
                WHERE JUDGE_ID = :judge_id";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":name", $name);
        oci_bind_by_name($statement, ":designation", $designation);
        oci_bind_by_name($statement, ":organization", $organization);
        oci_bind_by_name($statement, ":specialization", $specialization);
        oci_bind_by_name($statement, ":judge_id", $judgeId);

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

    public function deleteJudge($judgeId)
    {
        $sql = "DELETE FROM JUDGE WHERE JUDGE_ID = :judge_id";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":judge_id", $judgeId);

        $result = oci_execute($statement, OCI_NO_AUTO_COMMIT);

        if (!$result) {
            $error = oci_error($statement);
            oci_rollback($this->db);
            oci_free_statement($statement);
            throw new Exception("Could not delete judge: " . $error['message']);
        }

        oci_commit($this->db);
        oci_free_statement($statement);
        return true;
    }
}

?>

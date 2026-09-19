<?php

require_once __DIR__ . '/../../config/database.php';

class AwardModel
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getAwards()
    {
        $sql = "SELECT a.AWARD_ID, a.AWARD_TITLE, a.PRIZE_AMOUNT, a.CERTIFICATE_NUMBER, a.AWARD_DATE,
                       a.EVALUATION_ID, e.SCORE, s.PROJECT_TITLE, t.TEAM_NAME
                FROM AWARD a
                JOIN EVALUATION e ON e.EVALUATION_ID = a.EVALUATION_ID
                JOIN PROJECT_SUBMISSION s ON s.SUBMISSION_ID = e.SUBMISSION_ID
                JOIN TEAM t ON t.TEAM_ID = s.TEAM_ID
                ORDER BY a.AWARD_ID DESC";

        $statement = oci_parse($this->db, $sql);
        oci_execute($statement);

        $rows = array();
        while ($row = oci_fetch_assoc($statement)) {
            $rows[] = $row;
        }

        oci_free_statement($statement);
        return $rows;
    }

    public function getAward($awardId)
    {
        $sql = "SELECT AWARD_ID, AWARD_TITLE, PRIZE_AMOUNT, CERTIFICATE_NUMBER, AWARD_DATE, EVALUATION_ID
                FROM AWARD
                WHERE AWARD_ID = :award_id";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":award_id", $awardId);
        oci_execute($statement);

        $row = oci_fetch_assoc($statement);
        oci_free_statement($statement);
        return $row;
    }

    public function createAward($title, $prizeAmount, $certificateNumber, $date, $evaluationId)
    {
        $sql = "INSERT INTO AWARD
                (AWARD_ID, AWARD_TITLE, PRIZE_AMOUNT, CERTIFICATE_NUMBER, AWARD_DATE, EVALUATION_ID)
                VALUES
                (AWARD_SEQ.NEXTVAL, :title, :prize_amount, :certificate_number, TO_DATE(:award_date, 'YYYY-MM-DD'), :evaluation_id)";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":title", $title);
        oci_bind_by_name($statement, ":prize_amount", $prizeAmount);
        oci_bind_by_name($statement, ":certificate_number", $certificateNumber);
        oci_bind_by_name($statement, ":award_date", $date);
        oci_bind_by_name($statement, ":evaluation_id", $evaluationId);

        $result = oci_execute($statement, OCI_NO_AUTO_COMMIT);

        if (!$result) {
            $error = oci_error($statement);
            oci_rollback($this->db);
            oci_free_statement($statement);
            // ORA-00001 on CERTIFICATE_NUMBER means it's already used (UNIQUE constraint).
            throw new Exception($error['message']);
        }

        oci_commit($this->db);
        oci_free_statement($statement);
        return true;
    }

    public function updateAward($awardId, $title, $prizeAmount, $certificateNumber, $date, $evaluationId)
    {
        $sql = "UPDATE AWARD
                SET AWARD_TITLE = :title,
                    PRIZE_AMOUNT = :prize_amount,
                    CERTIFICATE_NUMBER = :certificate_number,
                    AWARD_DATE = TO_DATE(:award_date, 'YYYY-MM-DD'),
                    EVALUATION_ID = :evaluation_id
                WHERE AWARD_ID = :award_id";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":title", $title);
        oci_bind_by_name($statement, ":prize_amount", $prizeAmount);
        oci_bind_by_name($statement, ":certificate_number", $certificateNumber);
        oci_bind_by_name($statement, ":award_date", $date);
        oci_bind_by_name($statement, ":evaluation_id", $evaluationId);
        oci_bind_by_name($statement, ":award_id", $awardId);

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

    public function deleteAward($awardId)
    {
        $sql = "DELETE FROM AWARD WHERE AWARD_ID = :award_id";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":award_id", $awardId);

        $result = oci_execute($statement, OCI_NO_AUTO_COMMIT);

        if (!$result) {
            $error = oci_error($statement);
            oci_rollback($this->db);
            oci_free_statement($statement);
            throw new Exception("Could not delete award: " . $error['message']);
        }

        oci_commit($this->db);
        oci_free_statement($statement);
        return true;
    }
}

?>

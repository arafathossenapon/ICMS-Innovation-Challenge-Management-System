<?php

require_once __DIR__ . '/../../config/database.php';

class EvaluationModel
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getEvaluations()
    {
        $sql = "SELECT e.EVALUATION_ID, e.SCORE, e.COMMENTS, e.EVALUATION_DATE, e.RECOMMENDATIONS,
                       e.SUBMISSION_ID, e.JUDGE_ID, s.PROJECT_TITLE, j.NAME AS JUDGE_NAME
                FROM EVALUATION e
                JOIN PROJECT_SUBMISSION s ON s.SUBMISSION_ID = e.SUBMISSION_ID
                JOIN JUDGE j ON j.JUDGE_ID = e.JUDGE_ID
                ORDER BY e.EVALUATION_ID DESC";

        $statement = oci_parse($this->db, $sql);
        oci_execute($statement);

        $rows = array();
        while ($row = oci_fetch_assoc($statement)) {
            $rows[] = $row;
        }

        oci_free_statement($statement);
        return $rows;
    }

    public function getEvaluationsList()
    {
        $sql = "SELECT EVALUATION_ID, SCORE FROM EVALUATION ORDER BY EVALUATION_ID DESC";

        $statement = oci_parse($this->db, $sql);
        oci_execute($statement);

        $rows = array();
        while ($row = oci_fetch_assoc($statement)) {
            $rows[] = $row;
        }

        oci_free_statement($statement);
        return $rows;
    }

    public function getEvaluation($evaluationId)
    {
        $sql = "SELECT EVALUATION_ID, SCORE, COMMENTS, EVALUATION_DATE, RECOMMENDATIONS, SUBMISSION_ID, JUDGE_ID
                FROM EVALUATION
                WHERE EVALUATION_ID = :evaluation_id";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":evaluation_id", $evaluationId);
        oci_execute($statement);

        $row = oci_fetch_assoc($statement);
        oci_free_statement($statement);
        return $row;
    }

    public function createEvaluation($score, $comments, $date, $recommendations, $submissionId, $judgeId)
    {
        $sql = "INSERT INTO EVALUATION
                (EVALUATION_ID, SCORE, COMMENTS, EVALUATION_DATE, RECOMMENDATIONS, SUBMISSION_ID, JUDGE_ID)
                VALUES
                (EVALUATION_SEQ.NEXTVAL, :score, :comments, TO_DATE(:evaluation_date, 'YYYY-MM-DD'), :recommendations, :submission_id, :judge_id)";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":score", $score);
        oci_bind_by_name($statement, ":comments", $comments);
        oci_bind_by_name($statement, ":evaluation_date", $date);
        oci_bind_by_name($statement, ":recommendations", $recommendations);
        oci_bind_by_name($statement, ":submission_id", $submissionId);
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

    public function updateEvaluation($evaluationId, $score, $comments, $date, $recommendations, $submissionId, $judgeId)
    {
        $sql = "UPDATE EVALUATION
                SET SCORE = :score,
                    COMMENTS = :comments,
                    EVALUATION_DATE = TO_DATE(:evaluation_date, 'YYYY-MM-DD'),
                    RECOMMENDATIONS = :recommendations,
                    SUBMISSION_ID = :submission_id,
                    JUDGE_ID = :judge_id
                WHERE EVALUATION_ID = :evaluation_id";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":score", $score);
        oci_bind_by_name($statement, ":comments", $comments);
        oci_bind_by_name($statement, ":evaluation_date", $date);
        oci_bind_by_name($statement, ":recommendations", $recommendations);
        oci_bind_by_name($statement, ":submission_id", $submissionId);
        oci_bind_by_name($statement, ":judge_id", $judgeId);
        oci_bind_by_name($statement, ":evaluation_id", $evaluationId);

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

    public function deleteEvaluation($evaluationId)
    {
        $sql = "DELETE FROM EVALUATION WHERE EVALUATION_ID = :evaluation_id";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":evaluation_id", $evaluationId);

        $result = oci_execute($statement, OCI_NO_AUTO_COMMIT);

        if (!$result) {
            $error = oci_error($statement);
            oci_rollback($this->db);
            oci_free_statement($statement);
            throw new Exception("Could not delete evaluation: " . $error['message']);
        }

        oci_commit($this->db);
        oci_free_statement($statement);
        return true;
    }
}

?>

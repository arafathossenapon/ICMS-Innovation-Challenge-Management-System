<?php

require_once __DIR__ . '/../../config/database.php';

class ProjectSubmissionModel
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getSubmissions()
    {
        $sql = "SELECT s.SUBMISSION_ID, s.PROJECT_TITLE, s.PROJECT_CATEGORY, s.SUBMISSION_DATE,
                       s.PROJECT_DESCRIPTION, s.REPOSITORY_LINK, s.SUBMISSION_STATUS,
                       s.TEAM_ID, s.CHALLENGE_ID, t.TEAM_NAME, c.TITLE AS CHALLENGE_TITLE
                FROM PROJECT_SUBMISSION s
                JOIN TEAM t ON t.TEAM_ID = s.TEAM_ID
                JOIN CHALLENGE c ON c.CHALLENGE_ID = s.CHALLENGE_ID
                ORDER BY s.SUBMISSION_ID DESC";

        $statement = oci_parse($this->db, $sql);
        oci_execute($statement);

        $rows = array();
        while ($row = oci_fetch_assoc($statement)) {
            $rows[] = $row;
        }

        oci_free_statement($statement);
        return $rows;
    }

    public function getSubmissionsList()
    {
        $sql = "SELECT SUBMISSION_ID, PROJECT_TITLE FROM PROJECT_SUBMISSION ORDER BY SUBMISSION_ID DESC";

        $statement = oci_parse($this->db, $sql);
        oci_execute($statement);

        $rows = array();
        while ($row = oci_fetch_assoc($statement)) {
            $rows[] = $row;
        }

        oci_free_statement($statement);
        return $rows;
    }

    public function getSubmission($submissionId)
    {
        $sql = "SELECT SUBMISSION_ID, PROJECT_TITLE, PROJECT_CATEGORY, SUBMISSION_DATE,
                       PROJECT_DESCRIPTION, REPOSITORY_LINK, SUBMISSION_STATUS, TEAM_ID, CHALLENGE_ID
                FROM PROJECT_SUBMISSION
                WHERE SUBMISSION_ID = :submission_id";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":submission_id", $submissionId);
        oci_execute($statement);

        // CLOB column (PROJECT_DESCRIPTION) needs to be read out explicitly.
        $row = oci_fetch_array($statement, OCI_ASSOC + OCI_RETURN_LOBS);

        oci_free_statement($statement);
        return $row;
    }

    public function createSubmission($title, $category, $date, $description, $repoLink, $status, $teamId, $challengeId)
    {
        $sql = "INSERT INTO PROJECT_SUBMISSION
                (SUBMISSION_ID, PROJECT_TITLE, PROJECT_CATEGORY, SUBMISSION_DATE,
                 PROJECT_DESCRIPTION, REPOSITORY_LINK, SUBMISSION_STATUS, TEAM_ID, CHALLENGE_ID)
                VALUES
                (PROJECT_SUBMISSION_SEQ.NEXTVAL, :title, :category, TO_DATE(:submission_date, 'YYYY-MM-DD'),
                 :description, :repo_link, :status, :team_id, :challenge_id)";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":title", $title);
        oci_bind_by_name($statement, ":category", $category);
        oci_bind_by_name($statement, ":submission_date", $date);
        oci_bind_by_name($statement, ":description", $description);
        oci_bind_by_name($statement, ":repo_link", $repoLink);
        oci_bind_by_name($statement, ":status", $status);
        oci_bind_by_name($statement, ":team_id", $teamId);
        oci_bind_by_name($statement, ":challenge_id", $challengeId);

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

    public function updateSubmission($submissionId, $title, $category, $date, $description, $repoLink, $status, $teamId, $challengeId)
    {
        $sql = "UPDATE PROJECT_SUBMISSION
                SET PROJECT_TITLE = :title,
                    PROJECT_CATEGORY = :category,
                    SUBMISSION_DATE = TO_DATE(:submission_date, 'YYYY-MM-DD'),
                    PROJECT_DESCRIPTION = :description,
                    REPOSITORY_LINK = :repo_link,
                    SUBMISSION_STATUS = :status,
                    TEAM_ID = :team_id,
                    CHALLENGE_ID = :challenge_id
                WHERE SUBMISSION_ID = :submission_id";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":title", $title);
        oci_bind_by_name($statement, ":category", $category);
        oci_bind_by_name($statement, ":submission_date", $date);
        oci_bind_by_name($statement, ":description", $description);
        oci_bind_by_name($statement, ":repo_link", $repoLink);
        oci_bind_by_name($statement, ":status", $status);
        oci_bind_by_name($statement, ":team_id", $teamId);
        oci_bind_by_name($statement, ":challenge_id", $challengeId);
        oci_bind_by_name($statement, ":submission_id", $submissionId);

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

    public function deleteSubmission($submissionId)
    {
        $sql = "DELETE FROM PROJECT_SUBMISSION WHERE SUBMISSION_ID = :submission_id";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":submission_id", $submissionId);

        $result = oci_execute($statement, OCI_NO_AUTO_COMMIT);

        if (!$result) {
            $error = oci_error($statement);
            oci_rollback($this->db);
            oci_free_statement($statement);
            throw new Exception("Could not delete submission: " . $error['message']);
        }

        oci_commit($this->db);
        oci_free_statement($statement);
        return true;
    }
}

?>

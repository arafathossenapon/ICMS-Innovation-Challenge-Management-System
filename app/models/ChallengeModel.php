<?php

require_once __DIR__ . '/../../config/database.php';

class ChallengeModel
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    // Lightweight list of ALL challenges (any organizer), used for
    // dropdowns in other modules (Participation, Project Submissions).
    public function getAllChallengesList()
    {
        $sql = "SELECT CHALLENGE_ID, TITLE FROM CHALLENGE ORDER BY CHALLENGE_ID DESC";

        $statement = oci_parse($this->db, $sql);
        oci_execute($statement);

        $challenges = array();
        while ($row = oci_fetch_assoc($statement)) {
            $challenges[] = $row;
        }

        oci_free_statement($statement);
        return $challenges;
    }

    // Get all challenges of the logged-in organizer
    public function getChallenges($organizerId)
    {
        $sql = "SELECT
                    CHALLENGE_ID,
                    TITLE,
                    THEME,
                    START_DATE,
                    END_DATE,
                    VENUE,
                    STATUS,
                    ORGANIZER_ID
                FROM CHALLENGE
                WHERE ORGANIZER_ID = :organizer_id
                ORDER BY CHALLENGE_ID DESC";

        $statement = oci_parse($this->db, $sql);

        oci_bind_by_name(
            $statement,
            ":organizer_id",
            $organizerId
        );

        oci_execute($statement);

        $challenges = array();

        while ($row = oci_fetch_assoc($statement)) {
            $challenges[] = $row;
        }

        oci_free_statement($statement);

        return $challenges;
    }


    // Get one challenge
    public function getChallenge($challengeId, $organizerId)
    {
        $sql = "SELECT
                    CHALLENGE_ID,
                    TITLE,
                    THEME,
                    START_DATE,
                    END_DATE,
                    VENUE,
                    STATUS,
                    ORGANIZER_ID
                FROM CHALLENGE
                WHERE CHALLENGE_ID = :challenge_id
                AND ORGANIZER_ID = :organizer_id";

        $statement = oci_parse($this->db, $sql);

        oci_bind_by_name(
            $statement,
            ":challenge_id",
            $challengeId
        );

        oci_bind_by_name(
            $statement,
            ":organizer_id",
            $organizerId
        );

        oci_execute($statement);

        $challenge = oci_fetch_assoc($statement);

        oci_free_statement($statement);

        return $challenge;
    }


    // Create challenge
    public function createChallenge(
        $title,
        $theme,
        $startDate,
        $endDate,
        $venue,
        $status,
        $organizerId
    ) {
        $sql = "INSERT INTO CHALLENGE
                (
                    CHALLENGE_ID,
                    TITLE,
                    THEME,
                    START_DATE,
                    END_DATE,
                    VENUE,
                    STATUS,
                    ORGANIZER_ID
                )
                VALUES
                (
                    CHALLENGE_SEQ.NEXTVAL,
                    :title,
                    :theme,
                    TO_DATE(:start_date, 'YYYY-MM-DD'),
                    TO_DATE(:end_date, 'YYYY-MM-DD'),
                    :venue,
                    :status,
                    :organizer_id
                )";

        $statement = oci_parse($this->db, $sql);

        oci_bind_by_name($statement, ":title", $title);
        oci_bind_by_name($statement, ":theme", $theme);
        oci_bind_by_name($statement, ":start_date", $startDate);
        oci_bind_by_name($statement, ":end_date", $endDate);
        oci_bind_by_name($statement, ":venue", $venue);
        oci_bind_by_name($statement, ":status", $status);
        oci_bind_by_name($statement, ":organizer_id", $organizerId);

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


    // Update challenge
    public function updateChallenge(
        $challengeId,
        $title,
        $theme,
        $startDate,
        $endDate,
        $venue,
        $status,
        $organizerId
    ) {
        $sql = "UPDATE CHALLENGE
                SET
                    TITLE = :title,
                    THEME = :theme,
                    START_DATE = TO_DATE(:start_date, 'YYYY-MM-DD'),
                    END_DATE = TO_DATE(:end_date, 'YYYY-MM-DD'),
                    VENUE = :venue,
                    STATUS = :status
                WHERE CHALLENGE_ID = :challenge_id
                AND ORGANIZER_ID = :organizer_id";

        $statement = oci_parse($this->db, $sql);

        oci_bind_by_name($statement, ":title", $title);
        oci_bind_by_name($statement, ":theme", $theme);
        oci_bind_by_name($statement, ":start_date", $startDate);
        oci_bind_by_name($statement, ":end_date", $endDate);
        oci_bind_by_name($statement, ":venue", $venue);
        oci_bind_by_name($statement, ":status", $status);
        oci_bind_by_name($statement, ":challenge_id", $challengeId);
        oci_bind_by_name($statement, ":organizer_id", $organizerId);

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


    // Delete challenge
    public function deleteChallenge($challengeId, $organizerId)
    {
        $sql = "DELETE FROM CHALLENGE
                WHERE CHALLENGE_ID = :challenge_id
                AND ORGANIZER_ID = :organizer_id";

        $statement = oci_parse($this->db, $sql);

        oci_bind_by_name(
            $statement,
            ":challenge_id",
            $challengeId
        );

        oci_bind_by_name(
            $statement,
            ":organizer_id",
            $organizerId
        );

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
}

?>
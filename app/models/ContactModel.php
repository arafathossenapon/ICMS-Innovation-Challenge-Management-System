<?php

require_once __DIR__ . '/../../config/database.php';

// ================================
// CONTACT MODEL
// Generic add/delete for the *_EMAIL and *_PHONE tables
// (ORGANIZER_EMAIL/PHONE, PARTICIPANT_EMAIL/PHONE, JUDGE_EMAIL/PHONE).
// They all share the same shape:
//   {X}_EMAIL(Email_ID PK, {Entity}_ID FK, Email UNIQUE)
//   {X}_PHONE(Phone_ID PK, {Entity}_ID FK, Phone)
// ================================

class ContactModel
{
    private $db;

    // Whitelist of allowed entity types -> table/column names.
    // Never build SQL from raw user input for identifiers.
    private static $types = array(
        'organizer' => array(
            'email_table' => 'ORGANIZER_EMAIL',
            'phone_table' => 'ORGANIZER_PHONE',
            'email_seq'   => 'ORGANIZER_EMAIL_SEQ',
            'phone_seq'   => 'ORGANIZER_PHONE_SEQ',
            'fk_column'   => 'ORGANIZER_ID',
        ),
        'participant' => array(
            'email_table' => 'PARTICIPANT_EMAIL',
            'phone_table' => 'PARTICIPANT_PHONE',
            'email_seq'   => 'PARTICIPANT_EMAIL_SEQ',
            'phone_seq'   => 'PARTICIPANT_PHONE_SEQ',
            'fk_column'   => 'PARTICIPANT_ID',
        ),
        'judge' => array(
            'email_table' => 'JUDGE_EMAIL',
            'phone_table' => 'JUDGE_PHONE',
            'email_seq'   => 'JUDGE_EMAIL_SEQ',
            'phone_seq'   => 'JUDGE_PHONE_SEQ',
            'fk_column'   => 'JUDGE_ID',
        ),
    );

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public static function isValidType($type)
    {
        return array_key_exists($type, self::$types);
    }

    private function config($type)
    {
        if (!self::isValidType($type)) {
            throw new Exception("Invalid contact type.");
        }
        return self::$types[$type];
    }

    public function getEmails($type, $entityId)
    {
        $cfg = $this->config($type);

        $sql = "SELECT EMAIL_ID, EMAIL FROM {$cfg['email_table']}
                WHERE {$cfg['fk_column']} = :entity_id
                ORDER BY EMAIL_ID";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":entity_id", $entityId);
        oci_execute($statement);

        $rows = array();
        while ($row = oci_fetch_assoc($statement)) {
            $rows[] = $row;
        }
        oci_free_statement($statement);
        return $rows;
    }

    public function getPhones($type, $entityId)
    {
        $cfg = $this->config($type);

        $sql = "SELECT PHONE_ID, PHONE FROM {$cfg['phone_table']}
                WHERE {$cfg['fk_column']} = :entity_id
                ORDER BY PHONE_ID";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":entity_id", $entityId);
        oci_execute($statement);

        $rows = array();
        while ($row = oci_fetch_assoc($statement)) {
            $rows[] = $row;
        }
        oci_free_statement($statement);
        return $rows;
    }

    public function addEmail($type, $entityId, $email)
    {
        $cfg = $this->config($type);

        $sql = "INSERT INTO {$cfg['email_table']} (EMAIL_ID, {$cfg['fk_column']}, EMAIL)
                VALUES ({$cfg['email_seq']}.NEXTVAL, :entity_id, :email)";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":entity_id", $entityId);
        oci_bind_by_name($statement, ":email", $email);

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

    public function addPhone($type, $entityId, $phone)
    {
        $cfg = $this->config($type);

        $sql = "INSERT INTO {$cfg['phone_table']} (PHONE_ID, {$cfg['fk_column']}, PHONE)
                VALUES ({$cfg['phone_seq']}.NEXTVAL, :entity_id, :phone)";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":entity_id", $entityId);
        oci_bind_by_name($statement, ":phone", $phone);

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

    public function deleteEmail($type, $emailId)
    {
        $cfg = $this->config($type);

        $sql = "DELETE FROM {$cfg['email_table']} WHERE EMAIL_ID = :email_id";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":email_id", $emailId);

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

    public function deletePhone($type, $phoneId)
    {
        $cfg = $this->config($type);

        $sql = "DELETE FROM {$cfg['phone_table']} WHERE PHONE_ID = :phone_id";

        $statement = oci_parse($this->db, $sql);
        oci_bind_by_name($statement, ":phone_id", $phoneId);

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

<?php
/**
 * This class contains all database queries.
 *
 * author: Christian Högerle
 */

require_once( PROJECT_ROOT . 'conf/db_config.php');

class Db {
    protected static $connection;

    /**
     * Connect to the database
     *
     * @return bool false on failure / mysqli MySQLi object instance on success
     */
    public function connect() {
        if(!isset(self::$connection)) {
            self::$connection = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
            self::$connection->set_charset("utf8");
        }

        // If connection was not successful, handle the error
        if(self::$connection === false) {
            print("Keine Verbindung zu Datenbank. Versuchen Sie es später nochmals!");
            // Handle error - notify administrator, log to a file, show an error screen, etc.
            return false;
        }
        return self::$connection;
    }

    /**
     * Query the database for an user
     *
     * @return returns email as string or false
     */
    public function queryForEmail($email) {
        $connection = $this->connect();

        $stmt = $connection->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($dbEmail);
        $stmt->fetch();
        if(!$stmt->fetch()) {
            $dbmail = false;
        }
        $stmt->close();
        return $dbEmail;
    }

    /**
     * Query the database for an email, id and password hash
     *
     * @return returns email as string or false
     */
    public function queryForIDPassEmail($email) {
        $connection = $this->connect();

        $arr = array();

        $stmt = $connection->prepare("SELECT id, active, flagAdmin, password, email FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($id, $active, $flagAdmin, $passwordHash, $dbEmail);
        if(!$stmt->fetch()) {
            $arr['status'] = false;
        } else {
            $arr['status'] = true;
            $arr['active'] = $active;
            $arr['id'] = $id;
            $arr['flagAdmin'] = $flagAdmin;
            $arr['passwordHash'] = $passwordHash;
            $arr['email'] = $dbEmail;
        }

        $stmt->close();
        return $arr;
    }

    /**
     * get all news from database that are marked as active
     *
     * @return returns array of news
     */
    public function queryForNews() {
        $connection = $this->connect();

        $arr = array();

        $stmt = $connection->prepare("SELECT `id`, `titel`, `Text`, `timestamp` FROM `news` WHERE active = 1 ORDER BY timestamp DESC");
        $stmt->execute();
        $stmt->bind_result($id, $titel, $text, $timestamp);
        while($stmt->fetch()) {
            array_push($arr, $id, $titel, $text, $timestamp);
        }
        $stmt->close();
        return $arr;
    }

    /**
     * deactive a news entry by the given id
     *
     * @return returns true on access
     */
    public function deactivateNews($id) {
        $connection = $this->connect();

        $stmt = $connection->prepare("UPDATE `news` SET active=0 WHERE id = ?");
        $stmt->bind_param("i", $id);
        if(!$stmt->execute()) {
            $stmt->close();
            return false;
        };
        $stmt->close();
        return true;
    }


    /**
     * insert a new User
     *
     * @return return true if success
     */
    public function insertNewUser($firstname, $lastname, $email, $hash_pwd) {
        $connection = $this->connect();

        $flagAdmin = 0;

        $stmt = $connection->prepare("INSERT INTO users (flagAdmin, Vorname, Nachname, Email, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $flagAdmin, $firstname, $lastname, $email, $hash_pwd);
        if($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            //printf("Error: %s.\n", $stmt->error);
            $stmt->close();
            return false;
        }
    }

    /**
     * create new "News" entry
     *
     *
     * @return return true if success
     */
    public function insertNewNews($titel, $text) {
        $connection = $this->connect();

        $active = 1;

        $stmt = $connection->prepare("INSERT INTO news (titel, text, active) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $titel, $text, $active);
        if($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }
}

?>

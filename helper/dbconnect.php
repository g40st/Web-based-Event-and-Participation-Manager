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
     * @return returns email of user as string or false
     */
    public function queryForEmail($email) {
        $connection = $this->connect();

        $email = $connection->real_escape_string($email);

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
     * @return returns array of user
     */
    public function queryForIDPassEmail($email) {
        $connection = $this->connect();

        $email = $connection->real_escape_string($email);

        $arr = array();

        $stmt = $connection->prepare("SELECT id, active, flagAdmin, timeFlag, password, email, last_login FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($id, $active, $flagAdmin, $timeFlag, $passwordHash, $dbEmail, $timestamp);
        if(!$stmt->fetch()) {
            $arr['status'] = false;
        } else {
            $arr['status'] = true;
            $arr['active'] = $active;
            $arr['id'] = $id;
            $arr['flagAdmin'] = $flagAdmin;
            $arr['timeFlag'] = $timeFlag;
            $arr['passwordHash'] = $passwordHash;
            $arr['email'] = $dbEmail;
            $arr['timestamp'] = $timestamp;
        }

        $stmt->close();
        return $arr;
    }

    /**
     * Query the database for the upcoming events
     *
     * @return returns json array of events
     */
    public function queryForEvents($start, $end) {
        $connection = $this->connect();

        $start = $connection->real_escape_string($start);
        $end = $connection->real_escape_string($end);

        $events = array();

        $stmt = $connection->prepare("SELECT id, start , end, title, timestamp FROM  events where (date(start) >= ? AND date(start) <= ?)");
        $stmt->bind_param("ss", $start, $end);
        $stmt->execute();
        $stmt->bind_result($id, $start, $end, $title, $timestamp);
        while($stmt->fetch()) {
            $e = array();
            $e['id']= $id;
            $e['start']= $start;
            $e['end']= $end;
            $e['title']= $title;

            // highlight new events
            if($_SESSION['lastLogin'] < $timestamp) {
              $e['color']= '#ff9933';
            }
            array_push($events, $e);
        }
        $stmt->close();

        return json_encode($events);
    }

    /**
     * Query the database for the events on a day
     *
     * @return returns json array of events
     */
    public function queryForEventsOnDay($start) {
        $connection = $this->connect();

        $start = $connection->real_escape_string($start);

        $events = array();

        $stmt = $connection->prepare("SELECT id, start , end, title, description, participants FROM events where (date(start) = ?)");
        $stmt->bind_param("s", $start);
        $stmt->execute();
        $stmt->bind_result($id, $start, $end, $title, $description, $participants);
        while($stmt->fetch()) {
            $e = array();
            $e['id']= $id;
            $e['start']= $start;
            $e['end']= $end;
            $e['title']= $title;
            $e['description']= $description;
            $e['participants']= $participants;
            array_push($events, $e);
        }
        $stmt->close();

        return $events;
    }

    /**
     * Query the database for the participants on a event
     *
     * @return returns return an arry of names and id's
     */
    public function queryForParticipantsOnEvent($event_id) {
        $connection = $this->connect();

        $event_id = $connection->real_escape_string($event_id);

        $arr_id = array();

        $stmt = $connection->prepare("SELECT u.id, u.Vorname, u.Nachname FROM users_events ue, users u WHERE ue.event_id = ? AND ue.users_id = u.id");
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $stmt->bind_result($id, $forename, $lastname);
        $participants_string = "";
        while($stmt->fetch()) {
            $participants_string .= $forename . " " . $lastname . "; ";
            // check if user is already a participant
            array_push($arr_id, $id);
        }
        $stmt->close();

        return [$participants_string, $arr_id];
    }

    /**
     * Query the database for the working hours of the users
     *
     * @return returns return an arry of names and id's
     */
    public function queryForWorkingHours() {
        $connection = $this->connect();

        $users = array();
        $tmp_user_id = 0;

        $stmt = $connection->prepare("SELECT users.id, users.Vorname, users.Nachname, users_events.start_workingTime, users_events.end_workingTime FROM users_events INNER JOIN users ON users_events.users_id = users.id ORDER BY users_id");
        $stmt->execute();
        $stmt->bind_result($user_id, $firstname, $lastname, $start_workingTime, $end_workingTime);

        $u = null;
        while($stmt->fetch()) {
          if($u == null) {
            $tmp_user_id = $user_id;
            $u = array();
            $u['id'] = $user_id;
            $u['firstname'] = $firstname;
            $u['lastname'] = $lastname;
            $u['time'] = 0;
            $time1 = strtotime($start_workingTime);
            $time2 = strtotime($end_workingTime);
            if($time2 < $time1) {
                $time2 += 24 * 60 * 60;
            }
            $u['time'] += ($time2 - $time1) / 60;
          } else if($tmp_user_id == $user_id) {
            $time1 = strtotime($start_workingTime);
            $time2 = strtotime($end_workingTime);
            if($time2 < $time1) {
                $time2 += 24 * 60 * 60;
            }
            $u['time']+= ($time2 - $time1) / 60;
          } else {
            $tmp_user_id = $user_id;
            array_push($users, $u);
            $u = array();
            $u['id']= $user_id;
            $u['firstname']= $firstname;
            $u['lastname']= $lastname;
            $u['time'] = 0;
            $time1 = strtotime($start_workingTime);
            $time2 = strtotime($end_workingTime);
            if($time2 < $time1) {
                $time2 += 24 * 60 * 60;
            }
            $u['time']+= ($time2 - $time1) / 60;
          }
        }
        array_push($users, $u);

        $stmt->close();

        return $users;
    }

    /**
     * Query the database for the working time of a user on a event
     *
     * @return returns returns the start and end time of the working time
     */
    public function queryForWorkingTimeOfUserOnEvent($user_id, $event_id) {
        $connection = $this->connect();

        $event_id = $connection->real_escape_string($event_id);

        $stmt = $connection->prepare("SELECT start_workingTime, end_workingTime FROM users_events WHERE event_id=? AND users_id=?");
        $stmt->bind_param("ii", $event_id, $user_id);
        $stmt->execute();
        $stmt->bind_result($start_workingTime, $end_workingTime);
        $stmt->fetch();
        $stmt->close();

        return [$start_workingTime, $end_workingTime];
    }

    /**
     * Query the database for the comments on a event
     *
     * @return returns return an string of the comments
     */
    public function queryForCommentsOnEvent($event_id) {
        $connection = $this->connect();

        $event_id = $connection->real_escape_string($event_id);

        $stmt = $connection->prepare("SELECT co.comment, co.timestamp, u.Vorname, u.Nachname FROM comments co, users u WHERE co.id_event = ? AND co.id_user = u.id ORDER BY co.timestamp DESC");
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $stmt->bind_result($comment,$timestamp,$firstname,$lastname);
        $comment_string = "";
        $timestamp = new DateTime($timestamp);
        while($stmt->fetch()) {
            $comment_string .= $comment . " - ". $firstname . " " . $lastname . " [" . $timestamp . "] <hr> ";
        }
        $stmt->close();

        return $comment_string;
    }

    /**
     * Query the database for users that are not activated yet
     *
     * @return returns array of users
     */
    public function queryForNonActUsers() {
        $connection = $this->connect();

        $arr = array();

        $stmt = $connection->prepare("SELECT id, vorname, nachname, email FROM users WHERE active = 0");
        $stmt->execute();
        $stmt->bind_result($id, $forename, $surname, $dbEmail);
        while($stmt->fetch()) {
            array_push($arr, $id, $forename, $surname, $dbEmail);
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
     * @return returns true on success
     */
    public function deactivateNews($id) {
        $connection = $this->connect();

        $id = $connection->real_escape_string($id);

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
     * update the last login time of user
     *
     * @return returns true on success
     */
    public function updateLastLoginTime($id, $actTime) {
        $connection = $this->connect();

        $id = $connection->real_escape_string($id);

        $stmt = $connection->prepare("UPDATE `users` SET last_login=? WHERE id = ?");
        $stmt->bind_param("si", $actTime,$id);
        if(!$stmt->execute()) {
            $stmt->close();
            return false;
        };
        $stmt->close();
        return true;
    }


    /**
     * activate a new user
     *
     * @return returns true on success
     */
    public function activateUser($id) {
        $connection = $this->connect();

        $id = $connection->real_escape_string($id);

        $stmt = $connection->prepare("UPDATE `users` SET active=1 WHERE id = ?");
        $stmt->bind_param("i", $id);
        if(!$stmt->execute()) {
            $stmt->close();
            return false;
        };
        $stmt->close();
        return true;
    }

    /**
     * reset password for user
     *
     * @return returns true on success
     */
    public function resetPassword($email, $hash_pwd) {
        $connection = $this->connect();

        $email = $connection->real_escape_string($email);

        $stmt = $connection->prepare("UPDATE `users` SET password=? WHERE email = ?");
        $stmt->bind_param("ss", $hash_pwd, $email);
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

        $firstname = $connection->real_escape_string($firstname);
        $lastname = $connection->real_escape_string($lastname);
        $email = $connection->real_escape_string($email);
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
     * insert a comment
     *
     * @return return true if success
     */
    public function insertNewComment($userID, $eventID, $comment) {
        $connection = $this->connect();

        $stmt = $connection->prepare("INSERT INTO comments (id_user, id_event, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $userID, $eventID, $comment);
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

        $titel = $connection->real_escape_string($titel);
        $text = $connection->real_escape_string($text);
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
        printf("Error: %s.\n", $stmt->error);
    }

    /**
     * create a new event in events table
     *
     *
     * @return return true if success
     */
    public function insertNewEvent($title, $amountP, $start, $end, $description) {
        $connection = $this->connect();

        $title = $connection->real_escape_string($title);
        $amountP = $connection->real_escape_string($amountP);
        $start = $connection->real_escape_string($start);
        $end = $connection->real_escape_string($end);
        $description = $connection->real_escape_string($description);

        $stmt = $connection->prepare("INSERT INTO events (id, title, start, end, participants, description) VALUES (NULL, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $title, $start, $end, $amountP, $description);
        if($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
        printf("Error: %s.\n", $stmt->error);
    }

    /**
     * insert entry to users_events
     *
     *
     * @return return true if success
     */
    public function insertNewParticipant($user_id, $event_id) {
        $connection = $this->connect();

        $user_id = $connection->real_escape_string($user_id);
        $event_id = $connection->real_escape_string($event_id);

        $stmt = $connection->prepare("INSERT INTO users_events (users_id, event_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $event_id);
        if($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }


    /**
     * insert entry to users_events |
     *
     *
     * @return return true if success
     */
    public function insertWorkingTimeForUser($user_id, $event_id, $start, $end) {
        $connection = $this->connect();

        $user_id = $connection->real_escape_string($user_id);
        $event_id = $connection->real_escape_string($event_id);
        $start = $connection->real_escape_string($start);
        $end = $connection->real_escape_string($end);

        $stmt = $connection->prepare("UPDATE `users_events` SET start_workingTime=?, end_workingTime=? WHERE users_id=? AND event_id=?");
        $stmt->bind_param("ssii", $start, $end, $user_id, $event_id);
        if($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    /**
     * delete entry from users_events
     *
     *
     * @return return true if success
     */
    public function deleteEntryFromUsersEvents($user_id, $event_id) {
        $connection = $this->connect();

        $user_id = $connection->real_escape_string($user_id);
        $event_id = $connection->real_escape_string($event_id);

        $stmt = $connection->prepare("DELETE FROM users_events WHERE users_id=? AND event_id=?");
        $stmt->bind_param("ii", $user_id, $event_id);
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

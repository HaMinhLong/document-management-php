<?php
class UserGroupModel
{
  private $data = [];

  function __construct($conn)
  {
    $this->host = $conn->host;
    $this->user = $conn->user;
    $this->pass = $conn->pass;
    $this->db = $conn->db;
  }

  // open mysql data base
  public function open_db()
  {
    $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);
    if ($this->conn->connect_error) {
      die("Xảy ra lỗi khi kết nối với database: " . $this->conn->connect_error);
    }
  }
  // close database
  public function close_db()
  {
    $this->conn->close();
  }
  // select record
  public function selectRecord($id)
  {
    try {
      $this->open_db();
      if ($id > 0) {
        $query = $this->conn->prepare("SELECT * FROM userGroup WHERE id=?");
        $query->bind_param("i", $id);
      } else {
        $query = $this->conn->prepare("SELECT * FROM userGroup");
      }
      $query->execute();
      $result = $query->get_result();
      $query->close();
      $this->close_db();
      return $result;
    } catch (Exception $e) {
      $this->close_db();
      throw $e;
    }
  }
  // insert record
  public function insertRecord($obj)
  {
    try {
      $this->open_db();
      // check usernameGroup exits
      $checkNameExits = $this->conn->prepare(
        "SELECT id FROM userGroup WHERE userGroupName=?"
      );
      $checkNameExits->bind_param("s", $obj->userGroupName);
      $checkNameExits->execute();
      $checkNameResult = $checkNameExits->get_result();
      $checkNameExits->close();
      // if exits
      if ($checkNameResult->num_rows > 0) {
        $this->close_db();
        return "nameExits";
      } else {
        $query = $this->conn->prepare(
          "INSERT INTO userGroup (id, userGroupName, description, status) VALUES (?, ?, ?, ?)"
        );
        $query->bind_param(
          "isss",
          $obj->id,
          $obj->userGroupName,
          $obj->description,
          $obj->status
        );
        $query->execute();
        $result = $query->get_result();
        $last_id = $this->conn->insert_id;
        $query->close();
        $this->close_db();
        return $last_id;
      }
    } catch (Exception $e) {
      $this->close_db();
      throw $e;
    }
  }
  //update record
  public function updateRecord($obj)
  {
    try {
      $this->open_db();

      if ($obj->oldUserGroupName === $obj->userGroupName) {
        $query = $this->conn->prepare(
          "UPDATE userGroup SET userGroupName=?,description=?,status=? WHERE id=?"
        );
        $query->bind_param(
          "sssi",
          $obj->userGroupName,
          $obj->description,
          $obj->status,
          $obj->id
        );
        $query->execute();
        $result = $query->get_result();
        $query->close();
        $this->close_db();
        return true;
      } else {
        $checkNameExits = $this->conn->prepare(
          "SELECT id FROM userGroup WHERE userGroupName=?"
        );
        $checkNameExits->bind_param("s", $obj->userGroupName);
        $checkNameExits->execute();
        $checkNameResult = $checkNameExits->get_result();
        $checkNameExits->close();

        if ($checkNameResult->num_rows > 0) {
          $this->close_db();
          return "nameExits";
        } else {
          $query = $this->conn->prepare(
            "UPDATE userGroup SET userGroupName=?,description=?,status=? WHERE id=?"
          );
          $query->bind_param(
            "sssi",
            $obj->userGroupName,
            $obj->description,
            $obj->status,
            $obj->id
          );
          $query->execute();
          $result = $query->get_result();
          $query->close();
          $this->close_db();
          return true;
        }
      }
    } catch (Exception $e) {
      $this->close_db();
      throw $e;
    }
  }
  // delete record
  public function deleteRecord($id)
  {
    try {
      $this->open_db();
      $query = $this->conn->prepare("DELETE FROM `userGroup` WHERE id=?");
      $query->bind_param("i", $id);
      $query->execute();
      $result = $query->get_result();
      $query->close();
      $this->close_db();
      return true;
    } catch (Exception $e) {
      $this->closeDb();
      throw $e;
    }
  }
}
?>
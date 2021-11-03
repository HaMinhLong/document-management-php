<?php
class UserModel
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
  public function selectRecord($obj)
  {
    try {
      $this->open_db();
      $record_per_page = 10;
      $page = "";
      $output = "";
      if (isset($_GET["page"])) {
        $page = $_GET["page"];
      } else {
        $page = 1;
      }
      $start_from = ($page - 1) * $record_per_page;
      if ($obj) {
        $query = $this->conn->prepare(
          "SELECT user.id,username,fullName,email,userGroupName,user.status FROM user JOIN userGroup ON user.userGroupId = userGroup.id WHERE username LIKE '%$obj->username%' AND userGroupId LIKE '%$obj->userGroupId%' AND  user.status LIKE '%$obj->status%' LIMIT $start_from, $record_per_page"
        );
      } else {
        $query = $this->conn->prepare(
          "SELECT user.id,username,fullName,email,userGroupName,user.status FROM user JOIN userGroup ON user.userGroupId = userGroup.id LIMIT $start_from, $record_per_page"
        );
      }
      $query->execute();
      $result = $query->get_result();
      if ($result->num_rows > 0) {
        $output .= '<table id="example2" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th style="width: 15%">Tài khoản</th>
                <th style="width: 15%">Tên đầy đủ</th>
                <th style="width: 15%">Email</th>
                <th style="width: 20%">Nhóm tài khoản</th>
                <th style="width: 10%">Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>';
        while ($row = mysqli_fetch_array($result)) {
          $output .= "<tr>";
          $output .= "<td>" . $row["username"] . "</td>";
          $output .= "<td>" . $row["fullName"] . "</td>";
          $output .= "<td>" . $row["email"] . "</td>";
          $output .= "<td>" . $row["userGroupName"] . "</td>";
          $output .=
            $row["status"] == "1" ? "<td>Kích hoạt</td>" : "<td>Ẩn</td>";
          $output .= "<td>";
          $output .=
            "<a href='user?act=update&id=" .
            $row["id"] .
            "' data-toggle='tooltip' title='Sửa bản ghi'>
                      <button type='button' class='btn btn-outline-primary btn-sm'>Sửa&nbsp;&nbsp;
                          <i class='fa fa-edit'></i>
                      </button>
                  </a>&nbsp;&nbsp;";
          $output .=
            "<a href='#' data-toggle='modal' data-target='#modal-danger' onclick='setDeleteRecordId(" .
            $row["id"] .
            ")' data-toggle='tooltip' title='Xóa bản ghi'>
                      <button type='button' class='btn btn-outline-danger btn-sm'>Xóa&nbsp;&nbsp;
                          <i class='fa fa-trash'></i>
                      </button>
                  </a>";
          $output .= "</td>";
          $output .= "</tr>";
        }
        $output .= '</tbody>
        </table>';
      } else {
        $output .= "<p class='lead'><em>No records were found.</em></p>";
      }
      $output .= '<br /><div align="right">';
      $page_query = "";
      if ($obj) {
        $page_query = $this->conn->prepare(
          "SELECT * FROM user WHERE username LIKE '%$obj->username%' AND status LIKE '%$obj->status%' ORDER BY username DESC"
        );
      } else {
        $page_query = $this->conn->prepare(
          "SELECT * FROM user ORDER BY username DESC"
        );
      }
      $page_query->execute();
      $page_result = $page_query->get_result();
      $total_records = mysqli_num_rows($page_result);
      $total_pages = ceil($total_records / $record_per_page);
      for ($i = 1; $i <= $total_pages; $i++) {
        $output .=
          "<span class='pagination_link' style='cursor:pointer; padding:6px 12px; border:1px solid #ccc; margin-right: 5px' id='" .
          $i .
          "'>" .
          $i .
          "</span>";
      }
      $output .= "</div><br /><br />";
      $page_query->close();
      $query->close();
      $this->close_db();
      echo $output;
    } catch (Exception $e) {
      $this->close_db();
      throw $e;
    }
  }
  public function select()
  {
    echo "select";
  }
  public function getRecord($obj)
  {
    try {
      $this->open_db();
      if ($obj) {
        $query = $this->conn->prepare("SELECT * FROM user WHERE id = $obj");
      } else {
        $query = $this->conn->prepare("SELECT * FROM user");
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
        "SELECT id FROM user WHERE username=? OR email=?"
      );
      $checkNameExits->bind_param("ss", $obj->username, $obj->email);
      $checkNameExits->execute();
      $checkNameResult = $checkNameExits->get_result();
      $checkNameExits->close();
      // if exits
      if ($checkNameResult->num_rows > 0) {
        $this->close_db();
        return "nameExits";
      } else {
        $query = $this->conn->prepare(
          "INSERT INTO user (id, username, password, fullName, email, mobile, majorsId, userGroupId, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $query->bind_param(
          "isssssiis",
          $obj->id,
          $obj->username,
          $obj->password,
          $obj->fullName,
          $obj->email,
          $obj->mobile,
          $obj->majorsId,
          $obj->userGroupId,
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

      if (
        $obj->oldUsername === $obj->username ||
        $obj->oldEmail === $obj->email
      ) {
        $query = $this->conn->prepare(
          "UPDATE user SET username=?,password=?,fullName=?,email=?,mobile=?,majorsId=?,userGroupId=?,status=? WHERE id=?"
        );
        $query->bind_param(
          "sssssiisi",
          $obj->username,
          $obj->password,
          $obj->fullName,
          $obj->email,
          $obj->mobile,
          $obj->majorsId,
          $obj->userGroupId,
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
          "SELECT id FROM user WHERE username=? OR email=?"
        );
        $checkNameExits->bind_param("ss", $obj->username, $obj->email);
        $checkNameExits->execute();
        $checkNameResult = $checkNameExits->get_result();
        $checkNameExits->close();

        if ($checkNameResult->num_rows > 0) {
          $this->close_db();
          return "nameExits";
        } else {
          $query = $this->conn->prepare(
            "UPDATE user SET username=?,password=?,fullName=?,email=?,mobile=?,majorsId=?,userGroupId=?,status=? WHERE id=?"
          );
          $query->bind_param(
            "sssssiisi",
            $obj->username,
            $obj->password,
            $obj->fullName,
            $obj->email,
            $obj->mobile,
            $obj->majorsId,
            $obj->userGroupId,
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
      $query = $this->conn->prepare("DELETE FROM `user` WHERE id=?");
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
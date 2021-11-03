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
          "SELECT * FROM userGroup WHERE userGroupName LIKE '%$obj->userGroupName%' AND status LIKE '%$obj->status%' LIMIT $start_from, $record_per_page"
        );
      } else {
        $query = $this->conn->prepare(
          "SELECT * FROM userGroup LIMIT $start_from, $record_per_page"
        );
      }
      $query->execute();
      $result = $query->get_result();
      if ($result->num_rows > 0) {
        $output .= '<table id="example2" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th style="width: 40%">Nhóm tài khoản</th>
                <th style="width: 30%">Mô tả</th>
                <th style="width: 15%">Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>';
        while ($row = mysqli_fetch_array($result)) {
          $output .= "<tr>";
          $output .= "<td>" . $row["userGroupName"] . "</td>";
          $output .= "<td>" . $row["description"] . "</td>";
          $output .=
            $row["status"] == "1" ? "<td>Kích hoạt</td>" : "<td>Ẩn</td>";
          $output .= "<td>";
          $output .=
            "<a href='user-group?act=update&id=" .
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
          "SELECT * FROM userGroup WHERE userGroupName LIKE '%$obj->userGroupName%' AND status LIKE '%$obj->status%' ORDER BY userGroupName DESC"
        );
      } else {
        $page_query = $this->conn->prepare(
          "SELECT * FROM userGroup ORDER BY userGroupName DESC"
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
    $this->open_db();
    $output = "";
    $query = $this->conn->prepare("SELECT * FROM userGroup WHERE status = '1'");
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows > 0) {
      $output .= "<option value=''>Chọn nhóm tài khoản
        </option>
                                            ";
      while ($row = mysqli_fetch_array($result)) {
        $output .=
          "<option value=" .
          $row["id"] .
          ">" .
          $row["userGroupName"] .
          "</option>
                                            ";
      }
    }
    $query->close();
    $this->close_db();
    echo $output;
  }
  public function getRecord($obj)
  {
    try {
      $this->open_db();
      if ($obj) {
        $query = $this->conn->prepare(
          "SELECT * FROM userGroup WHERE id = $obj"
        );
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
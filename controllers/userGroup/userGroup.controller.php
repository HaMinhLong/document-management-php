<?php

require "models/userGroup/userGroup.model.php";
require "models/userGroup/userGroup.php";
require "config.php";

session_status() === PHP_SESSION_ACTIVE ? true : session_start();

class UserGroupController
{
  function __construct()
  {
    $this->config = new Config();
    $this->userGroup = new UserGroupModel($this->config);
  }
  public function mvcHandler()
  {
    $act = isset($_GET["act"]) ? $_GET["act"] : null;
    switch ($act) {
      case "add":
        $this->insert();
        break;
      case "update":
        $this->update();
        break;
      case "delete":
        $this->delete();
        break;
      default:
        $this->list();
    }
  }
  // page redirection
  public function pageRedirect($url)
  {
    header("Location:" . $url);
  }
  public function alert($message, $url)
  {
    echo '<script language="javascript">';
    echo 'alert("' . $message . '"); location.href="' . $url . '"';
    echo "</script>";
  }
  public function checkValidation($userGroup)
  {
    $noError = true;
    // Validate userGroupName
    if (!$userGroup->userGroupName) {
      $userGroup->nameMsg = "Vui lòng điền thông tin";
      $noError = false;
    } elseif (
      !filter_var($userGroup->userGroupName, FILTER_VALIDATE_REGEXP, [
        "options" => [
          "regexp" =>
            "/^[A-Za-zÀÁẢẠÃẦẤẨẬẪÂẮẰẶẴĂẲÈÉẸẺẼỂẾỀỆỄÊỊÌÍĨỈÒÓỎỌÕÔỐỒỔỘỖỜỚỠỢỞƠÙÚỤỦŨỨỪỬỮỰƯÝỲỶỸỴàáảạãầấẩậẫâắằặẵăẳèéẹẻẽểếềệễêịìíĩỉòóỏọõôốồổộỗờớỡợởơùúụủũứừửữựưýỳỷỹỵ ]{3,50}$$/i",
        ],
      ])
    ) {
      $userGroup->nameMsg =
        "Vui lòng nhập từ 3 đến 50 ký tự bao gồm chữ, số và bắt đầu bằng chữ cái";
      $noError = false;
    } else {
      $userGroup->nameMsg = "";
    }
    // Validate description
    if (!$userGroup->description) {
      $userGroup->descriptionMsg = "Vui lòng điền thông tin";
      $noError = false;
    } elseif (
      !filter_var($userGroup->description, FILTER_VALIDATE_REGEXP, [
        "options" => [
          "regexp" =>
            "/^[A-Za-zÀÁẢẠÃẦẤẨẬẪÂẮẰẶẴĂẲÈÉẸẺẼỂẾỀỆỄÊỊÌÍĨỈÒÓỎỌÕÔỐỒỔỘỖỜỚỠỢỞƠÙÚỤỦŨỨỪỬỮỰƯÝỲỶỸỴàáảạãầấẩậẫâắằặẵăẳèéẹẻẽểếềệễêịìíĩỉòóỏọõôốồổộỗờớỡợởơùúụủũứừửữựưýỳỷỹỵ ]{3,300}$$/i",
        ],
      ])
    ) {
      $userGroup->descriptionMsg =
        "Vui lòng nhập từ 3 đến 300 ký tự bao gồm chữ, số và bắt đầu bằng chữ cái";
      $noError = false;
    } else {
      $userGroup->descriptionMsg = "";
    }
    // Validate status
    if (!empty($userGroup->status)) {
      $userGroup->statusMsg = "Vui lòng chọn thông tin";
      $noError = false;
    } else {
      $userGroup->statusMsg = "";
    }
    return $noError;
  }

  // add new record
  public function insert()
  {
    try {
      if (isset($_POST["add-btn"])) {
        // read form value
        $newUserGroup = new UserGroup();
        $newUserGroup->id = null;
        $newUserGroup->userGroupName = trim($_POST["userGroupName"]);
        $newUserGroup->description = trim($_POST["description"]);
        $newUserGroup->status = trim($_POST["status"]);
        // call validation
        $checkNoError = $this->checkValidation($newUserGroup);
        if ($checkNoError) {
          //call insert record
          $result = $this->userGroup->insertRecord($newUserGroup);
          if ($result > 0 && $result !== "nameExits") {
            $this->alert("Tạo mới bản ghi thành công", "user-group");
          } elseif ($result === "nameExits") {
            $newUserGroup->nameExits = "Nhóm tài khoản đã tồn tại!";
            $_SESSION["userGroup"] = serialize($newUserGroup); //add session obj
            $this->pageRedirect("user-group/insert");
          } else {
            $this->alert(
              "Xảy ra lỗi khi thêm mới bản ghi, vui lòng thử lại!",
              "user-group/insert"
            );
          }
        } else {
          $_SESSION["userGroup"] = serialize($newUserGroup); //add session obj
          $this->pageRedirect("user-group/insert");
        }
      } else {
        echo "Hành động không hợp lệ. Vui lòng thử lại!";
      }
    } catch (Exception $e) {
      $this->close_db();
      throw $e;
    }
  }

  // update record
  public function update()
  {
    try {
      if (isset($_POST["update-btn"])) {
        $userGroup = new UserGroup();
        $userGroup->id = trim($_GET["id"]);
        $userGroup->oldUserGroupName = trim($_POST["oldUserGroupName"]);
        $userGroup->userGroupName = trim($_POST["userGroupName"]);
        $userGroup->description = trim($_POST["description"]);
        $userGroup->status = trim($_POST["status"]);
        // check validation
        $checkNoError = $this->checkValidation($userGroup);
        if ($checkNoError) {
          $result = $this->userGroup->updateRecord($userGroup);
          if ($result && $result !== "nameExits") {
            $this->alert("Cập nhật bản ghi thành công", "user-group");
          } elseif ($result === "nameExits") {
            $userGroup->nameExits = "Nhóm tài khoản đã tồn tại!";
            $_SESSION["userGroup"] = serialize($userGroup); //add session obj
            $this->pageRedirect("user-group/update");
          } else {
            $this->alert(
              "Xảy ra lỗi khi cập nhật bản ghi, vui lòng thử lại!",
              "user-group/update"
            );
          }
        } else {
          $_SESSION["userGroup"] = serialize($userGroup);
          $this->pageRedirect("user-group/update");
        }
      } elseif (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        $id = $_GET["id"];
        $result = $this->userGroup->selectRecord($id);
        $row = mysqli_fetch_array($result);
        $userGroup = new UserGroup();
        $userGroup->id = $row["id"];
        $userGroup->userGroupName = $row["userGroupName"];
        $userGroup->description = $row["description"];
        $userGroup->status = $row["status"];
        $_SESSION["userGroup"] = serialize($userGroup);
        $this->pageRedirect("user-group/update");
      } else {
        $this->alert("Hành động không hợp lệ. Vui lòng thử lại!", "user-group");
      }
    } catch (Exception $e) {
      $this->close_db();
      throw $e;
    }
  }
  // delete record
  public function delete()
  {
    try {
      if (isset($_GET["id"])) {
        $id = $_GET["id"];
        $result = $this->userGroup->deleteRecord($id);
        if ($result) {
          $this->alert("Xóa bản ghi thành công", "user-group");
        } else {
          $this->alert(
            "Xảy ra lỗi khi xóa bản ghi, vui lòng thử lại!",
            "user-group"
          );
        }
      } else {
        $this->alert("Hành động không hợp lệ. Vui lòng thử lại!", "user-group");
      }
    } catch (Exception $e) {
      $this->close_db();
      throw $e;
    }
  }
  // get list
  public function list()
  {
    $result = $this->userGroup->selectRecord(0);
    include "views/userGroup/list.php";
  }
}

?>
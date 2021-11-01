<?php

require "models/user/user.model.php";
require "models/user/user.php";
require "config.php";

session_status() === PHP_SESSION_ACTIVE ? true : session_start();

class UserController
{
  function __construct()
  {
    $this->config = new Config();
    $this->user = new UserModel($this->config);
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
      case "page":
        $this->page();
        break;
      case "select":
        $this->select();
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
  public function checkValidation($user)
  {
    $noError = true;
    // Validate username
    if (!$user->username) {
      $user->nameMsg = "Vui lòng điền thông tin";
      $noError = false;
    } elseif (
      !filter_var($user->username, FILTER_VALIDATE_REGEXP, [
        "options" => [
          "regexp" =>
            "/^[A-Za-zÀÁẢẠÃẦẤẨẬẪÂẮẰẶẴĂẲÈÉẸẺẼỂẾỀỆỄÊỊÌÍĨỈÒÓỎỌÕÔỐỒỔỘỖỜỚỠỢỞƠÙÚỤỦŨỨỪỬỮỰƯÝỲỶỸỴàáảạãầấẩậẫâắằặẵăẳèéẹẻẽểếềệễêịìíĩỉòóỏọõôốồổộỗờớỡợởơùúụủũứừửữựưýỳỷỹỵ ]{3,50}$$/i",
        ],
      ])
    ) {
      $user->nameMsg =
        "Vui lòng nhập từ 3 đến 50 ký tự bao gồm chữ, số và bắt đầu bằng chữ cái";
      $noError = false;
    } else {
      $user->nameMsg = "";
    }
    // Validate description
    if (!$user->description) {
      $user->descriptionMsg = "Vui lòng điền thông tin";
      $noError = false;
    } elseif (
      !filter_var($user->description, FILTER_VALIDATE_REGEXP, [
        "options" => [
          "regexp" =>
            "/^[A-Za-zÀÁẢẠÃẦẤẨẬẪÂẮẰẶẴĂẲÈÉẸẺẼỂẾỀỆỄÊỊÌÍĨỈÒÓỎỌÕÔỐỒỔỘỖỜỚỠỢỞƠÙÚỤỦŨỨỪỬỮỰƯÝỲỶỸỴàáảạãầấẩậẫâắằặẵăẳèéẹẻẽểếềệễêịìíĩỉòóỏọõôốồổộỗờớỡợởơùúụủũứừửữựưýỳỷỹỵ ]{3,300}$$/i",
        ],
      ])
    ) {
      $user->descriptionMsg =
        "Vui lòng nhập từ 3 đến 300 ký tự bao gồm chữ, số và bắt đầu bằng chữ cái";
      $noError = false;
    } else {
      $user->descriptionMsg = "";
    }
    // Validate status
    if (!empty($user->status)) {
      $user->statusMsg = "Vui lòng chọn thông tin";
      $noError = false;
    } else {
      $user->statusMsg = "";
    }
    return $noError;
  }

  // add new record
  public function insert()
  {
    try {
      if (isset($_POST["add-btn"])) {
        // read form value
        $newUser = new User();
        $newUser->id = null;
        $newUser->username = trim($_POST["username"]);
        $newUser->description = trim($_POST["description"]);
        $newUser->status = trim($_POST["status"]);
        // call validation
        $checkNoError = $this->checkValidation($newUser);
        if ($checkNoError) {
          //call insert record
          $result = $this->user->insertRecord($newUser);
          if ($result > 0 && $result !== "nameExits") {
            $this->alert("Tạo mới bản ghi thành công", "user");
          } elseif ($result === "nameExits") {
            $newUser->nameExits = "Tài khoản đã tồn tại!";
            $_SESSION["user"] = serialize($newUser); //add session obj
            $this->pageRedirect("user/insert");
          } else {
            $this->alert(
              "Xảy ra lỗi khi thêm mới bản ghi, vui lòng thử lại!",
              "user/insert"
            );
          }
        } else {
          $_SESSION["user"] = serialize($newUser); //add session obj
          $this->pageRedirect("user/insert");
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
        $user = new User();
        $user->id = trim($_GET["id"]);
        $user->oldUsername = trim($_POST["oldUsername"]);
        $user->username = trim($_POST["username"]);
        $user->description = trim($_POST["description"]);
        $user->status = trim($_POST["status"]);
        // check validation
        $checkNoError = $this->checkValidation($user);
        if ($checkNoError) {
          $result = $this->user->updateRecord($user);
          if ($result && $result !== "nameExits") {
            $this->alert("Cập nhật bản ghi thành công", "user");
          } elseif ($result === "nameExits") {
            $user->nameExits = "Tài khoản đã tồn tại!";
            $_SESSION["user"] = serialize($user); //add session obj
            $this->pageRedirect("user/update");
          } else {
            $this->alert(
              "Xảy ra lỗi khi cập nhật bản ghi, vui lòng thử lại!",
              "user/update"
            );
          }
        } else {
          $_SESSION["user"] = serialize($user);
          $this->pageRedirect("user/update");
        }
      } elseif (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        unset($_SESSION["user"]);
        $id = $_GET["id"];
        $result = $this->user->getRecord($id);
        $row = mysqli_fetch_array($result);
        $user = new User();
        $user->id = $row["id"];
        $user->username = $row["username"];
        $user->description = $row["description"];
        $user->status = $row["status"];
        $_SESSION["user"] = serialize($user);
        $this->pageRedirect("user/update");
      } else {
        $this->alert("Hành động không hợp lệ. Vui lòng thử lại!", "user");
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
        $result = $this->user->deleteRecord($id);
        if ($result) {
          $this->alert("Xóa bản ghi thành công", "user");
        } else {
          $this->alert("Xảy ra lỗi khi xóa bản ghi, vui lòng thử lại!", "user");
        }
      } else {
        $this->alert("Hành động không hợp lệ. Vui lòng thử lại!", "user");
      }
    } catch (Exception $e) {
      $this->close_db();
      throw $e;
    }
  }
  // get list
  public function page()
  {
    $filters = new User();
    $filters->username = trim($_GET["username"]);
    $filters->status = trim($_GET["status"]);
    $filters->userGroupId = trim($_GET["userGroupId"]);
    if (
      isset($_GET["username"]) ||
      isset($_GET["status"]) ||
      isset($_GET["userGroupId"])
    ) {
      $this->user->selectRecord($filters);
    } else {
      $this->user->selectRecord(0);
    }
  }
  public function select()
  {
    $this->user->select();
  }
  public function list()
  {
    include "views/user/list.php";
  }
}

?>
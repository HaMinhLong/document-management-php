<?php

require "models/subjectType/subjectType.model.php";
require "models/subjectType/subjectType.php";
require "config.php";

session_status() === PHP_SESSION_ACTIVE ? true : session_start();

class SubjectTypeController
{
  function __construct()
  {
    $this->config = new Config();
    $this->subjectType = new SubjectTypeModel($this->config);
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
  public function checkValidation($subjectType)
  {
    $noError = true;
    // Validate subjectTypeName
    if (!$subjectType->subjectTypeName) {
      $subjectType->nameMsg = "Vui lòng điền thông tin";
      $noError = false;
    } elseif (
      !filter_var($subjectType->subjectTypeName, FILTER_VALIDATE_REGEXP, [
        "options" => [
          "regexp" =>
            "/^[A-Za-zÀÁẢẠÃẦẤẨẬẪÂẮẰẶẴĂẲÈÉẸẺẼỂẾỀỆỄÊỊÌÍĨỈÒÓỎỌÕÔỐỒỔỘỖỜỚỠỢỞƠÙÚỤỦŨỨỪỬỮỰƯÝỲỶỸỴàáảạãầấẩậẫâắằặẵăẳèéẹẻẽểếềệễêịìíĩỉòóỏọõôốồổộỗờớỡợởơùúụủũứừửữựưýỳỷỹỵ\-+*?@#$.%^&_.=: ]{3,50}$$/i",
        ],
      ])
    ) {
      $subjectType->nameMsg =
        "Vui lòng nhập từ 3 đến 50 ký tự bao gồm chữ, số và bắt đầu bằng chữ cái";
      $noError = false;
    } else {
      $subjectType->nameMsg = "";
    }
    // Validate status
    if (!empty($subjectType->status)) {
      $subjectType->statusMsg = "Vui lòng chọn thông tin";
      $noError = false;
    } else {
      $subjectType->statusMsg = "";
    }
    return $noError;
  }

  // add new record
  public function insert()
  {
    try {
      if (isset($_POST["add-btn"])) {
        // read form value
        $newSubjectType = new SubjectType();
        $newSubjectType->id = null;
        $newSubjectType->subjectTypeName = trim($_POST["subjectTypeName"]);
        $newSubjectType->description = trim($_POST["description"]);
        $newSubjectType->status = trim($_POST["status"]);
        // call validation
        $checkNoError = $this->checkValidation($newSubjectType);
        if ($checkNoError) {
          //call insert record
          $result = $this->subjectType->insertRecord($newSubjectType);
          if ($result > 0 && $result !== "nameExits") {
            $this->alert("Tạo mới bản ghi thành công", "subjectType");
          } elseif ($result === "nameExits") {
            $newSubjectType->nameExits = "Loại môn học đã tồn tại!";
            $_SESSION["subjectType"] = serialize($newSubjectType); //add session obj
            $this->pageRedirect("subjectType/insert");
          } else {
            $this->alert(
              "Xảy ra lỗi khi thêm mới bản ghi, vui lòng thử lại!",
              "subjectType/insert"
            );
          }
        } else {
          $_SESSION["subjectType"] = serialize($newSubjectType); //add session obj
          $this->pageRedirect("subjectType/insert");
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
        $subjectType = new SubjectType();
        $subjectType->id = trim($_GET["id"]);
        $subjectType->oldSubjectTypeName = trim($_POST["oldSubjectTypeName"]);
        $subjectType->subjectTypeName = trim($_POST["subjectTypeName"]);
        $subjectType->description = trim($_POST["description"]);
        $subjectType->status = trim($_POST["status"]);
        // check validation
        $checkNoError = $this->checkValidation($subjectType);
        if ($checkNoError) {
          $result = $this->subjectType->updateRecord($subjectType);
          if ($result && $result !== "nameExits") {
            $this->alert("Cập nhật bản ghi thành công", "subjectType");
          } elseif ($result === "nameExits") {
            $subjectType->nameExits = "Loại môn học đã tồn tại!";
            $_SESSION["subjectType"] = serialize($subjectType); //add session obj
            $this->pageRedirect("subjectType/update");
          } else {
            $this->alert(
              "Xảy ra lỗi khi cập nhật bản ghi, vui lòng thử lại!",
              "subjectType/update"
            );
          }
        } else {
          $_SESSION["subjectType"] = serialize($subjectType);
          $this->pageRedirect("subjectType/update");
        }
      } elseif (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        unset($_SESSION["subjectType"]);
        $id = $_GET["id"];
        $result = $this->subjectType->getRecord($id);
        $row = mysqli_fetch_array($result);
        $subjectType = new SubjectType();
        $subjectType->id = $row["id"];
        $subjectType->subjectTypeName = $row["subjectTypeName"];
        $subjectType->description = $row["description"];
        $subjectType->status = $row["status"];
        $_SESSION["subjectType"] = serialize($subjectType);
        $this->pageRedirect("subjectType/update");
      } else {
        $this->alert(
          "Hành động không hợp lệ. Vui lòng thử lại!",
          "subjectType"
        );
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
        $result = $this->subjectType->deleteRecord($id);
        if ($result) {
          $this->alert("Xóa bản ghi thành công", "subjectType");
        } else {
          $this->alert(
            "Xảy ra lỗi khi xóa bản ghi, vui lòng thử lại!",
            "subjectType"
          );
        }
      } else {
        $this->alert(
          "Hành động không hợp lệ. Vui lòng thử lại!",
          "subjectType"
        );
      }
    } catch (Exception $e) {
      $this->close_db();
      throw $e;
    }
  }
  // get list
  public function page()
  {
    $filters = new SubjectType();
    $filters->subjectTypeName = trim($_GET["subjectTypeName"]);
    $filters->status = trim($_GET["status"]);
    if (isset($_GET["subjectTypeName"]) || isset($_GET["status"])) {
      $this->subjectType->selectRecord($filters);
    } else {
      $this->subjectType->selectRecord(0);
    }
  }
  public function select()
  {
    $this->subjectType->select();
  }
  public function list()
  {
    include "views/subjectType/list.php";
  }
}

?>
<?php

require "models/majors/majors.model.php";
require "models/majors/majors.php";
require "config.php";

session_status() === PHP_SESSION_ACTIVE ? true : session_start();

class MajorsController
{
  function __construct()
  {
    $this->config = new Config();
    $this->majors = new MajorsModel($this->config);
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
  public function checkValidation($majors)
  {
    $noError = true;
    // Validate majorsName
    if (!$majors->majorsName) {
      $majors->nameMsg = "Vui lòng điền thông tin";
      $noError = false;
    } elseif (
      !filter_var($majors->majorsName, FILTER_VALIDATE_REGEXP, [
        "options" => [
          "regexp" =>
            "/^[A-Za-zÀÁẢẠÃẦẤẨẬẪÂẮẰẶẴĂẲÈÉẸẺẼỂẾỀỆỄÊỊÌÍĨỈÒÓỎỌÕÔỐỒỔỘỖỜỚỠỢỞƠÙÚỤỦŨỨỪỬỮỰƯÝỲỶỸỴàáảạãầấẩậẫâắằặẵăẳèéẹẻẽểếềệễêịìíĩỉòóỏọõôốồổộỗờớỡợởơùúụủũứừửữựưýỳỷỹỵ\-+*?@#$.%^&_.=: ]{3,50}$$/i",
        ],
      ])
    ) {
      $majors->nameMsg =
        "Vui lòng nhập từ 3 đến 50 ký tự bao gồm chữ, số và bắt đầu bằng chữ cái";
      $noError = false;
    } else {
      $majors->nameMsg = "";
    }
    if (!$majors->majorsCode) {
      $majors->codeMsg = "Vui lòng điền thông tin";
      $noError = false;
    } elseif (
      !filter_var($majors->majorsCode, FILTER_VALIDATE_REGEXP, [
        "options" => [
          "regexp" =>
            "/^[A-Za-zÀÁẢẠÃẦẤẨẬẪÂẮẰẶẴĂẲÈÉẸẺẼỂẾỀỆỄÊỊÌÍĨỈÒÓỎỌÕÔỐỒỔỘỖỜỚỠỢỞƠÙÚỤỦŨỨỪỬỮỰƯÝỲỶỸỴàáảạãầấẩậẫâắằặẵăẳèéẹẻẽểếềệễêịìíĩỉòóỏọõôốồổộỗờớỡợởơùúụủũứừửữựưýỳỷỹỵ\-+*?@#$.%^&_.=: ]{2,10}$$/i",
        ],
      ])
    ) {
      $majors->codeMsg =
        "Vui lòng nhập từ 2 đến 10 ký tự bao gồm chữ, số và bắt đầu bằng chữ cái";
      $noError = false;
    } else {
      $majors->codeMsg = "";
    }
    // Validate sectionId
    if (!$majors->sectionId) {
      $majors->parentMsg = "Vui lòng chọn thông tin";
      $noError = false;
    } else {
      $majors->parentMsg = "";
    }
    // Validate status
    if (!empty($majors->status)) {
      $majors->statusMsg = "Vui lòng chọn thông tin";
      $noError = false;
    } else {
      $majors->statusMsg = "";
    }
    return $noError;
  }

  // add new record
  public function insert()
  {
    try {
      if (isset($_POST["add-btn"])) {
        // read form value
        $newMajors = new Majors();
        $newMajors->id = null;
        $newMajors->majorsName = trim($_POST["majorsName"]);
        $newMajors->majorsCode = trim($_POST["majorsCode"]);
        $newMajors->description = trim($_POST["description"]);
        $newMajors->sectionId = trim($_POST["sectionId"]);
        $newMajors->status = trim($_POST["status"]);
        // call validation
        $checkNoError = $this->checkValidation($newMajors);
        if ($checkNoError) {
          //call insert record
          $result = $this->majors->insertRecord($newMajors);
          if ($result > 0 && $result !== "nameExits") {
            $this->alert("Tạo mới bản ghi thành công", "majors");
          } elseif ($result === "nameExits") {
            $newMajors->nameExits = "Ngành học đã tồn tại!";
            $_SESSION["majors"] = serialize($newMajors); //add session obj
            $this->pageRedirect("majors/insert");
          } else {
            $this->alert(
              "Xảy ra lỗi khi thêm mới bản ghi, vui lòng thử lại!",
              "majors/insert"
            );
          }
        } else {
          $_SESSION["majors"] = serialize($newMajors); //add session obj
          $this->pageRedirect("majors/insert");
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
        $majors = new Majors();
        $majors->id = trim($_GET["id"]);
        $majors->oldMajorsName = trim($_POST["oldMajorsName"]);
        $majors->oldMajorsCode = trim($_POST["oldMajorsCode"]);
        $majors->majorsName = trim($_POST["majorsName"]);
        $majors->majorsCode = trim($_POST["majorsCode"]);
        $majors->description = trim($_POST["description"]);
        $majors->sectionId = trim($_POST["sectionId"]);
        $majors->status = trim($_POST["status"]);
        // check validation
        $checkNoError = $this->checkValidation($majors);
        if ($checkNoError) {
          $result = $this->majors->updateRecord($majors);
          if ($result && $result !== "nameExits") {
            $this->alert("Cập nhật bản ghi thành công", "majors");
          } elseif ($result === "nameExits") {
            $majors->nameExits = "Ngành học đã tồn tại!";
            $_SESSION["majors"] = serialize($majors); //add session obj
            $this->pageRedirect("majors/update");
          } else {
            $this->alert(
              "Xảy ra lỗi khi cập nhật bản ghi, vui lòng thử lại!",
              "majors/update"
            );
          }
        } else {
          $_SESSION["majors"] = serialize($majors);
          $this->pageRedirect("majors/update");
        }
      } elseif (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        unset($_SESSION["majors"]);
        $id = $_GET["id"];
        $result = $this->majors->getRecord($id);
        $row = mysqli_fetch_array($result);
        $majors = new Majors();
        $majors->id = $row["id"];
        $majors->majorsName = $row["majorsName"];
        $majors->majorsCode = $row["majorsCode"];
        $majors->description = $row["description"];
        $majors->sectionId = $row["sectionId"];
        $majors->status = $row["status"];
        $_SESSION["majors"] = serialize($majors);
        $this->pageRedirect("majors/update");
      } else {
        $this->alert("Hành động không hợp lệ. Vui lòng thử lại!", "majors");
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
        $result = $this->majors->deleteRecord($id);
        if ($result) {
          $this->alert("Xóa bản ghi thành công", "majors");
        } else {
          $this->alert(
            "Xảy ra lỗi khi xóa bản ghi, vui lòng thử lại!",
            "majors"
          );
        }
      } else {
        $this->alert("Hành động không hợp lệ. Vui lòng thử lại!", "majors");
      }
    } catch (Exception $e) {
      $this->close_db();
      throw $e;
    }
  }
  // get list
  public function page()
  {
    $filters = new Majors();
    $filters->majorsName = trim($_GET["majorsName"]);
    $filters->sectionId = trim($_GET["sectionId"]);
    $filters->status = trim($_GET["status"]);
    if (
      isset($_GET["majorsName"]) ||
      isset($_GET["status"]) ||
      isset($_GET["sectionId"])
    ) {
      $this->majors->selectRecord($filters);
    } else {
      $this->majors->selectRecord(0);
    }
  }
  public function select()
  {
    $this->majors->select();
  }
  public function list()
  {
    include "views/majors/list.php";
  }
}

?>
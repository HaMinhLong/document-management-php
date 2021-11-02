<?php

require "models/section/section.model.php";
require "models/section/section.php";
require "config.php";

session_status() === PHP_SESSION_ACTIVE ? true : session_start();

class SectionController
{
  function __construct()
  {
    $this->config = new Config();
    $this->section = new SectionModel($this->config);
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
  public function checkValidation($section)
  {
    $noError = true;
    // Validate sectionName
    if (!$section->sectionName) {
      $section->nameMsg = "Vui lòng điền thông tin";
      $noError = false;
    } elseif (
      !filter_var($section->sectionName, FILTER_VALIDATE_REGEXP, [
        "options" => [
          "regexp" =>
            "/^[A-Za-zÀÁẢẠÃẦẤẨẬẪÂẮẰẶẴĂẲÈÉẸẺẼỂẾỀỆỄÊỊÌÍĨỈÒÓỎỌÕÔỐỒỔỘỖỜỚỠỢỞƠÙÚỤỦŨỨỪỬỮỰƯÝỲỶỸỴàáảạãầấẩậẫâắằặẵăẳèéẹẻẽểếềệễêịìíĩỉòóỏọõôốồổộỗờớỡợởơùúụủũứừửữựưýỳỷỹỵ\-+*?@#$.%^&_.=: ]{3,50}$$/i",
        ],
      ])
    ) {
      $section->nameMsg =
        "Vui lòng nhập từ 3 đến 50 ký tự bao gồm chữ, số và bắt đầu bằng chữ cái";
      $noError = false;
    } else {
      $section->nameMsg = "";
    }
    // Validate departmentId
    if (!$section->departmentId) {
      $section->parentMsg = "Vui lòng chọn thông tin";
      $noError = false;
    } else {
      $section->parentMsg = "";
    }
    // Validate status
    if (!empty($section->status)) {
      $section->statusMsg = "Vui lòng chọn thông tin";
      $noError = false;
    } else {
      $section->statusMsg = "";
    }
    return $noError;
  }

  // add new record
  public function insert()
  {
    try {
      if (isset($_POST["add-btn"])) {
        // read form value
        $newSection = new Section();
        $newSection->id = null;
        $newSection->sectionName = trim($_POST["sectionName"]);
        $newSection->description = trim($_POST["description"]);
        $newSection->departmentId = trim($_POST["departmentId"]);
        $newSection->status = trim($_POST["status"]);
        // call validation
        $checkNoError = $this->checkValidation($newSection);
        if ($checkNoError) {
          //call insert record
          $result = $this->section->insertRecord($newSection);
          if ($result > 0 && $result !== "nameExits") {
            $this->alert("Tạo mới bản ghi thành công", "section");
          } elseif ($result === "nameExits") {
            $newSection->nameExits = "Bộ môn đã tồn tại!";
            $_SESSION["section"] = serialize($newSection); //add session obj
            $this->pageRedirect("section/insert");
          } else {
            $this->alert(
              "Xảy ra lỗi khi thêm mới bản ghi, vui lòng thử lại!",
              "section/insert"
            );
          }
        } else {
          $_SESSION["section"] = serialize($newSection); //add session obj
          $this->pageRedirect("section/insert");
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
        $section = new Section();
        $section->id = trim($_GET["id"]);
        $section->oldSectionName = trim($_POST["oldSectionName"]);
        $section->sectionName = trim($_POST["sectionName"]);
        $section->description = trim($_POST["description"]);
        $section->departmentId = trim($_POST["departmentId"]);
        $section->status = trim($_POST["status"]);
        // check validation
        $checkNoError = $this->checkValidation($section);
        if ($checkNoError) {
          $result = $this->section->updateRecord($section);
          if ($result && $result !== "nameExits") {
            $this->alert("Cập nhật bản ghi thành công", "section");
          } elseif ($result === "nameExits") {
            $section->nameExits = "Bộ môn đã tồn tại!";
            $_SESSION["section"] = serialize($section); //add session obj
            $this->pageRedirect("section/update");
          } else {
            $this->alert(
              "Xảy ra lỗi khi cập nhật bản ghi, vui lòng thử lại!",
              "section/update"
            );
          }
        } else {
          $_SESSION["section"] = serialize($section);
          $this->pageRedirect("section/update");
        }
      } elseif (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        unset($_SESSION["section"]);
        $id = $_GET["id"];
        $result = $this->section->getRecord($id);
        $row = mysqli_fetch_array($result);
        $section = new Section();
        $section->id = $row["id"];
        $section->sectionName = $row["sectionName"];
        $section->description = $row["description"];
        $section->departmentId = $row["departmentId"];
        $section->status = $row["status"];
        $_SESSION["section"] = serialize($section);
        $this->pageRedirect("section/update");
      } else {
        $this->alert("Hành động không hợp lệ. Vui lòng thử lại!", "section");
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
        $result = $this->section->deleteRecord($id);
        if ($result) {
          $this->alert("Xóa bản ghi thành công", "section");
        } else {
          $this->alert(
            "Xảy ra lỗi khi xóa bản ghi, vui lòng thử lại!",
            "section"
          );
        }
      } else {
        $this->alert("Hành động không hợp lệ. Vui lòng thử lại!", "section");
      }
    } catch (Exception $e) {
      $this->close_db();
      throw $e;
    }
  }
  // get list
  public function page()
  {
    $filters = new Section();
    $filters->sectionName = trim($_GET["sectionName"]);
    $filters->departmentId = trim($_GET["departmentId"]);
    $filters->status = trim($_GET["status"]);
    if (
      isset($_GET["sectionName"]) ||
      isset($_GET["status"]) ||
      isset($_GET["departmentId"])
    ) {
      $this->section->selectRecord($filters);
    } else {
      $this->section->selectRecord(0);
    }
  }
  public function select()
  {
    $this->section->select();
  }
  public function list()
  {
    include "views/section/list.php";
  }
}

?>
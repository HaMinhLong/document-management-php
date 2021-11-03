<?php

require "models/subject/subject.model.php";
require "models/subject/subject.php";
require "config.php";

session_status() === PHP_SESSION_ACTIVE ? true : session_start();

class SubjectController
{
  function __construct()
  {
    $this->config = new Config();
    $this->subject = new SubjectModel($this->config);
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
  public function checkValidation($subject)
  {
    $noError = true;
    // Validate subjectName
    if (!$subject->subjectName) {
      $subject->nameMsg = "Vui lòng điền thông tin";
      $noError = false;
    } elseif (
      !filter_var($subject->subjectName, FILTER_VALIDATE_REGEXP, [
        "options" => [
          "regexp" =>
            "/^[A-Za-zÀÁẢẠÃẦẤẨẬẪÂẮẰẶẴĂẲÈÉẸẺẼỂẾỀỆỄÊỊÌÍĨỈÒÓỎỌÕÔỐỒỔỘỖỜỚỠỢỞƠÙÚỤỦŨỨỪỬỮỰƯÝỲỶỸỴàáảạãầấẩậẫâắằặẵăẳèéẹẻẽểếềệễêịìíĩỉòóỏọõôốồổộỗờớỡợởơùúụủũứừửữựưýỳỷỹỵ\-+*?@#$.%^&_.=: ]{3,50}$$/i",
        ],
      ])
    ) {
      $subject->nameMsg =
        "Vui lòng nhập từ 3 đến 50 ký tự bao gồm chữ, số và bắt đầu bằng chữ cái";
      $noError = false;
    } else {
      $subject->nameMsg = "";
    }
    if (!$subject->subjectCode) {
      $subject->codeMsg = "Vui lòng điền thông tin";
      $noError = false;
    } elseif (
      !filter_var($subject->subjectCode, FILTER_VALIDATE_REGEXP, [
        "options" => [
          "regexp" =>
            "/^[A-Za-zÀÁẢẠÃẦẤẨẬẪÂẮẰẶẴĂẲÈÉẸẺẼỂẾỀỆỄÊỊÌÍĨỈÒÓỎỌÕÔỐỒỔỘỖỜỚỠỢỞƠÙÚỤỦŨỨỪỬỮỰƯÝỲỶỸỴàáảạãầấẩậẫâắằặẵăẳèéẹẻẽểếềệễêịìíĩỉòóỏọõôốồổộỗờớỡợởơùúụủũứừửữựưýỳỷỹỵ\-+*?@#$.%^&_.=: ]{2,10}$$/i",
        ],
      ])
    ) {
      $subject->codeMsg =
        "Vui lòng nhập từ 2 đến 10 ký tự bao gồm chữ, số và bắt đầu bằng chữ cái";
      $noError = false;
    } else {
      $subject->codeMsg = "";
    }
    // Validate subjectTypeId
    if (!$subject->subjectTypeId) {
      $subject->parentMsg = "Vui lòng chọn thông tin";
      $noError = false;
    } else {
      $subject->parentMsg = "";
    }
    // Validate status
    if (!empty($subject->status)) {
      $subject->statusMsg = "Vui lòng chọn thông tin";
      $noError = false;
    } else {
      $subject->statusMsg = "";
    }
    return $noError;
  }

  // add new record
  public function insert()
  {
    try {
      if (isset($_POST["add-btn"])) {
        // read form value
        $newSubject = new Subject();
        $newSubject->id = null;
        $newSubject->subjectName = trim($_POST["subjectName"]);
        $newSubject->subjectCode = trim($_POST["subjectCode"]);
        $newSubject->description = trim($_POST["description"]);
        $newSubject->subjectTypeId = trim($_POST["subjectTypeId"]);
        $newSubject->status = trim($_POST["status"]);
        // call validation
        $checkNoError = $this->checkValidation($newSubject);
        if ($checkNoError) {
          //call insert record
          $result = $this->subject->insertRecord($newSubject);
          if ($result > 0 && $result !== "nameExits") {
            $this->alert("Tạo mới bản ghi thành công", "subject");
          } elseif ($result === "nameExits") {
            $newSubject->nameExits = "Môn học đã tồn tại!";
            $_SESSION["subject"] = serialize($newSubject); //add session obj
            $this->pageRedirect("subject/insert");
          } else {
            $this->alert(
              "Xảy ra lỗi khi thêm mới bản ghi, vui lòng thử lại!",
              "subject/insert"
            );
          }
        } else {
          $_SESSION["subject"] = serialize($newSubject); //add session obj
          $this->pageRedirect("subject/insert");
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
        $subject = new Subject();
        $subject->id = trim($_GET["id"]);
        $subject->oldSubjectName = trim($_POST["oldSubjectName"]);
        $subject->oldSubjectCode = trim($_POST["oldSubjectCode"]);
        $subject->subjectName = trim($_POST["subjectName"]);
        $subject->subjectCode = trim($_POST["subjectCode"]);
        $subject->description = trim($_POST["description"]);
        $subject->subjectTypeId = trim($_POST["subjectTypeId"]);
        $subject->status = trim($_POST["status"]);
        // check validation
        $checkNoError = $this->checkValidation($subject);
        if ($checkNoError) {
          $result = $this->subject->updateRecord($subject);
          if ($result && $result !== "nameExits") {
            $this->alert("Cập nhật bản ghi thành công", "subject");
          } elseif ($result === "nameExits") {
            $subject->nameExits = "Môn học đã tồn tại!";
            $_SESSION["subject"] = serialize($subject); //add session obj
            $this->pageRedirect("subject/update");
          } else {
            $this->alert(
              "Xảy ra lỗi khi cập nhật bản ghi, vui lòng thử lại!",
              "subject/update"
            );
          }
        } else {
          $_SESSION["subject"] = serialize($subject);
          $this->pageRedirect("subject/update");
        }
      } elseif (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        unset($_SESSION["subject"]);
        $id = $_GET["id"];
        $result = $this->subject->getRecord($id);
        $row = mysqli_fetch_array($result);
        $subject = new Subject();
        $subject->id = $row["id"];
        $subject->subjectName = $row["subjectName"];
        $subject->subjectCode = $row["subjectCode"];
        $subject->description = $row["description"];
        $subject->subjectTypeId = $row["subjectTypeId"];
        $subject->status = $row["status"];
        $_SESSION["subject"] = serialize($subject);
        $this->pageRedirect("subject/update");
      } else {
        $this->alert("Hành động không hợp lệ. Vui lòng thử lại!", "subject");
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
        $result = $this->subject->deleteRecord($id);
        if ($result) {
          $this->alert("Xóa bản ghi thành công", "subject");
        } else {
          $this->alert(
            "Xảy ra lỗi khi xóa bản ghi, vui lòng thử lại!",
            "subject"
          );
        }
      } else {
        $this->alert("Hành động không hợp lệ. Vui lòng thử lại!", "subject");
      }
    } catch (Exception $e) {
      $this->close_db();
      throw $e;
    }
  }
  // get list
  public function page()
  {
    $filters = new Subject();
    $filters->subjectName = trim($_GET["subjectName"]);
    $filters->subjectTypeId = trim($_GET["subjectTypeId"]);
    $filters->status = trim($_GET["status"]);
    if (
      isset($_GET["subjectName"]) ||
      isset($_GET["status"]) ||
      isset($_GET["subjectTypeId"])
    ) {
      $this->subject->selectRecord($filters);
    } else {
      $this->subject->selectRecord(0);
    }
  }
  public function select()
  {
    $this->subject->select();
  }
  public function list()
  {
    include "views/subject/list.php";
  }
}

?>
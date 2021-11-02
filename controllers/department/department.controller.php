<?php

require "models/department/department.model.php";
require "models/department/department.php";
require "config.php";

session_status() === PHP_SESSION_ACTIVE ? true : session_start();

class DepartmentController
{
  function __construct()
  {
    $this->config = new Config();
    $this->department = new DepartmentModel($this->config);
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
  public function checkValidation($department)
  {
    $noError = true;
    // Validate departmentName
    if (!$department->departmentName) {
      $department->nameMsg = "Vui lòng điền thông tin";
      $noError = false;
    } elseif (
      !filter_var($department->departmentName, FILTER_VALIDATE_REGEXP, [
        "options" => [
          "regexp" =>
            "/^[A-Za-zÀÁẢẠÃẦẤẨẬẪÂẮẰẶẴĂẲÈÉẸẺẼỂẾỀỆỄÊỊÌÍĨỈÒÓỎỌÕÔỐỒỔỘỖỜỚỠỢỞƠÙÚỤỦŨỨỪỬỮỰƯÝỲỶỸỴàáảạãầấẩậẫâắằặẵăẳèéẹẻẽểếềệễêịìíĩỉòóỏọõôốồổộỗờớỡợởơùúụủũứừửữựưýỳỷỹỵ\-+*?@#$.%^&_.=: ]{3,50}$$/i",
        ],
      ])
    ) {
      $department->nameMsg =
        "Vui lòng nhập từ 3 đến 50 ký tự bao gồm chữ, số và bắt đầu bằng chữ cái";
      $noError = false;
    } else {
      $department->nameMsg = "";
    }
    // Validate departmentCode
    if (!$department->departmentCode) {
      $department->codeMsg = "Vui lòng điền thông tin";
      $noError = false;
    } elseif (
      !filter_var($department->departmentCode, FILTER_VALIDATE_REGEXP, [
        "options" => [
          "regexp" =>
            "/^[A-Za-zÀÁẢẠÃẦẤẨẬẪÂẮẰẶẴĂẲÈÉẸẺẼỂẾỀỆỄÊỊÌÍĨỈÒÓỎỌÕÔỐỒỔỘỖỜỚỠỢỞƠÙÚỤỦŨỨỪỬỮỰƯÝỲỶỸỴàáảạãầấẩậẫâắằặẵăẳèéẹẻẽểếềệễêịìíĩỉòóỏọõôốồổộỗờớỡợởơùúụủũứừửữựưýỳỷỹỵ ]{2,10}$$/i",
        ],
      ])
    ) {
      $department->codeMsg =
        "Vui lòng nhập từ 2 đến 10 ký tự bao gồm chữ, số và bắt đầu bằng chữ cái";
      $noError = false;
    } else {
      $department->codeMsg = "";
    }
    // Validate status
    if (!empty($department->status)) {
      $department->statusMsg = "Vui lòng chọn thông tin";
      $noError = false;
    } else {
      $department->statusMsg = "";
    }
    return $noError;
  }

  // add new record
  public function insert()
  {
    try {
      if (isset($_POST["add-btn"])) {
        // read form value
        $newDepartment = new Department();
        $newDepartment->id = null;
        $newDepartment->departmentCode = trim($_POST["departmentCode"]);
        $newDepartment->departmentName = trim($_POST["departmentName"]);
        $newDepartment->description = trim($_POST["description"]);
        $newDepartment->status = trim($_POST["status"]);
        // call validation
        $checkNoError = $this->checkValidation($newDepartment);
        if ($checkNoError) {
          //call insert record
          $result = $this->department->insertRecord($newDepartment);
          if ($result > 0 && $result !== "nameExits") {
            $this->alert("Tạo mới bản ghi thành công", "department");
          } elseif ($result === "nameExits") {
            $newDepartment->nameExits = "Khoa đã tồn tại!";
            $_SESSION["department"] = serialize($newDepartment); //add session obj
            $this->pageRedirect("department/insert");
          } else {
            $this->alert(
              "Xảy ra lỗi khi thêm mới bản ghi, vui lòng thử lại!",
              "department/insert"
            );
          }
        } else {
          $_SESSION["department"] = serialize($newDepartment); //add session obj
          $this->pageRedirect("department/insert");
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
        $department = new Department();
        $department->id = trim($_GET["id"]);
        $department->oldDepartmentName = trim($_POST["oldDepartmentName"]);
        $department->oldDepartmentCode = trim($_POST["oldDepartmentCode"]);
        $department->departmentCode = trim($_POST["departmentCode"]);
        $department->departmentName = trim($_POST["departmentName"]);
        $department->description = trim($_POST["description"]);
        $department->status = trim($_POST["status"]);
        // check validation
        $checkNoError = $this->checkValidation($department);
        if ($checkNoError) {
          $result = $this->department->updateRecord($department);
          if ($result && $result !== "nameExits") {
            $this->alert("Cập nhật bản ghi thành công", "department");
          } elseif ($result === "nameExits") {
            $department->nameExits = "Khoa đã tồn tại!";
            $_SESSION["department"] = serialize($department); //add session obj
            $this->pageRedirect("department/update");
          } else {
            $this->alert(
              "Xảy ra lỗi khi cập nhật bản ghi, vui lòng thử lại!",
              "department/update"
            );
          }
        } else {
          $_SESSION["department"] = serialize($department);
          $this->pageRedirect("department/update");
        }
      } elseif (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        unset($_SESSION["department"]);
        $id = $_GET["id"];
        $result = $this->department->getRecord($id);
        $row = mysqli_fetch_array($result);
        $department = new Department();
        $department->id = $row["id"];
        $department->departmentCode = $row["departmentCode"];
        $department->departmentName = $row["departmentName"];
        $department->description = $row["description"];
        $department->status = $row["status"];
        $_SESSION["department"] = serialize($department);
        $this->pageRedirect("department/update");
      } else {
        $this->alert("Hành động không hợp lệ. Vui lòng thử lại!", "department");
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
        $result = $this->department->deleteRecord($id);
        if ($result) {
          $this->alert("Xóa bản ghi thành công", "department");
        } else {
          $this->alert(
            "Xảy ra lỗi khi xóa bản ghi, vui lòng thử lại!",
            "department"
          );
        }
      } else {
        $this->alert("Hành động không hợp lệ. Vui lòng thử lại!", "department");
      }
    } catch (Exception $e) {
      $this->close_db();
      throw $e;
    }
  }
  // get list
  public function page()
  {
    $filters = new Department();
    $filters->departmentName = trim($_GET["departmentName"]);
    $filters->status = trim($_GET["status"]);
    if (isset($_GET["departmentName"]) || isset($_GET["status"])) {
      $this->department->selectRecord($filters);
    } else {
      $this->department->selectRecord(0);
    }
  }
  public function select()
  {
    $this->department->select();
  }
  public function list()
  {
    include "views/department/list.php";
  }
}

?>
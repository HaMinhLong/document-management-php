<?php
include "includes/header.php";
include "includes/navigation.php";
require "models/department/department.php";
session_start();
$department = isset($_SESSION["department"])
  ? unserialize($_SESSION["department"])
  : new Department();
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Sửa khoa</h1>
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="/final-php">Home</a></li>
                        <li class="breadcrumb-item"><a href="/final-php/department">khoa</a></li>
                        <li class="breadcrumb-item active">Sửa khoa</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Sửa <?php echo $department->departmentName; ?></h3>
                        </div>
                        <div class="container-fluid">
                            <div class="row">
                                <form class='form__container col-6'
                                    action='/final-php/department?act=update&id=<?php echo $department->id; ?>'
                                    method='post'>
                                    <div class="card-body">
                                        <input hidden type="text" name='oldDepartmentName'
                                            value="<?php echo $department->departmentName; ?>">
                                        <input hidden type="text" name='oldDepartmentCode'
                                            value="<?php echo $department->departmentCode; ?>">
                                        <div class="form-group">
                                            <label for='departmentName'>Tên khoa</label>
                                            <input type="text" name="departmentName" id='departmentName'
                                                placeholder="Nhập tên khoa" class="<?php echo $department->nameMsg ||
                                                $department->nameExits
                                                  ? "form-control is-invalid"
                                                  : "form-control"; ?>"
                                                value="<?php echo $department->departmentName; ?>">
                                            <span class="error-msg"><?php echo $department->nameExits; ?></span>
                                            <span class="error-msg"><?php echo $department->nameMsg; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for='departmentCode'>Mã khoa</label>
                                            <input type="text" name="departmentCode" id='departmentCode'
                                                placeholder="Nhập mã khoa" class="<?php echo $department->codeMsg ||
                                                $department->nameExits
                                                  ? "form-control is-invalid"
                                                  : "form-control"; ?>"
                                                value="<?php echo $department->departmentCode; ?>">
                                            <span class="error-msg"><?php echo $department->nameExits; ?></span>
                                            <span class="error-msg"><?php echo $department->codeMsg; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for='description'>Mô tả</label>
                                            <textarea name="description" id='description' class="form-control" rows="3"
                                                placeholder="Mô tả"
                                                maxlength='300'><?php echo $department->description; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for='status'>Trạng thái</label>
                                            <select id='status' default='1' name='status' class="<?php echo $department->statusMsg
                                              ? "form-control is-invalid"
                                              : "form-control"; ?>">
                                                <?php echo $department->status ===
                                                0
                                                  ? "<option value='1'>Kích hoạt</option>
                                            <option value='0' selected>Ẩn</option>"
                                                  : "<option value='1' selected>Kích hoạt</option>
                                            <option value='0'>Ẩn</option>"; ?>
                                            </select>
                                            <span class="error-msg"><?php echo $department->statusMsg; ?></span>
                                        </div>
                                        <button type="submit" name="update-btn" class="btn btn-primary"><i
                                                style='font-size: 14px' class="fas fa-save"></i>&nbsp;&nbsp;Lưu
                                            lại</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php
include "includes/footer.php";
include "includes/scripts.php";


?>
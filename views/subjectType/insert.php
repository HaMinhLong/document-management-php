<?php
include "includes/header.php";
include "includes/navigation.php";
require "models/subjectType/subjectType.php";
session_start();
$subjectType = isset($_SESSION["subjectType"])
  ? unserialize($_SESSION["subjectType"])
  : new SubjectType();
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Thêm mới loại môn học</h1>
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="/final-php">Home</a></li>
                        <li class="breadcrumb-item"><a href="/final-php/subjectType">Loại môn học</a></li>
                        <li class="breadcrumb-item active">Thêm mới loại môn học</li>
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
                            <h3 class="card-title">Thêm mới loại môn học</h3>
                        </div>
                        <div class="container-fluid">
                            <div class="row">
                                <form class='form__container col-6' action='/final-php/subjectType?act=add'
                                    method='post'>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for='subjectTypeName'>Tên loại môn học</label>
                                            <input type="text" name="subjectTypeName" id='subjectTypeName'
                                                placeholder="Nhập tên loại môn học" class="<?php echo $subjectType->nameMsg ||
                                                $subjectType->nameExits
                                                  ? "form-control is-invalid"
                                                  : "form-control"; ?>"
                                                value="<?php echo $subjectType->subjectTypeName; ?>">
                                            <span class="error-msg"><?php echo $subjectType->nameExits; ?></span>
                                            <span class="error-msg"><?php echo $subjectType->nameMsg; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for='description'>Mô tả</label>
                                            <textarea name="description" id='description' class=" form-control" rows="3"
                                                placeholder="Mô tả" maxlength='300'></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for='status'>Trạng thái</label>
                                            <select id='status' default='1' name='status' class="<?php echo $subjectType->statusMsg
                                              ? "form-control is-invalid"
                                              : "form-control"; ?>">
                                                <?php echo $subjectType->status ===
                                                "0"
                                                  ? "<option value='1'>Kích hoạt</option>
                                            <option value='0' selected>Ẩn</option>"
                                                  : "<option value='1' selected>Kích hoạt</option>
                                            <option value='0'>Ẩn</option>"; ?>
                                            </select>
                                            <span class="error-msg"><?php echo $subjectType->statusMsg; ?></span>
                                        </div>
                                        <button type="submit" name="add-btn" class="btn btn-primary"><i
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
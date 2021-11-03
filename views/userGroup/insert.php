<?php
include "includes/header.php";
include "includes/navigation.php";
require "models/userGroup/userGroup.php";
session_start();
$userGroup = isset($_SESSION["userGroup"])
  ? unserialize($_SESSION["userGroup"])
  : new UserGroup();
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Thêm mới nhóm tài khoản</h1>
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="/final-php">Home</a></li>
                        <li class="breadcrumb-item"><a href="/final-php/user-group">Nhóm tài khoản</a></li>
                        <li class="breadcrumb-item active">Thêm mới nhóm tài khoản</li>
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
                            <h3 class="card-title">Thêm mới nhóm tài khoản</h3>
                        </div>
                        <div class="container-fluid">
                            <div class="row">
                                <form class='form__container col-6' action='/final-php/user-group?act=add'
                                    method='post'>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for='userGroupName'><span style='color: red'>*&nbsp;</span>Tên nhóm
                                                tài khoản</label>
                                            <input type="text" name="userGroupName" id='userGroupName'
                                                placeholder="Nhập tên nhóm tài khoản" class="<?php echo $userGroup->nameMsg ||
                                                $userGroup->nameExits
                                                  ? "form-control is-invalid"
                                                  : "form-control"; ?>"
                                                value="<?php echo $userGroup->userGroupName; ?>">
                                            <span class="error-msg"><?php echo $userGroup->nameExits; ?></span>
                                            <span class="error-msg"><?php echo $userGroup->nameMsg; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for='description'><span style='color: red'>*&nbsp;</span>Mô
                                                tả</label>
                                            <textarea name="description" id='description' class="<?php echo $userGroup->descriptionMsg
                                              ? "form-control is-invalid"
                                              : "form-control"; ?>" rows="3" placeholder="Mô tả"
                                                maxlength='300'><?php echo $userGroup->description; ?></textarea>
                                            <span class="error-msg"><?php echo $userGroup->descriptionMsg; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for='status'><span style='color: red'>*&nbsp;</span>Trạng
                                                thái</label>
                                            <select id='status' default='1' name='status' class="<?php echo $userGroup->statusMsg
                                              ? "form-control is-invalid"
                                              : "form-control"; ?>">
                                                <?php echo $userGroup->status ===
                                                "0"
                                                  ? "<option value='1'>Kích hoạt</option>
                                            <option value='0' selected>Ẩn</option>"
                                                  : "<option value='1' selected>Kích hoạt</option>
                                            <option value='0'>Ẩn</option>"; ?>
                                            </select>
                                            <span class="error-msg"><?php echo $userGroup->statusMsg; ?></span>
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
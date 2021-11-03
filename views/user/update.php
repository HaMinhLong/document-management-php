<?php
include "includes/header.php";
include "includes/navigation.php";
require "models/user/user.php";
session_start();
$user = isset($_SESSION["user"]) ? unserialize($_SESSION["user"]) : new User();
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Sửa tài khoản</h1>
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="/final-php">Home</a></li>
                        <li class="breadcrumb-item"><a href="/final-php/user">Tài khoản</a></li>
                        <li class="breadcrumb-item active">Sửa tài khoản</li>
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
                            <h3 class="card-title">Sửa tài khoản</h3>
                        </div>
                        <div class="container-fluid">
                            <div class="row">
                                <form class='form__container col-6'
                                    action='/final-php/user?act=update&id=<?php echo $user->id; ?>' method='post'>
                                    <div class="card-body">
                                        <input hidden type="text" name='oldUsername'
                                            value="<?php echo $user->username; ?>">
                                        <input hidden type="text" name='oldEmail' value="<?php echo $user->email; ?>">
                                        <div class="form-group">
                                            <label for='username'><span style='color: red'>*&nbsp;</span>Tên tài
                                                khoản</label>
                                            <input type="text" name="username" id='username'
                                                placeholder="Nhập tên tài khoản" class="<?php echo $user->nameMsg ||
                                                $user->nameExits
                                                  ? "form-control is-invalid"
                                                  : "form-control"; ?>" value="<?php echo $user->username; ?>">
                                            <span class="error-msg"><?php echo $user->nameExits; ?></span>
                                            <span class="error-msg"><?php echo $user->nameMsg; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for='password'><span style='color: red'>*&nbsp;</span>Mật
                                                khẩu</label>
                                            <input type="password" name="password" id='password'
                                                placeholder="Nhập mật khẩu" class="<?php echo $user->passwordMsg
                                                  ? "form-control is-invalid"
                                                  : "form-control"; ?>" value="<?php echo $user->password; ?>">
                                            <span class="error-msg"><?php echo $user->passwordMsg; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for='fullName'><span style='color: red'>*&nbsp;</span>Tên đầy
                                                đủ</label>
                                            <input type="text" name="fullName" id='fullName'
                                                placeholder="Nhập tên đầy đủ" class="<?php echo $user->fullNameMsg
                                                  ? "form-control is-invalid"
                                                  : "form-control"; ?>" value="<?php echo $user->fullName; ?>">
                                            <span class="error-msg"><?php echo $user->fullNameMsg; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for='email'><span style='color: red'>*&nbsp;</span>Email</label>
                                            <input type="email" name="email" id='email' placeholder="Nhập email" class="<?php echo $user->emailMsg ||
                                            $user->nameExits
                                              ? "form-control is-invalid"
                                              : "form-control"; ?>" value="<?php echo $user->email; ?>">
                                            <span class="error-msg"><?php echo $user->nameExits; ?></span>
                                            <span class="error-msg"><?php echo $user->emailMsg; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for='mobile'>Số diện thoại</label>
                                            <input type="text" name="mobile" id='mobile'
                                                placeholder="Nhập số diện thoại" class="<?php echo $user->mobileMsg
                                                  ? "form-control is-invalid"
                                                  : "form-control"; ?>" value="<?php echo $user->mobile; ?>">
                                            <span class="error-msg"><?php echo $user->mobileMsg; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for='u_insert_majors'><span style='color: red'>*&nbsp;</span>Ngành
                                                học</label>
                                            <select name="majorsId" id="u_insert_majors" class="<?php echo $user->parentMsg
                                              ? "form-control is-invalid"
                                              : "form-control"; ?>"></select>
                                            <span class="error-msg"><?php echo $user->parentMsg; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for='u_insert_userGroup'><span style='color: red'>*&nbsp;</span>Nhóm
                                                tài khoản</label>
                                            <select name="userGroupId" id="u_insert_userGroup" class="<?php echo $user->parent2Msg
                                              ? "form-control is-invalid"
                                              : "form-control"; ?>"></select>
                                            <span class="error-msg"><?php echo $user->parent2Msg; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for='status'><span style='color: red'>*&nbsp;</span>Trạng
                                                thái</label>
                                            <select id='status' default='1' name='status' class="<?php echo $user->statusMsg
                                              ? "form-control is-invalid"
                                              : "form-control"; ?>">
                                                <?php echo $user->status === "0"
                                                  ? "<option value='1'>Kích hoạt</option>
                                            <option value='0' selected>Ẩn</option>"
                                                  : "<option value='1' selected>Kích hoạt</option>
                                            <option value='0'>Ẩn</option>"; ?>
                                            </select>
                                            <span class="error-msg"><?php echo $user->statusMsg; ?></span>
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
<script>
$(document).ready(function() {

    load_majors_select();
    load_userGroup_select();

    function load_majors_select() {
        $.ajax({
            url: "/final-php/majors?act=select",
            method: "GET",
            data: {},
            success: data => {
                $('#u_insert_majors').html(data);
            }
        })
    }

    function load_userGroup_select() {
        $.ajax({
            url: "/final-php/user-group?act=select",
            method: "GET",
            data: {},
            success: data => {
                $('#u_insert_userGroup').html(data);
            }
        })
    }



});
</script>
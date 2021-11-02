<?php
include "includes/header.php";
include "includes/navigation.php";
require "models/majors/majors.php";
session_start();
$majors = isset($_SESSION["majors"])
  ? unserialize($_SESSION["majors"])
  : new Majors();
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Thêm mới ngành học</h1>
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="/final-php">Home</a></li>
                        <li class="breadcrumb-item"><a href="/final-php/majors">Ngành học</a></li>
                        <li class="breadcrumb-item active">Thêm mới ngành học</li>
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
                            <h3 class="card-title">Thêm mới ngành học</h3>
                        </div>
                        <div class="container-fluid">
                            <div class="row">
                                <form class='form__container col-6' action='/final-php/majors?act=add' method='post'>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for='majorsName'>Tên ngành học</label>
                                            <input type="text" name="majorsName" id='majorsName'
                                                placeholder="Nhập tên ngành học" class="<?php echo $majors->nameMsg ||
                                                $majors->nameExits
                                                  ? "form-control is-invalid"
                                                  : "form-control"; ?>" value="<?php echo $majors->majorsName; ?>">
                                            <span class="error-msg"><?php echo $majors->nameExits; ?></span>
                                            <span class="error-msg"><?php echo $majors->nameMsg; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for='majorsCode'>Mã ngành học</label>
                                            <input type="text" name="majorsCode" id='majorsCode'
                                                placeholder="Nhập tên ngành học" class="<?php echo $majors->codeMsg ||
                                                $majors->nameExits
                                                  ? "form-control is-invalid"
                                                  : "form-control"; ?>" value="<?php echo $majors->majorsCode; ?>">
                                            <span class="error-msg"><?php echo $majors->nameExits; ?></span>
                                            <span class="error-msg"><?php echo $majors->codeMsg; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for='description'>Mô tả</label>
                                            <textarea name="description" id='description' class=" form-control" rows="3"
                                                placeholder="Mô tả"
                                                maxlength='300'><?php echo $majors->description; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for='sec_insert_section'>Bộ môn</label>
                                            <select name="sectionId" id="sec_insert_section" class="<?php echo $majors->parentMsg
                                              ? "form-control is-invalid"
                                              : "form-control"; ?>"></select>
                                            <span class="error-msg"><?php echo $majors->parentMsg; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for='status'>Trạng thái</label>
                                            <select id='status' default='1' name='status' class="<?php echo $majors->statusMsg
                                              ? "form-control is-invalid"
                                              : "form-control"; ?>">
                                                <?php echo $majors->status ===
                                                "0"
                                                  ? "<option value='1'>Kích hoạt</option>
                                            <option value='0' selected>Ẩn</option>"
                                                  : "<option value='1' selected>Kích hoạt</option>
                                            <option value='0'>Ẩn</option>"; ?>
                                            </select>
                                            <span class="error-msg"><?php echo $majors->statusMsg; ?></span>
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
<script>
$(document).ready(function() {

    load_section_select();

    function load_section_select() {
        $.ajax({
            url: "/final-php/section?act=select",
            method: "GET",
            data: {},
            success: data => {
                $('#sec_insert_section').html(data);
            }
        })
    }



});
</script>
<?php
include "includes/header.php";
include "includes/navigation.php";
require "models/section/section.php";
session_start();
$section = isset($_SESSION["section"])
  ? unserialize($_SESSION["section"])
  : new Section();
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Sửa bộ môn</h1>
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="/final-php">Home</a></li>
                        <li class="breadcrumb-item"><a href="/final-php/section">Bộ môn</a></li>
                        <li class="breadcrumb-item active">Sửa bộ môn</li>
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
                            <h3 class="card-title">Sửa bộ môn</h3>
                        </div>
                        <div class="container-fluid">
                            <div class="row">
                                <form class='form__container col-6'
                                    action='/final-php/section?act=update&id=<?php echo $section->id; ?>' method='post'>
                                    <div class="card-body">
                                        <input hidden type="text" name='oldSectionName'
                                            value="<?php echo $section->sectionName; ?>">
                                        <div class="form-group">
                                            <label for='sectionName'><span style='color: red'>*&nbsp;</span>Tên bộ
                                                môn</label>
                                            <input type="text" name="sectionName" id='sectionName'
                                                placeholder="Nhập tên bộ môn" class="<?php echo $section->nameMsg ||
                                                $section->nameExits
                                                  ? "form-control is-invalid"
                                                  : "form-control"; ?>" value="<?php echo $section->sectionName; ?>">
                                            <span class="error-msg"><?php echo $section->nameExits; ?></span>
                                            <span class="error-msg"><?php echo $section->nameMsg; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for='description'>Mô tả</label>
                                            <textarea name="description" id='description' class=" form-control" rows="3"
                                                placeholder="Mô tả"
                                                maxlength='300'><?php echo $section->description; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for='sec_update_department'><span
                                                    style='color: red'>*&nbsp;</span>Khoa</label>
                                            <select name="departmentId" id="sec_update_department" class="<?php echo $section->parentMsg
                                              ? "form-control is-invalid"
                                              : "form-control"; ?>"></select>
                                            <span class="error-msg"><?php echo $section->parentMsg; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for='status'><span style='color: red'>*&nbsp;</span>Trạng
                                                thái</label>
                                            <select id='status' default='1' name='status' class="<?php echo $section->statusMsg
                                              ? "form-control is-invalid"
                                              : "form-control"; ?>">
                                                <?php echo $section->status ===
                                                "0"
                                                  ? "<option value='1'>Kích hoạt</option>
                                            <option value='0' selected>Ẩn</option>"
                                                  : "<option value='1' selected>Kích hoạt</option>
                                            <option value='0'>Ẩn</option>"; ?>
                                            </select>
                                            <span class="error-msg"><?php echo $section->statusMsg; ?></span>
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

    load_department_select();

    function load_department_select() {
        $.ajax({
            url: "/final-php/department?act=select",
            method: "GET",
            data: {},
            success: data => {
                $('#sec_update_department').html(data);
            }
        })
    }



});
</script>
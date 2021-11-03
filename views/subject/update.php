<?php
include "includes/header.php";
include "includes/navigation.php";
require "models/subject/subject.php";
session_start();
$subject = isset($_SESSION["subject"])
  ? unserialize($_SESSION["subject"])
  : new Subject();
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Sửa môn học</h1>
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="/final-php">Home</a></li>
                        <li class="breadcrumb-item"><a href="/final-php/subject">Môn học</a></li>
                        <li class="breadcrumb-item active">Sửa môn học</li>
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
                            <h3 class="card-title">Sửa môn học</h3>
                        </div>
                        <div class="container-fluid">
                            <div class="row">
                                <form class='form__container col-6'
                                    action='/final-php/subject?act=update&id=<?php echo $subject->id; ?>' method='post'>
                                    <div class="card-body">
                                        <input hidden type="text" name='oldSubjectName'
                                            value="<?php echo $subject->subjectName; ?>">
                                        <input hidden type="text" name='oldSubjectCode'
                                            value="<?php echo $subject->subjectCode; ?>">
                                        <div class="form-group">
                                            <label for='subjectName'><span style='color: red'>*&nbsp;</span>Tên môn
                                                học</label>
                                            <input type="text" name="subjectName" id='subjectName'
                                                placeholder="Nhập tên môn học" class="<?php echo $subject->nameMsg ||
                                                $subject->nameExits
                                                  ? "form-control is-invalid"
                                                  : "form-control"; ?>" value="<?php echo $subject->subjectName; ?>">
                                            <span class="error-msg"><?php echo $subject->nameExits; ?></span>
                                            <span class="error-msg"><?php echo $subject->nameMsg; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for='subjectCode'><span style='color: red'>*&nbsp;</span>Mã môn
                                                học</label>
                                            <input type="text" name="subjectCode" id='subjectCode'
                                                placeholder="Nhập mã môn học" class="<?php echo $subject->codeMsg ||
                                                $subject->nameExits
                                                  ? "form-control is-invalid"
                                                  : "form-control"; ?>" value="<?php echo $subject->subjectCode; ?>">
                                            <span class="error-msg"><?php echo $subject->nameExits; ?></span>
                                            <span class="error-msg"><?php echo $subject->codeMsg; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for='description'>Mô tả</label>
                                            <textarea name="description" id='description' class=" form-control" rows="3"
                                                placeholder="Mô tả"
                                                maxlength='300'><?php echo $subject->description; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for='sj_update_subjectType'><span
                                                    style='color: red'>*&nbsp;</span>Loại môn học</label>
                                            <select name="subjectTypeId" id="sj_update_subjectType" class="<?php echo $subject->parentMsg
                                              ? "form-control is-invalid"
                                              : "form-control"; ?>"></select>
                                            <span class="error-msg"><?php echo $subject->parentMsg; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for='status'><span style='color: red'>*&nbsp;</span>Trạng
                                                thái</label>
                                            <select id='status' default='1' name='status' class="<?php echo $subject->statusMsg
                                              ? "form-control is-invalid"
                                              : "form-control"; ?>">
                                                <?php echo $subject->status ===
                                                "0"
                                                  ? "<option value='1'>Kích hoạt</option>
                                            <option value='0' selected>Ẩn</option>"
                                                  : "<option value='1' selected>Kích hoạt</option>
                                            <option value='0'>Ẩn</option>"; ?>
                                            </select>
                                            <span class="error-msg"><?php echo $subject->statusMsg; ?></span>
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

    load_subjectType_select();

    function load_subjectType_select() {
        $.ajax({
            url: "/final-php/subjectType?act=select",
            method: "GET",
            data: {},
            success: data => {
                $('#sj_update_subjectType').html(data);
            }
        })
    }



});
</script>
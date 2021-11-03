<?php
include "includes/header.php";
include "includes/navigation.php";
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Môn học</h1>
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="/final-php">Home</a></li>
                        <li class="breadcrumb-item active">Môn học</li>
                    </ol>
                </div><!-- /.col -->
                <div class='d-flex align-items-center justify-content-end col-sm-6' style='margin-bottom: 10px'>
                    <a href="subject/insert" data-toggle='tooltip' title='Thêm mới'><button type='button'
                            class='btn btn-primary'><i class="fas fa-plus"></i>&nbsp;&nbsp;Thêm
                            mới</button></a>
                </div>
            </div>
        </div>
    </div>

    <section class="content table__container">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="subject" method='get' onsubmit='return false;'>
                                <div class="container-fluid">
                                    <div class="row">
                                        <!-- <input type="text" name='act' value='search' hidden> -->
                                        <div class="input-group input-group-sm col-12 col-md-6 col-lg-4">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="">Tên môn học</span>
                                            </div>
                                            <input type="text" name='subjectName' id='sj_majors-name'
                                                placeholder='Nhập tên môn học' class="form-control"
                                                value="<?php echo $filters->subjectName; ?>">
                                        </div>
                                        <div class="input-group input-group-sm col-12 col-md-6 col-lg-4">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="">Loại môn học</span>
                                            </div>
                                            <select name="subjectTypeId" id="sj_subjectType"
                                                class="form-control"></select>
                                        </div>
                                        <div class="input-group input-group-sm col-12 col-md-6 col-lg-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="">Trạng thái</span>
                                            </div>
                                            <select name="status" id='sj_status' class="form-control">
                                                <?php echo $filters->status ===
                                                "0"
                                                  ? "<option value='1'>Kích hoạt</option>
                                            <option value='0' selected>Ẩn</option>"
                                                  : "<option value='1' selected>Kích hoạt</option>
                                            <option value='0'>Ẩn</option>"; ?>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-1 d-flex justify-content-end">
                                            <button class='btn btn-primary btn-sm' id='sj_search'><i
                                                    class="fas fa-search"></i>&nbsp;&nbsp;Tìm
                                                kiếm</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div id="subject-table" style='margin-top: 10px;padding: 0px 5px'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modal-danger">
    <div class="modal-dialog">
        <div class="modal-content bg-danger">
            <div class="modal-header">
                <h4 class="modal-title">Xác nhận xóa</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Hành động này sẽ không thể hoàn tác. Bạn có chắc chắn muốn xóa bản ghi này không? </p>
                <p></p>
            </div>
            <div class="modal-footer justify-content-start">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Hủy</button>
                <a href="#" id='sj_delete-link'>
                    <button type="button" class="btn btn-outline-light">Xóa</button>
                </a>
            </div>
        </div>
    </div>
</div>

<?php
include "includes/footer.php";
include "includes/scripts.php";
?>
<script>
$(function() {
    $('#example2').DataTable({
        "paging": false,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
});

function setDeleteRecordId(id) {
    document.getElementById("sj_delete-link").href = `subject?act=delete&id=${id}`;
};
$(document).ready(function() {
    let subjectName = '';
    let subjectTypeId = '';
    let status = '';

    load_data();
    load_subjectType_select();

    function load_data(page) {
        $.ajax({
            url: "subject?act=page",
            method: "GET",
            data: {
                page: page,
                subjectName: subjectName,
                subjectTypeId: subjectTypeId,
                status: status
            },
            success: data => {
                $('#subject-table').html(data);
            }
        })
    }

    function load_subjectType_select() {
        $.ajax({
            url: "subjectType?act=select",
            method: "GET",
            data: {},
            success: data => {
                $('#sj_subjectType').html(data);
            }
        })
    }

    $(document).on('click', '.pagination_link', function() {
        var page = $(this).attr("id");
        load_data(page);
    });

    function changeSubjectName() {
        var name = document.getElementById("sj_subjectType-name").value;
        subjectName = name;
    }

    function changeStatus() {
        var statusSearch = document.getElementById("sj_status").value;
        status = statusSearch;
    }

    function changeSubjectTypeId() {
        var parentSearch = document.getElementById("sj_subjectType").value;
        subjectTypeId = parentSearch;
    }
    $(document).on('input', '#sj_subjectType-name', changeSubjectName);
    $(document).on('input', '#sj_status', changeStatus);
    $(document).on('input', '#sj_subjectType', changeSubjectTypeId);
    $("#sj_search").click(function() {
        load_data();
    });

});
</script>
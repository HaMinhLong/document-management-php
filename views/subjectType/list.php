<?php
include "includes/header.php";
include "includes/navigation.php";
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Loại môn học</h1>
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="/final-php">Home</a></li>
                        <li class="breadcrumb-item active">Loại môn học</li>
                    </ol>
                </div>
                <div class='d-flex align-items-center justify-content-end col-sm-6' style='margin-bottom: 10px'>
                    <a href="subjectType/insert" data-toggle='tooltip' title='Thêm mới'><button type='button'
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
                            <form action="subjectType" method='get' onsubmit='return false;'>
                                <div class="container-fluid">
                                    <div class="row">
                                        <!-- <input type="text" name='act' value='search' hidden> -->
                                        <div class="input-group input-group-sm col-12 col-md-6 col-lg-4">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="">Tên loại môn học</span>
                                            </div>
                                            <input type="text" name='subjectTypeName' id='st-subjectType-name'
                                                placeholder='Nhập tên loại môn học' class="form-control"
                                                value="<?php echo $filters->subjectTypeName; ?>">
                                        </div>
                                        <div class="input-group input-group-sm col-12 col-md-6 col-lg-4">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="">Trạng thái</span>
                                            </div>
                                            <select name="status" id='st-status' class="form-control">
                                                <?php echo $filters->status ===
                                                "0"
                                                  ? "<option value='1'>Kích hoạt</option>
                                            <option value='0' selected>Ẩn</option>"
                                                  : "<option value='1' selected>Kích hoạt</option>
                                            <option value='0'>Ẩn</option>"; ?>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-end">
                                            <button class='btn btn-primary btn-sm' id='st-search'><i
                                                    class="fas fa-search"></i>&nbsp;&nbsp;Tìm
                                                kiếm</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div id="subjectType-table" style='margin-top: 10px;padding: 0px 5px'></div>
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
                <a href="#" id='d_delete-link'>
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
    });

});

function setDeleteRecordId(id) {
    document.getElementById("d_delete-link").href = `subjectType?act=delete&id=${id}`;
};

$(document).ready(function() {
    let subjectTypeName = '';
    let status = '';

    load_data();

    function load_data(page) {
        $.ajax({
            url: "subjectType?act=page",
            method: "GET",
            data: {
                page: page,
                subjectTypeName: subjectTypeName,
                status: status
            },
            success: data => {
                $('#subjectType-table').html(data);
            }
        })
    }
    $(document).on('click', '.pagination_link', function() {
        var page = $(this).attr("id");
        load_data(page);
    });

    function changeSubjectTypeName() {
        var name = document.getElementById("st-subjectType-name").value;
        subjectTypeName = name;
    }

    function changeStatus() {
        var statusSearch = document.getElementById("st-status").value;
        status = statusSearch;
    }
    $(document).on('input', '#st-subjectType-name', changeSubjectTypeName);
    $(document).on('input', '#st-status', changeStatus);
    $("#st-search").click(function() {
        load_data();
    });

});
</script>
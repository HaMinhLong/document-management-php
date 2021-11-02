<?php
include "includes/header.php";
include "includes/navigation.php";
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Nhóm tài khoản</h1>
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="/final-php">Home</a></li>
                        <li class="breadcrumb-item active">Nhóm tài khoản</li>
                    </ol>
                </div>
                <div class='d-flex align-items-center justify-content-end col-sm-6' style='margin-bottom: 10px'>
                    <a href="user-group/insert" data-toggle='tooltip' title='Thêm mới'><button type='button'
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
                            <form action="user-group" method='get' onsubmit='return false;'>
                                <div class="container-fluid">
                                    <div class="row">
                                        <!-- <input type="text" name='act' value='search' hidden> -->
                                        <div class="input-group input-group-sm col-12 col-md-6 col-lg-4">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="">Tên nhóm tài khoản</span>
                                            </div>
                                            <input type="text" name='userGroupName' id='ug-user-group-name'
                                                placeholder='Nhập tên nhóm tài khoản' class="form-control"
                                                value="<?php echo $filters->userGroupName; ?>">
                                        </div>
                                        <div class="input-group input-group-sm col-12 col-md-6 col-lg-4">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="">Trạng thái</span>
                                            </div>
                                            <select name="status" id='ug-status' class="form-control">
                                                <?php echo $filters->status ===
                                                "0"
                                                  ? "<option value='1'>Kích hoạt</option>
                                            <option value='0' selected>Ẩn</option>"
                                                  : "<option value='1' selected>Kích hoạt</option>
                                            <option value='0'>Ẩn</option>"; ?>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-end">
                                            <button class='btn btn-primary btn-sm' id='ug-search'><i
                                                    class="fas fa-search"></i>&nbsp;&nbsp;Tìm
                                                kiếm</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div id="user-group-table" style='margin-top: 10px;padding: 0px 5px'></div>
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
                <a href="#" id='ug_delete-link'>
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
    document.getElementById("ug_delete-link").href = `user-group?act=delete&id=${id}`;
};

$(document).ready(function() {
    let userGroupName = '';
    let status = '';

    load_data();

    function load_data(page) {
        $.ajax({
            url: "user-group?act=page",
            method: "GET",
            data: {
                page: page,
                userGroupName: userGroupName,
                status: status
            },
            success: data => {
                $('#user-group-table').html(data);
            }
        })
    }
    $(document).on('click', '.pagination_link', function() {
        var page = $(this).attr("id");
        load_data(page);
    });

    function changeUserGroupName() {
        var name = document.getElementById("ug-user-group-name").value;
        userGroupName = name;
    }

    function changeStatus() {
        var statusSearch = document.getElementById("ug-status").value;
        status = statusSearch;
    }
    $(document).on('input', '#ug-user-group-name', changeUserGroupName);
    $(document).on('input', '#ug-status', changeStatus);
    $("#ug-search").click(function() {
        load_data();
    });

});
</script>
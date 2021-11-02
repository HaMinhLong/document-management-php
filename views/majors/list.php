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
                    <h1 class="m-0">Ngành học</h1>
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="/final-php">Home</a></li>
                        <li class="breadcrumb-item active">Ngành học</li>
                    </ol>
                </div><!-- /.col -->
                <div class='d-flex align-items-center justify-content-end col-sm-6' style='margin-bottom: 10px'>
                    <a href="majors/insert" data-toggle='tooltip' title='Thêm mới'><button type='button'
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
                            <form action="majors" method='get' onsubmit='return false;'>
                                <div class="container-fluid">
                                    <div class="row">
                                        <!-- <input type="text" name='act' value='search' hidden> -->
                                        <div class="input-group input-group-sm col-12 col-md-6 col-lg-4">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="">Tên ngành học</span>
                                            </div>
                                            <input type="text" name='majorsName' id='ma_majors-name'
                                                placeholder='Nhập tên ngành học' class="form-control"
                                                value="<?php echo $filters->majorsName; ?>">
                                        </div>
                                        <div class="input-group input-group-sm col-12 col-md-6 col-lg-4">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="">Bộ môn</span>
                                            </div>
                                            <select name="sectionId" id="ma_section" class="form-control"></select>
                                        </div>
                                        <div class="input-group input-group-sm col-12 col-md-6 col-lg-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="">Trạng thái</span>
                                            </div>
                                            <select name="status" id='ma_status' class="form-control">
                                                <?php echo $filters->status ===
                                                "0"
                                                  ? "<option value='1'>Kích hoạt</option>
                                            <option value='0' selected>Ẩn</option>"
                                                  : "<option value='1' selected>Kích hoạt</option>
                                            <option value='0'>Ẩn</option>"; ?>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-1 d-flex justify-content-end">
                                            <button class='btn btn-primary btn-sm' id='ma_search'><i
                                                    class="fas fa-search"></i>&nbsp;&nbsp;Tìm
                                                kiếm</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div id="majors-table" style='margin-top: 10px;padding: 0px 5px'></div>
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
                <a href="#" id='ma_delete-link'>
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
    document.getElementById("ma_delete-link").href = `majors?act=delete&id=${id}`;
};
$(document).ready(function() {
    let majorsName = '';
    let sectionId = '';
    let status = '';

    load_data();
    load_section_select();

    function load_data(page) {
        $.ajax({
            url: "majors?act=page",
            method: "GET",
            data: {
                page: page,
                majorsName: majorsName,
                sectionId: sectionId,
                status: status
            },
            success: data => {
                $('#majors-table').html(data);
            }
        })
    }

    function load_section_select() {
        $.ajax({
            url: "section?act=select",
            method: "GET",
            data: {},
            success: data => {
                $('#ma_section').html(data);
            }
        })
    }

    $(document).on('click', '.pagination_link', function() {
        var page = $(this).attr("id");
        load_data(page);
    });

    function changeMajorsName() {
        var name = document.getElementById("ma_section-name").value;
        majorsName = name;
    }

    function changeStatus() {
        var statusSearch = document.getElementById("ma_status").value;
        status = statusSearch;
    }

    function changeSectionId() {
        var parentSearch = document.getElementById("ma_section").value;
        sectionId = parentSearch;
    }
    $(document).on('input', '#ma_section-name', changeMajorsName);
    $(document).on('input', '#ma_status', changeStatus);
    $(document).on('input', '#ma_section', changeSectionId);
    $("#ma_search").click(function() {
        load_data();
    });

});
</script>
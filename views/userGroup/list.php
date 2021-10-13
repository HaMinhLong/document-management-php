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

                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Nhóm tài khoản</th>
                                        <th>Mô tả</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result->num_rows > 0) {
                                      while (
                                        $row = mysqli_fetch_array($result)
                                      ) {
                                        echo "<tr>";
                                        echo "<td>" .
                                          $row["userGroupName"] .
                                          "</td>";
                                        echo "<td>" .
                                          $row["description"] .
                                          "</td>";
                                        echo $row["status"] == "1"
                                          ? "<td>Kích hoạt</td>"
                                          : "<td>Ẩn</td>";
                                        echo "<td>";
                                        echo "<a href='user-group?act=update&id=" .
                                          $row["id"] .
                                          "' data-toggle='tooltip' title='Sửa bản ghi'>
                                                    <button type='button' class='btn btn-outline-primary btn-sm'>Sửa&nbsp;&nbsp;
                                                        <i class='fa fa-edit'></i>
                                                    </button>
                                                </a>&nbsp;&nbsp;";
                                        echo "<a href='#' data-toggle='modal' data-target='#modal-danger' onclick='setDeleteRecordId(" .
                                          $row["id"] .
                                          ")' data-toggle='tooltip' title='Xóa bản ghi'>
                                                    <button type='button' class='btn btn-outline-danger btn-sm'>Xóa&nbsp;&nbsp;
                                                        <i class='fa fa-trash'></i>
                                                    </button>
                                                </a>";
                                        echo "</td>";
                                        echo "</tr>";
                                      }
                                    } else {
                                      echo "<p class='lead'><em>No records were found.</em></p>";
                                    } ?>
                                </tbody>
                            </table>
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
                <a href="#" id='delete-link'>
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
function setDeleteRecordId(id) {
    document.getElementById("delete-link").href = `user-group?act=delete&id=${id}`;

};
</script>
<script>
$(function() {
    $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });

});
</script>
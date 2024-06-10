<?php

// if not logged in, then can't access any page except login and register page
session_start();
// if (!isset($_SESSION["admin_id"])) {
//     header("location: login.php");
//     exit;
// }

// if not id passed, go to list page
if (!(isset($_GET["id"]))) {
    header("location: branch.php");
    exit;
}
$branch_id = $_GET["id"];


require_once  "../shared/connection.php";


try {
    $stmt = $conn->prepare("SELECT P.branch_id, P.branch_code, P.branch_name, P.branch_address, P.branch_email_address, P.branch_phone_no, P.inserted_at, P.updated_at, A.name
        FROM tb_branch P INNER JOIN tb_Admin A ON A.admin_id = P.updated_by_admin
            WHERE p.branch_id = :branch_id;");
    $stmt->bindParam(':branch_id', $branch_id);
    $stmt->execute();
    $result = $stmt->fetch();

    // print_r($result);
    // exit;
} catch (PDOException $e) {
    $error = $e->getMessage();
}


// delete product
if (isset($_GET["btn_delete_product"])) {
    try {
        $stmt = $conn->prepare("DELETE FROM tb_branch WHERE branch_id=:branch_id;");
        $stmt->bindParam(':branch_id', $branch_id);
        $stmt->execute();
        $_SESSION["success"] = "Branch deleted successfully!";
        header("location: branch.php");
        exit;
    } catch (PDOException $e) {
        $error = $e->getMessage();
    }
}



$title = "Branch Details";
$style = "
  ";

$headScript = "";

$headList = [
    '<link rel="stylesheet" href="../Templates/Admin/css/bootstrap1.min.css" />',

    '<link rel="stylesheet" href="../Templates/Admin/vendors/themefy_icon/themify-icons.css" />',

    '<link rel="stylesheet" href="../Templates/Admin/vendors/scroll/scrollable.css" />',

    '<link rel="stylesheet" href="../Templates/Admin/vendors/font_awesome/css/all.min.css" />',
    '<link rel="stylesheet" href="../Templates/Admin/vendors/text_editor/summernote-bs4.css" />',
    '<link rel="stylesheet" href="../Templates/Admin/vendors/datatable/css/jquery.dataTables.min.css" />',
    '<link rel="stylesheet" href="../Templates/Admin/vendors/datatable/css/responsive.dataTables.min.css" />',
    '<link rel="stylesheet" href="../Templates/Admin/vendors/datatable/css/buttons.dataTables.min.css" />',


    '<link rel="stylesheet" href="../Templates/Admin/css/metisMenu.css">',

    '<link rel="stylesheet" href="../Templates/Admin/css/style1.css" />',
];

include "../shared/Admin/head_include.php";
?>


<div class="main_content_iner overly_inner">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="page_title_box d-flex flex-wrap align-items-center justify-content-between">
                    <div class="page_title_left d-flex align-items-center">
                        <h3 class="f_s_25 f_w_700 dark_text mr_30">Branch Details</h3>
                        <ol class="breadcrumb page_bradcam mb-0">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Branch Details</li>
                        </ol>
                    </div>
                    <div class="page_title_right">
                        <a id="btn_add_new_product" href="branch.php" class="btn_1">
                            <i class="ti-menu-alt"></i> All Branches
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="modal-header justify-content-start theme_bg_1">
                    <h5 class="modal-title text_white">Branch Details</h5>
                </div>
                <div class="white_card card_height_100 p-3">
                    <div class="white_card_body">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th scope="row">Branch Name</th>
                                        <td><?php echo $result["branch_name"]; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Branch Email Id</th>
                                        <td><?php echo $result["branch_email_address"]; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Branch Address</th>
                                        <td><?php echo $result["branch_address"]; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Branch Code</th>
                                        <td><?php echo $result["branch_code"]; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Branch Phone Number</th>
                                        <td><?php echo $result["branch_phone_no"]; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Inserted at</th>
                                        <td><?php echo $result["inserted_at"]; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Updated at</th>
                                        <td><?php echo $result["updated_at"]; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Updated by Admin</th>
                                        <td><?php echo $result["name"]; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="edit_branch.php?id=<?php echo $result["branch_id"]; ?>" class="btn btn-secondary mb-3">Edit Branch</a>

                            <button type="button" class="btn btn-secondary mb-3" data-bs-toggle="modal" data-bs-target="#view_delete_modal">Delete Branch</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="view_delete_modal" tabindex="-1" role="dialog" aria-labelledby="view_delete_modalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="view_delete_modalTitle">Delete Branch</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are yo sure you want to delete this branch?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                <form action="view_branch.php" method="get">
                    <input type="hidden" name="id" value="<?php echo $result["branch_id"]; ?>">
                    <button type="submit" class="btn btn-primary" name="btn_delete_product">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$scriptList = [
    '<script src="../Templates/Admin/js/jquery1-3.4.1.min.js"></script>',

    '<script src="../Templates/Admin/js/popper1.min.js"></script>',

    '<script src="../Templates/Admin/js/bootstrap1.min.js"></script>',

    '<script src="../Templates/Admin/js/metisMenu.js"></script>',

    '<script src="../Templates/Admin/vendors/datatable/js/jquery.dataTables.min.js"></script>',
    '<script src="../Templates/Admin/vendors/datatable/js/dataTables.responsive.min.js"></script>',
    '<script src="../Templates/Admin/vendors/datatable/js/dataTables.buttons.min.js"></script>',
    '<script src="../Templates/Admin/vendors/datatable/js/buttons.flash.min.js"></script>',
    '<script src="../Templates/Admin/vendors/datatable/js/jszip.min.js"></script>',
    '<script src="../Templates/Admin/vendors/datatable/js/pdfmake.min.js"></script>',
    '<script src="../Templates/Admin/vendors/datatable/js/vfs_fonts.js"></script>',
    '<script src="../Templates/Admin/vendors/datatable/js/buttons.html5.min.js"></script>',
    '<script src="../Templates/Admin/vendors/datatable/js/buttons.print.min.js"></script>',

    '<script src="../Templates/Admin/vendors/scroll/perfect-scrollbar.min.js"></script>',
    '<script src="../Templates/Admin/vendors/scroll/scrollable-custom.js"></script>',

    '<script src="../Templates/Admin/js/custom.js"></script>',
];

$footScript = "
  ";

include "../shared/Admin/foot_include.php";
?>
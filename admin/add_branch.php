<?php

// if not logged in, then can't access any page except login and register page
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("location: login.php");
    exit;
}

require_once  "../shared/connection.php";

// add product
if (isset($_POST["btn_add_branch"])) {
    try {
        $branch_name = $_POST["branch_name"];
        $branch_address = $_POST["branch_address"];
        $branch_code = $_POST["branch_code"];
        $branch_email_address = $_POST["branch_email_address"];
        $branch_phone_no = $_POST["branch_phone_no"];
        $inserted_at = date("Y-m-d H:i:s");
        //$updated_at = date("Y-m-d H:i:s");
        $updated_by_admin = $_SESSION["admin_id"];

        $stmt = $conn->prepare("INSERT INTO tb_branch (branch_name, branch_address, branch_code, branch_email_address, branch_phone_no,inserted_at,updated_by_admin) 
        VALUES (:branch_name, :branch_address, :branch_code, :branch_email_address, :branch_phone_no, :inserted_at,:updated_by_admin);");

        $stmt->bindParam(':branch_name', $branch_name);
        $stmt->bindParam(':branch_address', $branch_address);
        $stmt->bindParam(':branch_code', $branch_code);
        $stmt->bindParam(':branch_email_address', $branch_email_address);
        $stmt->bindParam(':branch_phone_no', $branch_phone_no);
        $stmt->bindParam(':inserted_at', $inserted_at);
        $stmt->bindParam(':updated_by_admin', $updated_by_admin);
        $stmt->execute();
        $_SESSION["success"] = "New Branch added successfully!";
        header("location: branch.php");
        exit;
        } catch (Exception $e) {
        $stmt = $conn->prepare("SELECT branch_name FROM tb_branch WHERE branch_name = :branch_name;");
        $stmt->bindParam(':branch_name', $branch_name);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result_branch_name = $stmt->fetch();

        $stmt = $conn->prepare("SELECT branch_email_address FROM tb_branch WHERE branch_email_address = :branch_email_address;");
        $stmt->bindParam(':branch_email_address', $branch_email_address);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result_email_id = $stmt->fetch();

        $error = "<ul>";
        if ($result_branch_name) {
            $error .= "<li><b>Branch name</b> already exists!</li>";
        }
        if ($result_email_id) {
            $error .=  "<li><b>Branch Email Address</b> already exists!</li>";
        }
        if (!($result_branch_name || $result_email_id)) {
            $error .= $e->getMessage();
        }
        $error .= "</ul>";
    }
}

$conn = null;

$title = "Add Branch";
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
                        <h3 class="f_s_25 f_w_700 dark_text mr_30">Add New Branch</h3>
                        <ol class="breadcrumb page_bradcam mb-0">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Add Branch</li>
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
        <?php include "../shared/Admin/notification_success.php";  ?>
        <?php include "../shared/Admin/notification_error.php";  ?>
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="modal-header justify-content-start theme_bg_1">
                    <h5 class="modal-title text_white">Add New Branch</h5>
                </div>
                <div class="white_card card_height_100 p-3">
                    <div class="white_card_body">
                        <div class="QA_section">
                            <div class="QA_table">
                                <form action="add_branch.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="branch_id" id="branch_id">
                                    <div class="modal-body">
                                        <div class="container">
                                            <?php include "../shared/Admin/notification_error.php";  ?>
                                            <div class="row mb-3">
                                                <label for="product_name" class="form-label col-sm-4 col-form-label">Branch Name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="product_name" name="branch_name" placeholder="Branch Name" value="<?php echo isset($_POST["branch_name"]) ? $_POST["branch_name"] : "";  ?>" required maxlength="100">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="sku" class="form-label col-sm-4 col-form-label">Branch Address</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="sku" name="branch_address" placeholder="123 Main Street,karachi, Pakistan" value="<?php echo isset($_POST["branch_address"]) ? $_POST["branch_address"] : "";  ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="price" class="form-label col-sm-4 col-form-label">Postal Code</label>
                                                <div class="col-sm-8">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-text">
                                                            <span class="text_white">ZIP CODE</span>
                                                        </div>
                                                        <input type="number" class="form-control" aria-label="PKR" id="price" name="branch_code" placeholder="Postal code" value="<?php echo isset($_POST["branch_code"]) ? $_POST["branch_code"] : "";  ?>">
                                                        <!-- <div class="input-group-text">
                                                            <span class="text_white">.00</span>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="stock" class="form-label col-sm-4 col-form-label">Phone Number</label>
                                                <div class="col-sm-8">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" aria-label="PKR" id="stock" name="branch_phone_no" placeholder="0333-33333333" value="<?php echo isset($_POST["branch_phone_no"]) ? $_POST["branch_phone_no"] : "";  ?>">
                                                        <!-- <div class="input-group-text">
                                                            <span class="text_white">Units</span>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="stock" class="form-label col-sm-4 col-form-label">Email Address</label>
                                                <div class="col-sm-8">
                                                    <div class="input-group mb-3">
                                                        <input type="email" class="form-control" aria-label="PKR" id="stock" name="branch_email_address" placeholder="someone@gmail.com" value="<?php echo isset($_POST["branch_email_address"]) ? $_POST["branch_email_address"] : "";  ?>">
                                                        <!-- <div class="input-group-text">
                                                            <span class="text_white">Units</span>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <!-- <button type="button" class="btn btn-secondary mx-2" data-bs-dismiss="modal">Clear</button> -->
                                        <button type="submit" name="btn_add_branch" class="btn btn-primary mx-2"><i class="fas fa-arrow-right"></i> Add Branch</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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
    '<script src="../module_js_scripts/Admin/products.js"></script>',
];

$footScript = "
  ";

include "../shared/Admin/foot_include.php";
?>
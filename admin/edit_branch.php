<?php

// if not logged in, then can't access any page except login and register page
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("location: login.php");
    exit;
}

// if no product id is send, redirect to homepage
if (!(isset($_GET["id"]) || isset($_POST["branch_id"]))) {
    header("location: branch.php");
    exit;
}

// fetch id 
if (isset($_GET["id"])) {
    $branch_id = $_GET["id"];
}

if (isset($_POST["branch_id"])) {
    $branch_id = $_POST["branch_id"];
}


// get product from db
require_once  "../shared/connection.php";
try {
    // find product
    $stmt = $conn->prepare(" SELECT * FROM tb_branch WHERE branch_id = :branch_id;");
    $stmt->bindParam(':branch_id', $branch_id);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $product = $stmt->fetch();

    // if not found any product with entered id
    if (!$product) {
        header("location: branch.php");
        exit;
    }
} catch (PDOException $e) {
    // $error = "Something went wrong!";
    $error = $e->getMessage();
    $conn = null;
}

if (isset($_POST["btn_edit_branch"])) {
    try {
        $branch_name = $_POST["branch_name"];
        $branch_address = $_POST["branch_address"];
        $branch_code = $_POST["branch_code"];
        $branch_email_address = $_POST["branch_email_address"];
        $branch_phone_no = $_POST["branch_phone_no"];
        $updated_at = date("Y-m-d H:i:s");
        $updated_by_admin = $_SESSION["admin_id"];
           $stmt = $conn->prepare("UPDATE tb_branch SET branch_name=:branch_name, branch_address=:branch_address, branch_code=:branch_code, branch_email_address=:branch_email_address, branch_phone_no=:branch_phone_no, updated_at=:updated_at, updated_by_admin = :updated_by_admin WHERE branch_id = :branch_id;");
            $stmt->bindParam(':branch_name', $branch_name);
            $stmt->bindParam(':branch_address', $branch_address);
            $stmt->bindParam(':branch_code', $branch_code);
            $stmt->bindParam(':branch_email_address', $branch_email_address);
            $stmt->bindParam(':branch_phone_no', $branch_phone_no);
            $stmt->bindParam(':updated_at', $updated_at);
            $stmt->bindParam(':updated_by_admin', $updated_by_admin);
            $stmt->bindParam(':branch_id', $branch_id);
            $stmt->execute();
            $_SESSION["success"] = "Branch UPDATED successfully!";
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

$title = "Edit Branch";
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
                        <h3 class="f_s_25 f_w_700 dark_text mr_30">Edit Branch</h3>
                        <ol class="breadcrumb page_bradcam mb-0">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="branch.php">Branch</a></li>
                            <li class="breadcrumb-item active">Edit Branch</li>
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
                    <h5 class="modal-title text_white">Edit Branch</h5>
                </div>
                <div class="white_card card_height_100 p-3">
                    <div class="white_card_body">
                        <div class="QA_section">
                            <div class="QA_table">
                                <form action="edit_branch.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="branch_id" id="branch_id" value="<?php echo $branch_id; ?>">
                                    <div class="modal-body">
                                        <div class="container">
                                            <?php include "../shared/Admin/notification_success.php";  ?>
                                            <?php include "../shared/Admin/notification_error.php";  ?>
                                            <div class="row mb-3">
                                                <label for="product_name" class="form-label col-sm-4 col-form-label">Branch Name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="product_name" name="branch_name" placeholder="Branch Name" value="<?php echo isset($_POST["branch_name"]) ? $_POST["branch_name"] : $product["branch_name"];  ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="sku" class="form-label col-sm-4 col-form-label">Branch Email Address</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="sku" name="branch_email_address" placeholder="Branch Email Address" value="<?php echo isset($_POST["branch_email_address"]) ? $_POST["branch_email_address"] : $product["branch_email_address"];  ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="price" class="form-label col-sm-4 col-form-label">Branch Code</label>
                                                <div class="col-sm-8">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-text">
                                                            <span class="text_white">ZIP CODE</span>
                                                        </div>
                                                        <input type="number" class="form-control" aria-label="PKR" id="price" name="branch_code" placeholder="Branch Code" value="<?php echo isset($_POST["branch_code"]) ? $_POST["branch_code"] : $product["branch_code"];  ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="stock" class="form-label col-sm-4 col-form-label">Branch Phone Number</label>
                                                <div class="col-sm-8">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" aria-label="PKR" id="stock" name="branch_phone_no" placeholder="Branch Phone Number" value="<?php echo isset($_POST["branch_phone_no"]) ? $_POST["branch_phone_no"] : $product["branch_phone_no"];  ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="long_description" class="form-label col-sm-4 col-form-label">Branch Address</label>
                                                <div class="col-sm-8">
                                                    <!-- text area doesn't support value attr.; instead write inside container tag -->
                                                    <textarea class="form-control" name="branch_address" id="long_description" cols="30" rows="4"><?php echo isset($_POST["branch_address"]) ? $_POST["branch_address"] : $product["branch_address"];  ?></textarea>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <!-- <button type="button" class="btn btn-secondary mx-2" data-bs-dismiss="modal">Clear</button> -->
                                        <button type="submit" name="btn_edit_branch" class="btn btn-primary mx-2"><i class="fas fa-arrow-right"></i> Update Branch</button>
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
<?php

// if not logged in, then can't access any page except login and register page
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("location: login.php");
    exit;
}

require_once  "../shared/connection.php";
try {
    $stmt = $conn->prepare("SELECT * FROM tb_Branch;");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $branches = $stmt->fetchAll();

    if (count($branches) < 1) {
        $_SESSION["error"] = "Please enter branches before adding agent!";
        header("location: branches.php");
        exit;
    }
} catch (PDOException $e) {
    $error = $e->getMessage();
}


// add agent
if (isset($_POST["btn_add_agent"])) {

    try {

        $name = $_POST["agent_name"];
        $city = $_POST["city"];
        $branch_location = $_POST["branch"];
        $email_address = $_POST["email"];
        $agent_password = $_POST["password"];
        $contact_num = $_POST["contact_num"];
        $inserted_at = date("Y-m-d H:i:s");
        $updated_at = date("Y-m-d H:i:s");
        $updated_by_admin = $_SESSION["admin_id"];

        $stmt = $conn->prepare("INSERT INTO tb_Agent (agent_name, city, email_address, agent_password, contact_num, branch_location, inserted_at, updated_at, updated_by_admin) VALUES (:agent_name, :city, :email_address, :agent_password, :contact_num, :branch_location, :inserted_at, :updated_at, :updated_by_admin);");

        $stmt->bindParam(':agent_name', $name);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':email_address', $email_address);
        $stmt->bindParam(':agent_password', $agent_password);
        $stmt->bindParam(':contact_num', $contact_num);
        $stmt->bindParam(':branch_location', $branch_location);
        $stmt->bindParam(':inserted_at', $inserted_at);
        $stmt->bindParam(':updated_at', $updated_at);
        $stmt->bindParam(':updated_by_admin', $updated_by_admin);
        $stmt->execute();
        $_SESSION["success"] = "New Agent added successfully!";
        header("location: agents.php");
        exit;
    } catch (Exception $e) {

        $stmt = $conn->prepare("SELECT email_address FROM tb_Agent WHERE email_address = :email_address;");
        $stmt->bindParam(':email_address', $email_address);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result_email_address = $stmt->fetch();

        $error = "<ul>";
        if ($result_email_address) {
            $error .= "<li><b>Email Address</b> already exists!</li>";
        }
        if (!$result_email_address) {
            $error .= $e->getMessage();
        }
        $error .= "</ul>";
    }
}

$conn = null;

$title = "Add Agents";
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
                        <h3 class="f_s_25 f_w_700 dark_text mr_30">Add New Agents</h3>
                        <ol class="breadcrumb page_bradcam mb-0">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Add Agent</li>
                        </ol>
                    </div>
                    <div class="page_title_right">
                        <a id="btn_add_new_agent" href="agents.php" class="btn_1">
                            <i class="ti-menu-alt"></i> All Agents
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
                    <h5 class="modal-title text_white">Add New Agent</h5>
                </div>
                <div class="white_card card_height_100 p-3">
                    <div class="white_card_body">
                        <div class="QA_section">
                            <div class="QA_table">
                                <form action="add_agent.php" method="post">
                                    <input type="hidden" name="agent_id" id="agent_id">
                                    <div class="modal-body">
                                        <div class="container">
                                            <div class="row mb-3">
                                                <label for="agent_name" class="form-label col-sm-4 col-form-label">Agent Name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="agent_name" name="agent_name" placeholder="Agent Name" value="<?php echo isset($_POST["agent_name"]) ? $_POST["agent_name"] : "";  ?>" required maxlength="100">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="city" class="form-label col-sm-4 col-form-label">City</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="city" name="city" placeholder="City" value="<?php echo isset($_POST["city"]) ? $_POST["city"] : "";  ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="branch" class="form-label col-sm-4 col-form-label">Branch</label>
                                                <div class="col-sm-8">
                                                    <?php
                                                    $branches_options = array();
                                                    foreach ($branches as $row) {
                                                        if (isset($_POST["branch"])) {
                                                            if ($_POST["branch"] == $row["branch_id"]) {
                                                                array_push($branches_options, '<option value="' . $row["branch_id"] . '" selected>' . $row["branch_name"] . '</option>');
                                                            } else {
                                                                array_push($branches_options, '<option value="' . $row["branch_id"] . '">' . $row["branch_name"] . '</option>');
                                                            }
                                                        } else {
                                                            array_push($branches_options, '<option value="' . $row["branch_id"] . '">' . $row["branch_name"] . '</option>');
                                                        }
                                                    }
                                                    ?>

                                                    <select id="branch" name="branch" class="form-select form-control">
                                                        <?php
                                                        foreach ($branches_options as $row) {
                                                            echo $row;
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="email" class="form-label col-sm-4 col-form-label">Email Address</label>
                                                <div class="col-sm-8">
                                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" value="<?php echo isset($_POST["email"]) ? $_POST["email"] : "";  ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="password" class="form-label col-sm-4 col-form-label">Password</label>
                                                <div class="col-sm-8">
                                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="<?php echo isset($_POST["password"]) ? $_POST["password"] : "";  ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="contact_num" class="form-label col-sm-4 col-form-label">Contact Number</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="contact_num" name="contact_num" placeholder="Contact Number" value="<?php echo isset($_POST["contact_num"]) ? $_POST["contact_num"] : "";  ?>">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <!-- <button type="button" class="btn btn-secondary mx-2" data-bs-dismiss="modal">Clear</button> -->
                                        <button type="submit" name="btn_add_agent" class="btn btn-primary mx-2"><i class="fas fa-arrow-right"></i> Add Agent</button>
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
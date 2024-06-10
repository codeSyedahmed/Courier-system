<?php

session_start();

if(isset( $_SESSION["admin_id"]))
{ 
    session_destroy();
    session_start();
    $_SESSION["success"] = "Admin logged-out successfully!";
    header("location: admin_login.php");
exit;
}


if(isset( $_SESSION["agent_id"]))
{ 
    session_destroy();
    session_start();
    $_SESSION["success"] = "Agent logged-out successfully!";
    header("location: agent_login.php");
exit;
}

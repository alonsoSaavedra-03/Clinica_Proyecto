<?php 
session_start();
session_destroy();
session_unset();
header("Location: ../views/layout/login.html");
exit();
?>
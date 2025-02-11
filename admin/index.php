<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
 include'./components/topnav.php';
?>





<?php  include './components/footer.php'; ?>
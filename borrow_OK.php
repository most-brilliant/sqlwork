<?php
header("Content-type:text/html;charset=utf-8");
$host = '127.0.0.1';
$user = 'root';
$database = 'sqlwork';
$con = new mysqli($host, $user, '', $database);
if ($con->connect_error) {
    echo "<script type=\"text/javascript\">window.alert($connect_error)</script>";
    die("连接失败：" . $con->connect_error);
}

session_start();
$theip = $_SESSION['theip'];
$bookID = $_SESSION['borrow_bookID'];
$username = $_SESSION['username'];
$sql = "update books set remain=0 where bookID='$bookID';";
$addoutbook = "insert into outbooks set bookID='$bookID',username='$username',thedate=now()";
$addlog = "insert into borrow_log
                set bookID='$bookID',
                    username='$username',
                    thedate=now();";
$updateusers = "update users set borrowNum=borrowNum+1 where username='$username'";
if ($con->query($sql)) {
    $con->query($updateusers);
    $con->query($addoutbook);
    $con->query($addlog);
    echo "<p class=\"font-weight-bold text-center\" id=\"theinfo\">借书成功，请于90天之内归还！</p>";
} else {
    // echo $con->error;
    echo "<p class=\"font-weight-bold text-center\" id=\"theinfo\">借书失败！</p>";
}

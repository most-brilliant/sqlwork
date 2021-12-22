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
$bookID = $_SESSION['return_bookID'];
$username = $_SESSION['username'];

$result = $con->query("select datediff(now(),thedate) as a,term as b from outbooks where bookID='$bookID' and username='$username'");
$diff = $result->fetch_assoc();
if ($diff['a'] > $diff['b']) {
    $drop = "update users set typeID=3 where username=$username";
    $con->query($drop); //标记为失信用户
    echo "<script type=\"text/javascript\"> window.alert('图书已超期，请联系管理员缴纳罚金后还书');</script>";
    echo "<script type=\"text/javascript\"> window.location='http://$theip:8080/sqlwork/return.html';</script>";
    exit;
}

$sql = "update books set remain=1 where bookID='$bookID';";
$deleteoutbook = "delete from outbooks where bookID='$bookID' and username='$username'";
$addlog = "insert into return_log
                set bookID='$bookID',
                    username='$username',
                    thedate=now();";
$updateusers = "update users set borrowNum=borrowNum-1 where username='$username'";
if ($con->query($sql)) {
    // echo $con->error;
    $con->query($updateusers);
    $con->query($deleteoutbook);
    $con->query($addlog);
    echo "<p class=\"font-weight-bold text-center\" id=\"theinfo\">还书成功！</p>";
    // echo "<script type=\"text/javascript\"> window.location='http://$theip:8080/sqlwork/return.html';</script>";
} else {
    // echo $con->error;
    echo "<p class=\"font-weight-bold text-center\" id=\"theinfo\">还书失败！</p>";
    // echo "<script type=\"text/javascript\"> window.location='http://localhost:8080/sqlwork/return.html';</script>";
}

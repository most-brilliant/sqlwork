<?php
// print_r($_POST);
session_start();
$theip = $_SESSION['theip'];
header("Content-type:text/html;charset=utf-8");
$host = '127.0.0.1';
$user = 'root';
$database = 'sqlwork';
$con = new mysqli($host, $user, '', $database);
if ($con->connect_error) {
    echo "<script type=\"text/javascript\">window.alert($connect_error)</script>";
    die("连接失败：" . $con->connect_error);
}

$username = $_SESSION['username'];
$rec = $_POST['password'];
$oldpassword = "";
$newpassword = "";
for ($i = 0; $rec[$i] != ';'; ++$i)
    $oldpassword .= $rec[$i];

for ($j = $i + 1; $j < strlen($rec); ++$j)
    $newpassword .= $rec[$j];

if ($oldpassword == $_SESSION['password']) {
    $sql = "update login set password='$newpassword' where username='$username';";
    $con->query($sql);
    session_destroy();
    // echo 1;
    echo "<h2 id='rec'>修改成功！</h2>";
    // echo "<script> window.location='http://$theip:8080/sqlwork/login.html';</script>";
} else {
    // echo 0;
    echo "<h2 id='rec'>密码错误！</h2>";
}

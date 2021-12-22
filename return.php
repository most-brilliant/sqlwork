<?php
session_start();
$theip = $_SESSION['theip'];
header("Content-type:text/html;charset=utf-8");
if (isset($_SESSION['username']) == false) {
    echo "<p class=\"font-weight-bold text-center\">您还未登陆！</p>";
    exit;
}
$host = '127.0.0.1';
$user = 'root';
$database = 'sqlwork';
$con = new mysqli($host, $user, '', $database);
if ($con->connect_error) {
    echo $connect_error;
    die("连接失败：" . $con->connect_error);
}

$username = $_SESSION['username'];
$bookID = $_POST['q'];
if ($bookID == "") {
    echo "<p class=\"font-weight-bold text-center\">请输入图书编号！</p>";
    // echo "window.location='http://$theip:8080/sqlwork/return.html'";
    $con->close();
    exit;
}

$sql = "select * from `outbooks` where bookID = '$bookID'";
$result = $con->query($sql);
$row = $result->fetch_all();
if (count($row) == 0) {
    echo "<p class=\"font-weight-bold text-center\">该书不存在或未借出！</p>";
    // echo "<script type=\"text/javascript\"> window.location='http://$theip:8080/sqlwork/return.html';</script>";
    $con->close();
    exit;
} else if ($row[0][2] != $username) {
    echo $row[0][2];
    echo "<p class=\"font-weight-bold text-center\">您并未借此书！</p>";
    // echo "<script type=\"text/javascript\"> window.location='http://localhost:8080/sqlwork/borrow.html';</script>";
    $con->close();
    exit;
}
$_SESSION['return_bookID'] = $bookID;
$colname = $result->fetch_fields();
echo "<table class='table table-hover'>";
echo "<tr>";
foreach ($colname as $val) {
    echo "<th>&nbsp&nbsp&nbsp" . $val->name . "&nbsp&nbsp&nbsp</th>";
}
echo "<tr>";
for ($j = 0; $j < count($row[0]); ++$j) {
    echo "<td>&nbsp&nbsp&nbsp" . $row[0][$j] . "&nbsp&nbsp&nbsp</td>";
}
echo "</tr>";
echo "</table>";
echo
"<div class=\"container\"><button type=\"submit\" class=\"btn btn-primary btn-block\" name=\"return_ok\" onclick='returnBook()'>确认还书</button></div>";

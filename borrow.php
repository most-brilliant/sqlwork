<?php
session_start();
$theip = $_SESSION['theip'];
header("Content-type:text/html;charset=utf-8");
if (isset($_SESSION['username']) == false) {
    echo "您还未登陆！";
    exit;
}
$host = '127.0.0.1';
$user = 'root';
$database = 'sqlwork';
$con = new mysqli($host, $user, '', $database);
if ($con->connect_error) {
    echo "<script type=\"text/javascript\">window.alert($connect_error)</script>";
    die("连接失败：" . $con->connect_error);
}

$username = $_SESSION['username'];
//失信用户拒绝借书
$judge = "select typeID from users where username='$username'";
$result = $con->query($judge);
$row = $result->fetch_assoc();
if ($row['typeID'] == 3) {
    echo "<p class=\"font-weight-bold text-center\" id=\"theinfo\">你已被标记为失信用户，请联系管理员缴纳罚金！</p>";
    $con->close();
    exit;
}

if ($_POST['q'] == "") {
    echo "<p class=\"font-weight-bold text-center\" id=\"theinfo\">请输入图书编号！</p>";
    $con->close();
    exit;
}
$bookID = $_POST['q'];
$sql = "select * from `books` where bookID = '$bookID'";
$result = $con->query($sql);
$row = $result->fetch_all();
if (count($row) == 0) {
    echo "<p class=\"font-weight-bold text-center\" id=\"theinfo\">不存在该书，请检查图书编号输入是否正确！</p>";
    $con->close();
    exit;
}
if ($row[0][5] == 0) {
    echo "<p class=\"font-weight-bold text-center\" id=\"theinfo\">该书已借出！</p>";
    // echo "<p class=\"font-weight-bold text-center\" id=\"theinfo\">你可以预约该书，是否预约？</p>";
    // echo "<button>是</button>";
    $con->close();
    exit;
}
$_SESSION['borrow_bookID'] = $bookID;
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
'<div class="container"><button type="submit" class="btn btn-primary btn-block" name="borrow_ok" onclick="borrow()">确认借书</button></div>';
<?php
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
$name = $_SESSION['truename'];
echo "<h2 style=\"text-align:center;\"> $name 的个人空间</h2>";

echo "<br/>";
$username = $_SESSION['username'];
$sql = "select outbooks.bookID '图书编号',bookname '书名',booktype '图书类型',thedate '借书日期',term-datediff(now(),thedate) '剩余还书时间',outbooks.note '备注'  from outbooks join books on outbooks.bookID=books.bookID where outbooks.username='$username';";
$result = $con->query($sql);
$rows = $result->fetch_all();
if (count($rows) > 0) {
    echo "<h3>在借图书</h3>";
    echo "<br/>";
    $fieldinfo = $result->fetch_fields();
    echo "<table class='table table-hover'>";
    echo "<tr>";
    foreach ($fieldinfo as $val) {
        echo "<th>&nbsp&nbsp&nbsp" . $val->name . "&nbsp&nbsp&nbsp</th>";
    }
    echo "</tr>";

    for ($i = 0; $i < count($rows); ++$i) {
        echo "<tr onclick='copyID($i)'>";
        echo "<td id='bookID$i'>&nbsp&nbsp&nbsp" . $rows[$i][0] . "&nbsp&nbsp&nbsp</td>";
        for ($j = 1; $j < count($rows[$i]); ++$j) {
            echo "<td>&nbsp&nbsp&nbsp" . $rows[$i][$j] . "&nbsp&nbsp&nbsp</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>你没有在借图书</p>";
}

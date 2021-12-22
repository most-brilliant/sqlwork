<?php
header("Content-type:text/html;charset=utf-8");
session_start();
$theip = $_SESSION['theip'];
$host = '127.0.0.1';
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$database = 'sqlwork';
$con = new mysqli($host, $username, $password, $database);
if ($con->connect_error) {
    die("连接失败：" . $con->connect_error . $password);
}

$sqlquery = $_POST["sqlquery"];
$result = $con->query($sqlquery);
if (is_bool($result)) {
    if ($result) {
        echo "<p>执行操作成功</p>";
        // echo "<script type=\"text/javascript\">window.location='http://$theip:8080/sqlwork/sqlquery.html';</script>";
    } else {
        // echo "<h3 style='text-align:center'>$sqlquery</h3>";
        // echo $sqlquery . "<br/><br/>";
        echo "<p>执行操作失败：" . mysqli_error($con) . "</p><br/><br/>";
    }
    // echo "<script type=\"text/javascript\"> window.location='http://localhost:8080/sqlwork/sqlquery.html';</script>";
} else {
    // echo "<h3 style='text-align:center'>$sqlquery</h3>";
    $rows = $result->fetch_all();
    $fieldinfo = $result->fetch_fields();
    // print_r($fieldinfo);
    echo "<table class='table table-hover'>";
    echo "<tr>";
    foreach ($fieldinfo as $val) {
        echo "<th>&nbsp&nbsp&nbsp" . $val->name . "&nbsp&nbsp&nbsp</th>";
    }
    echo "<tr/>";
    for ($i = 0; $i < count($rows); ++$i) {
        $bookname=$rows[$i][1];
        echo "<tr onclick=\"window.open('/sqlwork/books/$bookname.html')\">";
        for ($j = 0; $j < count($rows[$i]); ++$j) {
            echo "<td>&nbsp&nbsp&nbsp" . $rows[$i][$j] . "&nbsp&nbsp&nbsp</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}


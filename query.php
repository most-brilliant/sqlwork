<?php
// error_reporting(0);
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


$bookname = $_POST['q'];
// $bookname = trim($bookname, " ");
// echo $bookname;
$sqlquery = "select * from books where bookname like '%$bookname%'";
$result = $con->query($sqlquery);

// echo "<h3 style='text-align:center'>查询结果</h3>";
$rows = $result->fetch_all();
$fieldinfo = $result->fetch_fields();
// print_r($fieldinfo);
echo "<table class='table table-hover'>";
echo "<tr>";
foreach ($fieldinfo as $val) {
    echo "<th>&nbsp&nbsp&nbsp" . $val->name . "&nbsp&nbsp&nbsp</th>";
}
echo "</tr>";
for ($i = 0; $i < count($rows); ++$i) {
    $bookname = str_replace('#', '%23', $rows[$i][1]);
    echo "<tr onclick=\"window.open('/sqlwork/books/$bookname.html')\">";
    for ($j = 0; $j < count($rows[$i]); ++$j) {
        echo "<td>&nbsp&nbsp&nbsp" . $rows[$i][$j] . "&nbsp&nbsp&nbsp</td>";
    }
    echo "</tr>";
}
echo "</table>";

    // echo
    // "<script type=\"text/javascript\">
    //         function back() {
    //             window.location = \"http://$theip:8080/sqlwork/query.html\";
    //         }
    //     </script>
    //     <br />
    //     <div style=\"text-align: center;\"><button class=\"btn btn-dark\" name=\"backtoquery\" onclick=\"back()\">返回执行界面</button></div>";

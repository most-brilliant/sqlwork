<?php
function getip()
{
    $server_hostname = gethostname();
    $server_hostname .= ".";
    $server_ip = gethostbynamel($server_hostname);
    return $server_ip[2];
}

$theip = getip();
header("Content-type:text/html;charset=utf-8");
$host = '127.0.0.1';
$user = 'root';
$database = 'sqlwork';
$con = new mysqli($host, $user, '', $database);
if ($con->connect_error) {
    echo "<script type=\"text/javascript\">window.alert($connect_error)</script>";
    die("连接失败：" . $con->connect_error);
}
if (isset($_POST["login_sub"])) {
    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];
    if ($username == "" or $password == "") {
        echo "<script type=\"text/javascript\"> window.alert('请填写正确信息！');</script>";
        echo "<script type=\"text/javascript\"> window.location='http://$theip:8080/sqlwork/login.html';</script>";
    }
    $sql = "select `password`,`name` from `login` where `username`=$username";
    $result = $con->query($sql);
    if (!$result->num_rows) {
        echo "<script type=\"text/javascript\"> window.alert('用户不存在！');</script>";
        echo "<script type=\"text/javascript\"> window.location='http://$theip:8080/sqlwork/login.html';</script>";
    }
    $row = $result->fetch_assoc();
    $pa = $row["password"];
    $name = $row["name"];
    if ($pa == $password) {
        // echo "登录成功,$name";
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        $_SESSION['truename'] = $name;
        $_SESSION['theip'] = $theip;
        $con->query("drop user '$username'@'localhost';");
        $con->query("flush privileges;");
        $con->query("create user '$username'@'localhost' identified by '$password'");

        $result = $con->query("select typeID from users where username='$username'");
        $row = $result->fetch_assoc();
        switch ($row['typeID']) {
            case 0:
                $con->query("grant select on sqlwork.books to '$username'@'localhost';");
                break;
            case 1:
            case 3:
                $con->query("grant all privileges on sqlwork.books to '$username'@'localhost';");
                $con->query("grant all privileges on sqlwork.login to '$username'@'localhost';");
                $con->query("grant select on sqlwork.users to '$username'@'localhost';");
                $con->query("grant all privileges on sqlwork.outbooks to '$username'@'localhost';");
                $con->query("grant select on sqlwork.borrow_log to '$username'@'localhost';");
                $con->query("grant select on sqlwork.retrun_log to '$username'@'localhost';");
                break;
            case 2:
                $con->query("grant all privileges on sqlwork.* to '$username'@'localhost';");
                break;
            case 4:
                break;
        }

        echo "<script type=\"text/javascript\"> window.location='http://$theip:8080/sqlwork/Index.html';</script>";
    } else {
        echo "<script type=\"text/javascript\"> window.alert('密码错误！');</script>";
        echo "<script type=\"text/javascript\"> window.location='http://$theip:8080/sqlwork/login.html';</script>";
    }
    $con->close();
}
exit;

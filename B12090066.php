<?php  
// 顯示 PHP 環境資訊（測試用）
phpinfo(); 
?>
<html>     
<head><title>html+php</title></head>           
<body>
    <!-- 輸出 Hello world! -->
    <?php echo "Hello world!"; ?>          
    <h1>html</h1>
    <?php echo "Hello world!"; ?>          
    <h1>html</h1>
    <?php echo "Hello world!"; ?>      
</body> 
</html> 

<?php  
// 接收使用者傳來的帳號密碼（POST）
echo $_POST["id"];    
echo "<br>";    
echo $_POST["pwd"]; 
?>

<?php  
// 使用靜態帳號密碼驗證登入
if (($_POST["id"] == "john") && ($_POST["pwd"]=="john1234"))
    echo "登入成功";
else
    echo "登入失敗";
?>

<?php  
// 連接到資料庫（MySQL）
$conn = mysqli_connect("db4free.net", "immust", "immustimmust", "immust");

// 從 user 表格查詢所有資料
$result = mysqli_query($conn, "select * from user");

// 抓出第一筆資料並顯示 id 和 pwd
$row = mysqli_fetch_array($result);
echo $row["id"] . " " . $row["pwd"]."<br>";

// 抓出第二筆資料
$row = mysqli_fetch_array($result);
echo $row["id"] . " " . $row["pwd"];
?>

<?php  
// 登入驗證邏輯（用 while 逐筆比對資料庫中的帳號密碼）
$conn = mysqli_connect("db4free.net", "immust", "immustimmust", "immust");
$result = mysqli_query($conn, "select * from user");
$login = FALSE;

while ($row = mysqli_fetch_array($result)) {
    if (($_POST["id"] == $row["id"]) && ($_POST["pwd"] == $row["pwd"])) {
        $login = TRUE;
    }
}

if ($login == TRUE)
    echo "登入成功";
else
    echo "帳號/密碼 錯誤";
?>

<?php  
// 顯示 bulletin 表格資料
error_reporting(0); // 關閉錯誤訊息
$conn = mysqli_connect("db4free.net", "immust", "immustimmust", "immust");
$result = mysqli_query($conn, "select * from bulletin");

// 顯示公告表格
echo "<table border=2><tr><td>佈告編號</td><td>佈告類別</td><td>標題</td><td>佈告內容</td><td>發佈時間</td></tr>";
while ($row = mysqli_fetch_array($result)){
    echo "<tr><td>";
    echo $row["bid"];
    echo "</td><td>";
    echo $row["type"];
    echo "</td><td>"; 
    echo $row["title"];
    echo "</td><td>";
    echo $row["content"];
    echo "</td><td>";
    echo $row["time"];
    echo "</td></tr>";
}
echo "</table>";
?>

<?php  
// 使用 session 計算瀏覽次數
session_start();
if (!isset($_SESSION["counter"]))
    $_SESSION["counter"] = 1;
else
    $_SESSION["counter"]++;

// 顯示目前 counter 數值
echo "counter=".$_SESSION["counter"];
echo "<br><a href=9.reset_counter.php>重置counter</a>";
?>

<?php  
// 重置 counter 的頁面
session_start();
unset($_SESSION["counter"]); // 移除 counter 變數
echo "counter重置成功....";

// 2 秒後導向 counter 頁面
echo "<meta http-equiv=REFRESH content='2; url=8.counter.php'>";
?>

<?php  
// 登入後啟動 session 並導向公告頁
$conn = mysqli_connect("db4free.net", "immust", "immustimmust", "immust");
$result = mysqli_query($conn, "select * from user");
$login = FALSE;

while ($row = mysqli_fetch_array($result)) {
    if (($_POST["id"] == $row["id"]) && ($_POST["pwd"] == $row["pwd"])) {
        $login = TRUE;
    }
}

if ($login == TRUE) {
    session_start();
    $_SESSION["id"] = $_POST["id"];
    echo "登入成功";
    echo "<meta http-equiv=REFRESH content='3, url=11.bulletin.php'>";
} else {
    echo "帳號/密碼 錯誤";
    echo "<meta http-equiv=REFRESH content='3, url=2.login.html'>";
}
?>

<?php  
// 顯示佈告欄，登入驗證 + 列出資料 + 編輯/刪除功能
error_reporting(0);
session_start();

if (!$_SESSION["id"]) {
    echo "請先登入";
    echo "<meta http-equiv=REFRESH content='3, url=2.login.html'>";
} else {
    echo "歡迎, ".$_SESSION["id"]."[<a href=12.logout.php>登出</a>] [<a href=18.user.php>管理使用者</a>] [<a href=22.bulletin_add_form.php>新增佈告</a>]<br>";

    $conn = mysqli_connect("db4free.net", "immust", "immustimmust", "immust");
    $result = mysqli_query($conn, "select * from bulletin");

    echo "<table border=2><tr><td></td><td>佈告編號</td><td>佈告類別</td><td>標題</td><td>佈告內容</td><td>發佈時間</td></tr>";
    while ($row = mysqli_fetch_array($result)){
        echo "<tr><td>
        <a href=26.bulletin_edit_form.php?bid={$row["bid"]}>修改</a> 
        <a href=28.bulletin_delete.php?bid={$row["bid"]}>刪除</a></td><td>";
        echo $row["bid"];
        echo "</td><td>";
        echo $row["type"];
        echo "</td><td>"; 
        echo $row["title"];
        echo "</td><td>";
        echo $row["content"];
        echo "</td><td>";
        echo $row["time"];
        echo "</td></tr>";
    }
    echo "</table>";
}
?>

<?php  
// 登出功能，清除 session
session_start();
unset($_SESSION["id"]);
echo "登出成功....";

// 3 秒後導回登入頁
echo "<meta http-equiv=REFRESH content='3; url=2.login.html'>";
?>

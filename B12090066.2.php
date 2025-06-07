<html>
    <head><title>新增使用者</title></head>
    <body>
<?php        
    // 關閉錯誤報告（不建議正式環境使用，應使用 error_log 等方式記錄錯誤）
    error_reporting(0);

    // 啟動 session 功能，用來追蹤登入狀態
    session_start();

    // 如果沒有登入（$_SESSION["id"] 不存在）
    if (!$_SESSION["id"]) {
        // 顯示提示訊息
        echo "請登入帳號";
        // 3 秒後自動跳轉回登入頁面 2.login.html
        echo "<meta http-equiv=REFRESH content='3, url=2.login.html'>";
    }
    else {    
        // 如果已登入，顯示新增使用者表單
        echo "
            <form action=15.user_add.php method=post>
                帳號：<input type=text name=id><br>
                密碼：<input type=text name=pwd><p></p>
                <input type=submit value=新增> <input type=reset value=清除>
            </form>
        ";
    }
?>
    </body>
</html>
<?php
// 關閉錯誤提示（建議開發時開啟 error_reporting(E_ALL)）
error_reporting(0);

// 啟動 Session 功能，用來判斷使用者是否登入
session_start();

// 如果使用者尚未登入，提示登入並自動跳轉回登入頁面
if (!$_SESSION["id"]) {
    echo "請登入帳號";
    echo "<meta http-equiv=REFRESH content='3, url=2.login.html'>";
}
else {    
    // 建立與 MySQL 資料庫的連線（遠端伺服器：db4free.net）
    $conn = mysqli_connect("db4free.net", "immust", "immustimmust", "immust");

    // 組合 SQL 語句，從表格 user 中插入帳號與密碼
    $sql = "insert into user(id, pwd) values('{$_POST['id']}', '{$_POST['pwd']}')";

    // 執行 SQL 查詢
    if (!mysqli_query($conn, $sql)) {
        echo "新增命令錯誤"; // 若執行失敗顯示錯誤訊息
    }
    else {
        echo "新增使用者成功，三秒鐘後回到網頁";
        echo "<meta http-equiv=REFRESH content='3, url=18.user.php'>";
    }
}
?>
<?php
    // 關閉錯誤提示（建議開發時使用 error_reporting(E_ALL);）
    error_reporting(0);

    // 啟動 Session，檢查使用者是否已登入
    session_start();

    // 如果未登入，提示訊息並在 3 秒後跳轉回登入畫面
    if (!$_SESSION["id"]) {
        echo "請登入帳號";
        echo "<meta http-equiv=REFRESH content='3, url=2.login.html'>";
    }
    else {
        // 連接 MySQL 資料庫（伺服器：db4free.net）
        $conn = mysqli_connect("db4free.net", "immust", "immustimmust", "immust");

        // 組成 SQL 刪除語句，從 user 表格中刪除指定 id 的使用者（來自 URL 的 GET 參數）
        $sql = "delete from user where id='{$_GET["id"]}'";

        // 如果查詢執行失敗，顯示錯誤訊息
        if (!mysqli_query($conn, $sql)) {
            echo "使用者刪除錯誤";
        } else {
            echo "使用者刪除成功";
        }

        // 3 秒後自動跳轉回使用者列表頁面
        echo "<meta http-equiv=REFRESH content='3, url=18.user.php'>";
    }
?>
<?php
    // 關閉錯誤報告（實務上建議開發時使用 error_reporting(E_ALL);）
    error_reporting(0);

    // 啟動 session，用於驗證登入狀態
    session_start();

    // 檢查是否已登入（$_SESSION["id"] 有值代表已登入）
    if (!$_SESSION["id"]) {
        echo "請登入帳號"; // 顯示提示訊息
        echo "<meta http-equiv=REFRESH content='3, url=2.login.html'>"; // 3 秒後自動跳轉至登入頁
    } else {
        // 建立與資料庫的連線
        $conn = mysqli_connect("db4free.net", "immust", "immustimmust", "immust");

        // 使用 GET 方法取得要刪除的使用者帳號（來自 URL 參數）
        $sql = "delete from user where id='{$_GET["id"]}'"; // 組成 SQL 刪除語句

        // 嘗試執行 SQL 指令
        if (!mysqli_query($conn, $sql)) {
            echo "使用者刪除錯誤"; // 若執行失敗則顯示錯誤訊息
        } else {
            echo "使用者刪除成功"; // 成功則提示
        }

        // 不論成功與否，3 秒後返回使用者管理頁面
        echo "<meta http-equiv=REFRESH content='3, url=18.user.php'>";
    }
?>
<html>
    <head>
        <title>使用者管理</title>
    </head>
    <body>
    <?php
        // 關閉錯誤顯示（建議開發階段使用 error_reporting(E_ALL);）
        error_reporting(0);

        // 啟用 Session 以確認使用者是否登入
        session_start();

        // 檢查登入狀態，若尚未登入，提示訊息並 3 秒後導向登入頁面
        if (!$_SESSION["id"]) {
            echo "請登入帳號";
            echo "<meta http-equiv=REFRESH content='3, url=2.login.html'>";
        }
        else {
            // 顯示功能標題與功能連結（新增使用者／回佈告欄列表）
            echo "<h1>使用者管理</h1>
                [<a href=14.user_add_form.php>新增使用者</a>] 
                [<a href=11.bulletin.php>回佈告欄列表</a>]<br>

                <table border=1>
                    <tr>
                        <td>操作</td><td>帳號</td><td>密碼</td>
                    </tr>";

            // 建立與資料庫的連線
            $conn = mysqli_connect("db4free.net", "immust", "immustimmust", "immust");

            // 執行查詢，抓取所有使用者資料
            $result = mysqli_query($conn, "select * from user");

            // 逐筆列出使用者資料
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr>
                        <td>
                            <a href=19.user_edit_form.php?id={$row['id']}>修改</a> ||
                            <a href=17.user_delete.php?id={$row['id']}>刪除</a>
                        </td>
                        <td>{$row['id']}</td>
                        <td>{$row['pwd']}</td>
                      </tr>";
            }

            echo "</table>";
        }
    ?> 
    </body>
</html>
<html>
    <head>
        <title>修改使用者</title>
    </head>
    <body>
    <?php
        // 關閉錯誤訊息顯示（實務開發時建議使用 error_reporting(E_ALL);）
        error_reporting(0);

        // 啟動 session 機制
        session_start();

        // 若尚未登入，提示並跳轉回登入頁
        if (!$_SESSION["id"]) {
            echo "請登入帳號";
            echo "<meta http-equiv=REFRESH content='3, url=2.login.html'>";
        }
        else {
            // 建立與資料庫的連線
            $conn = mysqli_connect("db4free.net", "immust", "immustimmust", "immust");

            // 從網址取得要編輯的帳號 id，並查詢對應資料
            $result = mysqli_query($conn, "SELECT * FROM user WHERE id='{$_GET['id']}'");

            // 取出資料列
            $row = mysqli_fetch_array($result);

            // 顯示表單，預設值為資料庫中的密碼
            echo "
            <form method=post action=20.user_edit.php>
                <input type=hidden name=id value={$row['id']}>
                帳號：{$row['id']}<br> 
                密碼：<input type=text name=pwd value={$row['pwd']}><p></p>
                <input type=submit value=修改>
            </form>
            ";
        }
    ?>
    </body>
</html>
<?php
    // 關閉錯誤訊息（開發階段建議使用 error_reporting(E_ALL);）
    error_reporting(0);

    // 啟用 Session
    session_start();

    // 檢查是否已登入
    if (!$_SESSION["id"]) {
        echo "請登入帳號";
        echo "<meta http-equiv=REFRESH content='3, url=2.login.html'>";
    } else {
        // 建立資料庫連線
        $conn = mysqli_connect("db4free.net", "immust", "immustimmust", "immust");

        // 執行修改密碼的 SQL 命令
        $sql = "update user set pwd='{$_POST['pwd']}' where id='{$_POST['id']}'";

        if (!mysqli_query($conn, $sql)) {
            echo "修改錯誤";
            echo "<meta http-equiv=REFRESH content='3, url=18.user.php'>";
        } else {
            echo "修改成功，三秒鐘後回到網頁";
            echo "<meta http-equiv=REFRESH content='3, url=18.user.php'>";
        }
    }
?>
<?php
    // 關閉錯誤報告（開發階段建議打開：error_reporting(E_ALL);）
    error_reporting(0);

    // 啟動 session，檢查是否已登入
    session_start();
    if (!$_SESSION["id"]) {
        // 未登入提示並跳轉登入頁
        echo "please login first";
        echo "<meta http-equiv=REFRESH content='3, url=2.login.html'>";
    }
    else {
        // 建立與資料庫的連線
        $conn = mysqli_connect("db4free.net", "immust", "immustimmust", "immust");

        // 查詢欲編輯的佈告資料（透過 GET 傳入的 bid 編號）
        $result = mysqli_query($conn, "select * from bulletin where bid={$_GET["bid"]}");
        $row = mysqli_fetch_array($result);  // 將結果轉成陣列

        // 預設選項：依照資料表中的 type 設定哪個 radio 被勾選
        $checked1 = "";
        $checked2 = "";
        $checked3 = "";
        if ($row['type'] == 1) $checked1 = "checked";
        if ($row['type'] == 2) $checked2 = "checked";
        if ($row['type'] == 3) $checked3 = "checked";

        // 輸出 HTML 表單，並將資料填入預設值
        echo "
        <html>
            <head><title>修改佈告</title></head>
            <body>
                <form method=post action=27.bulletin_edit.php>
                    佈告編號：{$row['bid']} <input type=hidden name=bid value={$row['bid']}><br>
                    標    題：<input type=text name=title value='{$row['title']}'><br>
                    內    容：<br><textarea name=content rows=20 cols=20>{$row['content']}</textarea><br>
                    佈告類型：<input type=radio name=type value=1 {$checked1}>系上公告 
                            <input type=radio name=type value=2 {$checked2}>獲獎資訊
                            <input type=radio name=type value=3 {$checked3}>徵才資訊<br>
                    發布時間：<input type=date name=time value={$row['time']}><p></p>
                    <input type=submit value=修改佈告> <input type=reset value=清除>
                </form>
            </body>
        </html>
        ";
    }
?>
<?php
    // 關閉錯誤顯示（開發時建議改為 error_reporting(E_ALL);）
    error_reporting(0);

    // 啟動 session 用於登入檢查
    session_start();

    // 檢查是否已登入，若無則跳轉登入頁
    if (!$_SESSION["id"]) {
        echo "請登入帳號";
        echo "<meta http-equiv=REFRESH content='3, url=2.login.html'>";
    }
    else {   
        // 建立資料庫連線
        $conn = mysqli_connect("db4free.net", "immust", "immustimmust", "immust");

        // 執行更新佈告內容的 SQL 語句
        $sql = "update bulletin 
                set title='{$_POST['title']}',
                    content='{$_POST['content']}',
                    time='{$_POST['time']}',
                    type={$_POST['type']} 
                where bid='{$_POST['bid']}'";

        // 檢查是否成功執行 SQL 指令
        if (!mysqli_query($conn, $sql)) {
            echo "修改錯誤";
            echo "<meta http-equiv=REFRESH content='3, url=11.bulletin.php'>";
        } else {
            echo "修改成功，三秒鐘後回到佈告欄列表";
            echo "<meta http-equiv=REFRESH content='3, url=11.bulletin.php'>";
        }
    }
?>
<?php
    // 關閉錯誤顯示（上線環境常用，開發環境應設定為 error_reporting(E_ALL)）
    error_reporting(0);

    // 啟用 session 機制
    session_start();

    // 檢查是否登入，若未登入則顯示提示並三秒後跳轉至登入頁面
    if (!$_SESSION["id"]) {
        echo "請登入帳號";
        echo "<meta http-equiv=REFRESH content='3, url=2.login.html'>";
    }
    else {
        // 建立與資料庫的連線
        $conn = mysqli_connect("db4free.net", "immust", "immustimmust", "immust");

        // 使用 GET 取得 bid，執行刪除指令
        $sql = "delete from bulletin where bid='{$_GET["bid"]}'";

        // #echo $sql; // 除錯用途，實際使用時建議關閉

        // 執行 SQL 並依結果給予回應
        if (!mysqli_query($conn, $sql)) {
            echo "佈告刪除錯誤";
        } else {
            echo "佈告刪除成功";
        }

        // 三秒後跳轉回佈告欄列表
        echo "<meta http-equiv=REFRESH content='3, url=11.bulletin.php'>";
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>明新科技大學資訊管理系</title>
    <meta charset="utf-8">

    <!-- 載入 Flexslider 樣式表與 jQuery 函式庫 -->
    <link href="https://cdn.bootcss.com/flexslider/2.6.3/flexslider.min.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/jquery/2.2.2/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/flexslider/2.6.3/jquery.flexslider-min.js"></script>

    <!-- 初始化輪播效果 -->
    <script>
        $(window).load(function() {
            $('.flexslider').flexslider({
                animation: "slide", // 動畫類型為滑動
                rtl: true            // 啟用從右往左
            });
        });
    </script>

    <!-- 頁面樣式設定 -->
    <style>
        /* 全域設定 */
        * {
            margin: 0;
            color: gray;
            text-align: center;
        }

        /* 頁首區塊 */
        .top {
            background-color: white;
        }

        .top .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
        }

        .top .logo {
            font-size: 35px;
            font-weight: bold;
        }

        .top .logo img {
            width: 100px;
            vertical-align: middle;
        }

        .top .top-nav {
            font-size: 25px;
            font-weight: bold;
        }

        .top .top-nav a {
            text-decoration: none;
        }

        /* 導覽列樣式 */
        .nav {
            background-color: #333;
            display: flex;
            justify-content: center;
        }

        .nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #333;
        }

        .nav li {
            float: left;
        }

        .nav li a {
            display: block;
            color: white;
            padding: 14px 16px;
            text-decoration: none;
        }

        .nav li a:hover {
            background-color: #111;
        }

        /* 下拉選單樣式 */
        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #333;
            min-width: 160px;
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            display: block;
            text-align: left;
        }

        /* 輪播區塊背景 */
        .slider {
            background-color: black;
        }

        /* 系所簡介橫幅區塊 */
        .banner {
            background-image: linear-gradient(#ABDCFF, #0396FF);
            padding: 30px;
        }

        .banner h1 {
            padding: 20px;
        }

        /* 師資介紹區塊 */
        .faculty {
            background-color: white;
            padding: 40px;
        }

        .faculty h2 {
            font-size: 25px;
            color: rgb(50, 51, 52);
            padding-bottom: 40px;
        }

        .faculty .container {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .faculty .teacher {
            text-decoration: none;
        }

        .faculty .teacher img {
            height: 200px;
            width: 200px;
        }

        .faculty .teacher h3 {
            color: white;
            background-color: rgba(39, 40, 34, 0.5);
            text-align: center;
        }

        /* 聯絡資訊區塊 */
        .contact {
            margin: 30px 0;
        }

        .contact h2 {
            color: rgb(54, 82, 110);
            font-size: 25px;
        }

        .contact .infos {
            display: flex;
            margin-top: 30px;
            justify-content: center;
        }

        .contact .infos .left {
            text-align: left;
            margin-right: 30px;
        }

        .contact .infos .left b {
            display: block;
            font-size: 18px;
            line-height: 18px;
        }

        .contact .infos .left span {
            display: block;
            color: rgba(39, 40, 34, 0.5);
            font-size: 16px;
            padding-left: 27px;
        }

        .contact .infos .right {
            height: 200px;
        }

        .contact .infos .right iframe {
            width: 100%;
            height: 100%;
            border: 1px solid rgba(39, 40, 34, 0.5);
        }

        /* 頁尾樣式 */
        .footer {
            display: flex;
            justify-content: center;
            background-color: rgb(25, 26, 30);
            padding: 30px 0;
        }

        /* 登入彈出視窗樣式 */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            right: 50;
            top: 50;
            width: 20%;
            height: 20%;
            overflow: auto;
            background-color: rgba(255, 255, 255, 0.9);
            padding-top: 50px;
        }

        /* 佈告欄樣式 */
        .bulletin {
            background-color: rgb(255, 204, 153);
            padding: 30px 0;
        }

        .bulletin h1 {
            padding: 10px;
        }

        .bulletin table {
            border-collapse: collapse;
            font-family: 微軟正黑體;
            font-size: 16px;
            border: 1px solid #000;
        }

        .bulletin table th {
            background-color: #abdcff;
            color: #ffffff;
        }

        .bulletin table td {
            background-color: #ffffff;
            color: #0396ff;
        }
    </style>
</head>

<body>
    <!-- 頁首 -->
    <div class="top">
        <div class="container">
            <div class="logo">
                <img src="https://github.com/shhuangmust/html/raw/111-1/IMMUST_LOGO.JPG">
                明新科技大學資訊管理系
            </div>
            <div class="top-nav">
                <a href="#">明新科大</a>
                <a href="#">明新管理學院</a>
                <!-- 點擊後開啟登入視窗 -->
                <label onclick="document.getElementById('login').style.display='block'">登入</label>
                <div id="login" class="modal">
                    <span onclick="document.getElementById('login').style.display='none'">&times; 管理系統登入</span>
                    <form method="post" action="10.login.php">
                        帳號：<input type="text" name="id"><br />
                        密碼：<input type="password" name="pwd"><p></p>
                        <input type="submit" value="登入"> 
                        <input type="reset" value="清除">
                    </form>
                </div>
            </div>
        </div>
    </div>

    /* 導覽列 */
    <div class="nav">
        <ul>
            <li><a href="#home">首頁</a></li>
            <li><a href="#introduction">系所簡介</a></li>
            <li class="dropdown"><a href="#faculty">成員簡介</a>
                <div class="dropdown-content">
                    <a href="#faculty">黃老師</a>
                    <a href="#faculty">李老師</a>
                    <a href="#faculty">應老師</a>
                </div>
            </li>
            <li><a href="#about">相關資訊</a></li>
        </ul>
    </div>

    <!-- 輪播圖區塊 -->
    <div class="slider">
        <div class="flexslider">
            <ul class="slides">
                <li><img src="https://github.com/shhuangmust/html/raw/111-1/slider1.JPG" /></li>
                <li><img src="https://github.com/shhuangmust/html/raw/111-1/slider2.JPG" /></li>
                <li><img src="https://github.com/shhuangmust/html/raw/111-1/slider3.JPG" /></li>
            </ul>
        </div>
    </div>

    // 最新佈告欄（PHP 後端處理） //
    <div class="bulletin">
        <h1>最新公告</h1>
        <?php
            // 連接 MySQL 資料庫
            $conn = mysqli_connect("db4free.net", "immust", "immustimmust", "immust");

            // 查詢 bulletin 資料表
            $result = mysqli_query($conn, "SELECT * FROM bulletin");

            // 顯示表格表頭
            echo "<table border=2><tr><th>佈告編號</th><th>佈告類別</th><th>標題</th><th>佈告內容</th><th>發佈時間</th></tr>";

            // 逐筆輸出資料
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr><td>";
                echo $row["bid"];
                echo "</td><td>";
                if ($row["type"] == 1) echo "系上公告";
                if ($row["type"] == 2) echo "獲獎資訊";
                if ($row["type"] == 3) echo "徵才資訊";
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
    </div>

    <!-- 系所簡介 -->
    <div class="banner" id="introduction">
        <h1>系所簡介</h1>
        <h1>歷年教育部評鑑皆榮獲一等</h1>
        <h1>明新科技大學資訊管理系</h1>
        <h1>全國私立科大第一資管系</h1>
    </div>

    <!-- 師資介紹 -->
    <div class="faculty" id="faculty">
        <h2>師資介紹</h2>
        <div class="container">
            <a class="teacher" href="">
                <img src="https://github.com/shhuangmust/html/raw/111-1/faculty1.jpg" />
                <h3>黃老師</h3>
            </a>
            <a class="teacher" href="">
                <img src="https://github.com/shhuangmust/html/raw/111-1/faculty2.jpg" />
                <h3>李老師</h3>
            </a>
            <a class="teacher" href="">
                <img src="https://github.com/shhuangmust/html/raw/111-1/faculty3.jpg" />
                <h3>應老師</h3>
            </a>
        </div>
    </div>

    <!-- 聯絡資訊 -->
    <div class="contact" id="about">
        <h2>相關資訊</h2>
        <div class="infos">
            <div class="left">
                <b>明新科技大學管理學院大樓二樓</b>
                <span>304新竹縣新豐鄉新興路1號</span>
                <b>電話:03-5593142</b>
                <span>分機:3431、3432、3433</span>
                <b>傳真:03-5593142</b>
                <span>分機:3440</span>
            </div>
            <div class="right">
                <!-- Google 地圖嵌入 -->
                <iframe src="https://www.google.com/maps/embed?pb=..." frameborder="0" style="border:0" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <!-- 頁尾 -->
    <div class="footer">
        &copy;Copyright 2022 Department of Information Management, MUST. All rights reserved. 維護者 Tony SHHuang
    </div>
</body>
</html>

<?php
session_start();

$pdo = new PDO('mysql:host=localhost;dbname=db55', 'admin', '1234');

// 登入功能
function login($username, $password) {
    global $pdo;

    // 檢查帳號和密碼是否正確
    $sql = "SELECT * FROM user WHERE name = :name AND password = :password";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':name' => $username, ':password' => $password));
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user) {
        $_SESSION['username'] = $username;
        // 將登入狀態設置為 1
        $sql = "UPDATE `user` SET `log-in` = '1' WHERE name = :name AND password = :password";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':name' => $username, ':password' => $password));
        return true;
    } else {
        return false;
    }
}


// 登出
function logout() {
    global $pdo;

    if(isset($_SESSION['username'])) {
        // 清除 session
        session_unset();
        session_destroy();
        // 將登入狀態設置為 0
        $sql = "UPDATE `user` SET `log-in` = '0' WHERE name = :name";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':name' => $_SESSION['username']));
    }
}

// 檢查
if(isset($_POST['action']) && $_POST['action'] == 'login') {
    if(login($_POST['username'], $_POST['password'])) {
        echo 'loggedin';
    } else {
        echo 'failed';
    }
} elseif(isset($_GET['action']) && $_GET['action'] == 'logout') {
    logout();
}
?>

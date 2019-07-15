<meta charset="UTF-8">

<?php
    // 데이터베이스 연결
    include_once 'data/db/conn.php';

    // 사용자의 입력 값중에 SQL injection 및 XSS 예방
    $user_id = strip_tags(stripslashes($_POST['user_id']));
    $user_password = strip_tags(stripslashes($_POST['user_password']));

    // SQL injection 공격에 대비하여 sprintf와 escape 함수로 구문 처리
    $login_query = sprintf('SELECT user_id, user_password FROM tbl_user WHERE user_id = "%s"', mysqli_real_escape_string($conn, $user_id));

    // 로그인 쿼리 전송하는 부분으로, 로그인 쿼리 전송 후 DB로부터 정보가 없을 경우
    $result = $conn -> query($login_query);
    if(!$result) {
        echo "<script>alert('로그인 과정에 문제가 발생하였습니다.');</script>";
        echo "<meta http-equiv='refresh' content='0;url=Login.php'>";
        exit();
    
    // DB가 동일한 사용자 정보를 발견하였을 경우
    }else {
        $row = $result -> fetch_assoc();
        $get_user_id = $row['user_id'];
        $get_user_hash_password = $row['user_password'];
    }

    // 사용이 완료된 데이터베이스 연결 끊기
    $conn->close();
    
    // 사용자가 입력한 계정이 DB에 있는 계정과 모두 동일할 경우
    if(!strcmp($user_id, $get_user_id) && password_verify($user_password, $get_user_hash_password)) {
        // 세션 시작
        session_start();
        $_SESSION['user_id'] = $user_id;
        // 여기부터 세션 하이재킹 보안 작업 필요

        echo "<meta http-equiv='refresh' content='0;url=Database.php'>";
        exit();

    }else {
        echo "<script>alert('아이디 혹은 비밀번호가 올바르지 않습니다.');</script>";
        echo "<meta http-equiv='refresh' content='0;url=Login.php'>";
        exit();
    }
?>
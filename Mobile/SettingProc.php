<meta charset="UTF-8">

<?php
    // 데이터베이스 연결
    include_once 'data/db/conn.php';

    // 세션 시작
    session_start();
    if(!isset($_SESSION['user_id'])) {
        echo '<script>alert("로그인된 정보를 찾을 수 없습니다.");</script>';
        echo "<meta http-equiv='refresh' content='0;url=Login.php'>";
        exit();
    }

    $language = strip_tags(stripslashes($_POST['language']));
    $theme = strip_tags(stripslashes($_POST['theme']));
    $user_id = strip_tags(stripslashes($_SESSION['user_id']));

    // DB에 저장하기 위한 쿼리문
    $modify_setting_query = sprintf('UPDATE user_setting
                                    SET language = %d, theme = %d
                                    WHERE user_id = "%s";',
                                    mysqli_real_escape_string($conn, $language),
                                    mysqli_real_escape_string($conn, $theme),
                                    mysqli_real_escape_string($conn, $user_id)
                                    );

    $result = $conn -> query($modify_setting_query);
    if(!$result) {
        echo '<script>alert("환경설정 적용에 실패하였습니다.");</script>';
        echo '<meta http-equiv="refresh" content="0;url=Database.php">';
        exit();

    }else {
        echo '<script>alert("환경설정이 적용되었습니다.");</script>';
        echo '<meta http-equiv="refresh" content="0;url=Database.php">';
        exit();
    }
?>
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

    // Database.php 으로부터 전달된 POST 값을 변수에 저장
    $table_idx = strip_tags(stripslashes($_POST['db_idx']));
    $table_name = strip_tags(stripslashes($_POST['db_name']));
    $table_disc = strip_tags(stripslashes($_POST['db_disc']));

    // 삭제하는 데이터의 소유자와 로그인 된 소유자가 동일한지 확인하는 보안 작업 필요
    $get_db_user = sprintf('SELECT id FROM tbl_table WHERE table_idx = %d;', mysqli_real_escape_string($conn, $table_idx));

    $result = $conn -> query($get_db_user);
    if(!$result) {
        echo '<script>alert("회원정보를 불러오는데 실패하였습니다.");</script>';
        echo '<meta http-equiv="refresh" content="0;url=Database.php">';
        exit();
        
    }else {
        $row = $result -> fetch_assoc();
        $get_user_id = $row['id'];
    }

    if(!strcmp($_SESSION['user_id'], $get_user_id) == true) {
        // DB에 저장하기 위한 쿼리문
        $modify_table_query = sprintf('UPDATE tbl_table
                                SET table_name = "%s",
                                table_disc = "%s"
                                WHERE table_idx = %d;',
                                mysqli_real_escape_string($conn, $table_name),
                                mysqli_real_escape_string($conn, $table_disc),
                                mysqli_real_escape_string($conn, $table_idx)
                                );

        $result = $conn -> query($modify_table_query);
        if(!$result) {
            echo '<script>alert("시스템 문제로 데이터베이스 수정이 실패하였습니다.");</script>';
            echo '<meta http-equiv="refresh" content="0;url=Database.php">';
            exit();

        }else {
            echo '<script>alert("데이터베이스 수정이 완료되었습니다.");</script>';
            echo '<meta http-equiv="refresh" content="0;url=Database.php">';
            exit();
        }
        
    }else {
        echo '<script>alert("비정상적인 경로로 접근하셨습니다.");</script>';
        echo '<meta http-equiv="refresh" content="0;url=Database.php">';
        exit();
    }


    

?>
<meta charset="UTF-8">

<?php
    // 데이터베이스 연결
    include_once 'data/db/conn.php';

    // 세션 시작
    session_start();

    // Database.php 로부터 전달된 POST 값을 변수에 저장
    // SQL injection 및 XSS 공격 예방
    $db_name = strip_tags(stripslashes($_POST['db_name']));
    $db_disc = strip_tags(stripslashes($_POST['db_disc']));

    // DB에 저장하기 위한 쿼리문
    $create_table_query = sprintf('INSERT INTO tbl_table (table_name, table_disc, id)
                                values ("%s", "%s", "%s");',
                                mysqli_real_escape_string($conn, $db_name),
                                mysqli_real_escape_string($conn, $db_disc),
                                mysqli_real_escape_string($conn, $_SESSION['user_id'])
                                );

    $result = $conn -> query($create_table_query);
    if(!$result) {
        echo '<script>alert("데이터베이스 저장에 실패하였습니다.");</script>';
        echo '<meta http-equiv="refresh" content="0;url=Database.php">';
        exit();

    }else {
        echo '<script>alert("데이터베이스 저장이 완료되었습니다.");</script>';
        echo '<meta http-equiv="refresh" content="0;url=Database.php">';
        exit();
    }
?>
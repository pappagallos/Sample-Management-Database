<meta charset="UTF-8">

<?php
    // 데이터베이스 연결
    include_once 'data/db/conn.php';

    // 세션 시작
    session_start();

    // TableForm.php 으로부터 전달된 POST 값을 변수에 저장
    // XSS와 SQL injection 을 예방하기 위해 html 태그를 제거하고 특수문자가 존재한다면 특수문자 그대로 표시
    $table_idx = stripslashes($_POST['table_idx']);
    $data_idx = strip_tags(stripslashes($_POST['data_idx']));
    $data_name = str_replace("\r\n", "<br>", stripslashes($_POST['data_name'])); // 데이터 제목에는 기능상 html 태그를 제거할 수 없음
    $year = strip_tags(stripslashes($_POST['year']));
    $month = strip_tags(stripslashes($_POST['month']));
    $days = strip_tags(stripslashes($_POST['days']));
    $data_vector_type = strip_tags(stripslashes($_POST['data_vector_type']));
    $data_concentration = strip_tags(stripslashes($_POST['data_concentration']));
    $data_etc = strip_tags(stripslashes($_POST['data_etc']));

    // DBMS에서 data_date 컬럼이 date 자료형을 사용하기 때문에 date 자료형으로 변환
    $data_date = date("Y-m-d", strtotime($year.'-'.$month.'-'.$days));

    // 삭제하는 데이터의 소유자와 로그인 된 소유자가 동일한지 확인하는 작업
    $get_db_user = sprintf('SELECT t.id
                    FROM tbl_table t, tbl_data d
                    WHERE d.data_idx = %d AND t.table_idx = d.table_idx;',
                    mysqli_real_escape_string($conn, $data_idx)
                    );

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
        $modify_data_query = sprintf('UPDATE tbl_data SET
                                data_name = "%s",
                                data_date = "%s",
                                data_vector_type = "%s",
                                data_concentration = "%s",
                                data_etc = "%s"
                                WHERE table_idx = %d AND data_idx = %d;',
                                mysqli_real_escape_string($conn, $data_name),
                                mysqli_real_escape_string($conn, $data_date),
                                mysqli_real_escape_string($conn, $data_vector_type),
                                mysqli_real_escape_string($conn, $data_concentration),
                                mysqli_real_escape_string($conn, $data_etc),
                                mysqli_real_escape_string($conn, $table_idx),
                                mysqli_real_escape_string($conn, $data_idx)
                                );

        $result = $conn -> query($modify_data_query);
        if(!$result) {
            echo '<script>alert("시스템 문제로 데이터 수정에 실패하였습니다.");</script>';
            echo '<meta http-equiv="refresh" content="0;url=TableForm.php?idx='.mysqli_real_escape_string($conn, $table_idx).'">';
            exit();

        }else {
            echo '<script>alert("데이터 수정이 완료되었습니다.");</script>';
            echo '<meta http-equiv="refresh" content="0;url=TableForm.php?idx='.mysqli_real_escape_string($conn, $table_idx).'">';
            exit();
        }

    }else {
        echo '<script>alert("비정상적인 경로로 접근하셨습니다.");</script>';
        echo '<meta http-equiv="refresh" content="0;url=TableForm.php?idx='.mysqli_real_escape_string($conn, $table_idx).'">';
        exit();
    }
?>
<meta charset="UTF-8">

<?php
    // 데이터베이스 연결
    include_once 'data/db/conn.php';

    // 세션 시작
    session_start();

    // Database.php 로부터 전달된 POST 값을 변수에 저장
    $table_idx = strip_tags(stripslashes($_POST['table_idx']));
    $data_name = str_replace("\r\n", "<br>", stripslashes($_POST['data_name']));
    $year = strip_tags(stripslashes($_POST['year']));
    $month = strip_tags(stripslashes($_POST['month']));
    $days = strip_tags(stripslashes($_POST['days']));
    $data_vector_type = strip_tags(stripslashes($_POST['data_vector_type']));
    $data_concentration = strip_tags(stripslashes($_POST['data_concentration']));
    $data_etc = strip_tags(stripslashes($_POST['data_etc']));
    $data_row = strip_tags(stripslashes($_POST['data_row']));
    $data_col = strip_tags(stripslashes($_POST['data_col']));

    // DBMS에서 data_date 컬럼이 date 자료형을 사용하기 때문에 date 자료형으로 변환
    $data_date = date( "Y-m-d", strtotime($year.'-'.$month.'-'.$days));

    // DB에 저장하기 위한 쿼리문
    $create_data_query = sprintf('INSERT INTO tbl_data (table_idx, data_name, data_date, data_vector_type, data_concentration, data_etc, data_row, data_col)
                        values (%d, "%s", "%s", "%s", "%s", "%s", %d, %d);',
                        mysqli_real_escape_string($conn, $table_idx),
                        mysqli_real_escape_string($conn, $data_name),
                        mysqli_real_escape_string($conn, $data_date),
                        mysqli_real_escape_string($conn, $data_vector_type),
                        mysqli_real_escape_string($conn, $data_concentration),
                        mysqli_real_escape_string($conn, $data_etc),
                        mysqli_real_escape_string($conn, $data_row),
                        mysqli_real_escape_string($conn, $data_col)
                        );

    $result = $conn -> query($create_data_query);
    if(!$result) {
        echo '<script>alert("데이터 저장에 실패하였습니다.");</script>';
        echo '<meta http-equiv="refresh" content="0;url=TableForm.php?idx='.$table_idx.'">';
        exit();

    }else { 
        echo '<script>alert("데이터가 저장되었습니다.");</script>';
        echo '<meta http-equiv="refresh" content="0;url=TableForm.php?idx='.$table_idx.'">';
        exit();
    }

?>
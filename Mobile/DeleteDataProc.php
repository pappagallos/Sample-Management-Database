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

    // 삭제할 idx 파라미터 값을 GET으로 받아온 뒤 초기화
    $delete_data_table_idx = strip_tags(stripslashes($_GET['idx']));
    $delete_data_idx = strip_tags(stripslashes($_GET['data_idx']));

    // 삭제하는 데이터의 소유자와 로그인 된 소유자가 동일한지 확인하는 보안 작업 필요
    $get_db_user = sprintf('SELECT t.id
                            FROM tbl_table t, tbl_data d
                            WHERE d.data_idx = %d
                            AND t.table_idx = d.table_idx;',
                            mysqli_real_escape_string($conn, $delete_data_idx)
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
    
    if($_SESSION['user_id'] == $get_user_id) {
        // DB에서 해당하는 table을 삭제
        $delete_data_query = sprintf('DELETE FROM tbl_data
                                    WHERE table_idx = %d
                                    AND data_idx = %d;',
                                    mysqli_real_escape_string($conn, $delete_data_table_idx),
                                    mysqli_real_escape_string($conn, $delete_data_idx)
                                    );

        $result = $conn -> query($delete_data_query);
        if(!$result) {
            echo '<script>alert("선택하신 데이터 삭제에 실패하였습니다.");</script>';
            echo '<meta http-equiv="refresh" content="0;url=TableForm.php?idx='.$delete_data_table_idx.'">';
            exit();

        }else {
            echo '<script>alert("선택하신 데이터 삭제가 완료되었습니다.");</script>';
            echo '<meta http-equiv="refresh" content="0;url=TableForm.php?idx='.$delete_data_table_idx.'">';
            exit();
        }

    }else {
        echo '<script>alert("비정상적인 경로로 접근하셨습니다.");</script>';
        echo '<meta http-equiv="refresh" content="0;url=TableForm.php?idx='.$delete_data_table_idx.'">';
        exit();
    }
?>
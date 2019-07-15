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

    // 삭제할 idx 파라미터 값을 GET으로 받아온 뒤 초기화, 이때 숫자가 아닌 다른 문자들은 전부 제거
    // strip_tags는 XSS 공격을 방지하고자 html 태그를 제거하고 stripslashes는 '를 붙여 특수문자를 문자로 보이게 만들어 놓은 문자들을 다시 ' 를 제거해서 특수문자로 만들어준다.
    $delete_table_idx = strip_tags(stripslashes($_GET['idx']));

    // 삭제하는 데이터의 소유자와 로그인 된 소유자가 동일한지 확인하는 작업
    // sprintf 를 mysqli_real_escape_string 과 함께 사용하는 것은 SQL injection 해킹 기법을 최대한 막고자 하는 방어 기법
    $get_db_user = sprintf('SELECT id FROM tbl_table WHERE table_idx = %d', mysqli_real_escape_string($conn, $delete_table_idx));
    //echo '<script>alert("'.$get_db_user.'");</script>';

    $result = $conn -> query($get_db_user);
    if(!$result) {
        echo '<script>alert("회원정보를 불러오는데 실패하였습니다.");</script>';
        echo '<meta http-equiv="refresh" content="0;url=Database.php">';
        exit();
        
    }else {
        $row = $result -> fetch_assoc();
        $get_user_id = $row['id'];
    }

    // 만약 로그인한 사용자와 데이터베이스 소유자의 사용자가 동일하다면 데이터베이스 삭제
    if($_SESSION['user_id'] == $get_user_id) {
        // DB에서 해당하는 table을 삭제
        $delete_table_query = sprintf('DELETE FROM tbl_table WHERE table_idx = %d', mysqli_real_escape_string($conn, $delete_table_idx));

        $result = $conn -> query($delete_table_query);
        if(!$result) {
            echo '<script>alert("데이터베이스 삭제에 실패하였습니다.");</script>';
            echo '<meta http-equiv="refresh" content="0;url=Database.php">';
            exit();

        }else {
            echo '<script>alert("데이터베이스 삭제가 완료되었습니다.");</script>';
            echo '<meta http-equiv="refresh" content="0;url=Database.php">';
            exit();
        }

    // 로그인한 사용자와 데이터베이스 소유자의 사용자가 동일하지 않다면 삭제 거부
    }else {
        echo '<script>alert("비정상적인 경로로 접근하셨습니다.");</script>';
        echo '<meta http-equiv="refresh" content="0;url=Database.php">';
        exit();
    }

?>
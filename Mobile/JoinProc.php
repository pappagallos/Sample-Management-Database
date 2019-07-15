<meta charset="UTF-8">

<?php
    // 데이터베이스 연결
    include_once 'data/db/conn.php';

    // 이용약관과 개인정보처리방침에 동의를 하였는지 검사, 동의없이는 가입이 불가능
    if($_POST['chkterm'] == NULL || $_POST['chkterm'] != 1) {
        echo "<script>alert('이용약관을 동의해주셔야 회원가입이 가능합니다.');</script>";
        echo "<meta http-equiv='refresh' content='0;url=Login.php'>";
        exit();

    }else if($_POST['chkinfo'] == NULL || $_POST['chkinfo'] != 1) {
        echo "<script>alert('개인정보처리방침을 동의해주셔야 회원가입이 가능합니다.');</script>";
        echo "<meta http-equiv='refresh' content='0;url=Login.php'>";
        exit();
    }

    // 사용자의 입력 값중에 SQL injection 및 XSS 예방
    // stripslashes 함수가 작동되지 않는 문제 발생
    $get_id = strip_tags(stripslashes($_POST['user_id']));
    $get_password = strip_tags(stripslashes($_POST['user_password']));
    $get_name = strip_tags(stripslashes($_POST['user_name']));
    $get_team = strip_tags(stripslashes($_POST['user_team']));
    
    // 사용자 비밀번호 암호화
    $get_hash_password = password_hash($get_password, PASSWORD_BCRYPT);

    // 중복된 아이디가 있는지 검사
    $chk_exist_id = sprintf('SELECT count(user_id) FROM tbl_user WHERE user_id = "%s"', mysqli_real_escape_string($conn, $get_id));
    
    $chk_exist_result = $conn -> query($chk_exist_id);

    if($chk_exist_result == true) {
        $row = $chk_exist_result -> fetch_assoc();
        $is_exist_id = $row['count(user_id)'];
        
        // 사용자가 입력한 id 값을 쿼리로 보내 카운트가 0 이상이면 존재한다는 의미이므로 가입 거절
        if($is_exist_id > 0) {
            echo '<script>alert("입력하신 아이디는 이미 사용중인 아이디 입니다.");</script>';
            echo "<meta http-equiv='refresh' content='0;url=Login.php'>";
            exit();
        }

    }else {
        echo '<script>alert("사용자가 입력한 아이디가 존재하는지 확인하는 작업에서 문제가 발생하였습니다.");</script>';
        echo "<meta http-equiv='refresh' content='0;url=Login.php'>";
        exit();
        
    }

    // 서버단에서도 회원가입을 위해 필수 입력 값들을 입력하였는지 검사하고 입력이 안되어 있다면 가입 거절
    if(!strcmp((mysqli_real_escape_string($conn, $get_id)), '') == true) {
        echo "<script>alert('아이디를 입력해주세요.');</script>";
        echo "<meta http-equiv='refresh' content='0;url=Login.php'>";
        exit();

    }else if(!strcmp((mysqli_real_escape_string($conn, $get_password)), '') == true) {
        echo "<script>alert('비밀번호를 입력해주세요.');</script>";
        echo "<meta http-equiv='refresh' content='0;url=Login.php'>";
        exit();

    }else if(!strcmp((mysqli_real_escape_string($conn, $get_name)), '') == true) {
        echo "<script>alert('이름을 입력해주세요.');</script>";
        echo "<meta http-equiv='refresh' content='0;url=Login.php'>";
        exit();

    }else if(!strcmp((mysqli_real_escape_string($conn, $get_team)), '') == true) {
        echo "<script>alert('전공 또는 조직명을 입력해주세요.');</script>";
        echo "<meta http-equiv='refresh' content='0;url=Login.php'>";
        exit();
    }

    // 위의 모든 서버단 작업을 거쳤을 경우 최종적으로 회원가입 승인
    $insert = sprintf('insert into tbl_user (user_id, user_password, user_name, user_team, info_agree_date, term_agree_date)
                 values ("%s", "%s", "%s", "%s", "%s", "%s");',
                    mysqli_real_escape_string($conn, $get_id),
                    mysqli_real_escape_string($conn, $get_hash_password),
                    mysqli_real_escape_string($conn, $get_name),
                    mysqli_real_escape_string($conn, $get_team),
                    date('Y-m-d H:i:s', time()),
                    date('Y-m-d H:i:s', time()));

    // 아래 INSERT 구문은 사용자 환경설정 DB 추가 부분
    // SQL DB측에서 ON CASCADE UPDATE, DELETE 가 적용되지 않는 문제가 발생하고 있어 수동적으로 추가
    // 이 부분은 추후 보완할 예정
    $insert_set = sprintf('INSERT INTO user_setting (user_id) VALUES ("%s");', mysqli_real_escape_string($conn, $get_id));
    
    // 회원가입 절차와 사용자 환경설정 데이터 추가 실행
    $result = $conn->query($insert);
    $result_set = $conn->query($insert_set);

    // 모든 SQL 쿼리문 실행이 안정적으로 마쳐졌다면 회원가입 완료
    if($result == true && $result_set == true) {
        echo "<script>alert('회원가입이 완료되었습니다.');</script>";
        echo "<meta http-equiv='refresh' content='0;url=Login.php'>";
        exit();
    
    // 쿼리문에 문제가 있거나 DB에 문제가 발생하였을 경우 회원가입 실패
    }else {
        echo "<script>alert('문제가 발생하여 회원가입을 실패하였습니다.');</script>";
        echo "<meta http-equiv='refresh' content='0;url=Login.php'>";
        exit();
    }

    $conn->close();
?>
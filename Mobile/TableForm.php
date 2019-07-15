<?php include_once 'Header.php'; ?>
<?php
session_start();

// 본 페이지에 접근한 사용자가 로그인 후 정상적인 경로로 접근하였는지 확인
if(!isset($_SESSION['user_id'])) {
    echo '<script>alert("로그인된 정보를 찾을 수 없습니다.");</script>';
    echo '<meta http-equiv="refresh" content="0;url=Login.php">';
    exit();
}

$idx = strip_tags(stripslashes($_GET['idx']));

// 해당 유저의 데이터베이스 테이블이 맞는지 확인하기 위해 GET으로 받아온 idx 값을 쿼리문으로 전송하여 해당 idx의 id값을 가져오는 쿼리문
$get_table_user_id_query = sprintf('SELECT id, table_name
                                    FROM tbl_table
                                    WHERE table_idx = %d;', 
                                    mysqli_real_escape_string($conn, $idx)
                                    );
$result = $conn -> query($get_table_user_id_query);
if(!$result) {
    echo '<script>alert("데이터베이스 소유자를 가져오는데 실패하였습니다.");</script>';
    echo '<meta http-equiv="refresh" content="0;url=Database.php">';
    exit();

}else {
    $row = $result -> fetch_assoc();
    $table_user_id = $row['id'];
    $table_name = $row['table_name'];
}

// 로그인된 user_id 값과 사용자가 조회하려는 idx 값의 테이블의 소유자와 동일한지 확인
if(strcmp($_SESSION['user_id'], $table_user_id)) {
    echo '<script>alert("해당 데이터베이스를 볼 수 있는 권한이 없거나 존재하지 않는 데이터베이스 입니다.");</script>';
    echo '<meta http-equiv="refresh" content="0;url=Database.php">';
    exit();
}
?>
    <!-- 디자인 -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <?php 
    // 사용자 환경설정 정보를 불러오는 쿼리문
    $get_user_setting_query = sprintf('SELECT * FROM user_setting WHERE user_id = "%s";', $_SESSION['user_id']);
    $result = $conn -> query($get_user_setting_query);

    if(!$result) {
        echo '<script>alert("사용자 환경설정 정보를 불러오는데 실패하였습니다.");</script>';

    }else {
        $row = $result -> fetch_assoc();
        $user_id = $row['user_id'];
        $language = $row['language'];
        $theme = $row['theme'];
    }
    ?>
    <link rel="stylesheet" href="data/css/init.css">
    <?php if($language == 0) echo '<link rel="stylesheet" href="data/css/korean.css">'; else echo '<link rel="stylesheet" href="data/css/english.css">'; ?>
    <?php if($theme == 0) echo '<link rel="stylesheet" href="data/css/default.css">'; else echo '<link rel="stylesheet" href="data/css/pastel.css">'; ?>
</head>

<body>
    <!-- 데이터베이스 메뉴 부분 -->
    <div class="database-menu">
        <span class="title"><b><?php echo $table_name; ?></b></span>
        <a href="Database.php"><i class="material-icons mobile-menu-icon" style="right: 65px;">home</i></a>
        <a href="#" onClick="javascript: openBoard(3);"><i class="material-icons mobile-menu-icon">search</i></a>
    </div>

    <div class="database-screen">
        <table>
            <thead>
                <tr>
                    <?php
                    // 표를 보기 편하기 위해 생성한 col 알파벳 배열 생성
                    $col_alphabet = array('', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I');

                    // 알파벳 배열 table 생성
                    for($i=0; $i<count($col_alphabet); $i++) {
                        if($i === 0) echo '<td width="5%" class="first-td-style"></td>';
                        else echo '<td width="10%" class="td-style"><b>'.$col_alphabet[$i].'</b></td>';
                    }

                    // 사용 완료된 배열 삭제
                    unset($col_alphabet);
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                // 상수 선언, 변하지 않는 테이블 값
                const COL_MAX_COUNT = 9;
                const ROW_MAX_COUNT = 9;
                $data_recode_count = 0;

                // DBMS에서 데이터를 차례대로 가져오는 쿼리문
                for($i=0; $i<COL_MAX_COUNT; $i++) {
                    echo '<tr>';
                    echo '<td class="first-data-td-style"><b>'.($i+1).'</b></td>';

                    for($j=0; $j<ROW_MAX_COUNT; $j++) {
                        $get_table_contents = sprintf('SELECT * FROM tbl_data
                                                    WHERE table_idx = %d AND data_row = %d AND data_col = %d;',
                                                    mysqli_real_escape_string($conn, $idx),
                                                    $i, $j
                                                    );
                        $result = $conn -> query($get_table_contents);
                        
                        if(!$result) {
                            echo '<script>alert("데이터를 가져오는데 실패하였습니다.");</script>';
                            echo '<meta http-equiv="refresh" content="0;url=Database.php">';

                        }else {
                            $row = $result -> fetch_assoc();

                            // 변수 선언
                            $year = null;
                            $month = null;
                            $day = null;
                            $display = false;

                            $table_idx = $row['table_idx'];
                            $data_name = $row['data_name'];
                            $param_data_name = $row['data_name']; // 문자이므로 따옴표로 표시하여 출력

                            if(empty($row['data_date']) == true) $display = false;
                            else {
                                list($year, $month, $day) = explode('-', $row['data_date']);
                                $display = true;
                            }
                            $data_vector_type = $row['data_vector_type'];
                            $data_concentration = $row['data_concentration'];
                            $data_etc = $row['data_etc'];
                            $data_row = $row['data_row'];
                            $data_col = $row['data_col'];
                            $data_idx = $row['data_idx'];

                            if($display == true) echo '<td id="'.$data_recode_count.'" class="data-td-style" data-table-idx="'.$table_idx.'" data-name="'.$param_data_name.'" data-year="'.$year.'" data-month="'.$month.'" data-day="'.$day.'" data-vector-type="'.$data_vector_type.'" data-concentration="'.$data_concentration.'" data-etc="'.$data_etc.'" data-idx="'.$data_idx.'" onClick="openViewBoard('.($data_recode_count++).')"><div class="data-name"><span class="date">'.$year.$month.$day.'</span><br><span class="name">'.$data_name.'</span></div></td>';
                            else echo '<td class="data-td-style" onClick="writeBoard('.$_GET['idx'].', '.$i.', '.$j.')"></td>';
                        }
                    }

                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php
    // language = 1은 영어, language = 0은 한국어
    if($language == 1) $arr_language = array("Information", "Title(*)", "Date", "Vector", "Concentration", "Etc.", "Cancle", "Submit", "Information", "Submit");
    else $arr_language = array("데이터 등록", "이름(*)", "생산일자", "VECTOR 종류", "농도", "기타", "취소", "등록", "데이터 관리", "수정");
    ?>

    <!-- 데이터 등록 뷰어 -->
    <div class="create-table-form" id="data-write">
        <form action="CreateDataProc.php" method="post" class="data-form">

            <input type="hidden" id="table_idx" name="table_idx" value="">
            <input type="hidden" id="data_row" name="data_row" value="">
            <input type="hidden" id="data_col" name="data_col" value="">
        
            <div class="title"><b><?php echo $arr_language[0]; ?></b></div>
            <div class="input">
                <span><?php echo $arr_language[1]; ?></span>
                <!-- <input type="text" id="data_name" name="data_name" value="" required> -->
                <textarea id="data_name" name="data_name" require style="padding: 5px;"></textarea>
            </div>
            <div class="input">
                <span><?php echo $arr_language[2]; ?></span>
                <div class="select-box">
                    <select id="year" name="year">
                        <?php
                        $year = date("Y");
                        for($i=0; $i<10; $i++) {
                            echo '<option value="'.$year.'">'.$year.'</option>';
                            $year--;
                        }
                        ?>
                    </select>
                    <select id="month" name="month">
                        <?php
                        $month = 1;
                        for($i=0; $i<12; $i++) {
                            echo '<option value="'.$month.'">'.$month.'</option>';
                            $month++;
                        }
                        ?>
                    </select>
                    <select id="days" name="days">
                        <?php
                        $days = 1;
                        for($i=0; $i<31; $i++) {
                            echo '<option value="'.$days.'">'.$days.'</option>';
                            $days++;
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="input">
                <span><?php echo $arr_language[3]; ?></span>
                <input type="text" id="data_vector_type" name="data_vector_type" value="">
            </div>
            <div class="input">
                <span><?php echo $arr_language[4]; ?></span>
                <input type="text" id="data_concentration" name="data_concentration" value="">
            </div>
            <div class="input">
                <span><?php echo $arr_language[5]; ?></span>
                <input type="text" id="data_etc" name="data_etc" value="">
            </div>
            <a href="#" onClick="javascript: closeViewBoard(0);"><div class="btn-cancle"><?php echo $arr_language[6]; ?></div></a>
            <button type="submit" class="btn-create"><?php echo $arr_language[7]; ?></button>
        </form>
    </div>

    <!-- 등록된 데이터 정보 뷰어 -->
    <div class="create-table-form" id="data-view">
        <form action="ModifyDataProc.php" method="post" class="data-form">
            <a href="#" id="delete_data_link" onClick=""><i class="material-icons delete-data">delete_forever</i></a>

            <input type="hidden" id="view_table_idx" name="table_idx" value="">
            <input type="hidden" id="view_data_idx" name="data_idx" value="">

            <div class="title"><b><?php echo $arr_language[8]; ?></b></div>
            <div class="input">
                <span><?php echo $arr_language[1]; ?></span>
                <!-- <input type="text" id="view_data_name" name="data_name" value="" required> -->
                <textarea id="view_data_name" name="data_name" require style="padding: 5px;"></textarea>
            </div>
            <div class="input">
                <span><?php echo $arr_language[2]; ?></span>
                <div class="select-box">
                    <select id="view_year" name="year" value="">
                        <?php
                        $year = date("Y");
                        for($i=0; $i<10; $i++) {
                            echo '<option value="'.$year.'">'.$year.'</option>';
                            $year--;
                        }
                        ?>
                    </select>
                    <select id="view_month" name="month" value="">
                        <?php
                        $month = 1;
                        for($i=0; $i<12; $i++) {
                            echo '<option value="'.$month.'">'.$month.'</option>';
                            $month++;
                        }
                        ?>
                    </select>
                    <select id="view_days" name="days" value="">
                        <?php
                        $days = 1;
                        for($i=0; $i<31; $i++) {
                            echo '<option value="'.$days.'">'.$days.'</option>';
                            $days++;
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="input">
                <span><?php echo $arr_language[3]; ?></span>
                <input type="text" id="view_data_vector_type" name="data_vector_type" value="">
            </div>
            <div class="input">
                <span><?php echo $arr_language[4]; ?></span>
                <input type="text" id="view_data_concentration" name="data_concentration" value="">
            </div>
            <div class="input">
                <span><?php echo $arr_language[5]; ?></span>
                <input type="text" id="view_data_etc" name="data_etc" value="">
            </div>
            <a href="#" onClick="javascript: closeViewBoard(1);"><div class="btn-cancle"><?php echo $arr_language[6]; ?></div></a>
            <button type="submit" class="btn-create"><?php echo $arr_language[9]; ?></button>
        </form>
    </div>

    <!-- 검색 Modal -->
    <?php
        // language = 1은 영어, language = 0은 한국어
        if($language == 1) $arr_language = array("Search", "New Database", "Title", "Description", "Cancle", "Submit", "Database Management", "Submit", "Search", "Search Area", "Data", "Search", "All", "Database Name", "Data Name", "Date", "Vector Type", "Concentration", "Etc", "Location");
        else $arr_language = array("검색결과", "데이터베이스 등록", "데이터베이스 이름", "데이터베이스 세부설명", "취소", "등록", "데이터베이스 관리", "수정", "검색", "검색범위", "검색어", "검색", "전체검색", "데이터베이스 이름", "이름", "생산일자", "벡터 종류", "농도", "기타", "위치");
    ?>

    <!-- 검색 Modal -->
    <div class="create-table-form" id="search">
        <form action="Search.php" method="post" class="form">
            <div class="title"><b><?php echo $arr_language[8]; ?></b></div>
            <div class="input">
                <span><?php echo $arr_language[9]; ?></span>
                <select name="search_area" style="width: 100%; margin: 5px 0 5px 0;">
                    <?php
                    $get_table_information_query = sprintf('SELECT * FROM tbl_table WHERE id = "%s";', $user_id);
                    $result = $conn -> query($get_table_information_query);
                    if(!$result) { 
                        echo '<script>alert("검색을 위한 데이터베이스를 불러오는데 실패하였습니다.");</script>';
                        exit();

                    }else {
                        echo '<option value="-1">'.$arr_language[12].'</option>';
                        while($row = $result -> fetch_assoc()) {
                            $table_idx = $row['table_idx'];
                            $table_name = $row['table_name'];

                            echo '<option value="'.$table_idx.'">'.$table_name.'</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="input">
                <span><?php echo $arr_language[10]; ?></span>
                <input type="text" name="search" value="" maxlength="50" required>
            </div>
            <a href="#" onClick="javascript: closeBoard(3);"><div class="btn-cancle"><?php echo $arr_language[4]; ?></div></a>
            <button type="submit" class="btn-create"><?php echo $arr_language[11]; ?></button>
        </form>
    </div>

</body>
</html>
<?php
include_once 'Header.php';

session_start();
if(!isset($_SESSION['user_id'])) {
    echo '<script>alert("로그인된 정보를 찾을 수 없습니다.");</script>';
    echo "<meta http-equiv='refresh' content='0;url=Login.php'>";
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
        <span class="title"><img src="data/img/smdb-logo.png" width="auto" height="30px"></span>
        <a href="Database.php"><i class="material-icons mobile-menu-icon" style="right: 65px;">home</i></a>
        <a href="#" onClick="javascript: openBoard(3);"><i class="material-icons mobile-menu-icon">search</i></a>
    </div>
    
    <div class="database-screen">
        <?php
        // language = 1은 영어, language = 0은 한국어
        if($language == 1) $arr_language = array("Search", "New Database", "Title", "Description", "Cancle", "Submit", "Database Management", "Submit", "Search", "Search Area", "Data", "Search", "All", "Database Name", "Data Name", "Date", "Vector Type", "Concentration", "Etc", "Location");
        else $arr_language = array("검색결과", "데이터베이스 등록", "데이터베이스 이름", "데이터베이스 세부설명", "취소", "등록", "데이터베이스 관리", "수정", "검색", "검색범위", "검색어", "검색", "전체검색", "데이터베이스 이름", "이름", "생산일자", "벡터 종류", "농도", "기타", "위치");
        ?>

        <div class="tab" style="border: none;">
            <div class="tab-title"><b><?php echo $arr_language[0]; ?></b></div>
            <?php
            // 전체 검색 쿼리문
            $search_area = strip_tags(stripslashes($_POST['search_area']));
            $search = strip_tags(stripslashes($_POST['search']));

            if($_POST['search_area'] < 0) {
                $search_query = sprintf('SELECT t.table_name, d.data_name, d.data_date, d.data_vector_type, 
                                        d.data_concentration, d.data_etc, d.data_row, d.data_col
                                        FROM tbl_table t, tbl_data d
                                        WHERE t.id = "%s" AND t.table_idx = d.table_idx AND d.data_name LIKE "%%%s%%";',
                                        $user_id,
                                        mysqli_real_escape_string($conn, $search)
                                        );

            // 특정 데이터베이스 검색 쿼리문
            } else if($_POST['search_area'] > 0) {
                $search_query = sprintf('SELECT t.table_name, d.data_name, d.data_date, d.data_vector_type,
                                        d.data_concentration, d.data_etc, d.data_row, d.data_col
                                        FROM tbl_table t, tbl_data d
                                        WHERE t.id = "%s"
                                        AND t.table_idx = %d AND d.table_idx = t.table_idx AND d.data_name LIKE "%%%s%%";',
                                        $user_id,
                                        mysqli_real_escape_string($conn, $search_area),
                                        mysqli_real_escape_string($conn, $search)
                                        );
            }

            $result = $conn -> query($search_query);
            if(!$result) { 
                echo '<script>alert("검색결과를 불러오는데 실패하였습니다.");</script>';
                echo '<meta http-equiv="refresh" content="0;url=Database.php">';
                exit();
            }
            else {
                while($row = $result -> fetch_assoc()) {
                    $arr_alpha = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
                    $table_name = $row['table_name'];
                    $data_name = $row['data_name'];
                    $data_date = $row['data_date'];
                    $data_vector_type = $row['data_vector_type'];
                    $data_concentration = $row['data_concentration'];
                    $data_etc = $row['data_etc'];
                    $data_row = $row['data_row'];
                    $data_col = $row['data_col'];

                    echo '
                    <div class="search-tab">
                        <table style="font-size: .8rem;">
                        <tbody>
                    ';
                    if($table_name != NULL)
                    echo '
                            <tr>
                                <td class="search-first-td-style" style="width: 30%;">'.$arr_language[13].'</td>
                                <td class="search-data">'.$table_name.'</td>
                            </tr>
                    ';
                    if($data_name != NULL)
                    echo '
                            <tr>
                                <td class="search-first-td-style">'.$arr_language[14].'</td>
                                <td class="search-data">'.str_replace("<br>", "&nbsp;", $data_name).'</td>
                            </tr>
                    ';
                    if($data_date != NULL)
                    echo '
                            <tr>
                                <td class="search-first-td-style">'.$arr_language[15].'</td>
                                <td class="search-data">'.$data_date.'</td>
                            </tr>
                    ';
                    if($data_vector_type != NULL)
                    echo '
                            <tr>
                                <td class="search-first-td-style">'.$arr_language[16].'</td>
                                <td class="search-data">'.$data_vector_type.'</td>
                            </tr>
                    ';
                    if($data_concentration != NULL)
                    echo '
                            <tr>
                                <td class="search-first-td-style">'.$arr_language[17].'</td>
                                <td class="search-data">'.$data_concentration.'</td>
                            </tr>
                    ';
                    if($data_etc != NULL)
                    echo '
                            <tr>
                                <td class="search-first-td-style">'.$arr_language[18].'</td>
                                <td class="search-data">'.$data_etc.'</td>
                            </tr>
                    ';
                    if($data_col != NULL && $data_col != NULL)
                    echo ' 
                            <tr>
                                <td class="search-first-td-style">'.$arr_language[19].'</td>
                                <td class="search-data">'.$arr_alpha[$data_col].'-'.($data_row+1).'</td>
                            </tr>
                    ';
                    echo '
                        </tbody>
                        </table>
                    </div>
                    ';
                }
            }
            ?>
        </div>
    </div>

    <!-- Modal 소스 -->
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
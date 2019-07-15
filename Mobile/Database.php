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
    $get_user_setting_query = sprintf('SELECT * FROM user_setting WHERE user_id = "%s";',
                                    mysqli_real_escape_string($conn, $_SESSION['user_id']));

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
        <a href="#" onClick="javascript: openBoard(3);"><i class="material-icons mobile-menu-icon">search</i></a>
    </div>
    
    <div class="database-screen">
        <div class="tab">
            <div class="user-information">
                <?php
                // 로그인된 회원 정보를 가져오는 쿼리문
                $get_user_info = sprintf('SELECT user_name, user_team
                                        FROM tbl_user
                                        WHERE user_id="%s";',
                                        mysqli_real_escape_string($conn, $user_id)
                                        );
                $result = $conn -> query($get_user_info);
                if(!$result) { echo '<script>alert("로그인된 회원 정보를 가져오는데 실패하였습니다.");</script>'; }
                else {
                    $row = $result -> fetch_assoc();
                    $user_name = $row['user_name'];
                    $user_team = $row['user_team'];
                }
                ?>

                <!-- 로그인된 정보를 보여주는 부분 -->
                <!-- 현재 프로필 이미지 업로드는 구현되지 않아 이미지를 고정값으로 설정 -->
                <div class="picture" style="background-image: url('data/img/default_profile_img.png');"></div>
                <div class="name"><b><?php echo $user_name; ?></b></div>
                <div class="discript"><?php echo $user_team; ?></div>
            </div>
        </div>
        
        <?php
        // 로그인 후 만들어진 데이터베이스 테이블이 없을 경우 테이블 생성을 유도하기 위해 테이블 개수 가져오는 쿼리문
        $is_chk_table_query = sprintf('SELECT count(table_idx)
                                    FROM tbl_table
                                    WHERE id="%s";',
                                    mysqli_real_escape_string($conn, $user_id)
                                    );
        $result = $conn -> query($is_chk_table_query);
        $row = $result -> fetch_assoc();
        $table_count = $row['count(table_idx)'];
        ?>
        
        <?php
        // language = 1은 영어, language = 0은 한국어
        if($language == 1) $arr_language = array("Database List", "New Database", "Title", "Description", "Cancle", "Submit", "Database Management", "Submit", "Search", "Search Area", "Data", "Search", "All");
        else $arr_language = array("데이터베이스", "데이터베이스 등록", "데이터베이스 이름", "데이터베이스 세부설명", "취소", "등록", "데이터베이스 관리", "수정", "검색", "검색범위", "검색어", "검색", "전체검색");
        ?>

        <div class="tab" <?php ($table_count == 0) ? $style = '' : $style = 'style="border: none;"'; echo $style; ?>>
            <div class="tab-title"><b><?php echo $arr_language[0]; ?></b></div>
            <?php

            if($table_count == 0) {
                echo '<a href="#" onClick="javascript: openBoard(0); return false;"><i class="material-icons create-icon">add_circle</i></a>';
            
            }else {
                $get_database_query = sprintf('SELECT * FROM tbl_table WHERE id = "%s"',
                                            mysqli_real_escape_string($conn, $user_id)
                                            );
                $result = $conn -> query($get_database_query);
                
                while($row = $result -> fetch_assoc()) {
                    $table_idx = $row['table_idx'];
                    $table_name = $row['table_name'];
                    $table_desc = $row['table_disc'];

                    $image = "background-image: url('data/img/default_table_img.png');";
                    echo '
                    <a href="TableForm.php?idx='.$table_idx.'">
                        <div class="tab">
                            <div id="'.$table_idx.'" class="user-information" data-table-idx="'.$table_idx.'" data-table-name="'.$table_name.'" data-table-desc="'.$table_desc.'">
                                <div class="picture" style="'.$image.'"></div>
                                <div class="name"><b>'.$table_name.'</b></div>
                                <div class="discript">'.$table_desc.'</div>
                                <a href="#" onClick="javascript: tableSetting('.$table_idx.'); return false;"><i class="material-icons table-setting">create</i></a>
                                <a href="#" onClick="javascript: tableDelete('.$table_idx.'); return false;"><i class="material-icons table-delete">delete_forever</i></a>
                            </div>
                        </div>
                    </a>
                    ';
                }
            }
            ?>
            <div class="box-picture" style="background-image: url('data/img/default_profile_img.png');"></div>
        </div>
    </div>

    <div class="footer-menu">
        <a href="#" onClick="javascript: openBoard(0); return false;"><div class="create-menu"><i class="material-icons">add</i></div></a>
        <a href="#" onClick="javascript: openBoard(2); return false;"><div class="setting-menu"><i class="material-icons">settings</i></div></a>
    </div>

    <!-- Modal 소스 -->
    <!-- 데이터베이스 등록 Modal -->
    <div class="create-table-form" id="create">
        <form action="CreateProc.php" method="post" class="form">
            <div class="title"><b><?php echo $arr_language[1]; ?></b></div>
            <div class="input">
                <span><?php echo $arr_language[2]; ?></span>
                <input type="text" name="db_name" value="" maxlength="20" required>
            </div>
            <div class="input">
                <span><?php echo $arr_language[3]; ?></span>
                <input type="text" name="db_disc" value="" maxlength="50" required>
            </div>
            <a href="#" onClick="javascript: closeBoard(0); return false;"><div class="btn-cancle"><?php echo $arr_language[4]; ?></div></a>
            <button type="submit" class="btn-create"><?php echo $arr_language[5]; ?></button>
        </form>
    </div>

    <!-- 데이터베이스 관리 Modal -->
    <div class="create-table-form" id="modify">
        <form action="ModifyProc.php" method="post" class="form">
            <input type="hidden" id="view_db_idx" name="db_idx">
            <div class="title"><b><?php echo $arr_language[6]; ?></b></div>
            <div class="input">
                <span><?php echo $arr_language[2]; ?></span>
                <input type="text" id="view_db_name" name="db_name" value="" maxlength="20" required>
            </div>
            <div class="input">
                <span><?php echo $arr_language[3]; ?></span>
                <input type="text" id="view_db_disc" name="db_disc" value="" maxlength="50" required>
            </div>
            <a href="#" onClick="javascript: closeBoard(1); return false;"><div class="btn-cancle"><?php echo $arr_language[4]; ?></div></a>
            <button type="submit" class="btn-create"><?php echo $arr_language[7]; ?></button>
        </form>
    </div>

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
            <a href="#" onClick="javascript: closeBoard(3); return false;"><div class="btn-cancle"><?php echo $arr_language[4]; ?></div></a>
            <button type="submit" class="btn-create"><?php echo $arr_language[11]; ?></button>
        </form>
    </div>

    <!-- 환경설정 Modal -->
    <div class="create-table-form" id="setting">
        <?php
        if($language == 1) $arr_setting_language = array("Preferences", "Language", "한국어", "English", "Theme", "Blue", "Pastel", "Cancle", "Submit");
        else $arr_setting_language = array("환경설정", "언어", "한국어", "English", "디자인", "기본형", "파스텔", "취소", "적용");
        ?>
        <form action="SettingProc.php" method="post" class="form">
            <div class="title"><b><?php echo $arr_setting_language[0]; ?></b></div>
            <div class="input">
                <span><?php echo $arr_setting_language[1]; ?></span>
                <select name="language" style="width: 97%">
                <?php 
                if($language == 0) {
                    echo '<option value="0">'.$arr_setting_language[2].'</option>
                    <option value="1">'.$arr_setting_language[3].'</option>';
                }else {
                    echo '<option value="1">'.$arr_setting_language[3].'</option>
                    <option value="0">'.$arr_setting_language[2].'</option>';
                }
                ?>
                </select>
            </div>
            <div class="input">
                <span><?php echo $arr_setting_language[4]; ?></span>
                <select name="theme" style="width: 97%">
                <?php
                if($theme == 0) {
                    echo '<option value="0">'.$arr_setting_language[5].'</option>
                    <option value="1">'.$arr_setting_language[6].'</option>';
                }else {
                    echo '<option value="1">'.$arr_setting_language[6].'</option>
                    <option value="0">'.$arr_setting_language[5].'</option>';
                }
                ?>
                </select>
            </div>
            <a href="#" onClick="javascript: closeBoard(2); return false;"><div class="btn-cancle"><?php echo $arr_setting_language[7]; ?></div></a>
            <button type="submit" class="btn-create"><?php echo $arr_setting_language[8]; ?></button>
        </form>
    </div>

</body>
</html>
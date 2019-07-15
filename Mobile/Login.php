<?php
include_once 'Header.php';
session_start();
/*
if(isset($_SESSION['user_id'])) {
    echo "<meta http-equiv='refresh' content='0;url=Database.php'>";
    exit();
}
*/
?>
    <link rel="stylesheet" href="data/css/login.css?v=2">

    <script>
        function openSignup() {
            document.getElementById("signup-form").style.display = "block";
        }

        function closeSignup() {
            document.getElementById("signup-form").style.display = "none";
        }
    </script>
</head>
<body>
    <div class="login-frame">
        <img src="data/img/smdb-opensource-logo.png" class="smdb-logo">
        <form action="LoginProc.php" method="POST">
            <input type="text" name="user_id">
            <input type="password" name="user_password">
            <button type="submit" class="btn-login">로그인</button>
            <button class="btn-join" onclick="javascript: openSignup(); return false;">회원가입</button>
        </form>
    </div>

    <!-- 회원가입 Modal -->
    <div class="signup-table-form" id="signup-form">
        <form action="JoinProc.php" method="post" class="data-form">
            <div class="title"><b>회원가입</b></div>
            <div class="input">
                <span>아이디</span>
                <input type="text" id="user_id" name="user_id" value="">
            </div>
            <div class="input">
                <span>비밀번호</span>
                <input type="password" id="user_password" name="user_password" value="">
            </div>
            <div class="input">
                <span>이름</span>
                <input type="text" id="user_name" name="user_name" value="">
            </div>
            <div class="input">
                <span>전공 또는 조직명</span>
                <input type="text" id="user_team" name="user_team" value="">
            </div>
            <a href="#" onclick="javascript: closeSignup(); return false;"><div class="btn-cancle">취소</div></a>
            <button type="submit" class="btn-create">가입</button>

            <div class="input">
                <span>가입을 위해서는 우리의 <b>개인정보처리방침</b>과 <b>이용약관</b>에 동의를 해주셔야 합니다. 두 가지를 모두 읽어보시고 동의하신다면 가입하실 수 있습니다.</span>
                <div class="agree" style="margin-top: 10px;"><input type="checkbox" id="chkinfo" name="chkinfo" value="1"><span>개인정보처리방침에 동의합니다.</span></div>
                <div class="agree"><input type="checkbox" id="chkterm" name="chkterm" value="1"><span>이용약관에 동의합니다.</span></div>
            </div>
        </form>
    </div>

</body>
</html>
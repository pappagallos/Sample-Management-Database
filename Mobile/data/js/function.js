// 자바스크립트 ES6 기준
// 개발자, 대진대학교 컴퓨터공학과 이우진

function openBoard(idx) {
    switch(idx) {
        case 0: let create = document.getElementById("create"); create.style.display = "block"; break;
        case 1: let modify = document.getElementById("modify"); modify.style.display = "block"; break;
        case 2: let setting = document.getElementById("setting"); setting.style.display = "block"; break;
        default: let search = document.getElementById("search"); search.style.display = "block"; break;
    }
}

function closeBoard(idx) {
    switch(idx) {
        case 0: let create = document.getElementById("create"); create.style.display = "none"; break;
        case 1: let modify = document.getElementById("modify"); modify.style.display = "none"; break;
        case 2: let setting = document.getElementById("setting"); setting.style.display = "none"; break;
        default: let search = document.getElementById("search"); search.style.display = "none"; break;
    }
}

function openViewBoard(record) {
    // 사용해야할 데이터 입력폼 구역 아이디 불러오기
    let str = document.getElementById("view_data_name");
    let view = document.getElementById("data-view");
    let data = document.getElementById(record);
    view.style.display = "block";
    
    // DNA 이름을 데이터 속성에 있는 값을 가져와 <BR> 태그 변환 작업
    data_name = data.dataset.name;
    str.value = data_name.replace(/<br>/gi, "\r\n");

    // html 데이터 속성 값들 가져와 해당하는 아이디 value로 초기화
    document.getElementById("view_table_idx").value = data.dataset.tableIdx;
    document.getElementById("view_year").value = Number(data.dataset.year);
    document.getElementById("view_month").value = Number(data.dataset.month);
    document.getElementById("view_days").value = Number(data.dataset.day);
    document.getElementById("view_data_vector_type").value = data.dataset.vectorType;
    document.getElementById("view_data_concentration").value = data.dataset.concentration;
    document.getElementById("view_data_etc").value = data.dataset.etc;
    document.getElementById("view_data_idx").value = data.dataset.idx;
    document.getElementById("delete_data_link").setAttribute("onClick", "javascript: deleteData(" + data.dataset.tableIdx + ", " + data.dataset.idx + ");");
}

function closeViewBoard(idx) {
    switch(idx) {
        case 0: let write = document.getElementById("data-write"); write.style.display = "none"; break;
        default: let view = document.getElementById("data-view"); view.style.display = "none"; break;
    }
}

function writeBoard(table_idx, data_row, data_col) {
    let write = document.getElementById("data-write");

    document.getElementById("table_idx").value = table_idx;
    document.getElementById("data_row").value = data_row;
    document.getElementById("data_col").value = data_col;

    write.style.display = "block";
}

function tableDelete(table_idx) {
    chk = new Boolean(false);
    chk = confirm("정말 데이터베이스를 삭제하시겠습니까? 삭제 후에는 하위 데이터들도 삭제되며 복구가 불가능합니다.");
    if(chk == true) {
        window.location = "DeleteProc.php?idx="+ table_idx;
    }else {
        alert("삭제를 취소하셨으며 데이터베이스는 삭제되지 않았습니다.");
    }
}

function tableSetting(table_idx) {
    let modify = document.getElementById("modify");
    let data = document.getElementById(table_idx);
    
    document.getElementById("view_db_idx").value = data.dataset.tableIdx;
    document.getElementById("view_db_name").value = data.dataset.tableName;
    document.getElementById("view_db_disc").value = data.dataset.tableDesc;

    modify.style.display = "block";
}

function userSetting(language, theme) {
    let setting = document.getElementById("setting");
    
    document.getElementById("language").value = (language == 0) ? "한국어" : "영어";
    document.getElementById("theme").value = (theme == 0) ? "기본형" : "파스텔";

    setting.style.display = "block";
}

function deleteData(table_idx, data_idx) {
    chk = new Boolean(false);
    chk = confirm("정말 삭제하시겠습니까? 삭제 후에는 해당 데이터 복구가 불가능합니다.");
    if(chk == true) {
        window.location = "DeleteDataProc.php?idx="+ table_idx +"&data_idx=" + data_idx;
    }else {
        alert("삭제를 취소하셨으며 데이터는 삭제되지 않았습니다.");
    }
}
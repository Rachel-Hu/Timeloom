function rankUp(id, listNumber) {
    var task = id;
    var list = listNumber;
    $.ajax({
        url: "src/change_rank.php",
        type: "POST",
        data: {
            taskId: task,
            listId: list,
            addDisplayScore: 1
        },
        dataType: "json",
    });
    location.reload();
}

function rankDown(id, listNumber) {
    var task = id;
    var list = listNumber;
    $.ajax({
        url: "src/change_rank.php",
        type: "POST",
        data: {
            taskId: task,
            listId: list,
            addDisplayScore: -1
        },
        dataType: "json"
    });
    location.reload();
}

function switchList(list) {
    var listId = list;
    $.ajax({
        url: "src/switch_list.php",
        type: "POST",
        data: {
            list: listId,
        },
        dataType: "json"
    });
    location.reload();
}

$(document).ready(function() {
    $(".add-task-btn").click(function () {
        // console.log($(this).attr('id'));
        var id = String($(this).attr('id'));
        var prev_taskid = id.split('-')[6];
        // console.log(prev_taskid);
        var taskid = id.split('-')[3];
        // console.log(taskid);
        // console.log($(".add-submit-btn"));
        $(".add-submit-btn").val(prev_taskid + "and" + taskid);
    });
});
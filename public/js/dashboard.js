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

function selectTask(id, listNumber) {
    if($('#check-' + id)[0].checked) {
        $('.check-box').attr("disabled", true);
        $('#check-' + id).attr("disabled", false);
        $('#rank-up').attr("onclick", "rankUp(" + id + ", " + listNumber + ");");
        $('#rank-down').attr("onclick", "rankDown(" + id + ", " + listNumber + ");");
        $('#finish-btn').attr("href", "src/move_task.php?id=" + id + "&list=3");
        $('#postpone-btn').attr("href", "src/move_task.php?id=" + id + "&list=1");
        $('#resume-btn').attr("href", "src/move_task.php?id=" + id + "&list=2");
    }
    else {
        $('.check-box').attr("disabled", false);
    }
    $('.action-btns').toggle("slow");
}

var propertyNum = 0;

$(document).ready(function() {
    $('.add-properties').click(function(){
        var col = $('.dynamic-element').first().clone();
        col[0].childNodes[1].firstElementChild.lastElementChild.setAttribute('name', 'property-' + propertyNum);
        col[0].childNodes[1].firstElementChild.nextElementSibling.lastElementChild.setAttribute('name', 'property-value-' + + propertyNum);
        console.log(col[0].childNodes[1]);
        col.appendTo('.dynamic-properties').show();
        propertyNum++;
        attachDelete();
    });
});

function attachDelete(){
    $('.delete-properties').off();
    $('.delete-properties').click(function(){
      $(this).closest('.form-group').remove();
      propertyNum--;
    });
}
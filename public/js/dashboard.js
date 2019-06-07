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
        $('#edit-btn').attr("onclick", "editTask(" + id + ");");
        $('#finish-btn').attr("href", "src/move_task.php?id=" + id + "&list=3");
        $('#postpone-btn').attr("href", "src/move_task.php?id=" + id + "&list=1");
        $('#resume-btn').attr("href", "src/move_task.php?id=" + id + "&list=2");
    }
    else {
        $('.check-box').attr("disabled", false);
    }
    $('.action-btns').toggle("slow");
}

var propertyNumEdit = 0;
var presentOldProperties = false;

function editTask(id) {
    var properties = $('#properties-' + id).val();
    var task = $('#properties-' + id).attr('name');
    $('#task-label-edit').attr('value', task);
    if(properties != '{}' && !presentOldProperties) {
        properties = JSON.parse(properties);
        // First, parse fixed properties
        $('#due-date-value-edit').attr('value', properties.fixed.due_date);
        $('#priority-value-edit')[0].value = properties.fixed.priority;
        properties.user.forEach(function(element) {
            console.log(element);
            var col = $('.dynamic-element-edit').first().clone();
            var property = col[0].childNodes[1].firstElementChild.lastElementChild;
            property.setAttribute('name', 'property-' + propertyNumEdit);
            property.setAttribute('value', element.name);
            property.setAttribute('id', 'property-' + propertyNumEdit);
            var value = col[0].childNodes[1].firstElementChild.nextElementSibling.lastElementChild;
            value.setAttribute('name', 'property-value-' + + propertyNumEdit);
            value.setAttribute('value', element.value);
            value.setAttribute('type', element.type);
            value.setAttribute('id', 'property-value-' + + propertyNumEdit);
            col.appendTo('.dynamic-properties-edit').show();
            propertyNumEdit++;
            attachDeleteEdit();
        });
    }
    presentOldProperties = true;
    $(".edit-submit-btn").val(id);
}

$(document).ready(function() {
    $('.add-properties-edit').click(function(){
        var col = $('.dynamic-element').first().clone();
        var property = col[0].childNodes[1].firstElementChild.lastElementChild;
        property.setAttribute('name', 'property-' + propertyNumEdit);
        property.setAttribute('id', 'property-' + propertyNumEdit);
        var type = col[0].childNodes[1].firstElementChild.nextElementSibling.lastElementChild;
        type.setAttribute('name', 'property-type-' + + propertyNumEdit);
        type.setAttribute('id', 'property-type-' + + propertyNumEdit);
        var value = col[0].childNodes[3].firstElementChild.lastElementChild;
        value.setAttribute('name', 'property-value-' + + propertyNumEdit);
        value.setAttribute('id', 'property-value-' + + propertyNumEdit);
        // console.log(col[0].childNodes[1]);
        col.appendTo('.dynamic-properties-edit').show();
        propertyNum++;
        attachDelete();
    });
});

var propertyNum = 0;

$(document).ready(function() {
    $('.add-properties').click(function(){
        var col = $('.dynamic-element').first().clone();
        var property = col[0].childNodes[1].firstElementChild.lastElementChild;
        property.setAttribute('name', 'property-' + propertyNum);
        property.setAttribute('id', 'property-' + propertyNum);
        var type = col[0].childNodes[1].firstElementChild.nextElementSibling.lastElementChild;
        type.setAttribute('name', 'property-type-' + + propertyNum);
        type.setAttribute('id', 'property-type-' + + propertyNum);
        var value = col[0].childNodes[3].firstElementChild.lastElementChild;
        value.setAttribute('name', 'property-value-' + + propertyNum);
        value.setAttribute('id', 'property-value-' + + propertyNum);
        // console.log(col[0].childNodes[1]);
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

function attachDeleteEdit(){
    $('.delete-properties').off();
    $('.delete-properties').click(function(){
        $(this).closest('.form-group').remove();
        propertyNumEdit--;
    });
}


$(document).on("change", "select", function() {
    var type = this.value;
    var id = this.id.split('-')[2];
    $('#property-value-' + id).attr('type', type);
})


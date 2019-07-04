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

var tasks = new Array();

function rankUp(listNumber) {
    var list = listNumber;
    $.ajax({
        url: "src/change_rank.php",
        type: "POST",
        data: {
            taskIds: tasks,
            listId: list,
            addDisplayScore: 1
        },
        dataType: "json",
    });
    location.reload();
}

function rankDown(listNumber) {
    var list = listNumber;
    $.ajax({
        url: "src/change_rank.php",
        type: "POST",
        data: {
            taskIds: tasks,
            listId: list,
            addDisplayScore: -1
        },
        dataType: "json"
    });
    location.reload();
}

function selectTask(id, listNumber) {
    if($('#check-' + id)[0].checked) {
        tasks.push(id);
        $('.action-btns').show();
    }
    else {
        tasks.splice(tasks.indexOf(id), 1);
        // console.log(tasks);
        if(tasks.length == 0) $('.action-btns').hide();
    }
    var ids = '';
    tasks.forEach(function(id) {
        ids = ids + id + '_';
    });
    ids = ids.substring(0, ids.length - 1);
    // console.log(ids);
    $('#rank-up').attr("onclick", "rankUp(" + listNumber + ");");
    $('#rank-down').attr("onclick", "rankDown(" + listNumber + ");");
    $('#finish-btn').attr("href", "src/move_task.php?id=" + ids + "&list=3");
    $('#postpone-btn').attr("href", "src/move_task.php?id=" + ids + "&list=1");
    $('#resume-btn').attr("href", "src/move_task.php?id=" + ids + "&list=2");
    $('#expire-btn').attr("href", "src/move_task.php?id=" + ids + "&list=4");   
    $('#delete-btn').attr("href", "src/delete_task.php?id=" + ids);   
}

var propertyNumEdit = 0;

function editTask(id) {
    var properties = $('#properties-' + id).val();
    var task = $('#properties-' + id).attr('name');
    $('#task-label-edit').attr('value', task);
    if(properties != '{}') {
        properties = JSON.parse(properties);
        // First, parse fixed properties
        properties.forEach(function(element) {
            if(element.name == 'due_date') {
                $('#due-date-value-edit').attr('value', element.value);
            }
            else if(element.name == 'priority') {
                $('#priority-value-edit')[0].value = element.value;
            }
            else {
                var col = $('.dynamic-element-edit').first().clone();
                var property = col[0].childNodes[1].firstElementChild.lastElementChild.firstElementChild;
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
            }
        });
    }
    $(".edit-submit-btn").val(id);
}

// Reset edit form when closing 
function resetEditForm() {
    $('#due-date-value-edit').attr('value', '');
    $('#priority-value-edit')[0].value ='Choose...';
    $('.dynamic-properties-edit').html('');
}

function autoFillProperty() {
    // Set search input value on click of result item
    $(document).on("click", ".result div", function(){
        $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
        var type = $(this).next().val();
        $(this).parents(".search-box").parent().next().remove();
        $(this).parents(".search-box").parent().removeClass('col-md-6').addClass('col-md-5');
        var valueBoxes = $(this).parents(".search-box").parent().parent().next().children();
        var valueBox = valueBoxes.first();
        valueBox.removeClass('col-md-10').addClass('col-md-5');
        valueBox.find('input[type="text"]').attr('type', type);
        $(this).parents(".search-box").parent().parent().append(valueBoxes);
        $(this).parent(".result").empty();
    });
}

$(document).ready(function() {
    $('.add-properties-edit').click(function(){
        var col = $('.dynamic-element').first().clone();
        var property = col[0].childNodes[1].firstElementChild.lastElementChild.firstElementChild;
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
        $('.search-box input[type="text"]').on("keyup input", function(){
            /* Get input value on change */
            var inputVal = $(this).val();
            var resultDropdown = $(this).siblings(".result");
            if(inputVal.length){
                $.get("src/search_properties.php", {term: inputVal}).done(function(data){
                    // Display the returned data in browser
                    resultDropdown.html(data);
                });
            } else{
                resultDropdown.empty();
            }
        });
        
        autoFillProperty();
    });
});

var propertyNum = 0;

$(document).ready(function() {
    $('.add-properties').click(function(){
        var col = $('.dynamic-element').first().clone();
        var property = col[0].childNodes[1].firstElementChild.lastElementChild.firstElementChild;
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
        $('.search-box input[type="text"]').on("keyup input", function(){
            /* Get input value on change */
            var inputVal = $(this).val();
            var resultDropdown = $(this).siblings(".result");
            if(inputVal.length){
                $.get("src/search_properties.php", {term: inputVal}).done(function(data){
                    // Display the returned data in browser
                    // console.log(data);
                    resultDropdown.html(data);
                });
            } else{
                resultDropdown.empty();
            }
        });
        
        autoFillProperty();
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

// Number of predefined properties
const PREDEFINED = 9;

// Switch the list displayed to the user
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

// When the add button is clicked, add the id of the task it belongs to 
// and the previous task's id to the button value
$(document).ready(function() {
    $(".add-task-btn").click(function () {
        var id = String($(this).attr('id'));
        var prev_taskid = id.split('-')[6];
        var taskid = id.split('-')[3];
        $(".add-submit-btn").val(prev_taskid + "and" + taskid);
    });
});

var tasks = new Array();

// Move selected tasks up
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

// Move selected tasks down
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

/* When a task is selected, add them to the tasks array and modify the function
 * buttons with the id of selected tasks. */
function selectTask(id, listNumber) {
    if($('#check-' + id)[0].checked) {
        tasks.push(id);
        $('.action-btns').show();
    }
    else {
        tasks.splice(tasks.indexOf(id), 1);
        if(tasks.length == 0) $('.action-btns').hide();
    }
    var ids = '';
    tasks.forEach(function(id) {
        ids = ids + id + '_';
    });
    ids = ids.substring(0, ids.length - 1);
    $('#rank-up').attr("onclick", "rankUp(" + listNumber + ");");
    $('#rank-down').attr("onclick", "rankDown(" + listNumber + ");");
    $('#finish-btn').attr("href", "src/move_task.php?id=" + ids + "&list=3");
    $('#postpone-btn').attr("href", "src/move_task.php?id=" + ids + "&list=1");
    $('#resume-btn').attr("href", "src/move_task.php?id=" + ids + "&list=2");
    $('#expire-btn').attr("href", "src/move_task.php?id=" + ids + "&list=4");   
    $('#delete-btn').attr("href", "src/delete_task.php?id=" + ids);   
}

var propertyNumEdit = PREDEFINED;

/* When the edit button is clicked, display the properties stored in the hidden
 * value of the task. */
function editTask(id) {
    console.log(id);
    var properties = $('#properties-' + id).val();
    var task = $('#properties-' + id).attr('name');
    $('#task-label-edit').attr('value', task);
    if(properties != '{}') {
        properties = JSON.parse(properties);
        // First, parse fixed properties
        properties.forEach(function(element) {
            if(!element.user_defined) {
                // Fill in the predefined properties
                var value = $("[id='" + element.name + "']").parent().next().next(); // Some ids might have blank space 
                if(element.name == 'Priority') {
                    value[0].firstElementChild.value = element.value;
                }
                else {
                    value[0].firstElementChild.setAttribute('value', element.value);
                }
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
    $('.dynamic-properties-edit').html('');
    var valuesToReset = $('.fixed-property-values-edit');
    for(var i = 0; i < valuesToReset.length; i++) {
        valuesToReset[i].firstElementChild.setAttribute('value', '');
    }
}

/* If a property is found in the database, autofill the type of the property in 
 * the input box's type. */
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
        // Change the type of the input box
        if(type == 'float') {
            valueBox.find('input[type="text"]').attr('type', 'number');
            valueBox.find('input[type="number"]').attr('step', 'any');
        }
        else if(type == 'string') {
            var id = valueBox.find('input[type="text"]')[0].id;
            var name = valueBox.find('input[type="text"]')[0].name;
            valueBox.find('input[type="text"]').replaceWith('<textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>');
            valueBox.find('textarea').attr('name', name);
            valueBox.find('textarea').attr('id', id);
        }
        else if(type == "string array") {
            valueBox.find('input[type="text"]').attr('type', 'text');
        }
        else valueBox.find('input[type="text"]').attr('type', type);
        var id = valueBox.find('input[type="text"]')[0].id;
        id = id.split('-')[2];
        console.log(id);
        valueBox.append('<input type="hidden" name="property-type-' + id + '" value="' + type + '">');
        valueBox.append('<input type="hidden" name="user-defined-' + id + '" value="true">');
        $(this).parents(".search-box").parent().parent().append(valueBoxes);
        $(this).parent(".result").empty();
    });
}

/* When the add property button of the edit form is clicked, add the input boxes
 * for it. */
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
        col.appendTo('.dynamic-properties-edit').show();
        propertyNum++;
        attachDelete();
        // Search the property in the database
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

var propertyNum = PREDEFINED;

/* When the add property button of the add form is clicked, add the input boxes
 * for it. */
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
        col.appendTo('.dynamic-properties').show();
        attachDelete();
        $('.search-box input[type="text"]').on("keyup input", function(){
            /* Get input value on change */
            var inputVal = $(this).val();
            var resultDropdown = $(this).siblings(".result");
            if(inputVal.length){
                $.get("src/search_properties.php", {term: inputVal}).done(function(data){
                    resultDropdown.html(data);
                });
            } else{
                resultDropdown.empty();
            }
        });
        autoFillProperty();
        propertyNum++;
    });
});

// Attach delete button to the properties in the add task form
function attachDelete(){
    $('.delete-properties').off();
    $('.delete-properties').click(function(){
        $(this).closest('.form-group').remove();
        propertyNum--;
    });
}

// Attach delete button to the properties in the edit task form
function attachDeleteEdit(){
    $('.delete-properties').off();
    $('.delete-properties').click(function(){
        $(this).closest('.form-group').remove();
        propertyNumEdit--;
    });
}

// Change the type of the input box when the type is selected by the user
$(document).on("change", "select", function() {
    var type = this.value;
    var id = this.id.split('-')[2];
    $('#property-value-' + id).attr('type', type);
})

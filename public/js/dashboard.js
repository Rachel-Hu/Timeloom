function rankUp(id) {
    var task = id;
    $.ajax({
        url: "src/change_rank.php",
        type: "POST",
        data: {
            taskId: task,
            addDisplayScore: 1
        },
        dataType: "json",
    });
    location.reload();
}

function rankDown(id) {
    var task = id;
    $.ajax({
        url: "src/change_rank.php",
        type: "POST",
        data: {
            taskId: task,
            addDisplayScore: -1
        },
        dataType: "json"
    });
    location.reload();
}


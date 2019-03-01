$("li").on("click", function(){
    $(this).toggleClass("completed");
});

$("span").on("click", function(event, id){
    event.stopPropagation();  
});



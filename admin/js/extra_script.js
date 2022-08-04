$ = jQuery;

$("select#bus_state").change(function(){
    var state_id = $("select#bus_state").val();
    $.ajax({
        type : "GET",
        url : "fetch_lga_by_state.php",
        data : "state_id="+state_id,
        dataType : "json",
        contentType : "application/x-json",
        success : function(data){
            if(data.status == "success"){
                $("select#bus_lga").empty();
                var options = '<option value="">--Local Gov\'t Area</option>'
                $.each(data.data, function(key, val){
                    options += '<option val="'+val.name+'">'+val.name+'</option>';
                })
                $("select#bus_lga").append(options);
            } else {
                $("select#bus_state").after('<div class="message">'+data.message+'</div>');
                $(".message").fadeOut(5000);
            }
            console.log(data);
        },
        error : function(data){
            var result = "oopsie"+data.status+" "+data.statusType+" "+data.responseText;
            $("select#bus_state").after('<div class="message">'+result+'</div>');
            $(".message").fadeOut(5000);
            console.log(data);
        }
    });
});

var category_list = $("div#category_list");
function check_business_categories(){
    var business_string = $("input#business_string").val();
    $.ajax({
        type: "GET",
        url: "fetch_business_categories.php",
        data: "business_string="+business_string,
        dataType: "json",
        contentType: "application/x-json",
        success: function(data){
            if(data.status == "success"){
                $("div#category_list").empty();
                //$("label#categ_label").html(data.message);
                $.each(data.data, function(key, val){
                    var output =    '<div class="text-success p-2" style="float: left !important" id="div_'+val.verify_string+'">';
                    output +=           val.category;
                    output +=           '<button id="category_'+val.verify_string+'" class="btn-close ml-4" data-bs-dismiss="alert" aria-label="Close"></button>';
                    output +=       '</div>';
                    
                    $("div#category_list").append(output);
                        
                    $("button#category_"+val.verify_string).click(function(){
                        var mresult = 'string='+val.verify_string;
                        $.ajax({
                            type : "GET",
                            url : "delete_business_category.php",
                            data : mresult,
                            dataType : "json",
                            contentType : "application/x-json",
                            success : function(data){
                                $("div#div_"+val.verify_string).fadeOut(1000);  
                            },
                            error : function(data){
                                var result = "oopsie "+data.status+" "+data.responseText;
                                console.log(result);
                            }
                        });
                        return false;
                    })
                });
            } else {
                $("div#category_list").html(data.message);
            }
        },
        error: function(data){
            var result = "oopsie "+data.responseText;
            console.log(result);
        }
    });
}

if(category_list){
    setInterval(check_business_categories, 1500);
}

$("input#bus_category").change(function(){
    var business_string = $("input#business_string").val();
    var category = $("input#bus_category").val();
    if(category != ""){
        var mresult = 'business_string='+business_string+'&category='+category;
        $.ajax({
            type : "GET",
            url : "add_business_category.php",
            data : mresult,
            dataType: "json",
            contentType: "application/x-json",
            success: function(data){
                if(data.status == "success"){
                    $("input#bus_category").after('<div class="output">Category Added Successfully</div>');    
                } else {
                    $("input#bus_category").after('<div class="output">'+data.message+'</div>');
                }
                $(".output").fadeOut(3000);
                $("input#bus_category").val("");
                return false;
            },
            error: function(data){
                var result = "oopsie "+data.status+" "+data.responseText;
                console.log(result);
                return false;
            }
        });
    }
    return false;
});

$('table#all_categories').DataTable();

$('table#subscription_table').DataTable( {
    "order": [[ 1, "desc" ]]
} );

$('table#businesses_list').DataTable();

$('table#reviews_table').DataTable({
    "order": [[ 0, "desc" ]]
});

$('table#billboard_table').DataTable({
    "order": [[ 0, "desc" ]]
});
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
if(category_list.length > 0){
    function check_business_categories(){
        $.ajax({
            type: "GET",
            url: "fetch_business_categories.php",
            data: "",
            dataType: "json",
            contentType: "application/x-json",
            success: function(data){
                if(data.status == "success"){
                    $("div#category_list").empty();
                    $("label#categ_label").html(data.message);
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
    
    
    setInterval(check_business_categories, 1500);   
}


$("input#bus_category").change(function(){
    var category = $("input#bus_category").val();
    if(category != ""){
        var mresult = 'category='+category;
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

$("input.facility").click(function(){
    var checked = [];
    var facilities = $(".facility");
    $.each(facilities, function(key, attr){
        if(attr.checked == true){
            checked.push(attr.defaultValue);    
        }
    });
    var checked_len = checked.length;
    if(checked_len > 8){
        ($(this).prop('checked', false));
        alert('You can only pick a total of 8 facilities');
    }
})

$("button#update_facility").click(function(){
    $("button#update_facility").html('Updating...');
    var facilities = $(".facility");
    var checked = [];
    $.each(facilities, function(key, attr){
        if(attr.checked == true){
            checked.push(attr.value);
        }
    });
    var check_string = checked.join(',');
    var mresult = {
        'facilities': check_string
    }
    $.ajax({
        type: "POST",
        url: "business_facility_update.php",
        data: mresult,
        dataType: "json",
        success: function(data){
            $("button#update_facility").html('Update');
            alert(data.message);
        },
        error: function(data){
            $("button#update_facility").html('Update');
            var result = "oopsie "+data.status+" "+data.responseText;
            console.log(result);
            return false;
        }
    })
});

$("form#billboard_application").on('submit', function(){
    var file = getElementById("file_upload").files
    var title = $("input#advert_title").val();
    var adtext = $("textarea#text").val();
    var proposed_start = $("input#proposed_start").val();
    if((adtext == "") || (proposed_start == "") || (file.length == 0)){
        if(adtext == ""){
            $("button#advert_submit").after('<div class="error text-danger">Advert Text must be provided</div>');
        }
        if(proposed_start == ""){
            $("button#advert_submit").after('<div class="error text-danger">The Proposed Starting date for the Advert must be provided</div>');
        }
        if(file.length == 0){
            $("button#advert_submit").after('<div class="error text-danger">You must upload the Advert Photo</div>');
        }
        return false;
    }
    
    if((title.length > 50) || (adtext.length > 160)){
        if(title.length > 50){
            $("button#advert_submit").after('<div class="error text-danger">Headline CANNOT be more that 50 Characters</div>');
        }
        if(adtext.length > 160){
            $("button#advert_submit").after('<div class="error text-danger">Short Description CANNOT be more that 160 Characters</div>');
        }
        return false;
    }
    
    var action_type = $("select#action_type").val();
    var action_link = $("input#action_link");
    var action_text = $("input#action_text");
    
    if(action_type != ""){
        if((action_link.val() == "") || (action_text.val() == "")){
            if(action_link.val() == ""){
                $("button#advert_submit").after('<div class="error text-danger">The Call To Action Link must be Provided</div>');
            }
            if(action_text.val() == ""){
                $("button#advert_submit").after('<div class="error text-danger">The Call To Action Text must be Provided</div>');
            }
            return false;
        }
    }
});

$("select#action_type").change(function(){
    var action_type = $("select#action_type").val();
    
    $.getJSON("call_to_action_json.php", function(data){
        $.each(data, function(key, val){
            if(val.action == action_type){
                $("input#action_link").prop("type", val.input_type);
                $("input#action_link").attr("placeholder", val.placeholder);
                return false;
            }
        });
    })
});

$('table#subscription_table').DataTable( {
    "order": [[ 1, "desc" ]]
} );

$('table#report_table').DataTable( {
    "order": [[ 0, "desc" ]],
    "searching": false
} );

$('table#reviews_table').DataTable( {
    "order": [[ 0, "desc" ]]
} );





// const getPassword = document.querySelectorAll('.password-icon');
// const passwordIcon = document.querySelectorAll('.password-icon i');
// const getAllPassword = document.querySelectorAll('.password');

//  getPassword.forEach(curSpan => {
//     curSpan.addEventListener('click', e => {
//       if (e.target.classList.contains('bi-eye-slash')) {
//         // CHANGE THE ICON TO EYES
//         e.currentTarget.innerHTML = '<i class="bi bi-eye"></i>';
//         e.currentTarget.closest('div').children[1].type = 'password';
//         // CHANGE THE PASSWORD TYPE TO TEXT
//         // signInput.type = 'text';
//       } else {
//         e.currentTarget.innerHTML = '<i class="bi bi-eye-slash"></i>';
//         e.currentTarget.closest('div').children[1].type = 'text';
//       }
//     });
//   });


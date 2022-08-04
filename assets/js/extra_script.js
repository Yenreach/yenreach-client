$ = jQuery;

$("button#save_business").click(function(){
    $("button#save_business").html("Saving...");
    var string = $("input#business_id").val();
    
    var mresult = 'business_string='+string;
    $.ajax({
        type: "GET",
        url: "save_business.php",
        data: mresult,
        dataType: "json",
        contentType: "application/x-json",
        success: function(data){
            if(data.status == "success"){
                $("button#save_business").prop("disabled", true);
                $("button#save_business").after('<div class="col-12"><p>Business is already saved! Click <a href="users/saved_businesses" class="text-primary">here to see your Saved Businesses</a></p></div>')
                $("button#save_business").fadeOut(2000);
            } else {
                alert(data.message);
            }
        },
        error: function(data){
            var result = "oopsie "+data.responseText;
            console.log(result);
        }
    });
});

$("button#review_submit").click(function(){
    $(".errormsg").remove()
    var business_string = $("input#business_string").val();
    var review = $("textarea#review_text").val();
    var star = $("input.star:checked").val();
    
    if((business_string !== "") && (review !== "") && (star !== undefined)){
        $("button#review_submit").html("Submitting...");
        
        var mdata = {
            'business_string': business_string,
            'review': review,
            'star': star
        }
        
        $.ajax({
            type: "POST",
            url: "add_business_review.php",
            data: mdata,
            dataType: "json",
            success: function(data){
                if(data.status == "success"){
                    alert("Business Review Saved");
                    $("textarea#review_text").empty();
                } else {
                    alert(data.message);
                }
                $("button#review_submit").html("Submit");
            },
            error: function(data){
                var result = data.status+" "+data.response+" "+data.responseText;
                console.log(result);
                $("button#review_submit").html("Submit");
            }
        });
    } else {
        if(business_string === ""){
            $("button#review_submit").after('<div class="errormsg col-12 text-danger">Business was not provided</div>')
        }
        if(review === ""){
            $("textarea#review_text").after('<div class="errormsg col-12 text-danger">You cannot submit an empty review</div>')
        }
        if(star === undefined){
            $("div#rating").before('<div class="errormsg col-12 text-danger">You must select a Rating</div>')
        }
    }
});

function fetch_business_reviews(){
    var business_string = $("input#business_string").val();
    
    if(business_string && business_string !== ""){
        var mdata = "string="+business_string;
        $.ajax({
            type: "GET",
            url: "fetch_business_reviews.php",
            data: mdata,
            dataType: "json",
            contentType: "application/x-json",
            success: function(data){
                if(data.status == "success"){
                    var output = '';
                    $.each(data.data, function(key, val){
                        var colored = val.star;
                        var bland = 5 - colored;
                        var rates = '<p>';
                        for(i=1; i<=colored; i++){
                            rates += '<i class="bi bi-star-fill" id="1" style="color: #e1ad01"></i>'   
                        }
                        if(bland > 0){
                            for(t=1; t<=bland; t++){
                                rates += '<i class="bi bi-star-fill" id="1"></i>'   
                            }
                        }
                        rates +=    '</p>';
                        output +=   '<p class="py-3">';
                        output +=       '<p><strong>-'+val.user+'</strong></p>';
                        output +=       rates;
                        output +=       '<blockquote>'+val.review+'</blockquote>';
                        output +=   '</p>'; 
                    });
                    $("div#reviews").html(output);
                } else {
                    output = '<p>'+data.message+'</p>';
                }
            },
            error: function(data){
                var result = data.status+" "+data.response+" "+data.responseText;
                console.log(result);
            }
        });
    } else {
        console.log("Not here")
    }
}

setInterval(fetch_business_reviews, 2000);

function fetch_business_reviews_summary(){
    var business_string = $("input#business_string").val();
    
    if(business_string && business_string !== ""){
        var mdata = 'string='+business_string;
        
        $.ajax({
            type: 'GET',
            url: 'fetch_business_reviews_summary.php',
            data: mdata,
            dataType: "json",
            contentType: "application/x-json",
            success: function(data){
                if(data.status == "success"){
                    var val = data.data;
                    var rating = val.average;
                    var bland = 5 - rating;
                    
                    var output =    '<p>A total of '+val.total+' Review';
                    if(val.total > 1){
                        output += 's';
                    }
                    output +=       '</p>';
                    output +=       '<p><strong>Average Rating: </strong>';
                    for(var i=1; i<=rating; i++){
                        output +=       '<i class="bi bi-star-fill" style="color: #e1ad01"></i>';
                    }
                    if(bland > 0){
                        for(var m=1; m<=bland; m++){
                            output +=       '<i class="bi bi-star-fill"></i>';
                        }
                    }
                    output +=       '</p>';
                    $("div#review_summary").html(output);
                }
            },
            error: function(data){
                var result = data.status+" "+data.response+" "+data.responseText;
                console.log(result);
            } 
        })
    }
}

setInterval(fetch_business_reviews_summary, 2000);
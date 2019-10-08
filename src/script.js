

    function Like(id){
      
       
     var count = $(".count-"+id).attr("value");
     
  
     if($('.like[data-id='+id+']').attr("data-text")=="Like")
     {
        count++;
         type=1;
         
         $('.like[data-id='+id+']').html("Unlike"+"(<span value='"+count+"' class='count-"+id+"'>"+count+"</span>)");
         $('.like[data-id='+id+']').css("background-color","#e74c3c");
         $('.like[data-id='+id+']').attr("data-text","Unlike"); 
        
         
        
     }
    else{
        count--;
          type =2;
          
          $('.like[data-id='+id+']').html("Like"+"(<span value='"+count+"' class='count-"+id+"'>"+count+"</span>)");
          $('.like[data-id='+id+']').css("background-color","#0073aa");
          $('.like[data-id='+id+']').attr("data-text","Like"); 
         
        
     }

   

      
        $.ajax({
            url: window.location.origin+'/wp/wp-content/plugins/like-unlike/addLike.php',
            type: 'post',
            data: {postid:id,type:type},
            success: function(data){
               console.log(data);
            }
            
        });

    }
    


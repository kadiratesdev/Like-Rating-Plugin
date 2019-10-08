<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css">
   <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
   <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js"></script>
    <title>Document</title>
</head>
<body>
<?php
  
  global $wpdb;
  $table_name = $wpdb->base_prefix."likePlugin";
 
  $result = $wpdb->get_results("SELECT COUNT(postId) as Adet,postId,post_title,wp_posts.id FROM $table_name inner join wp_posts on wp_posts.ID = $table_name.postId GROUP by postId order by postId asc");

   ?>
    <h1>Like Sıralaması</h1>
    <div class="table-responsive">
    <table  id="deneme" class=" table table-bordered " style="width:100%">
   
    <thead>
        <tr>
            <th>ID</th>
            <th>Post Title</th>
            <th>Like Count</th>
            <th>Tags</th>
        </tr>
</thead>

        
        <tbody>
        <?php foreach ( $result as $print )   {




?>
        <tr><td><?php echo $print->postId; ?></td><td><?php echo $print->post_title; ?></td><td><?php echo $print->Adet; ?></td><td>
            <?php
            
        if(is_array(get_the_tags( $print->id ))){
           foreach (get_the_tags( $print->id ) as $tag) {
               echo $tag->name."<b> , </b>";
           }
        }
        else{
            echo "Bu Yazıda Etiket Kullanılmadı.";
        }
       

         ?></td></tr>
         <?php }
        ?>
        </tbody>
     
    </table>
   
         </div>
   
  

    <script>
 $(document).ready(function() {
      $('#deneme').dataTable({
        "order": [[ 3, "asc" ]]
      });  
  });
        </script>
   
     
   
</body>
</html>

<?php

?>
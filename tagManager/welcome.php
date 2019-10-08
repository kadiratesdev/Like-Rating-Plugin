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
  $result = $wpdb->get_results("SELECT * FROM wp_terms order by name desc");
 
   ?>
    <h1>Tag Listesi</h1>
    <div class="table-responsive">
    <table  id="deneme" class=" table table-bordered " style="width:100%">
   
    <thead>
        <tr>
            <th>ID</th>
            <th>Tag Name</th>
            <th>Tag Count</th>
            <th>Post Count</th>
        </tr>
</thead>

        
        <tbody>
        <?php foreach ( $result as $print )   {

$term = get_tag( $print->term_id );
$count = $term->count;

if(!isset($count))
$count = 0;
$termsCount = $wpdb->get_results("SELECT Count('term_taxonomy_id')as Sayi FROM wp_term_relationships where term_taxonomy_id=$print->term_id ");

?>
        <tr><td><?php echo $print->term_id; ?></td><td><?php echo $print->name; ?></td><td><?php echo $termsCount[0]->Sayi; ?></td><td><?php echo $count; ?></td></tr>
         <?php }
        ?>
        </tbody>
     
    </table>
   
         </div>
   
  

    <script>
 $(document).ready(function() {
      $('#deneme').dataTable({
        "order": [[ 3, "desc" ]]
      });  
  });
        </script>
   
     
   
</body>
</html>

<?php

?>
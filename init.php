<?php
/*
Plugin Name: Like-Unlike
Plugin URI: www.kadirates.me/like-unlike
Description: Bu eklenti ile sitenizin begeni ve popüler taglarinizi görebilirsiniz.
Version: 1.0
Author: Abdulkadir Ateş
Author URI: www.kadirates.me
License: GNU
*/

// register My_Widget
add_action( 'widgets_init', function(){
	register_widget( 'My_Widget' );
});

function create_button(){
	global $wpdb;
	
	$table_name = $wpdb->prefix . 'likePlugin';
	$sql  ="SELECT *  FROM $table_name WHERE postId =".get_the_ID()." and ipAddr='".$_SERVER['REMOTE_ADDR']."'";
	
$result = $wpdb->get_results ( $sql);
$likeCount = 0;
$likeCount = $wpdb->get_results ("Select Count(".get_the_ID().")as LikeCount from $table_name where postId=".get_the_ID()."");

if ($result[0]->postId>0) {
	
	return '<button onClick="Like('.get_the_ID().')" data-text="Unlike"  data-id="'.get_the_ID().'" style="background-color:#e74c3c;" class="like">Unlike (<span value="'.$likeCount[0]->LikeCount.'" class="count-'.get_the_ID().'">'.$likeCount[0]->LikeCount.'</span>)</button>';
}
else{
	

   return  '<button onClick="Like('.get_the_ID().')" class="like" data-text="Like" data-id="'.get_the_ID().'">Like (<span class="count-'.get_the_ID().'" value="'.$likeCount[0]->LikeCount.'">'.$likeCount[0]->LikeCount.'</span>)</button> ';
}
}







add_action('wp_enqueue_scripts','Ajax_Js');
function Ajax_Js() {
	wp_enqueue_script( 'ajax-js-2','https://code.jquery.com/jquery-3.4.1.js');
	wp_enqueue_script( 'ajax-js', plugins_url( 'src/script.js', __FILE__ ));


}





 
function welcome_callback(){
	include(dirname( __FILE__ ) .'/tagManager/welcome.php');
}
function list_tags_callback(){
	include(dirname( __FILE__ ) .'/tagManager/welcome.php');
}
function settings_callback(){
	include(dirname( __FILE__ ) .'/tagManager/settings.php');
}
function home(){
	include(dirname( __FILE__ ) .'/tagManager/home.php');
}
add_action('admin_menu','menuekle');

function menuekle(){
	

	add_menu_page('', 'Plugin Yönetimi', 'manage_options', 'home', 'home', plugins_url('like-unlike/images/icon.png',__DIR__));
	add_submenu_page('home', 'Tag Listesi', 'Tag Listesi', 'manage_options','/welcome', 'list_tags_callback');
	add_submenu_page(__FILE__, 'Settings', 'Settings', 'manage_options', __FILE__.'/settings.php', 'settings_callback');
	

}




add_filter("the_content","filter_the_content_in_the_main_loop");

function filter_the_content_in_the_main_loop( $content ) {
 
  
    if ( is_single() && in_the_loop() && is_main_query() ) {
		return $content . create_button();
		
    }
    else{

        return $content.create_button();
    }
}


register_activation_hook( __FILE__, 'jal_install' );
register_activation_hook( __FILE__, 'jal_install_data' );

global $jal_db_version;
$jal_db_version = '1.0';

function jal_install() {
	global $wpdb;
	global $jal_db_version;

	$table_name = $wpdb->prefix . 'likePlugin';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id smallint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
        postId smallint(9) ,
        ipAddr varchar(20)  ,
		time text  NOT NULL,
		CONSTRAINT AddLike UNIQUE (ipAddr,postId)) 
	 $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'jal_db_version', $jal_db_version );
}


class My_Widget extends WP_Widget {
	
	public function __construct() {
        $widget_ops = array( 
		'classname' => 'my_widget',
		'description' => 'A plugin for Kinsta blog readers',
	);
	parent::__construct( 'my_widget', 'Rating Widget', $widget_ops );}
	
	
	public function widget( $args, $instance ) {
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
		global $wpdb;
		$table_name = $wpdb->prefix . 'likePlugin';
        $result = $wpdb->get_results("Select COUNT(postId)as Rating,postId  from $table_name GROUP by  postId  order by postId ASC LIMIT 10");
	
	
       
			?>
			<h2>En Çok Begenilenler</h2>
            <ul>
				<?php
			for ($i=0; $i<count($result) ; $i++) {
			
				?>
		
				<li><a href="<?php  echo get_Post($result[$i]->postId)->guid ;?>"><?php  echo get_Post($result[$i]->postId)->post_title ;  ?></a> <?php echo "(".$result[$i]->Rating.") ";?></li>
		 <?php }?>
                
         
            </ul>
            <?php 
            
       
    
        echo $args['after_widget'];
    }

	public function form( $instance ) {

        $posts = get_posts( array( 
                'posts_per_page' => 20,
                'offset' => 0
            ) );
        $selected_posts = ! empty( $instance['selected_posts'] ) ? $instance['selected_posts'] : array();
        ?>
        <div style="max-height: 120px; overflow: auto;">
        <ul>
        <?php foreach ( $posts as $post ) { ?>
    
            <li><input 
                type="checkbox" 
                name="<?php echo esc_attr( $this->get_field_name( 'selected_posts' ) ); ?>[]" 
                value="<?php echo $post->ID; ?>" 
                <?php checked( ( in_array( $post->ID, $selected_posts ) ) ? $post->ID : '', $post->ID ); ?> />
                <?php echo get_the_title( $post->ID ); ?></li>
    
        <?php } ?>
        </ul>
        </div>
        <?php
    }





    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
            
        $selected_posts = ( ! empty ( $new_instance['selected_posts'] ) ) ? (array) $new_instance['selected_posts'] : array();
        $instance['selected_posts'] = array_map( 'sanitize_text_field', $selected_posts );
    
        return $instance;
    }
}
?>
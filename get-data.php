<?php

/**
 * Plugin Name: news app
 * Plugin URI:
 * Version: 0.0.1
 * Author: OuniHani
 * Description: this plugin is responsible for sending data from MYSQL data base to mobile apps
 * and notify user's for new posts...
 **/

 
 //necessary files to require 
$path = $_SERVER['DOCUMENT_ROOT'];

include_once $path . '/wp-config.php';
include_once $path . '/wp-load.php';
include_once $path . '/wp-includes/wp-db.php';
include_once $path . '/wp-includes/pluggable.php';

global $wpdb ;
 
//get a specific post by it's identifier passed by a get request 
  if ($_GET['id'] !="")
  {

 $post= get_post( $_GET['id'] ); 

$response = array();

        $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), array(300, 300), false, '');
  

        $posts[] = array('title' => $post->post_title ,  'date' => $post->post_date, 'url' => $image[0], 'category' => $post->post_name , 'id' => $post->ID , 'content' =>$post->post_content );


  


    array_push($response, $posts);


  

  echo json_encode($response[0]) ;


  
   }
  //get 6 post by offset
  else if (($_GET['offset'] !="") && ($_GET['categorie']!="") )
{

	  //get 6 posts from all categories starting from the offset passed by $_GET request 
	  
	  
if($_GET['categorie']=="all")
{  
	
    $result = $wpdb->get_results(" SELECT * FROM " . $wpdb->prefix . "posts inner join " . $wpdb->prefix . "term_relationships inner join " . $wpdb->prefix . "term_taxonomy inner join " . $wpdb->prefix . "terms on " . $wpdb->prefix . "posts.ID=" . $wpdb->prefix . "term_relationships.object_id AND " . $wpdb->prefix . "term_relationships.term_taxonomy_id = " . $wpdb->prefix . "term_taxonomy.term_taxonomy_id AND " . $wpdb->prefix . "term_taxonomy.term_id = " . $wpdb->prefix . "terms.term_id    where post_status = 'publish' and (name= 'politique' or name ='economie' or name ='monde' or name ='culture' or name ='environnement' or name ='proximité' or name ='sport' or name ='Société Civile' or name ='le passé...présent' or name ='En deux mots' or name ='un homme, des idées' or name ='Valeur en baisse' or name ='Editorial' or name ='cold case ... à la tunisienne' or name ='Ce que je pense' or name ='Caricature' or name ='brèves' or name ='santé'  ) ORDER BY post_date desc limit 6 offset ".$_GET['offset'].";");

}
else
{
	
	
	//get 6 posts information from a specific category . the category name is passed by a get request 
 $result = $wpdb->get_results(" SELECT * FROM " . $wpdb->prefix . "posts inner join " . $wpdb->prefix . "term_relationships inner join " . $wpdb->prefix . "term_taxonomy inner join " . $wpdb->prefix . "terms on " . $wpdb->prefix . "posts.ID=" . $wpdb->prefix . "term_relationships.object_id AND " . $wpdb->prefix . "term_relationships.term_taxonomy_id = " . $wpdb->prefix . "term_taxonomy.term_taxonomy_id AND " . $wpdb->prefix . "term_taxonomy.term_id = " . $wpdb->prefix . "terms.term_id    where post_status = 'publish' and name= '".$_GET['categorie']."' ORDER BY post_date desc limit 6 offset ".$_GET['offset'].";");
}
    $response = array();


    foreach ($result as $row) {

        $id=$row->ID;
        $title = $row->post_title;

       

        $date = $row->post_date;

        $categorie = $row->name;


        $image = wp_get_attachment_image_src(get_post_thumbnail_id($row->ID), array(300, 300), false, '');


        $posts[] = array('title' => $title,  'date' => $date, 'url' => $image[0], 'category' => $categorie , 'id'=>$id);


    }


    array_push($response, $posts);


  

  echo json_encode($response[0]) ;  //print the result in JSON format
	  
  
}

 ?>
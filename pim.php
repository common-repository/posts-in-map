<?
/*
Plugin Name: Posts in Map
Plugin URI: http://www.decristofano.it
Description: A JQuery plugin to create google maps with advanced features (overlays, clusters, callbacks, events...) and georeference posts in map
Version: 0.3
Author: lucdecri
Author URI: http://www.decristofano.it
License: GPL2
*/

function pim_gmap3_init() {
		// Google Maps API v3
		wp_deregister_script('googlemapsapi3');
		wp_enqueue_script('googlemapsapi3', 'http://maps.google.com/maps/api/js?sensor=false', false, '3', false); ;
}    
add_action('init', 'pim_gmap3_init');

/* aggiunge il codice con la mappa
 * 
 * uso : 
 *  width
 *  height
 *  border
 *  posttype
 *  taxonomy
 *  maxpost
 *  centermap
 *  view_title, view_excerpt, view_thumb, view_link --> per indicare cosa visualizzo quando clicco sul marker
 *  animate
 *  marker : url del marker
 *  icon : icona da utilizzare (se non specifico il marker)
 * 
 * poi leggo i campi georefer e icon e da li posiziono il marker del post
 */

function pim_map($atts, $content='') {
  
if ($atts=='') $atts=array();
$default = array(
        'width' => '500px',
        'height' => '350px',
        'border' => '2px solid #9c8169',
        'post_type' => 'post',
        'taxonomy' => '',
        'term' => '',
        'maxpost' => 15,
        'centermap' => '41.578498,14.457275',
        'view_title' => true,
        'view_excerpt' => true,
        'view_thumb' => true,
        'view_link' => true,
        'zoom'  => 8,
        'animation' => 0,
        'icon' => 'orange-dot',
        'debug' => false
    );
$args = array_merge($default,$atts);
$center=$args['centermap'];
$zoom=$args['zoom'];
$debug = $args['debug'];

if (!$args['marker']) $args['marker']=plugins_url( 'markers/'.$args['icon'].'.png' , __FILE__ );

   $str = '
<script type="text/javascript">// <![CDATA[

function showMap(element, center_lat,center_long,zoom) {
    
    var map = new google.maps.Map(element);	
	
    var center = new google.maps.LatLng(center_lat,center_long);
        
    map.setOptions({
				center: center,
				zoom: zoom,
				mapTypeId: google.maps.MapTypeId.ROADMAP
		});	
    return map;
}


function addMarker(map,position,draggable,title,icon,content) {
    
    // Creating a new marker and adding it to the map
    var marker = new google.maps.Marker({
		position: position,
		map: map,
		draggable: true,
		title: title,
		icon: icon
			});
    var infowindow = new google.maps.InfoWindow({
				content: content
			});
			
    google.maps.event.addListener(marker, "click", function() {
				 infowindow.open(map,marker);
                                 marker.setAnimation(google.maps.Animation.BOUNCE);
                                 setTimeout(function() {marker.setAnimation(null)}, 2000); 
			});
    google.maps.event.addListener(infowindow, "click", function() {
				 infowindow.close();
                                 
			});            
			
    //infowindow.open(map, marker);
}';


$str.="
jQuery(document).ready(function(){
    
    mapDiv = jQuery('#gmap');
    map=showMap(mapDiv[0],{$center},{$zoom});
      
    ";
   
 
   $query_array= array(
       'post_type' => $args['post_type'],
       'numberposts'  => $args['maxpost'],
       //'post_status' =>'any'
       );
   if ($args['taxonomy']!='') $query_array['tax_query'] = array( array(
                                    'taxonomy' => $args['taxonomy'],
                                    'field' => 'slug',
                                    'terms' => $args['term']
                                     ) );
   $posts = get_posts($query_array);
   
   if ($debug) print_r($query_array);
   if ($debug) print_r($args);
   
   foreach ($posts as $key => $post_data) {
       
       // legge il post
       $post_id = $post_data->ID;
       $title = $post_data->post_title;
       $excerpt = substr($post_data->post_excerpt, 0, 50).'<br />'.substr($post_data->post_excerpt, 50, 50).'<br />'.substr($post_data->post_excerpt, 100, 50);
       $link = $post_data->guid;
       
       // prepare il messaggio della onfo box
       $message="";
       if ($args['view_title'])     $message.="<a href=\'$link\'><b>$title</b></a><br />";
       if ($args['view_excerpt'])   $message.="<i>$excerpt</i>";
       if ($args['view_thumb'])     $message.="";
       if ($args['view_link'])      $message.="&nbsp;<a href=\'$link\'>...</a>";
                        
               
       
       
       // prepara il marker
       
       $georef=  get_post_meta($post_id, 'geolocation', true);
       
       $marker=  get_post_meta($post_id, 'marker', true);
       if ($marker=='') $marker=$args['marker'];
       if ($debug) echo $title.'-';
       if ($marker!='')  $str_mrk = "new google.maps.MarkerImage('".$marker."')";
       if ($debug) print_r($marker);
       if ($debug) echo "<br />";
       if ($georef!='')  $str.= "addMarker(map,new google.maps.LatLng($georef),false,'$title',$str_mrk,'$message');\n";
   }
   
        
$str.="
        }); // ready
       
       
        // ]]
        </script>";
   

 
$str .= '<div id="gmap" style="height:'.$args['height'].'; width:'.$args['height'].'; border:'.$args['border'].'; "></div>';

return $str;
    
}
add_shortcode('gmap', 'pim_map');


// permette di aggiungere un box con la visualizzazione della mappa e 
// l'inserimento automatico della georef, tramite completamento 
// automatico dell'indirizzo
function pim_insert_georef() {
//@TODO box per l'inserimento della georef
/*    
    $("#test").gmap3();
 
$('#address').autocomplete({
source: function() {
$("#test").gmap3({
action:'getAddress',
address: $(this).val(),
callback:function(results){
if (!results) return;
$('#address').autocomplete(
'display',
results,
false
);
}
});
},
cb:{
cast: function(item){
return item.formatted_address;
},
select: function(item) {
$("#test").gmap3(
{action:'clear', name:'marker'},
{action:'addMarker',
latLng:item.geometry.location,
map:{center:true}
}
);
}
}
});
  
 */ 
}

function map_init2() {
    ?>
<script lang="javascript">
var map;
var arrMarkers = [];
var arrInfoWindows = [];

 function mapInit(){
    var centerCoord = new google.maps.LatLng(18.23, -66.39); // Puerto Rico
    var mapOptions = {
            zoom: 9,
            center: centerCoord,
            mapTypeId: google.maps.MapTypeId.TERRAIN
        };
    map = new google.maps.Map(document.getElementById("map"), mapOptions);
    $.getJSON("map.json", {}, function(data){
            $.each(data.places, function(i, item){
                $("#markers").append('<li><a href="#" rel="' + i + '">' + item.title + '</a></li>');
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(item.lat, item.lng),
                    map: map,
                    title: item.title
                });
                arrMarkers[i] = marker;
                var infowindow = new google.maps.InfoWindow({
                    content: "<h3>"+ item.title +"</h3><p>"+ item.description +"</p>"
                });
                arrInfoWindows[i] = infowindow;
                google.maps.event.addListener(marker, 'click', function() {
                    infowindow.open(map, marker);
                });
            });
    });
}
$(function(){
    // initialize map (create markers, infowindows and list)
    mapInit();
    // "live" bind click event
    $("#markers a").live("click", function(){
    var i = $(this).attr("rel");
        // this next line closes all open infowindows before opening the selected one
        //for(x=0; x < arrInfoWindows.length; x++){ arrInfoWindows[x].close(); }
        arrInfoWindows[i].open(map, arrMarkers[i]);
    });
}); 
  </script>
 <?php
}
?>
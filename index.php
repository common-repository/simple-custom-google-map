<?php
/*
Plugin Name: Simple Custom Google Map
Plugin URI: http://djmweb.co
Description: The simplest ever customizable Google Map. Edit colours, markers and labels. Enter latitude and Longitude or simply drag marker into place.
Version: 0
Author: Daniel John Marsden
Author URI: http://www.djmweb.co/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Copyright: Daniel John Marsden


Simple Custom Google Map is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Simple Custom Google Map is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License 2
along with Simple Custom Google Map, if not, see https://www.gnu.org/licenses/gpl-2.0.html.

*/



class scgm_map_widget extends WP_Widget {
 
    function scgm_map_widget() {
        parent::WP_Widget(false, $name = 'Simple Custom Google Map');	
    }
 
    function widget($args, $instance) {	
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);

		echo $before_widget;
			if ( $title ) echo $before_title . $title . $after_title; 
			?>
			
		<script>
			var vrows = document.getElementsByName("map");
			var vrows2 = document.getElementsByName("map2");
			var vrows3 = document.getElementsByName("map3");
			var vrows4 = document.getElementsByName("map4");

			if (vrows.length == 0) {
				document.write("<div id='scgm_styled_map' class='scgm_styled_map scgm_styled_map_widget' name='map'></div>");
			}

			else if (vrows.length == 1 && vrows2.length == 0) {
				document.write("<div id='scgm_styled_map2' class='scgm_styled_map scgm_styled_map_widget' name='map2'></div>");
			}

			else if (vrows2.length == 1 && vrows3.length == 0) {
				document.write("<div id='scgm_styled_map3' class='scgm_styled_map scgm_styled_map_widget' name='map3'></div>");
			}

			else if (vrows3.length == 1 && vrows4.length == 0) {
				document.write("<div id='scgm_styled_map4' class='scgm_styled_map scgm_styled_map_widget' name='map4'></div>");
			}
		</script>					
					
		<?php echo $after_widget; 
	}
 
    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {		
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }
 
    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {	
 
        $title 		= esc_attr($instance['title']);
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <?php 
    }
 
} 
add_action('widgets_init', create_function('', 'return register_widget("scgm_map_widget");'));
// END OF WIDGET //






function scgm_include_google_map() {
    echo '<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"/></script>';
}
add_action('wp_head', 'scgm_include_google_map');
add_action('admin_head', 'scgm_include_google_map');





function scgm_color_picker()
{
    wp_register_script( 'custom-script', plugins_url('/jscolor.js', __FILE__) );
    wp_enqueue_script( 'custom-script' );
}
add_action( 'admin_enqueue_scripts', 'scgm_color_picker' );










function scgm_map_styling() {
	$latlong = esc_html(get_theme_mod( 'featured_textbox_text_coord' ) ); 
	$water_colour = "#".get_option( 'water_colour' );
	$landscape_colour = "#".get_option( 'landscape_colour' );
	$highway_colour = "#".get_option( 'highway_fill_colour' );
	$arterial_road_colour = "#".get_option( 'arterial_road' );
	$arterial_road_colour = "#".get_option( 'local_road' );
	$park_colour = "#".get_option( 'park_colour' );
	$poi_colour = "#".get_option( 'poi_colour' );
	$label_colour = "#".get_option( 'label_colour' );
	$label_stroke_colour = "#".get_option( 'label_stroke_colour' );
	$border_colour = "#".get_option( 'border_colour' );
	$latt = get_option( 'latt' );
	$longg = get_option( 'longg' );
	$labelstoggle = get_option( 'labelstoggle' );
	$customornot = get_option( 'customornot' );
	?>

<style>
.scgm_styled_map {height:300px;}
.scgm_styled_map.scgm_styled_map_widget {height:180px;}
</style>

	<?php if(esc_attr( get_option('zoomtype')) == "clickonly"){$zoomoptions = "scrollwheel: false,";} ?>
	<?php if(esc_attr( get_option('zoomtype')) == "both"){$zoomoptions = "scrollwheel: true,";} ?>
	<?php if(esc_attr( get_option('zoomtype')) == "nozooming"){$zoomoptions = "scrollwheel: false, disableDoubleClickZoom: true,";} ?>

	<script type="text/javascript">
		google.maps.event.addDomListener(window, 'load', init);
        
		var latt = "<?php Print($latt); ?>";
		var longg = "<?php Print($longg); ?>";
		var customornot = "<?php Print($customornot); ?>";
            
		function init() {
			var vrows = document.getElementsByName("map");
			var vrows2 = document.getElementsByName("map2");
			var vrows3 = document.getElementsByName("map3");
			var vrows4 = document.getElementsByName("map4");
    
    
    if (customornot == 'default'){
        var mapOptions = {
			<?php echo $zoomoptions; ?>
			zoom: <?php echo esc_attr( get_option('zoomno')); ?>,
			center: new google.maps.LatLng(latt, longg), 
       }
	}
	
    else{
			var mapOptions = {
				<?php echo $zoomoptions; ?>
				zoom: <?php echo esc_attr( get_option('zoomno')); ?>,
				center: new google.maps.LatLng(latt, longg), 
				styles: [
					{"featureType": "water", "elementType": "geometry", "stylers": [{"color": "<?php Print($water_colour); ?>"}]},
					{"featureType": "landscape", "elementType": "geometry", "stylers": [{"color": "<?php Print($landscape_colour); ?>"}]},
					{"featureType": "road.highway", "elementType": "geometry.fill", "stylers": [{"color": "<?php Print($highway_colour); ?>"}]},
					{"featureType": "road.arterial", "elementType": "geometry", "stylers": [{"color": "<?php Print($arterial_road_colour); ?>"}]},
					{"featureType": "road.local", "elementType": "geometry", "stylers": [{"color": "<?php Print($local_road_colour); ?>"}]},
					{"featureType": "poi", "elementType": "geometry", "stylers": [{"color": "<?php Print($poi_colour); ?>"}]},
					{"featureType": "poi.park","elementType": "geometry","stylers": [{"color": "<?php Print($park_colour); ?>"}]},    
					{"elementType": "labels.text.stroke","stylers": [{"visibility": "on"},{"color": "<?php Print($label_stroke_colour); ?>"}, {"weight": 2}]},
					{"elementType": "labels.text.fill", "stylers": [{"color": "<?php Print($label_colour); ?>"}]},
					{"elementType": "labels.icon","stylers": [{"visibility": "<?php Print($labelstoggle); ?>"}]},
					{"featureType": "transit","elementType": "geometry","stylers": [{"color": "#f2f2f2"}]},
					{"featureType": "administrative","elementType": "geometry.fill","stylers": [{"color": "#fefefe"}]},
					{"featureType": "administrative","elementType": "geometry.stroke","stylers": [{"color": "<?php Print($border_colour); ?>"},{"weight": 0.9}]}
				]};
	}

             if(vrows.length == 1){  
                var mapElement = document.getElementById('scgm_styled_map');
				var map = new google.maps.Map(mapElement, mapOptions);
                map.setMapTypeId(google.maps.MapTypeId.<?php echo esc_attr( get_option('type') ); ?>);
			 }
                
             if(vrows2.length == 1){  
                var mapElement2 = document.getElementById('scgm_styled_map2');
                var map2 = new google.maps.Map(mapElement2, mapOptions);
                map2.setMapTypeId(google.maps.MapTypeId.<?php echo esc_attr( get_option('type') ); ?>);
			 }
                
             if(vrows3.length == 1){  
                var mapElement3 = document.getElementById('scgm_styled_map3');
                var map3 = new google.maps.Map(mapElement3, mapOptions);
                map3.setMapTypeId(google.maps.MapTypeId.<?php echo esc_attr( get_option('type') ); ?>);
			 }
			 
             if(vrows4.length == 1){  
                var mapElement4 = document.getElementById('scgm_styled_map4');
                var map4 = new google.maps.Map(mapElement4, mapOptions);
                map4.setMapTypeId(google.maps.MapTypeId.<?php echo esc_attr( get_option('type') ); ?>);
			 }
                
				
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(latt, longg),
                map: map,
                title: 'test',
            });
               
			var marker2 = new google.maps.Marker({
				position: new google.maps.LatLng(latt, longg),
				map: map2,
				title: 'test'
			});   
                             
			var marker3 = new google.maps.Marker({
				position: new google.maps.LatLng(latt, longg),
				map: map3,
				title: 'test'
			}); 
                                                
			var marker4 = new google.maps.Marker({
				position: new google.maps.LatLng(latt, longg),
				map: map4,
				title: 'test'
			});   

				
				
			var markerstyle = '<?php echo esc_attr( get_option('marker')); ?>';
			var markerstylecustom = '<?php echo esc_attr( get_option('custommarker')); ?>';
				
			if (markerstylecustom == ''){
				if(markerstyle == 'original'){marker.setIcon(null)}
				if(markerstyle == 'green'){marker.setIcon('http://maps.google.com/mapfiles/ms/icons/green-dot.png');marker2.setIcon('http://maps.google.com/mapfiles/ms/icons/green-dot.png');marker3.setIcon('http://maps.google.com/mapfiles/ms/icons/green-dot.png');marker4.setIcon('http://maps.google.com/mapfiles/ms/icons/green-dot.png');}
				if(markerstyle == 'blue'){marker.setIcon('http://maps.google.com/mapfiles/ms/icons/blue-dot.png');marker2.setIcon('http://maps.google.com/mapfiles/ms/icons/blue-dot.png');marker3.setIcon('http://maps.google.com/mapfiles/ms/icons/blue-dot.png');marker4.setIcon('http://maps.google.com/mapfiles/ms/icons/blue-dot.png');}
				if(markerstyle == 'red'){marker.setIcon('http://maps.google.com/mapfiles/ms/icons/red-dot.png');marker2.setIcon('http://maps.google.com/mapfiles/ms/icons/red-dot.png');marker3.setIcon('http://maps.google.com/mapfiles/ms/icons/red-dot.png');marker4.setIcon('http://maps.google.com/mapfiles/ms/icons/red-dot.png');}
				if(markerstyle == 'purple'){marker.setIcon('http://maps.google.com/mapfiles/ms/icons/purple-dot.png');marker2.setIcon('http://maps.google.com/mapfiles/ms/icons/purple-dot.png');marker3.setIcon('http://maps.google.com/mapfiles/ms/icons/purple-dot.png');marker4.setIcon('http://maps.google.com/mapfiles/ms/icons/purple-dot.png');}
				if(markerstyle == 'yellow'){marker.setIcon('http://maps.google.com/mapfiles/ms/icons/yellow-dot.png');marker2.setIcon('http://maps.google.com/mapfiles/ms/icons/yellow-dot.png');marker3.setIcon('http://maps.google.com/mapfiles/ms/icons/yellow-dot.png');marker4.setIcon('http://maps.google.com/mapfiles/ms/icons/yellow-dot.png');}
			}
			else {
				marker.setIcon(markerstylecustom);                      
				marker2.setIcon(markerstylecustom);                      
				marker3.setIcon(markerstylecustom);                      
				marker4.setIcon(markerstylecustom);                      
			}
			
			if (customornot == 'default'){
				marker.setIcon(null)
			}
		}
</script>

<?php }

 add_action( 'wp_footer',  'scgm_map_styling');
// END FRONTEND MAP STYLING //





/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





// CREATE BACKEND INTERFACE //

add_action('admin_menu', 'scgm_create_menu');

function scgm_create_menu() {
	add_menu_page('Styleable Google Map Settings', 'Map Styles', 'administrator', __FILE__, 'scgm_settings_page', plugins_url('/images/icon.png', __FILE__));
	add_action( 'admin_init', 'register_scgm_settings' );
}


function register_scgm_settings() {
	register_setting( 'scgm-settings-group', 'water_colour' );
	register_setting( 'scgm-settings-group', 'landscape_colour' );
	register_setting( 'scgm-settings-group', 'highway_fill_colour' );
	register_setting( 'scgm-settings-group', 'highway_stroke_colour' );
	register_setting( 'scgm-settings-group', 'arterial_road' );
	register_setting( 'scgm-settings-group', 'local_road' );
	register_setting( 'scgm-settings-group', 'park_colour' );
	register_setting( 'scgm-settings-group', 'poi_colour' );
	register_setting( 'scgm-settings-group', 'labelstoggle' );
	register_setting( 'scgm-settings-group', 'label_colour' );
	register_setting( 'scgm-settings-group', 'label_stroke_colour' );
	register_setting( 'scgm-settings-group', 'border_colour' );
	register_setting( 'scgm-settings-group', 'zoomno' );
	register_setting( 'scgm-settings-group', 'latt' );
	register_setting( 'scgm-settings-group', 'longg' );
	register_setting( 'scgm-settings-group', 'type' );
	register_setting( 'scgm-settings-group', 'zoomtype' );
	register_setting( 'scgm-settings-group', 'marker' );
	register_setting( 'scgm-settings-group', 'custommarker' );
	register_setting( 'scgm-settings-group', 'customornot' );
}


function scgm_settings_page() 
	{ ?>
		<div class="wrap">
		<h2>Simple Custom Google Map Styles</h2>
		
		<style>
			th {vertical-align:middle !important;}
			.scgm-top-table th, .scgm-top-table td {width:Auto;}
			.scgm-main-table th {text-align:right;}
			.scgm-top-table small {color:#bbb; display:Block;}
			.scgm-top-table small a {color:#999;}
			.scgm-main-table p.submit {text-align:center; padding-right:40px;}
			.scgm-main-table td {width:25%;}
			.scgm-main-table td input {width:100%;}
			.scgm-main-table td select {width:100%;}
			.scgm-main-table .submit {padding-top:0; padding-right:0 !important;}
			.scgm-main-table .submit input {width:200px;}
			.cleary {width:100%; clear:both;}
			.table-contain {background:white; padding:15px 30px; box-sizing:border-box; -moz-box-sizing:border-box; -webkit-box-sizing:border-box;}
			.table-contain-2 {background:white; padding:15px 30px; box-sizing:border-box; -moz-box-sizing:border-box; -webkit-box-sizing:border-box;}
			.defaultstyles {margin-bottom:20px;}
			#map_preview {width:49%; border:8px double white; box-sizing:border-box; -moz-box-sizing:border-box; -webkit-box-sizing:border-box; float:left; height:490px}
		</style>

		<form method="post" action="options.php">
			<?php settings_fields( 'scgm-settings-group' ); ?>
			<?php do_settings_sections( 'scgm-settings-group' ); ?>


		<table class="form-table scgm-top-table"  style="border-bottom:1px solid white;">
			<tr valign="top">
				<th style="width:auto;" scope="row">Initial Zoom</th>
				<td><input type="number" id="zoomno" class="zoomno" name="zoomno" value="<?php if(esc_attr(get_option('zoomno')) != ''){echo esc_attr(get_option('zoomno'));} else{echo "4";} ?>"/></td>
			
				<th style="width:auto; text-align:right;" scope="row">Latitude & Longitude<small>Click map or <a target="BLANK" href="http://www.latlong.net/">find by address</a>.</small></th>
				<td>
					<input type="text" class="latt" id="id1" name="latt" value="<?php if(esc_attr(get_option('latt')) != ''){echo esc_attr(get_option('latt'));} else{echo "45";} ?>" /> , 
					<input type="text" class="longg" id="id2" name="longg" value="<?php if(esc_attr(get_option('longg')) != ''){echo esc_attr( get_option('longg'));} else{echo "45";} ?>" />
				</td>			
						
				<th style="width:auto; text-align:right;" scope="row">Allow Zooming?<small>Not reflected in preview.</small></th>
				<td>
					<select name="zoomtype" class="zoomtype">
						<option value="both" <?php if (esc_attr( get_option('zoomtype') ) == 'both'){echo "selected";} ?>>Double Click and Mousewheel</option>
						<option value="clickonly" <?php if (esc_attr( get_option('zoomtype') ) == 'clickonly'){echo "selected";} ?>>Double Click Only</option>
						<option value="nozooming" <?php if (esc_attr( get_option('zoomtype') ) == 'nozooming'){echo "selected";} ?>>No Zooming</option>
					</select>
				</td>		
			
				<th style="width:auto;" scope="row">View Type</th>
				<td>
					<select name="type" class="type">
						<option value="ROADMAP" <?php if (esc_attr( get_option('type') ) == 'ROADMAP'){echo "selected";} ?>>Road</option>
						<option value="HYBRID" <?php if (esc_attr( get_option('type') ) == 'HYBRID'){echo "selected";} ?>>Hybrid</option>
						<option value="SATELLITE" <?php if (esc_attr( get_option('type') ) == 'SATELLITE'){echo "selected";} ?>>Satellite</option>
						<option value="TERRAIN" <?php if (esc_attr( get_option('type') ) == 'TERRAIN'){echo "selected";} ?>>Terrain</option>
					</select>
				</td>
			</tr>
		</table>	
	
	
	
	

        <div style="float:right; margin-top:55px; width:50%;">
			<select class="defaultstyles" name="customornot">
				<option value="default" <?php if (esc_attr( get_option('customornot') ) == 'default'){echo "selected";} ?>>Default Styling</option>
				<option value="custom" <?php if (esc_attr( get_option('customornot') ) == 'custom'){echo "selected";} ?>>Custom Styling</option>
			</select>


			<div class="table-contain" <?php if(get_option('customornot') == 'default') {echo "style='display:none;'";} ?>>
				<table class="form-table scgm-main-table" >
					<tr valign="top">
						<td>Water Colour<input type="text" class="jscolor water_colour" name="water_colour" value="<?php echo esc_attr( get_option('water_colour') ); ?>" /></td>
						<td>Landscape Colour<input type="text" class="jscolor landscape_colour" name="landscape_colour" value="<?php echo esc_attr( get_option('landscape_colour') ); ?>" /></td>
						<td>Highway Fill<input type="text" class="jscolor highway_fill" name="highway_fill_colour" value="<?php echo esc_attr( get_option('highway_fill_colour') ); ?>" /></td>
						<td>Highway Stroke<input type="text" class="jscolor highway_stroke" name="highway_stroke_colour" value="<?php echo esc_attr( get_option('highway_stroke_colour') ); ?>" /></td>
					</tr>   
             
					<tr valign="top">
						<td>Arterial Road Colour<input type="text" class="jscolor arterial_road" name="arterial_road" value="<?php echo esc_attr( get_option('arterial_road') ); ?>" /></td>
						<td>Local Road Colour<input type="text" class="jscolor local_road" name="local_road" value="<?php echo esc_attr( get_option('local_road') ); ?>" /></td>
						<td>Park Colour<input type="text" class="jscolor park_colour" name="park_colour" value="<?php echo esc_attr( get_option('park_colour') ); ?>" /></td>
						<td>P.O.I Colour<input type="text" class="jscolor poi_colour" name="poi_colour" value="<?php echo esc_attr( get_option('poi_colour') ); ?>" /></td>
					</tr>        
					<tr valign="top">
						<td>Labels<select name="labelstoggle" class="labelstoggle"><option value="on" <?php if (esc_attr( get_option('labelstoggle') ) == 'on'){echo "selected";} ?>>More</option><option value="off" <?php if (esc_attr( get_option('labelstoggle') ) == 'off'){echo "selected";} ?>>Less</option></select></td>
						<td>Label Text Colour<input type="text" class="jscolor label_colour" name="label_colour" value="<?php echo esc_attr( get_option('label_colour') ); ?>" /></td>
							<td>Label Stroke Colour<input type="text" class="jscolor label_stroke_colour" name="label_stroke_colour" value="<?php echo esc_attr( get_option('label_stroke_colour') ); ?>" /></td>
						<td>Regional Borders<input type="text" class="jscolor border_colour" name="border_colour" value="<?php echo esc_attr( get_option('border_colour') ); ?>" /></td>
					</tr>    
					<tr valign="top">
						<td>Marker				
							<select name="marker" class="marker">
								<option value="original" <?php if (esc_attr( get_option('marker') ) == '' || esc_attr( get_option('original') ) == ''){echo "selected";} ?>>Original</option>
								<option value="green" <?php if (esc_attr( get_option('marker') ) == 'green'){echo "selected";} ?>>Green</option>
								<option value="blue" <?php if (esc_attr( get_option('marker') ) == 'blue'){echo "selected";} ?>>Blue</option>
								<option value="red" <?php if (esc_attr( get_option('marker') ) == 'red'){echo "selected";} ?>>Red</option>
								<option value="purple" <?php if (esc_attr( get_option('marker') ) == 'purple'){echo "selected";} ?>>Purple</option>
								<option value="yellow" <?php if (esc_attr( get_option('marker') ) == 'yellow'){echo "selected";} ?>>Yellow</option>
							</select>
						</td>			
						<td colspan="3">Custom Marker URL (Override)	
							<input type="text" class="custommarker" name="custommarker" value="<?php echo esc_attr( get_option('custommarker') ); ?>" />
						</td>
                            </tr>  
                          </table>  

			  </div>  
			  <div class="table-contain-2">
			                          <table>
					<tr valign="top">
						<td colspan="4" style="text-align:center;">Use shortcode: <pre style="display:inline;">[simple_custom_google_map]</pre><br/><br/>
						<?php submit_button(); ?></td>       
					</tr>  
					<div class="cleary"></div>
				</table>  
        </div>   
        </div>   
	
	
	
          
<h2>Preview:</h2>
<?php $zoomno = get_option( 'zoomno' ); ?>
<?php if(esc_attr( get_option('zoomtype')) == "clickonly"){$zoomoptions = "scrollwheel: false,";} ?>
<?php if(esc_attr( get_option('zoomtype')) == "both"){$zoomoptions = "scrollwheel: true,";} ?>
<?php if(esc_attr( get_option('zoomtype')) == "nozooming"){$zoomoptions = "scrollwheel: false, disableDoubleClickZoom: true,";} ?>


	
<script type="text/javascript">

jQuery(function() {
  jQuery(".water_colour").on("change", function(){init();});  
  jQuery(".landscape_colour").on("change", function(){init();});    
  jQuery(".highway_fill").on("change", function(){init();});    
  jQuery(".highway_stroke").on("change", function(){init();});    
  jQuery(".arterial_road").on("change", function(){init();});   
  jQuery(".local_road").on("change", function(){init();});   
  jQuery(".park_colour").on("change", function(){init();});    
  jQuery(".poi_colour").on("change", function(){init();});    
  jQuery(".labelstoggle").on("change", function(){init();});  
  jQuery(".label_colour").on("change", function(){init();});  
  jQuery(".label_stroke_colour").on("change", function(){init();});  
  jQuery(".border_colour").on("change", function(){init();});  
  jQuery(".zoom").on("keyup", function(){init();});  
  jQuery(".defaultstyles").on("change", function(){init();});  

  jQuery(".defaultstyles").on("change", function(){
  init();
  
  if((this).value == 'custom'){
   jQuery(".table-contain").slideDown();
  }  
  else if((this).value == 'default'){
   jQuery(".table-contain").slideUp();
  }
  });  
});



google.maps.event.addDomListener(window, 'load', init);





                  
    function init() {
        var customornot = jQuery(".defaultstyles").val();
        var water = jQuery(".water_colour").val();
        var landscape = jQuery(".landscape_colour").val();
        var highway_fill = jQuery(".highway_fill").val();
        var highway_stroke = jQuery(".highway_stroke").val();
        var arterial_road = jQuery(".arterial_road").val();
        var local_road = jQuery(".local_road").val();
        var park = jQuery(".park_colour").val();
        var poi = jQuery(".poi_colour").val();
        var labelstoggle = jQuery(".labelstoggle").val();
        var label = jQuery(".label_colour").val();
        var label_stroke = jQuery(".label_stroke_colour").val();
        var border = jQuery(".border_colour").val();
        var zoom = jQuery(".zoomno").val();
        var latlong = jQuery(".latlong").val();
        var latt = jQuery(".latt").val();
        var longg = jQuery(".longg").val();
        var myrange = jQuery("#myRange").val();
        
        

        
    if (customornot == 'default'){
        var mapOptions = {
			scrollwheel: false,
			disableDoubleClickZoom: true,
			<?php // echo $zoomoptions; ?>            
			center: new google.maps.LatLng(latt,longg)};
       }


    else{
        var mapOptions = {
			scrollwheel: false,
			disableDoubleClickZoom: true,			
			<?php //echo $zoomoptions; ?>            
			center: new google.maps.LatLng(latt,longg), 
			styles: [
				// WATER DEFAULT - #8DB6FD //
				{"featureType": "water", "elementType": "geometry", "stylers": [{"color": "#"+water}]},
				// LANDSCAPE DEFAULT - #F0EDE5 //
				{"featureType": "landscape", "elementType": "geometry", "stylers": [{"color": "#"+landscape}]},
				// HIGHWAY FILL DEFAULT - #FFE168 //
				{"featureType": "road.highway", "elementType": "geometry.fill", "stylers": [{"color": "#"+highway_fill}]},
				// ARTERIAL ROAD DEFAULT - #ffffff //
				{"featureType": "road.arterial", "elementType": "geometry", "stylers": [{"color": "#"+arterial_road}]},
				// LOCAL ROAD DEFAULT - #ffffff //
				{"featureType": "road.local", "elementType": "geometry", "stylers": [{"color": "#"+local_road}]},
				{"featureType": "poi", "elementType": "geometry", "stylers": [{"color": "#"+poi}]},
				{"featureType": "road.highway", "elementType": "geometry.stroke", "stylers": [{"color": "#"+highway_stroke}]},
				{"featureType": "poi.park","elementType": "geometry","stylers": [{"color": "#"+park}]},    
				{"elementType": "labels.text.stroke","stylers": [{"visibility": "on"},{"color": "#"+label_stroke}]},
				{"elementType": "labels.text.fill", "stylers": [{"saturation": 36},{"color": "#"+label}]},
				{"elementType": "labels.icon","stylers": [{"visibility": ""+labelstoggle}]},
				//{"featureType": "transit","elementType": "geometry","stylers": [{"color": "#f2f2f2"}]},
				//{"featureType": "administrative","elementType": "geometry.fill","stylers": [{"color": "#fefefe"}]},
				{"featureType": "administrative","elementType": "geometry.stroke","stylers": [{"color": "#"+border},{"weight": 1.2}]}
			]};
	}
	   
	   
                var map = new google.maps.Map(document.getElementById('map_preview'), mapOptions);
                
                var marker = new google.maps.Marker({
                position: new google.maps.LatLng(latt, longg), 
                map: map,
                draggable:true

                });
	   

	   
				function moveToLocation(lat, lng){
					var center = new google.maps.LatLng(lat, lng);
					map.panTo(center);
					marker.setPosition( new google.maps.LatLng( lat, lng ) );
				}    
	   
	   

				                
                jQuery(".marker").on("change", function(){
                var custommarker = jQuery(".custommarker").val();
                var markerdesign = jQuery(".marker").val();
                if (custommarker == ''){
                var markerdesign = jQuery(".marker").val();
				if(markerdesign == 'original'){marker.setIcon(null)}
				if(markerdesign == 'green'){marker.setIcon('http://maps.google.com/mapfiles/ms/icons/green-dot.png')}
				if(markerdesign == 'blue'){marker.setIcon('http://maps.google.com/mapfiles/ms/icons/blue-dot.png')}
				if(markerdesign == 'red'){marker.setIcon('http://maps.google.com/mapfiles/ms/icons/red-dot.png')}
				if(markerdesign == 'purple'){marker.setIcon('http://maps.google.com/mapfiles/ms/icons/purple-dot.png')}
				if(markerdesign == 'yellow'){marker.setIcon('http://maps.google.com/mapfiles/ms/icons/yellow-dot.png')}
				}
				});                                    

				                
                jQuery(".custommarker").on("keyup", function(){
				var custommarker = jQuery(".custommarker").val();
				marker.setIcon(custommarker);
				if(custommarker == ''){
				var markerdesign = jQuery(".marker").val();
				if(markerdesign == 'original'){marker.setIcon(null)}
				if(markerdesign == 'green'){marker.setIcon('http://maps.google.com/mapfiles/ms/icons/green-dot.png')}
				if(markerdesign == 'blue'){marker.setIcon('http://maps.google.com/mapfiles/ms/icons/blue-dot.png')}
				if(markerdesign == 'red'){marker.setIcon('http://maps.google.com/mapfiles/ms/icons/red-dot.png')}
				if(markerdesign == 'purple'){marker.setIcon('http://maps.google.com/mapfiles/ms/icons/purple-dot.png')}
				if(markerdesign == 'yellow'){marker.setIcon('http://maps.google.com/mapfiles/ms/icons/yellow-dot.png')}
				}
								                });      
								                
                jQuery(".custommarker").on("change", function(){
				var custommarker = jQuery(".custommarker").val();
				marker.setIcon(custommarker);
				if(custommarker == ''){
				var markerdesign = jQuery(".marker").val();
				if(markerdesign == 'original'){marker.setIcon(null)}
				if(markerdesign == 'green'){marker.setIcon('http://maps.google.com/mapfiles/ms/icons/green-dot.png')}
				if(markerdesign == 'blue'){marker.setIcon('http://maps.google.com/mapfiles/ms/icons/blue-dot.png')}
				if(markerdesign == 'red'){marker.setIcon('http://maps.google.com/mapfiles/ms/icons/red-dot.png')}
				if(markerdesign == 'purple'){marker.setIcon('http://maps.google.com/mapfiles/ms/icons/purple-dot.png')}
				if(markerdesign == 'yellow'){marker.setIcon('http://maps.google.com/mapfiles/ms/icons/yellow-dot.png')}
				}
				});      
				                       
				                       
				                           
				var custommarker = jQuery(".custommarker").val();
				
				if (custommarker != ''){
				marker.setIcon(custommarker);                      
				}
				else{
				var markerdesign = jQuery(".marker").val();
				if(markerdesign == 'original'){marker.setIcon(null)}
				if(markerdesign == 'green'){marker.setIcon('http://maps.google.com/mapfiles/ms/icons/green-dot.png')}
				if(markerdesign == 'blue'){marker.setIcon('http://maps.google.com/mapfiles/ms/icons/blue-dot.png')}
				if(markerdesign == 'red'){marker.setIcon('http://maps.google.com/mapfiles/ms/icons/red-dot.png')}
				if(markerdesign == 'purple'){marker.setIcon('http://maps.google.com/mapfiles/ms/icons/purple-dot.png')}
				if(markerdesign == 'yellow'){marker.setIcon('http://maps.google.com/mapfiles/ms/icons/yellow-dot.png')}				
				}
				
				
				
				
                jQuery(".latt").on("keyup", function(){
					var latnew = jQuery(".latt").val();
					var longnew = jQuery(".longg").val();
					moveToLocation( latnew, longnew);
                });                  

                                
                jQuery(".longg").on("keyup", function(){
					var latnew = jQuery(".latt").val();
					var longnew = jQuery(".longg").val();
					moveToLocation( latnew, longnew);

                                          });              
                                          
                                          
                jQuery(".type").on("change", function(){
                	if(jQuery(".type").val() == 'HYBRID'){map.setMapTypeId(google.maps.MapTypeId.HYBRID);}
                	if(jQuery(".type").val() == 'ROADMAP'){map.setMapTypeId(google.maps.MapTypeId.ROADMAP);}
                	if(jQuery(".type").val() == 'SATELLITE'){map.setMapTypeId(google.maps.MapTypeId.SATELLITE);}
                	if(jQuery(".type").val() == 'TERRAIN'){map.setMapTypeId(google.maps.MapTypeId.TERRAIN);}
					
                }); 
				
				
google.maps.event.addListener(map, 'click', function( event ){
var latttt = event.latLng.lat(); 
var lngggg = event.latLng.lng(); 
document.getElementById('id1').value= latttt; 
document.getElementById('id2').value= lngggg; 
moveToLocation( latttt, lngggg);
});          
                                       

google.maps.event.addListener(marker, 'dragend', function (evt) {
  var latdrop =  evt.latLng.lat();
  var longdrop =  evt.latLng.lng();

  document.getElementById('id1').value= latdrop; 
document.getElementById('id2').value= longdrop;   
    moveToLocation( latdrop, longdrop);

});


    
// CHANGE ZOOM WITH MAP CONTROLLER
map.addListener('zoom_changed', function() {
var zoomn = map.getZoom()
document.getElementById('zoomno').value= zoomn; 
});   

				
                                                                        
       
// ZOOM CLICK PREVIEW //				                                        
jQuery(".zoomno").on("click", function(){var zoomnew = jQuery(".zoomno").val();	if (zoomnew == 1) {map.setZoom(1);}	if (zoomnew == 2) {map.setZoom(2);}	if (zoomnew == 3) {map.setZoom(3);}	if (zoomnew == 4) {map.setZoom(4);}	if (zoomnew == 5) {map.setZoom(5);}	if (zoomnew == 6) {map.setZoom(6);}	if (zoomnew == 7) {map.setZoom(7);}	if (zoomnew == 8) {map.setZoom(8);}	if (zoomnew == 9) {map.setZoom(9);}	if (zoomnew == 10) {map.setZoom(10);} if (zoomnew == 11) {map.setZoom(11);} if (zoomnew == 12) {map.setZoom(12);} if (zoomnew == 13) {map.setZoom(13);}	if (zoomnew == 14) {map.setZoom(14);} if (zoomnew == 15) {map.setZoom(15);}	if (zoomnew == 16) {map.setZoom(16);} if (zoomnew == 17) {map.setZoom(17);}	if (zoomnew == 18) {map.setZoom(18);} if (zoomnew == 19) {map.setZoom(19);}	if (zoomnew == 20) {map.setZoom(20);} if (zoomnew > 20) {map.setZoom(20);}});  		
                 		                                        
                 		                                        
// ZOOM TYPE PREVIEW //                		                                        
jQuery(".zoomno").on("keyup", function(){var zoomnew = jQuery(".zoomno").val(); if (zoomnew == 1) {map.setZoom(1);} if (zoomnew == 2) {map.setZoom(2);} if (zoomnew == 3) {map.setZoom(3);}	if (zoomnew == 4) {map.setZoom(4);}	if (zoomnew == 5) {map.setZoom(5);}	if (zoomnew == 6) {map.setZoom(6);}	if (zoomnew == 7) {map.setZoom(7);}	if (zoomnew == 8) {map.setZoom(8);}	if (zoomnew == 9) {map.setZoom(9);}	if (zoomnew == 10) {map.setZoom(10);} if (zoomnew == 11) {map.setZoom(11);}	if (zoomnew == 12) {map.setZoom(12);} if (zoomnew == 13) {map.setZoom(13);}	if (zoomnew == 14) {map.setZoom(14);}	if (zoomnew == 15) {map.setZoom(15);}	if (zoomnew == 16) {map.setZoom(16);}	if (zoomnew == 17) {map.setZoom(17);}	if (zoomnew == 18) {map.setZoom(18);}	if (zoomnew == 19) {map.setZoom(19);}	if (zoomnew == 20) {map.setZoom(20);}	if (zoomnew > 20) {map.setZoom(20);}});     
                                             
                                             
                          
// RETAIN ZOOM PREVIEW ON INIT() //                              
var zoomnewinit = jQuery(".zoomno").val()                         
if (zoomnewinit == 1) {map.setZoom(1);}	if (zoomnewinit == 2) {map.setZoom(2);}	if (zoomnewinit == 3) {map.setZoom(3);}	if (zoomnewinit == 4) {map.setZoom(4);}	if (zoomnewinit == 5) {map.setZoom(5);}	if (zoomnewinit == 6) {map.setZoom(6);}	if (zoomnewinit == 7) {map.setZoom(7);}	if (zoomnewinit == 8) {map.setZoom(8);}	if (zoomnewinit == 9) {map.setZoom(9);}	if (zoomnewinit == 10) {map.setZoom(10);} if (zoomnewinit == 11) {map.setZoom(11);} if (zoomnewinit == 12) {map.setZoom(12);} if (zoomnewinit == 13) {map.setZoom(13);}	if (zoomnewinit == 14) {map.setZoom(14);} if (zoomnewinit == 15) {map.setZoom(15);}	if (zoomnewinit == 16) {map.setZoom(16);} if (zoomnewinit == 17) {map.setZoom(17);} if (zoomnewinit == 18) {map.setZoom(18);} if (zoomnewinit == 19) {map.setZoom(19);} if (zoomnewinit == 20) {map.setZoom(20);} if (zoomnewinit > 20) {map.setZoom(20);}                         
                                             
                                             
// RETAIN POSITION PREVIEW ON INIT() //
var lattnew = jQuery(".latt").val()                        
var longgnew = jQuery(".longg").val()                        
var centernew = new google.maps.LatLng(lattnew, longgnew);
map.panTo(centernew);
marker.setPosition( new google.maps.LatLng( lattnew, longgnew ) );
                                           
              
				

// RETAIN MAP TYPE PREVIEW ON INIT() //
if(jQuery(".type").val() == 'HYBRID'){map.setMapTypeId(google.maps.MapTypeId.HYBRID);}
if(jQuery(".type").val() == 'ROADMAP'){map.setMapTypeId(google.maps.MapTypeId.ROADMAP);}
if(jQuery(".type").val() == 'SATELLITE'){map.setMapTypeId(google.maps.MapTypeId.SATELLITE);}
if(jQuery(".type").val() == 'TERRAIN'){map.setMapTypeId(google.maps.MapTypeId.TERRAIN);}


var def = jQuery(".defaultstyles").val();
if(def == 'default'){
marker.setIcon(null)
}

   
} // END INIT()


</script>

<div id="map_preview"></div>
	
	
	
</form>
</div>
<?php
} 






function map_shortcode(){
?>

<script>


			var vrows = document.getElementsByName("map");
			var vrows2 = document.getElementsByName("map2");
			var vrows3 = document.getElementsByName("map3");
			var vrows4 = document.getElementsByName("map4");

			if (vrows.length == 0) {
document.write("<div id='scgm_styled_map' class='scgm_styled_map' name='map'></div>");
			}

			else if (vrows.length == 1 && vrows2.length == 0) {
document.write("<div id='scgm_styled_map2' class='scgm_styled_map' name='map2'></div>");
			}

			else if (vrows2.length == 1 && vrows3.length == 0) {
document.write("<div id='scgm_styled_map3' class='scgm_styled_map' name='map3'></div>");
			}

			else if (vrows3.length == 1 && vrows4.length == 0) {
document.write("<div id='scgm_styled_map4' class='scgm_styled_map' name='map4'></div>");
			}




</script>

<?php
}
add_shortcode('simple_custom_google_map', 'map_shortcode'); 

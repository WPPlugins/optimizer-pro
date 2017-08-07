<?php
/*
Plugin Name: Optimizer Pro
Plugin URI: http://optimizerpro.com/
Description: Optimizer Pro is the world's easiest to use A/B, split and multivariate testing tool. Simply enable the plugin and start running tests on your Wordpress website without doing any other code changes. Visit <a href="http://optimizerpro.com/">Optimizer Pro</a>.

This relies on the actions being present in the themes header.php and footer.php
* header.php code before the closing </head> tag
* 	wp_head();
*
*/

//------------------------------------------------------------------------//
//---Config---------------------------------------------------------------//
//------------------------------------------------------------------------//


$clhf_header_script_async = '
<!-- Start OptimizerPro Code -->
<script type=\'text/javascript\'>
 (function(){
  analyticskey = 1317047321;
  optimizerid = VWO_ID;
  var e = document.createElement(\'script\'); e.type=\'text/javascript\'; e.async = true;
  e.src = \'http://optimizerpro.com/ab_test/variation.js\';
  var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(e, s);
  
  var e = document.createElement(\'script\'); e.type=\'text/javascript\'; e.async = true;
  e.src = \'http://optimizerpro.com/ab_test/js/clickmap.js\';
  var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(e, s);
  
  var e = document.createElement(\'script\'); e.type=\'text/javascript\'; e.async = true;
  e.src = \'http://optimizerpro.com/ab_test/js/heatmap.js\';
  var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(e, s);
  
  var e = document.createElement(\'link\'); e.rel=\'stylesheet\'; e.type=\'text/css\';
  e.href = \'http://optimizerpro.com/ab_test/css/user-style.css\';
  var s = document.getElementsByTagName(\'link\')[0]; s.parentNode.insertBefore(e, s);
 })();
</script>
<!-- End OptimizerPro Code -->
';


//------------------------------------------------------------------------//
//---Hook-----------------------------------------------------------------//
//------------------------------------------------------------------------//
add_action ( 'wp_head', 'clhf_headercode',1 );
add_action( 'admin_menu', 'clhf_plugin_menu' );
add_action( 'admin_init', 'clhf_register_mysettings' );
add_action( 'admin_notices','clhf_warn_nosettings');


//------------------------------------------------------------------------//
//---Functions------------------------------------------------------------//
//------------------------------------------------------------------------//
// options page link
function clhf_plugin_menu() {
  add_options_page('Optimizer Pro', 'OptimizerPro', 'create_users', 'clhf_vwo_options', 'clhf_plugin_options');
}

// whitelist settings
function clhf_register_mysettings(){
	register_setting('clhf_vwo_options','vwo_id','intval');
	register_setting('clhf_vwo_options','code_type');
	register_setting('clhf_vwo_options','settings_tolerance','intval');
	register_setting('clhf_vwo_options','library_tolerance','intval');
}

//------------------------------------------------------------------------//
//---Output Functions-----------------------------------------------------//
//------------------------------------------------------------------------//
function clhf_headercode(){
	// runs in the header
	global $clhf_header_script_sync, $clhf_header_script_async;
	$vwo_id = get_option('vwo_id');
	$code_type = get_option('code_type');
	if($vwo_id){
		if($code_type == 'SYNC')
			echo str_replace('VWO_ID', $vwo_id, $clhf_header_script_sync); // only output if options were saved
		else {
			$settings_tolerance = get_option('settings_tolerance');
			if(!is_numeric($settings_tolerance)) $settings_tolerance = 2000;

			$library_tolerance = get_option('library_tolerance');
			if(!is_numeric($library_tolerance)) $library_tolerance = 1500;

			$clhf_header_script_async = str_replace('VWO_ID', $vwo_id, $clhf_header_script_async);
			$clhf_header_script_async = str_replace('SETTINGS_TOLERANCE', $settings_tolerance, $clhf_header_script_async);
			echo str_replace('LIBRARY_TOLERANCE', $library_tolerance, $clhf_header_script_async);
		}
	}
}
//------------------------------------------------------------------------//
//---Page Output Functions------------------------------------------------//
//------------------------------------------------------------------------//
// options page
function clhf_plugin_options() {
  echo '<div class="wrap">';?>
	<h2>Optimizer Pro</h2>
	<p>You need to have a <a href="http://optimizerpro.com/">Optimizer Pro</a> account in order to use this plugin. This plugin inserts the neccessary code into your Wordpress site automatically without you having to touch anything. In order to use the plugin, you need to enter your OptimizerPro Account ID:</p>
	<form method="post" action="options.php">
	<?php settings_fields( 'clhf_vwo_options' ); ?>
	<table class="form-table">
        <tr valign="top">
            <th scope="row">Your OptimizerPro Account ID</th>
            <td><input type="text" name="vwo_id" value="<?php echo get_option('vwo_id'); ?>" /></td>
        </tr>
		<tr valign="top">
	        <th scope="row">Code (By default)</th>
	        <td>
		        <input style="vertical-align: text-top;" type="radio" onclick="selectCodeType();" name="code_type" id="code_type_async" value="ASYNC" <?php if(get_option('code_type')!='SYNC') echo "checked"; ?> /> Asynchronous&nbsp;&nbsp;&nbsp;
		        &nbsp;<a href="http://optimizerpro.com/ab_test/video_demo.php" target="_blank">[Help]</a>
	        </td>
        </tr>
		<tr valign="top" id='asyncOnly1' <?php if(get_option('code_type')=='SYNC') echo "style='display:none;'" ?>>
	        <th scope="row">Settings Timeout</th>
			<td style="vertical-align: middle;"><input type="text" name="settings_tolerance" value="<?php echo get_option('settings_tolerance')?get_option('settings_tolerance'):2000; ?>" />ms  (default: 2000)</td>
	    </tr>
		<tr valign="top" id='asyncOnly2' <?php if(get_option('code_type')=='SYNC') echo "style='display:none;'" ?>>
	        <th scope="row">Library Timeout</th>
			<td style="vertical-align: middle;"><input type="text" name="library_tolerance" value="<?php echo get_option('library_tolerance')?get_option('library_tolerance'):1500; ?>" />ms  (default: 1500)</td>
	    </tr>
	</table>

	<script type="text/javascript">
		function selectCodeType() {
			var code_type = 'ASYNC';
			if(document.getElementById('code_type_sync').checked)
				code_type = 'SYNC';

			if(code_type == 'ASYNC') {
				document.getElementById('asyncOnly1').style.display = 'table-row';
				document.getElementById('asyncOnly2').style.display = 'table-row';
			}
			else {
				document.getElementById('asyncOnly1').style.display = 'none';
				document.getElementById('asyncOnly2').style.display = 'none';
			}
		}
	</script>
	<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
	<p>Your Account ID (a number) can be found in <i>Settings</i> area (top-right) after you <a href="http://optimizerpro.com/login.php">login</a> into your Optimizer Pro account.</p>
<?php
  echo '</div>';
}

function clhf_warn_nosettings(){
    if (!is_admin())
        return;

  $clhf_option = get_option("vwo_id");
  if (!$clhf_option || $clhf_option < 1){
    echo "<div id='vwo-warning' class='updated fade'><p><strong>Optimizer Pro is almost ready.</strong> You must <a href=\"options-general.php?page=clhf_vwo_options\">enter your Account ID</a> for it to work.</p></div>";
  }
}
?>
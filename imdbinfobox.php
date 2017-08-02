<?php
/*
Plugin Name: IMDB info box
Plugin URI: http://wordpress.org/extend/plugins/imdb-info-box/
Description: This plugin helps to add movie information (from IMDB) in wordpress post using shortcode [imdb id="imdbmovieid"].
Version: 1.2
Author: Sunny Verma
Author URI: http://99webtools.com
Released under the GNU General Public License (GPL)
http://www.gnu.org/licenses/gpl.txt
*/
define('IMDBINFOURL', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
define('IMDBINFOABSPATH', str_replace("\\","/", WP_PLUGIN_DIR . '/' . plugin_basename( dirname(__FILE__) ) . '/' ));
$upload_dir = wp_upload_dir();
define('IMDBCACHE',$upload_dir['basedir']."/imdbcache");
define('IMDBCACHEURL',$upload_dir['baseurl']."/imdbcache");
function file_get_contents_curl($url) {
    $ch = curl_init();
	curl_setopt($ch, CURLOPT_REFERER, 'http://www.imdb.com/');
	curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
function imdb_info_box($a,$c=null,$t){
extract(shortcode_atts( array(
		'id' => null,
		'plot' => 'short',
	), $a ));
	if(empty($id))
	{
	return '<b>No imdb id passed<b>';
	}
$info =	imdb_cache($id,$plot);
if($info['Response']=='True')
{
$out='<table id="imdbinfo">
  <tr>
    <th colspan="2" scope="col">'.$info['Title'].' ('.$info['Year'].')</th>
  </tr>
  <tr>
    <td id="imdbimg"><img src="'.$info['Poster'].'" alt="'.$info['Title'].' poster" /></td>
    <td><b>Rating:</b> '.$info['imdbRating'].'/10 ('.$info['imdbVotes'].' votes)<br><b>Director:</b> '.$info['Director'].'<br><b>Writer:</b> '.$info['Writer'].'<br><b>Stars:</b> '.$info['Actors'].'<br><b>Runtime:</b> '.$info['Runtime'].'<br><b>Rated:</b> '.$info['Rated'].'<br><b>Genre:</b> '.$info['Genre'].'<br><b>Released:</b> '.$info['Released'].'</td>
  </tr>
  <tr>
    <td colspan="2"><b>Plot:</b> '.$info['Plot'].'</td>
  </tr>
</table>';
}
else
{
$out='Error: '.$info['Error'];
}
return $out;
}
function imdb_cache($id,$plot)
{
$cacheage=get_option('imdbcacheage',-1);
$imagecache=IMDBCACHE."/".$id.".jpg";
$textcache=IMDBCACHE."/".$id.".txt";
if(!file_exists($textcache) || ($cacheage>-1 && filemtime($textcache)< (time()-$cacheage)))
{
$raw=file_get_contents_curl('http://www.omdbapi.com/?i='.$id.'&plot='.$plot);
$info=json_decode($raw,true);
file_put_contents($textcache,$raw);
}
else
{
$raw=file_get_contents($textcache);
$info=json_decode($raw,true);
}
if(isset($info['Poster'])&&$info['Poster']!='N/A')
{
if(!file_exists($imagecache) || ($cacheage>-1 && filemtime($imagecache)< (time()-$cacheage)))
{
$img=file_get_contents_curl($info['Poster']);
file_put_contents($imagecache,$img);
}
$info['Poster']=IMDBCACHEURL.'/'.$id.'.jpg';
}
else
{
$info['Poster']=IMDBINFOURL.'default.jpg';
}
return $info;
}
function imdb_info_stylesheet() {
        wp_register_style( 'imdb-info-style', site_url('/?imdbstyle=custom') );
		wp_enqueue_style('imdb-info-style');
    }
	function imdb_activate()
{
add_option('imdbcacheage',-1);
add_option('imdbheadbg','FFCC00');
add_option('imdbheadfg','FFFFFF');
add_option('imdbbodybg','F4F3D9');
add_option('imdbbodyfg','333333');
add_option('imdbcorner','5');
if (!is_dir(IMDBCACHE)) {
    if(!wp_mkdir_p(IMDBCACHE))
	 {
	 die("Unable to create cache directory in uploads folder. Please make sure uploads directory is writable");
	 }
    }
}
function imdb_deactivate()
{
delete_option('imdbcacheage');
delete_option('imdbheadbg');
delete_option('imdbheadfg');
delete_option('imdbbodybg');
delete_option('imdbbodyfg');
delete_option('imdbcorner');
}
function imdb_menu() {
add_options_page('IMDB infobox Settings', 'IMDB infobox Settings', 'manage_options',
	'imdb-infobox', 'imdb_admin_option');
	}
function imdb_admin_option(){
wp_enqueue_script('imdb-addmin-js', plugins_url('jscolor/jscolor.js', __FILE__));
$abscachedir=IMDBCACHE."/";
$files=glob($abscachedir."*");
$filecount=count($files);
?>
<div class="wrap">
		<h2>IMDB infobox Settings</h2>
        <form method="post" action="options.php">
<?php
 settings_fields('imdb_options');
?><table style="padding:5px;background:#CDEFF3">
  <tr>
    <td><strong>Heading Background Color</strong></td>
    <td><input class="color" value="<?php echo get_option('imdbheadbg'); ?>" name="imdbheadbg"  /></td>
  </tr>
  <tr>
    <td><strong>Heading Text Color</strong></td>
    <td><input class="color" value="<?php echo get_option('imdbheadfg'); ?>" name="imdbheadfg" /></td>
  </tr>
  <tr>
    <td><strong>Body Background Color</strong></td>
    <td><input class="color" value="<?php echo get_option('imdbbodybg'); ?>" name="imdbbodybg"  /></td>
  </tr>
  <tr>
    <td><strong>Body Text Color</strong></td>
    <td><input class="color" value="<?php echo get_option('imdbbodyfg'); ?>" name="imdbbodyfg" /></td>
  </tr>
    <tr>
    <td><strong>IMDB box corner radius</strong></td>
    <td><input type="number" value="<?php echo get_option('imdbcorner'); ?>" name="imdbcorner" /></td>
  </tr>
  <tr>
    <td><strong>Cache Age</strong></td>
    <td><input type="text" value="<?php echo get_option('imdbcacheage'); ?>" name="imdbcacheage" /><br>Set cache age in seconds (eg: 3600 for 1 hour) or -1 for never expire</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></td>
  </tr>
</table>
</form>
</div>
<?php
}

function imdb_setting()
{
        register_setting( 'imdb_options', 'imdbcacheage', 'intval');
		register_setting( 'imdb_options', 'imdbcorner', 'intval');
		register_setting( 'imdb_options', 'imdbheadbg');
		register_setting( 'imdb_options', 'imdbheadfg');
		register_setting( 'imdb_options', 'imdbbodybg');
		register_setting( 'imdb_options', 'imdbbodyfg');
}
function imdb_add_query_vars($query_vars) {
    $query_vars[] = 'imdbstyle';
    return $query_vars;
}
function imdb_include_custom_css() {
    $style = get_query_var('imdbstyle');

    if($style == 'custom') {
        include_once(IMDBINFOABSPATH.'imdbinfocss.php');
        exit;
    }
}
add_action('admin_init', 'imdb_setting' );
add_action('admin_menu', 'imdb_menu');
add_shortcode('imdb','imdb_info_box');
add_filter('query_vars', 'imdb_add_query_vars');
add_action('template_redirect', 'imdb_include_custom_css');
add_action( 'wp_enqueue_scripts', 'imdb_info_stylesheet' );
register_activation_hook( __FILE__, 'imdb_activate' );
register_deactivation_hook( __FILE__, 'imdb_deactivate' );
?>
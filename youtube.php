<?php
/**
 * Plugin Name: YouTube White Label Shortcode
 * Plugin URI: http://austinpassy.com/wordpress-plugins/youtube-white-label-shortcode/
 * Description: Use this plugin to show off videos hosted on YouTube&trade; without the YouTube&trade; logo overlay or controls. It's as easy as entering the video ID in a shortcode OR using the built in shortcode generator metabox in the post[-new].php page. <code>[youtube-white-label id=""]</code>.
 * Version: 0.2.3
 * Author: Austin &ldquo;Frosty&rdquo; Passy
 * Author URI: http://austinpassy.com
 * Text Domain: youtube-white-label
 *
 * Developers can learn more about the WordPress shortcode API:
 * @link http://codex.wordpress.org/Shortcode_API
 *
 * @copyright 2011
 * @author Austin Passy
 * @link http://austinpassy.com/2011/05/announcing-youtube-white-label-shortcode-plugin/
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @help http://scribu.net/wordpress/optimal-script-loading.html
 * @ref http://bavotasan.com/tutorials/jquery-to-resize-videos/#comment-26200
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package YouTube_White_Label_Shortcode
 */

if ( !function_exists( 'youtube_white_label_shortcode' ) ) {
	function youtube_white_label_shortcode() {
		$plugin = new YouTube_White_Label_Shortcode();
	}
	add_action( 'plugins_loaded', 'youtube_white_label_shortcode' );
}

if( !class_exists( 'YouTube_White_Label_Shortcode' ) ) {
	class YouTube_White_Label_Shortcode {
		
		static $white_label_script;
		const version = '0.2.3';
		const domain  = 'youtube-white-label';
		
		function YouTube_White_Label_Shortcode() {
			$this->__construct();
		}
		
		function __construct() {
			register_activation_hook( __FILE__, array( __CLASS__, 'activate' ) );
			
			add_action( 'extra_theme_headers', array( __CLASS__, 'extra_theme_headers' ) );
			add_action( 'init', array( __CLASS__, 'activate' ) );
			add_action( 'init', array( __CLASS__, 'locale' ) );
			
			add_action( 'admin_init', array( __CLASS__, 'admin_scripts' ) );
			add_action( 'wp_print_footer_scripts', array( __CLASS__, 'scripts' ) );
			//add_action( 'admin_init', array( __CLASS__, 'styles' ) );
			
			/* Add the post meta box creation function to the 'admin_menu' hook. */
			add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_box' ) );
			add_action( 'admin_menu', array( __CLASS__, 'add_meta_box' ) );
			
			add_shortcode( 'youtube-embed', array( __CLASS__, 'shortcode' ) );
			add_shortcode( self::domain, array( __CLASS__, 'shortcode' ) );
		}
		
		/**
		 * @since 0.1.2
		 */
		function extra_theme_headers( $headers ) {
			$headers['Domain'] = 'Text Domain';
		
			return $headers;
		}
		
		function activate() {
			define( 'YOUTUBE_WLS_DIR', plugin_dir_path( __FILE__ ) );
			define( 'YOUTUBE_WLS_ADMIN', plugin_dir_path( __FILE__ ) . '/admin' );
			
			$dashboard = get_option( 'remove_youtube_white_label_dashboard' );
			
			if ( is_admin() && ( empty( $dashboard ) && $dashboard != '1' )  ) {
				require_once( trailingslashit( YOUTUBE_WLS_ADMIN ) . 'dashboard.php' );
			}
		}
		
		function locale() {
			load_plugin_textdomain( self::domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}
		
		function admin_scripts() {
			global $pagenow;
			if ( ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) && !defined( 'IFRAME_REQUEST' ) )
				wp_enqueue_script( self::domain . '-admin', plugins_url( 'admin/js/admin.js', __FILE__ ), array( 'jquery' ), self::version, false );
		}
		
		function scripts() {
			if ( !self::$white_label_script )
				return;
				
			wp_enqueue_script( self::domain, plugins_url( 'js/youtube.js', __FILE__ ), array( 'jquery' ), self::version, false );
			wp_print_scripts( self::domain );
		}
		
		function styles() {
			global $pagenow;
			if ( $pagenow == 'post.php' || $pagenow == 'post-new.php' )
				wp_register_style( self::domain . '-admin', plugins_url( 'library/css/admin.css', __FILE__ ), false, self::version, 'screen' );
		}
		
		function plugin_data( $arg ) {
			$plugin = get_plugin_data( __FILE__ );
			
			return $plugin[$arg];
		}
	
		/**
		 * shortcode function
		 *
		 * @since 0.1
		 * @use [youtube-white-label id=""]
		 */
		function shortcode( $attr ) {
			global $content_width;
			
			extract( shortcode_atts( array(
				'url'				=> '',
				'id'				=> '',
				'height'			=> '',
				'width'				=> '',
				'branding'			=> '1',
				'autohide' 			=> '1',
				'autoplay' 			=> '',
				'controls' 			=> '0',
				'hd' 				=> '0',
				'rel' 				=> '0',
				'showinfo' 			=> '0',
				'thanks' 			=> '1',
				'autosize'			=> '1',
				'border'			=> '0',
				'cc'				=> '0',
				'colorone'			=> '',
				'colortwo'			=> '',
				'disablekb'			=> '',
				'fullscreen'		=> '0',
			), $attr ) );
			
			if ( !empty( $autosize ) && $autosize == '1' )				
				self::$white_label_script = true;
			
			$height	= str_replace( array( '%', 'em', 'px' ), '', $height );
			$width	= str_replace( array( '%', 'em', 'px' ), '', $width  );
			
			$iframe = '';
			if ( !empty( $id ) ) {
				$iframe  = '<p>';
				$iframe .= '<iframe id="' . self::domain . '" type="text/html" ';
				
				if ( $autosize == '1' )
					$iframe .= 'class="autosize" ';
					
				$iframe .= 'src="http://www.youtube.com/embed/' . esc_attr( $id ) . '?';
				
				/* Branding option must be first in line */
				if ( $branding != '' )
					$iframe .= '&amp;modestbranding=' . $branding;
					
				if ( $autohide != '' )
					$iframe .= '&amp;autohide=' . $autohide;
					
				if ( $autoplay != '' && $autoplay == '1' ) 
					$iframe .= '&amp;autoplay=' . $autoplay;
					
				if ( $controls != '' )
					$iframe .= '&amp;controls=' . $controls;
					
				if ( $hd != '' )
					$iframe .= '&amp;hd=' . $hd;
					
				if ( $rel != '' )
					$iframe .= '&amp;rel=' . $rel;
					
				if ( $showinfo == '1' )
					$iframe .= '&amp;showinfo=' . $showinfo;
					
				if ( $border != '' )
					$iframe .= '&amp;border=' . $border;
					
				if ( $cc != '' )
					$iframe .= '&amp;cc_load_policy=' . $cc;
					
				if ( $colorone != '' )
					$iframe .= '&amp;color1=' . $colorone;
					
				if ( $colortwo != '' )
					$iframe .= '&amp;color2=' . $colortwo;
					
				if ( $fullscreen != '' )
					$iframe .= '&amp;fullscreen=' . $fullscreen;
					
				if ( !empty( $disablekb ) && $disablekb != '0' )
					$iframe .= '&amp;disablekb=' . $disablekb;
					
				if ( $showinfo == '1' && $branding == '0' )
					$iframe .= '&amp;title=';
					
				$iframe .= '" ';
				
				$h = ( isset( $height ) && !empty( $height ) ) ? $height : ( $content_width / 1.77 );
				$w = ( isset( $width ) && !empty( $width ) ) ? $width : $content_width;
				
				$iframe .= 'style="border:0; height:' . esc_attr( absint( $h ) ) . 'px; width:' . esc_attr( absint( $w ) ) . 'px">';			
				$iframe .= '</iframe>';
				
				if ( empty( $thanks ) || $thanks == '1' )
					$iframe .= '<span class="white-label" style="display:none;visability:hidden"><a href="http://austinpassy.com/wordpress-plugins/youtube-white-label-shortcode" title="' . __( 'Powered by YouTube White Label Shortcode', self::domain ) . '" rel="bookmark">White Label</a></span>';
				
				$iframe .= '</p>';
			}			
			return $iframe;
		}

		/**
		 * Creates a meta box on the post (page, other post types) editing screen for allowing the easy input of 
		 * commonly-used post metadata.  The function uses the get_post_types() function for grabbing a list of 
		 * available post types and adding a new meta box for each post type.
		 *
		 * @uses get_post_types() Gets an array of post type objects.
		 * @uses add_meta_box() Adds a meta box to the post editing screen.
		 */
		function add_meta_box() {		
			/* Gets available public post types. */
			$post_types = get_post_types( array( 'public' => true ), 'objects' );
		
			/* For each available post type, create a meta box on its edit page if it supports '$prefix-post-settings'. */
			foreach ( $post_types as $type ) {
				/* Add the meta box. */
				add_meta_box( self::domain . "-{$type->name}-meta-box", __( 'YouTube Embed Shortcode Creator', self::domain ), array( __CLASS__, 'meta_box' ), $type->name, 'side', 'default' );
			}
		}
		
		/**
		 * Creates the settings for the post meta box.  
		 *
		 * @param string $type The post type of the current post in the post editor.
		 */
		function meta_box_args( $type = '' ) {
			$meta = array();
		
			/* If no post type is given, default to 'post'. */
			if ( empty( $type ) )
				$type = 'post';
			
			$true_false = array ( 'true' => '1', 'false' => '0' );
			
			$false_true = array ( 'false' => '0', 'true' => '1' );
			
			/* Options */
			$meta['YouTube'] = array( 'name' => '_YouTube_id', 'title' => __( 'Enter the YouTube ID:', self::domain ), 'type' => 'text', 
				'description' => __( '<small>http://www.youtube.com/watch?v=</small><strong>Rt3Kiq9NH_k</strong>', self::domain ) );
			
			$meta['height'] = array( 'name' => '_YouTube_height', 'title' => __( 'Height:', self::domain ), 'type' => 'text', 'width' => '90px', 
				'description' => __( 'Enter desired height. Example: <code>400</code>', self::domain ) );
			
			$meta['width'] = array( 'name' => '_YouTube_width', 'title' => __( 'Width:', self::domain ), 'type' => 'text', 'width' => '90px', 'value' => '640',
				'description' => __( 'Enter desired width. Example: <code>640</code>', self::domain ) );
			
			$meta['autohide'] = array( 'name' => '_YouTube_autohide', 'title' => __( 'Autohide:', self::domain ), 'type' => 'select', 'options' => $true_false, 'use_key_and_value' => true, 
				'description' => __( '', self::domain ) );
			
			$meta['autoplay'] = array( 'name' => '_YouTube_autoplay', 'title' => __( 'Autoplay:', self::domain ), 'type' => 'select', 'options' => $false_true, 'use_key_and_value' => true, 
				'description' => __( 'Should this video start playing automatically?', self::domain ) );
			
			$meta['controls'] = array( 'name' => '_YouTube_controls', 'title' => __( 'Controls:', self::domain ), 'type' => 'select', 'options' => $false_true, 'use_key_and_value' => true, 
				'description' => __( 'The video controls, seen at the bottom of the player.', self::domain ) );
			
			$meta['branding'] = array( 'name' => '_YouTube_branding', 'title' => __( 'YouTube Logo:', self::domain ), 'type' => 'select', 'options' => $true_false, 'use_key_and_value' => true, 
				'description' => __( 'Hide the YouTube&trade; logo?', self::domain ) );
			
			$meta['hd'] = array( 'name' => '_YouTube_hd', 'title' => __( 'High Def:', self::domain ), 'type' => 'select', 'options' => $true_false, 'use_key_and_value' => true, 
				'description' => __( 'Auto start in <abbr title="High Definition">HD</abbr>.', self::domain ) );
			
			$meta['rel'] = array( 'name' => '_YouTube_rel', 'title' => __( 'Related:', self::domain ), 'type' => 'select', 'options' => $false_true, 'use_key_and_value' => true, 
				'description' => __( 'Show realted videos at the end?', self::domain ) );
			
			$meta['showinfo'] = array( 'name' => '_YouTube_showinfo', 'title' => __( 'Showinfo:', self::domain ), 'type' => 'select', 'options' => $false_true, 'use_key_and_value' => true, 
				'description' => __( '', self::domain ) );
			
			$meta['thanks'] = array( 'name' => '_YouTube_thanks', 'title' => __( 'Thanks:', self::domain ), 'type' => 'select', 'options' => $true_false, 'use_key_and_value' => true, 
				'description' => __( 'Leave link to author (Hidden from public view).', self::domain ) );
			
			$meta['autosize'] = array( 'name' => '_YouTube_autosize', 'title' => __( 'Autosize:', self::domain ), 'type' => 'select', 'options' => $true_false, 'use_key_and_value' => true, 
				'description' => __( 'Include a jQuery file to &ldquo;autosize&rdquo; the video to fit the content?', self::domain ) );
			
			$meta['border'] = array( 'name' => '_YouTube_border', 'title' => __( 'Border:', self::domain ), 'type' => 'select', 'options' => $false_true, 'use_key_and_value' => true, 
				'description' => __( 'Give your player a colored border? (uses <code>color1</code> and <code>color2</code> options)', self::domain ) );
			
			$meta['cc_load_policy'] = array( 'name' => '_YouTube_cc', 'title' => __( 'Closed Captions:', self::domain ), 'type' => 'select', 'options' => $false_true, 'use_key_and_value' => true, 
				'description' => __( 'Setting to true will cause closed captions to be shown by default, even if the user has turned captions off.', self::domain ) );
			
			$meta['colorone'] = array( 'name' => '_YouTube_colorone', 'title' => __( 'Color1:', self::domain ), 'type' => 'text', 'width' => '90px', 'value' => '',
				'description' => __( 'Set your primary color.', self::domain ) );
			
			$meta['colortwo'] = array( 'name' => '_YouTube_colortwo', 'title' => __( 'Color2:', self::domain ), 'type' => 'text', 'width' => '90px', 'value' => '',
				'description' => __( 'Set your secondary color.', self::domain ) );
			
			$meta['disablekb'] = array( 'name' => '_YouTube_disablekb', 'title' => __( 'Disable Keyboard:', self::domain ), 'type' => 'select', 'options' => $true_false, 'use_key_and_value' => true, 
				'description' => __( 'Disable the keyboard shortcuts?', self::domain ) );
			
			$meta['fullscreen'] = array( 'name' => '_YouTube_fullscreen', 'title' => __( 'Fullscreen:', self::domain ), 'type' => 'select', 'options' => $true_false, 'use_key_and_value' => true, 
				'description' => __( 'Allow fullscreen button?', self::domain ) );
		
			return $meta;
		}
		
		/**
		 * Displays the post meta box on the edit post page. The function gets the various metadata elements
		 * from the meta_box_args() function. It then loops through each item in the array and
		 * displays a form element based on the type of setting it should be.
		 *
		 * @parameter object $object Post object that holds all the post information.
		 * @parameter array $box The particular meta box being shown and its information.
		 */
		function meta_box( $object, $box ) {
		
			$meta_box_options = self::meta_box_args( $object->post_type );
		
			foreach ( $meta_box_options as $option ) {
                if ( method_exists( 'YouTube_White_Label_Shortcode', "meta_box_{$option['type']}" ) )
                    call_user_func( array( __CLASS__, "meta_box_{$option['type']}" ), $option, get_post_meta( $object->ID, $option['name'], true ) );
            } ?>
			
			<p class="youtube-advanced-wrap" style="text-align:right"><a class="youtube-advanced" href="#"><?php _e( 'Advanced options', self::domain ); ?></a></p>
            
            <div id="youtube-advanced" class="hide-if-js"></div>
            
            <p class="output">
				<label for="_YouTube_output"><?php _e( 'Output:', self::domain ); ?></label>
				<br />
				<span id="_YouTube_output" class="postbox" style="display:block; min-height: 50px; padding: 5px;"></span>
			</p>
            
			<p class="howto" style="text-align:right"><a class="frosty" href="#"><?php _e( 'Like this plugin?, donate', self::domain ); ?></a></p>
			
			<div id="frosty" style="display:none;text-align:center">
				<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XQRHECLPQ46TE" style="text-decoration:none">
					<input type="button" class="primary button" value="DONATE" />
				</a>
			</div>
		
			<?php
		}
		
		/**
		 * Outputs a text input box with the given arguments for use with the post meta box.
		 *
		 * @param array $args 
		 * @param string|bool $value Custom field value.
		 */
		function meta_box_text( $args = array(), $value = false ) {
			$name = preg_replace( "/[^A-Za-z_-]/", '-', $args['name'] ); ?>
			<p>
				<label for="<?php echo $name; ?>"><?php echo $args['title']; ?></label>
				<br />
				<input type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo esc_attr( $value ); ?>" size="30" tabindex="30" style="width: <?php echo ( !empty( $args['width'] ) ? $args['width'] : '99%' ); ?>;" />
				<?php if ( !empty( $args['description'] ) ) echo '<br /><span class="howto">' . $args['description'] . '</span>'; ?>
			</p>
			<?php
		}
		
		/**
		 * Outputs a select box with the given arguments for use with the post meta box.
		 *
		 * @param array $args
		 * @param string|bool $value Custom field value.
		 */
		function meta_box_select( $args = array(), $value = false ) {
			$name = preg_replace( "/[^A-Za-z_-]/", '-', $args['name'] ); ?>
			<p>
				<label for="<?php echo $name; ?>"><?php echo $args['title']; ?></label>
				<?php if ( !empty( $args['sep'] ) ) echo '<br />'; ?>
				<select name="<?php echo $name; ?>" id="<?php echo $name; ?>" style="width:60px">
					<?php // echo '<option value=""></option>'; ?>
					<?php $i = 0; foreach ( $args['options'] as $option => $val ) { $i++; ?>
						<option value="<?php echo esc_attr( $val ); ?>" <?php selected( esc_attr( $value ), esc_attr( $val ) ); //if ( $i == 1 ) echo 'selected="selected"'; ?>><?php echo ( !empty( $args['use_key_and_value'] ) ? $option : $val ); ?></option>
					<?php } ?>
				</select>
				<?php if ( !empty( $args['description'] ) ) echo '<br /><span class="howto">' . $args['description'] . '</span>'; ?>
			</p>
			<?php
		}
		
	}
};

?>
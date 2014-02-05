<?php
/**
 * Plugin Name: YouTube White Label Shortcode
 * Plugin URI: http://austin.passy.co/wordpress-plugins/youtube-white-label-shortcode/
 * Description: Use this plugin to show off videos hosted on YouTube&trade; without the YouTube&trade; logo overlay or controls. It's as easy as entering the video ID in a shortcode OR using the built in shortcode generator metabox in the post[-new].php page. <code>[youtube-white-label id=""]</code>.
 * Version: 0.3
 * Author: Austin &ldquo;Frosty&rdquo; Passy
 * Author URI: http://austin.passy.co/
 * Text Domain: youtube-white-label
 *
 * Developers can learn more about the WordPress shortcode API:
 * @link http://codex.wordpress.org/Shortcode_API
 *
 * @copyright 2011-2014
 * @author Austin Passy
 * @link 	http://austin.passy.co/2011/05/announcing-youtube-white-label-shortcode-plugin/
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @help 	http://scribu.net/wordpress/optimal-script-loading.html
 * @ref 	http://bavotasan.com/tutorials/jquery-to-resize-videos/#comment-26200
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package YouTube_White_Label_Shortcode
 */

if ( !class_exists( 'YouTube_White_Label_Shortcode' ) ) :

	class YouTube_White_Label_Shortcode {		
		
		/** Singleton *************************************************************/
		private static $instance;
		
		public static $white_label_script;
		
		const VERSION = '0.3';
		const DOMAIN  = 'youtube-white-label';
	
		/**
		 * Main engrade_core Instance
		 *
		 * @staticvar array $instance
		 * @see GrubHub()
		 * @return The one true engrade_core
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
				self::$instance->init();
			}
			return self::$instance;
		}
		
		function __construct() {}
		
		function init() {
			add_action( 'init', 					array( $this, 'locale' ) );
			
			add_action( 'admin_enqueue_scripts', 	array( $this, 'enqueue_scripts' ) );
			add_action( 'wp_print_footer_scripts', 	array( $this, 'scripts' ) );
			
			/* Add the post meta box creation function to the 'admin_menu' hook. */
			add_action( 'add_meta_boxes', 			array( $this, 'add_meta_box' ) );
			add_action( 'admin_menu', 				array( $this, 'add_meta_box' ) );
			
			add_shortcode( 'youtube-white-label',	array( $this, 'shortcode' ) );
			
			define( 'YOUTUBE_WLS_DIR',				plugin_dir_path( __FILE__ ) );
			define( 'YOUTUBE_WLS_ADMIN',			trailingslashit( plugin_dir_path( __FILE__ ) ) . 'admin' );
			
			$dashboard = get_option( 'remove_youtube_white_label_dashboard' );
			
			if ( is_admin() && ( empty( $dashboard ) && $dashboard !== '1' )  ) {
				require_once( trailingslashit( YOUTUBE_WLS_ADMIN ) . 'dashboard.php' );
			}
		}
		
		function locale() {
			load_plugin_textdomain( self::DOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}
		
		function enqueue_scripts() {
			global $pagenow;
			if ( ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) && !defined( 'IFRAME_REQUEST' ) ) {
				wp_enqueue_script( self::DOMAIN . '-admin', plugins_url( 'admin/js/admin.js', __FILE__ ), array( 'jquery' ), self::VERSION, false );
			}
		}
		
		function scripts() {
			if ( !self::$white_label_script )
				return;
				
			wp_enqueue_script( self::DOMAIN, plugins_url( 'js/youtube.js', __FILE__ ), array( 'jquery' ), self::VERSION, false );
			wp_print_scripts( self::DOMAIN );
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
			
			static $counter = 1;
			
			$iframe = '';
			
			if ( !empty( $id ) ) {
				
				$iframe .= '<iframe id="' . self::DOMAIN . '-' . absint( $counter ) . '" type="text/html" ';
				
				if ( !empty( $autosize ) && $autosize == '1' )
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
				
				$height = ( isset( $height ) && ( !empty( $height ) || $height != '' ) ) ? $height : ( $content_width / 1.77 );
				$width = ( isset( $width ) && ( !empty( $width ) || $width != '' ) ) ? $width : $content_width;
				
				$iframe .= 'style="border:0; height:' . esc_attr( absint( $height ) ) . 'px; width:' . esc_attr( absint( $width ) ) . 'px">';			
				$iframe .= '</iframe>';
				
				if ( isset( $thanks ) && ( empty( $thanks ) || $thanks != '0' ) )
					$iframe .= '<span class="white-label" style="display:none;visability:hidden"><a href="http://austinpassy.com/wordpress-plugins/youtube-white-label-shortcode" title="' . __( 'Powered by YouTube White Label Shortcode', self::DOMAIN ) . '" rel="bookmark">White Label</a></span>';
					
				$counter++;				
			}			
			return wpautop( $iframe );
		}
		
		function strip_url( $url ) {
			$id_match = '[0-9a-zA-Z\-_]+';
			if ( preg_match( '|https?://(www\.)?youtube\.com/(watch)?\?.*v=(' . $id_match . ')|', $url, $matches ) )
				$id = $matches[3];
			else if ( preg_match( '|https?://(www\.)?youtube(-nocookie)?\.com/embed/(' . $id_match . ')|', $url, $matches ) )
				$id = $matches[3];
			else if ( preg_match( '|https?://(www\.)?youtube\.com/v/(' . $id_match . ')|', $url, $matches ) )
				$id = $matches[2];
			else if ( preg_match( '|http://youtu\.be/(' . $id_match . ')|', $url, $matches ) )
				$id = $matches[1];
			else if ( !preg_match( '|^http|', $url, $matches ) )
				$id = $url;
			
			return $id;
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
			
			$post_types = apply_filters( 'remove_youtube_white_label_meta_box', $post_types );
			
			/* For each available post type, create a meta box on its edit page if it supports '$prefix-post-settings'. */
			foreach ( $post_types as $type ) {
				/* Add the meta box. */
				add_meta_box( self::DOMAIN . "-{$type->name}-meta-box", __( 'YouTube Embed Shortcode Creator (does not save meta)', self::DOMAIN ), array( $this, 'meta_box' ), $type->name, 'side', 'default' );
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
			$meta['YouTube'] = array( 'name' => '_YouTube_id', 'title' => __( 'Enter the YouTube ID:', self::DOMAIN ), 'type' => 'text', 
				'description' => __( '<small>http://www.youtube.com/watch?v=</small><strong>EObNvruiyR4</strong>', self::DOMAIN ) );
			
			$meta['height'] = array( 'name' => '_YouTube_height', 'title' => __( 'Height:', self::DOMAIN ), 'type' => 'text', 'width' => '90px', 
				'description' => __( 'Enter desired height. Example: <code>400</code>', self::DOMAIN ) );
			
			$meta['width'] = array( 'name' => '_YouTube_width', 'title' => __( 'Width:', self::DOMAIN ), 'type' => 'text', 'width' => '90px', 'value' => '640',
				'description' => __( 'Enter desired width. Example: <code>640</code>', self::DOMAIN ) );
			
			$meta['autohide'] = array( 'name' => '_YouTube_autohide', 'title' => __( 'Autohide:', self::DOMAIN ), 'type' => 'select', 'options' => $true_false, 'use_key_and_value' => true, 
				'description' => __( '', self::DOMAIN ) );
			
			$meta['autoplay'] = array( 'name' => '_YouTube_autoplay', 'title' => __( 'Autoplay:', self::DOMAIN ), 'type' => 'select', 'options' => $false_true, 'use_key_and_value' => true, 
				'description' => __( 'Should this video start playing automatically?', self::DOMAIN ) );
			
			$meta['controls'] = array( 'name' => '_YouTube_controls', 'title' => __( 'Controls:', self::DOMAIN ), 'type' => 'select', 'options' => $false_true, 'use_key_and_value' => true, 
				'description' => __( 'The video controls, seen at the bottom of the player.', self::DOMAIN ) );
			
			$meta['branding'] = array( 'name' => '_YouTube_branding', 'title' => __( 'YouTube Logo:', self::DOMAIN ), 'type' => 'select', 'options' => $true_false, 'use_key_and_value' => true, 
				'description' => __( 'Hide the YouTube&trade; logo?', self::DOMAIN ) );
			
			$meta['hd'] = array( 'name' => '_YouTube_hd', 'title' => __( 'High Def:', self::DOMAIN ), 'type' => 'select', 'options' => $true_false, 'use_key_and_value' => true, 
				'description' => __( 'Auto start in <abbr title="High Definition">HD</abbr>.', self::DOMAIN ) );
			
			$meta['rel'] = array( 'name' => '_YouTube_rel', 'title' => __( 'Related:', self::DOMAIN ), 'type' => 'select', 'options' => $false_true, 'use_key_and_value' => true, 
				'description' => __( 'Show realted videos at the end?', self::DOMAIN ) );
			
			$meta['showinfo'] = array( 'name' => '_YouTube_showinfo', 'title' => __( 'Showinfo:', self::DOMAIN ), 'type' => 'select', 'options' => $false_true, 'use_key_and_value' => true, 
				'description' => __( '', self::DOMAIN ) );
			
			$meta['thanks'] = array( 'name' => '_YouTube_thanks', 'title' => __( 'Thanks:', self::DOMAIN ), 'type' => 'select', 'options' => $true_false, 'use_key_and_value' => true, 
				'description' => __( 'Leave link to author (Hidden from public view).', self::DOMAIN ) );
			
			$meta['autosize'] = array( 'name' => '_YouTube_autosize', 'title' => __( 'Autosize:', self::DOMAIN ), 'type' => 'select', 'options' => $true_false, 'use_key_and_value' => true, 
				'description' => __( 'Include a jQuery file to &ldquo;autosize&rdquo; the video to fit the content?', self::DOMAIN ) );
			
			$meta['border'] = array( 'name' => '_YouTube_border', 'title' => __( 'Border:', self::DOMAIN ), 'type' => 'select', 'options' => $false_true, 'use_key_and_value' => true, 
				'description' => __( 'Give your player a colored border? (uses <code>color1</code> and <code>color2</code> options)', self::DOMAIN ) );
			
			$meta['cc_load_policy'] = array( 'name' => '_YouTube_cc', 'title' => __( 'Closed Captions:', self::DOMAIN ), 'type' => 'select', 'options' => $false_true, 'use_key_and_value' => true, 
				'description' => __( 'Setting to true will cause closed captions to be shown by default, even if the user has turned captions off.', self::DOMAIN ) );
			
			$meta['colorone'] = array( 'name' => '_YouTube_colorone', 'title' => __( 'Color1:', self::DOMAIN ), 'type' => 'text', 'width' => '90px', 'value' => '',
				'description' => __( 'Set your primary color.', self::DOMAIN ) );
			
			$meta['colortwo'] = array( 'name' => '_YouTube_colortwo', 'title' => __( 'Color2:', self::DOMAIN ), 'type' => 'text', 'width' => '90px', 'value' => '',
				'description' => __( 'Set your secondary color.', self::DOMAIN ) );
			
			$meta['disablekb'] = array( 'name' => '_YouTube_disablekb', 'title' => __( 'Disable Keyboard:', self::DOMAIN ), 'type' => 'select', 'options' => $true_false, 'use_key_and_value' => true, 
				'description' => __( 'Disable the keyboard shortcuts?', self::DOMAIN ) );
			
			$meta['fullscreen'] = array( 'name' => '_YouTube_fullscreen', 'title' => __( 'Fullscreen:', self::DOMAIN ), 'type' => 'select', 'options' => $true_false, 'use_key_and_value' => true, 
				'description' => __( 'Allow fullscreen button?', self::DOMAIN ) );
		
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
                    call_user_func( array( $this, "meta_box_{$option['type']}" ), $option, get_post_meta( $object->ID, $option['name'], true ) );
            } ?>
			
			<p class="youtube-advanced-wrap" style="text-align:right"><a class="youtube-advanced" href="#"><?php _e( 'Advanced options', self::DOMAIN ); ?></a></p>
            
            <div id="youtube-advanced" class="hide-if-js"></div>
            
            <p class="output">
				<label for="_YouTube_output"><?php _e( 'Output:', self::DOMAIN ); ?></label>
				<br />
				<span id="_YouTube_output" class="postbox" style="display:block; min-height: 50px; padding: 5px;"></span>
			</p>
            
			<!--<a id="youtube-send-to-content" href="#"><?php _e( 'send to content', self::DOMAIN ); ?></a>-->
                
            <div id="youtube-colorpicker"></div>
            
            <?php printf( __( 'Like this plugin? <a href="%s" target="_blank">Buy me a beer</a>!', self::DOMAIN ), 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XQRHECLPQ46TE' ); ?></p>
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

	/**
	 * The main function responsible for returning the one true 
	 * Instance to functions everywhere.
	 *
	 * Use this function like you would a global variable, except without needing
	 * to declare the global.
	 *
	 * Example: <?php $youtube = YOUTUBE_WHITE_LABEL_SHORTCODE(); ?>
	 *
	 * @return The one true Instance
	 */
	function YOUTUBE_WHITE_LABEL_SHORTCODE() {
		return YouTube_White_Label_Shortcode::instance();
	}
	
	// Starts EXNTEDD_CORE running
	add_action( 'plugins_loaded', 'YOUTUBE_WHITE_LABEL_SHORTCODE' );

endif;
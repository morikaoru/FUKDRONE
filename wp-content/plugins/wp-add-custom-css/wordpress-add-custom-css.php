<?php
/*
Plugin Name: WP Add Custom CSS
Plugin URI: http://www.danieledesantis.net
Description: Add custom css to the whole website and to specific posts, pages and custom post types.
Version: 1.1.6
Author: Daniele De Santis
Author URI: http://www.danieledesantis.net
Text Domain: wp-add-custom-css
Domain Path: /languages/
License: GPL2
*/

/*
Copyright 2014-2019  Daniele De Santis  (email : hello@danieledesantis.net)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!defined('ABSPATH')) die ('No direct access allowed');

if(!class_exists('Wpacc'))
{
    class Wpacc
    {
		private $options;

		public function __construct() {
      		add_action('admin_menu', array($this, 'add_menu'));
    		add_action( 'admin_init', array( $this, 'init_settings' ) );
      		add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts' ) );
			add_action( 'add_meta_boxes', array($this, 'add_meta_box' ) );
			add_action( 'save_post', array( $this, 'single_save' ) );
			add_action('init', array($this, 'init'));
			add_filter('query_vars', array($this, 'add_wp_var'));
			add_action( 'wp_enqueue_scripts', array($this, 'add_custom_css'), 9999 );
			add_action('wp_head', array($this, 'single_custom_css'), 99 );
		}

		public function init() {
			load_plugin_textdomain( 'wp-add-custom-css', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

	    private function is_enabled_post_type( $post_type = false ) {
	      if ( ! $post_type ) {
	        global $post;
	        $post_type = $post->post_type;
	      }
	      $enabled_post_types = array('post', 'page');
	      $this->options = get_option( 'wpacc_settings' );
	      if ( isset($this->options['selected_post_types']) ) {
	        $enabled_post_types = array_merge( $enabled_post_types, $this->options['selected_post_types'] );
	      }
	      return in_array( $post_type, $enabled_post_types );
	    }

	    public function load_admin_scripts( $hook ) {
	      if ( ( in_array( $hook, array('post.php', 'post-new.php') ) && $this->is_enabled_post_type() ) || $hook === 'toplevel_page_wp-add-custom-css_settings' ) {
	        $this->options = get_option( 'wpacc_settings' );
	        if ( isset($this->options['enable_advanced_editor']) ) {
	        	if ( function_exists( 'vc_is_inline' ) && vc_is_inline() ) return; // fix issues with WPBakery Frontend Editor
    			$wp_scripts = wp_scripts();
			    wp_enqueue_style(
			      'jquery-ui-theme-smoothness',
			      sprintf(
			        '//ajax.googleapis.com/ajax/libs/jqueryui/%s/themes/smoothness/jquery-ui.css',
			        $wp_scripts->registered['jquery-ui-core']->ver
			      )
			    );
    			wp_enqueue_script('jquery-ui-resizable');
      			wp_enqueue_style( 'wpacc_ace', plugin_dir_url( __FILE__ ) . 'lib/ace/ace-custom.css' );
      			wp_enqueue_script( 'wpacc_ace', plugin_dir_url( __FILE__ ) . 'lib/ace/ace.js');
      			wp_enqueue_script( 'wpacc_scripts_ace', plugin_dir_url( __FILE__ ) . 'js/scripts-ace.js', array('jquery', 'jquery-ui-resizable', 'wpacc_ace') );
	  		}
	      }
	    }

		public function add_meta_box( $post_type ) {
    	if ( $this->is_enabled_post_type($post_type) ) {
				add_meta_box('wp_add_custom_css', __( 'Custom CSS', 'wp-add-custom-css' ), array( $this, 'render_meta_box_content' ), $post_type, 'advanced', 'high');
			}
		}

		public function single_save( $post_id ) {
			if ( ! isset( $_POST['wp_add_custom_css_box_nonce'] ) || ! wp_verify_nonce( $_POST['wp_add_custom_css_box_nonce'], 'single_add_custom_css_box' ) ) {
				return;
			}
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}
			if ( 'page' == $_POST['post_type'] ) {
				if ( ! current_user_can( 'edit_page', $post_id ) )
					return;
			} else {
				if ( ! current_user_can( 'edit_post', $post_id ) )
					return;
			}

			$single_custom_css = wp_kses( $_POST['single_custom_css'], array( '\'', '\"' ) );
			update_post_meta( $post_id, '_single_add_custom_css', $single_custom_css );
		}

		public function render_meta_box_content( $post ) {
			wp_nonce_field( 'single_add_custom_css_box', 'wp_add_custom_css_box_nonce' );
	  		$single_custom_css = get_post_meta( $post->ID, '_single_add_custom_css', true );
	  		$rtl_style = is_rtl() ? ' direction: ltr;' : '';
      		$class = ( isset($this->options['advanced_editor_theme']) && $this->options['advanced_editor_theme'] === 'dark' ) ? ' class="wpacc_editor_dark"' : '';
			echo '<p>'.  sprintf( __( 'Add custom CSS rules for this %s', 'wp-add-custom-css' ), $post->post_type ). '</p> ';
			echo '<div class="wpacc_editor_container">';
			echo '<textarea id="single_custom_css" name="single_custom_css" style="width:100%; min-height:300px;' . $rtl_style . '"' . $class . '>' . esc_attr( $single_custom_css ) . '</textarea>';
			echo '<div id="single_custom_css_ace" class="custom_css_ace"></div>';
			echo '</div>';
		}

		public function add_menu() {
			global $wpacc_settings_page;
			$wpacc_settings_page = add_menu_page( __('Wordpress Add Custom CSS', 'wp-add-custom-css'), __('Add Custom CSS', 'wp-add-custom-css'), 'manage_options', 'wp-add-custom-css_settings', array($this, 'create_settings_page'), plugin_dir_url( __FILE__ ) . 'images/icon.png');
		}

		public function create_settings_page() {
			$this->options = get_option( 'wpacc_settings' );
			?>
			<div class="wrap">
      	<h2><?php echo __('Wordpress Add Custom CSS', 'wp-add-custom-css'); ?></h2>
        <form id="worpress_custom_css_form" method="post" action="options.php">
        <?php settings_fields( 'wpacc_group' ); ?>
        <?php do_settings_sections( 'wp-add-custom-css_settings' ); ?>
				<?php submit_button( __('Save', 'wp-add-custom-css') ); ?>
				</form>
				<h3><?php echo __('Donations', 'wp-add-custom-css'); ?></h3>
				<p><?php echo __('Help me mantaining WP Add Custom CSS by making a donation:', 'wp-add-custom-css'); ?></p>
				<p><?php echo __('If you have a PayPal account use', 'wp-add-custom-css'); ?> <strong><?php echo __('paypal.me (it has lower fees):', 'wp-add-custom-css'); ?></strong></p>
				<a href="https://paypal.me/dandesantis" target="_blank" rel="nofollow" style="padding: 2px 6px;"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/btn_donate.gif'; ?>"></a>
				<br>
				<p><?php echo __('If you don\'t have a PayPal account use this PayPal donate button:', 'wp-add-custom-css'); ?></p>
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
					<input type="hidden" name="cmd" value="_s-xclick" />
					<input type="hidden" name="hosted_button_id" value="LKSYYTM9GRMEL" />
					<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
					<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
				</form>
				<br>
				<h3><?php echo __('Credits', 'wp-add-custom-css'); ?></h3>
				<ul>
					<li><?php echo __('If you like "WP Add Custom CSS" leave a', 'wp-add-custom-css'); ?> <a href="https://wordpress.org/support/plugin/wp-add-custom-css/reviews/?filter=5" target="_blank" title="Daniele De Santis"><?php echo __('5 star review,', 'wp-add-custom-css'); ?></a> <?php echo __('thank you!', 'wp-add-custom-css'); ?></li>
					<li><?php echo __('"WP Add Custom CSS" is a plugin by', 'wp-add-custom-css'); ?> <a href="http://www.danieledesantis.net/" target="_blank" title="Daniele De Santis">Daniele De Santis</a></li>
				</ul>
			</div>
      <?php
		}

		public function print_section_info() {
			echo __('Write here the CSS rules you want to apply to the whole website.', 'wp-add-custom-css');
    	}

		public function main_css_input() {
    	$custom_rules = isset( $this->options['main_custom_style'] ) ? esc_attr( $this->options['main_custom_style'] ) : '';
    	$class = ( isset($this->options['advanced_editor_theme']) && $this->options['advanced_editor_theme'] === 'dark' ) ? ' class="wpacc_editor_dark"' : '';
    	$rtl_style = is_rtl() ? ' direction: ltr;' : '';
      		echo '<div class="wpacc_editor_container">';
			echo '<textarea id="main_custom_css" name="wpacc_settings[main_custom_style]" style="width:100%; min-height:300px;' . $rtl_style . '"' . $class . '>' . $custom_rules . '</textarea>';
			echo '<div id="main_custom_css_ace" class="custom_css_ace"></div>';
			echo '</div>';
    	}

		public function print_section_2_info() {
			echo __('Enable page specific CSS for the post types below.', 'wp-add-custom-css');
    	}

		public function post_types_checkboxes() {
			$available_post_types = get_post_types( array('public' => true, '_builtin' => false), 'objects' );
			foreach ( $available_post_types as $post_type ) {
				if ( isset( $this->options['selected_post_types'] ) ) {
					$checked = in_array( $post_type->name, $this->options['selected_post_types'] ) ? ' checked' : '';
				} else {
					$checked = '';
				}
				echo '<div style="margin-bottom:10px"><input type="checkbox" name="wpacc_settings[selected_post_types][]" value="' . $post_type->name . '"' . $checked . '>' . $post_type->label . '</div>'; // output checkbox
			}
    	}

	    public function print_section_3_info() {
				echo __('Enable advanced css editor, including line numbers and code coloring.', 'wp-add-custom-css');
	    }

	    public function advanced_editor_checkbox() {
				if ( isset( $this->options['enable_advanced_editor'] ) ) {
					$checked = ' checked';
				} else {
					$checked = '';
				}
				echo '<div style="margin-bottom:10px"><input type="checkbox" name="wpacc_settings[enable_advanced_editor]" value="true"' . $checked . '></div>'; // output checkbox
	    }

	    public function advanced_editor_select() {
	      echo '<div style="margin-bottom:10px"><select name="wpacc_settings[advanced_editor_theme]">';
	      $available_themes = array( 'dark' => __('Dark', 'wp-add-custom-css'), 'light' => __('Light', 'wp-add-custom-css') );
	      foreach ( $available_themes as $theme_value => $theme_name ) {
	  			if ( isset( $this->options['advanced_editor_theme'] ) ) {
	          $selected = ( $theme_value === $this->options['advanced_editor_theme'] ) ? ' selected' : '';
	  			} else {
	  				$selected = '';
	  			}
	        echo '<option value="' . $theme_value . '"' . $selected . '>' . $theme_name . '</option>';
	      }
				echo '</select></div>';
	    }

		public function init_settings() {
			register_setting(
				'wpacc_group',
				'wpacc_settings'
			);
			add_settings_section(
					'wpacc_main_style',
					__('Main CSS', 'wp-add-custom-css'),
					array( $this, 'print_section_info' ),
					'wp-add-custom-css_settings'
			);
			add_settings_field(
					'main_custom_style',
					__('CSS rules', 'wp-add-custom-css'),
					array( $this, 'main_css_input' ),
					'wp-add-custom-css_settings',
					'wpacc_main_style'
			);
			add_settings_section(
					'wpacc_post_types',
					__('Post types', 'wp-add-custom-css'),
					array( $this, 'print_section_2_info' ),
					'wp-add-custom-css_settings'
			);
			add_settings_field(
					'selected_post_types',
					__('Available post types', 'wp-add-custom-css'),
					array( $this, 'post_types_checkboxes' ),
					'wp-add-custom-css_settings',
					'wpacc_post_types'
			);
      		add_settings_section(
					'wpacc_advanced_editor',
					__('Advanced editor', 'wp-add-custom-css'),
					array( $this, 'print_section_3_info' ),
					'wp-add-custom-css_settings'
			);
			add_settings_field(
					'enable_advanced_editor',
					__('Enable', 'wp-add-custom-css'),
					array( $this, 'advanced_editor_checkbox' ),
					'wp-add-custom-css_settings',
					'wpacc_advanced_editor'
			);
      		add_settings_field(
					'advanced_editor_theme',
					__('Advanced editor layout', 'wp-add-custom-css'),
					array( $this, 'advanced_editor_select' ),
					'wp-add-custom-css_settings',
					'wpacc_advanced_editor'
			);
		}

		public static function add_wp_var($public_query_vars) {
    		$public_query_vars[] = 'display_custom_css';
    		return $public_query_vars;
		}

		public static function display_custom_css(){
	    	$display_css = get_query_var('display_custom_css');
	    	if ($display_css == 'css'){
					include_once (plugin_dir_path( __FILE__ ) . '/css/custom-css.php');
	      	exit;
	    	}
		}

		public function add_custom_css() {
			$this->options = get_option( 'wpacc_settings' );
			if ( isset($this->options['main_custom_style']) && $this->options['main_custom_style'] != '') {
				if ( function_exists('icl_object_id') ) {
					$css_base_url = site_url();
					if ( is_ssl() ) {
						$css_base_url = site_url('/', 'https');
					}
				} else {
					$css_base_url = get_bloginfo('url');
					if ( is_ssl() ) {
						$css_base_url = str_replace('http://', 'https://', $css_base_url);
					}
				}
				wp_register_style( 'wp-add-custom-css', $css_base_url . '?display_custom_css=css' );
				wp_enqueue_style( 'wp-add-custom-css' );
			}
		}

		public function single_custom_css() {
			if ( is_single() || is_page() ) {
	        	if ( ! $this->is_enabled_post_type() ) {
					return;
				}
        		global $post;
				$single_custom_css = get_post_meta( $post->ID, '_single_add_custom_css', true );
				if ( $single_custom_css !== '' ) {
					$single_custom_css = str_replace ( '&gt;' , '>' , $single_custom_css );
					$output = "<style type=\"text/css\">\n" . $single_custom_css . "\n</style>\n";
					echo $output;
				}
			}
		}


    }
}

if(class_exists('Wpacc')) {
	add_action('template_redirect', array('Wpacc', 'display_custom_css'));
	$wpacc = new Wpacc();
}

if(isset($wpacc)) {
    function wpacc_settings_link($links) {
        $settings_link = '<a href="admin.php?page=wp-add-custom-css_settings">' . __('Settings', 'wp-add-custom-css') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
    add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wpacc_settings_link');
}
?>
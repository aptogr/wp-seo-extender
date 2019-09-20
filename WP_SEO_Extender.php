<?php

/**
 * A class that extends the functionalities of WordPress
 * SEO Plugins to add custom meta Titles and meta Descriptions
 * for custom post types and taxonomies
 *
 * SEO WP Plugins Supported: 'All in One SEO Pack', 'YOAST SEO'
 *
 * @since      1.0.0
 * @author     APTO <info@apto.gr>
 */
class WP_SEO_Extender {


	/**
	 * $seo_plugin is responsible for indexing the current active
	 * SEO plugin of the WordPress website.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string|bool  $seo_plugin
	 */
	protected $seo_plugin;



	/**
	 * An array with all the supported SEO Plugins
	 *
	 */
	const SEO_PLUGINS = array(
		//All in one Seo Pack
		'all-in-one-seo-pack' => array(
			'plugin'        => 'all-in-one-seo-pack/all_in_one_seo_pack.php',
			'title-filter'  => 'aioseop_title',
			'desc-filter'   => 'aioseop_description',
			'title-meta'    => '_aioseop_title',
			'desc-meta'     => '_aioseop_description',
		),
		//Yoast
		'wordpress-seo' => array(
			'plugin'        => array( 'wordpress-seo/wp-seo.php', 'wordpress-seo-premium/wp-seo-premium.php' ),
			'title-filter'  => 'wpseo_title',
			'desc-filter'   => 'wpseo_metadesc',
		),
	);



	/**
	 * Initialize the class and its properties
	 *
	 * @return   void
	 * @since    1.0.0
	 * @access   public
	 */
	public function __construct() {

		$this->seo_plugin = $this->get_active_seo_plugin();

	}



	/**
	 * Registers the filter hooks
	 * The name of the hooks are dynamic based on the
	 * current active SEO Plugin
	 *
	 * @return   void
	 * @since    1.0.0
	 * @access   public
	 */
	public function register_hooks(){

		add_filter( self::SEO_PLUGINS[ $this->seo_plugin ]['title-filter'], array( $this, 'change_seo_title' ), 10, 1 );
		add_filter( self::SEO_PLUGINS[ $this->seo_plugin ]['desc-filter'], array( $this, 'change_seo_description' ), 10, 1 );

	}



	/**
	 * Gets the active SEO plugin of the current
	 * WordPress website
	 *
	 * @return   string | bool
	 * @since    1.0.0
	 * @access   private
	 */
	private function get_active_seo_plugin(){

		$plugin = false;

		include_once(ABSPATH.'wp-admin/includes/plugin.php');

		foreach( self::SEO_PLUGINS as $plugin_id => $plugin_info ){

			if( isset( $plugin_info['plugin'] ) ){

				if( is_array( $plugin_info['plugin'] ) ){

					foreach( $plugin_info['plugin'] as $version ){

						if( is_plugin_active( $version ) ) {

							$plugin = $plugin_id;

						}
					}

				}
				else {

					if( is_plugin_active( $plugin_info['plugin'] ) ){

						$plugin = $plugin_id;
					}

				}

			}

		}

		return $plugin;

	}



	/**
	 * Changes the SEO Title of a post or a taxonomy
	 *
	 * @param    $title string
	 * @return   string
	 * @since    1.0.0
	 * @access   private
	 */
	public function change_seo_title( $title ){

		$object         = get_queried_object();
		$custom_title   = $this->get_custom_seo_title();

		if( $custom_title ) {

			if ( is_singular() ) {

				$default_title = get_post_meta( $object->ID, self::SEO_PLUGINS[ $this->seo_plugin ]['title-meta'], true );
				$title         = ( ! empty( $default_title ) ? $default_title : $custom_title );

			}
			else if ( is_tax() ) {

				$title = $custom_title;

			}

		}

		return $title;

	}



	/**
	 * Changes the SEO Description of a post or a taxonomy
	 *
	 * @param    $description string
	 * @return   string
	 * @since    1.0.0
	 * @access   private
	 */
	public function change_seo_description( $description ){

		$object         = get_queried_object();
		$custom_desc    = $this->get_custom_seo_desc();

		if( $custom_desc ) {

			if ( is_singular() ) {

				$default_desc = get_post_meta( $object->ID, self::SEO_PLUGINS[ $this->seo_plugin ]['desc-meta'], true );
				$description  = ( ! empty( $default_desc ) ? $default_desc : $custom_desc );

			}
			else if ( is_tax() ) {

				$description   = ( ! empty( $meta_seo_desc ) ? $meta_seo_desc : $custom_desc );

			}

		}

		return $description;

	}



	/**
	 * Your custom seo titles code here
	 *
	 * @return   string
	 * @since    1.0.0
	 * @access   private
	 */
	private function get_custom_seo_title(){

		$custom_title   = false;
		$object         = get_queried_object();

		//Your code and conditions for the custom seo title

		return $custom_title;

	}



	/**
	 * Your custom seo meta descriptions code here
	 *
	 * @return   string
	 * @since    1.0.0
	 * @access   private
	 */
	private function get_custom_seo_desc(){

		$custom_desc    = false;
		$object         = get_queried_object();

		//Your code and conditions for the custom seo description

		return $custom_desc;

	}


}

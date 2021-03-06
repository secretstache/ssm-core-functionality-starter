<?php

namespace SSM\Includes;

use SSM\Includes\Loader;
use SSM\Includes\I18n;
use SSM\Includes\Helpers as SSMH;
use SSM\Includes\SSMCLI;
use SSM\Admin\AdminSetup;
use SSM\Admin\RequiredPlugins;
use SSM\Admin\FieldFactory;
use SSM\Admin\CPT;
use SSM\Admin\OptionsPage;
use SSM\Front\FrontSetup;

class Root
{

	/**
	 * The loader that"s responsible for maintaining and registering all hooks that power
	 * the plugin.
	 */
	protected $loader;

	/**
	 * Define the core functionality of the plugin.
	 */
	public function __construct() {

		$this->loader = new Loader();

		$this->setLocale();

		$this->setAdminModules();
		$this->setFrontModules();
		$this->setObjectModules();
		// $this->runSSMCLI();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the I18n class in order to set the domain
	 */
	private function setLocale()
	{

		$plugin_i18n = new I18n();

		$this->loader->add_action( "plugins_loaded", $plugin_i18n, "loadPluginTextdomain" );

	}

	/**
	 * Scan /json/admin/ dir for admin modules and register them if available
	 *
	 */
	private function setAdminModules()
	{

		if ( count( scandir( SSMC_DIR . "includes/json/admin/") ) > 2 ) {

			$modules = array_diff( scandir( SSMC_DIR . "includes/json/admin/" ), array( ".", ".." ) ); // remove . and .. from list of files

			foreach ( $modules as $module ) {

				$$module = json_decode( file_get_contents( SSMC_DIR . "includes/json/admin/{$module}" ), true ); // $required_plugins = array( ... )

				if ( isset( $$module["hooks"] ) && !empty( $$module["hooks"] ) ) { // if ( isset( $required_plugins["hooks"] ) && !empty( $required_plugins["hooks"] ) )
					$this->registerModule( $$module ); // registerModule( $required_plugins )
				}

			}

		}

	}

	/**
	 * Scan /json/front/ dir for front modules and register them if available
	 *
	 */
	private function setFrontModules()
	{
		if ( count( scandir( SSMC_DIR . "includes/json/front/") ) > 2 ) {

			$modules = array_diff( scandir( SSMC_DIR . "includes/json/front/" ), array( ".", ".." ) ); // remove . and .. from list of files

			foreach ( $modules as $module ) {

				$$module = json_decode( file_get_contents( SSMC_DIR . "includes/json/front/{$module}" ), true ); // $front_setup = array( ... )

				if ( isset( $$module["hooks"] ) && !empty( $$module["hooks"] ) ) { // if ( isset( $front_setup["hooks"] ) && !empty( $front_setup["hooks"] ) )
					$this->registerModule( $$module ); // registerModule( $front_setup )
				}

			}

		}

	}

	/**
	 * Scan /json/objects/ dir for front modules and register them if available
	 *
	 */
	private function setObjectModules()
	{
		if ( count( scandir( SSMC_DIR . "includes/json/objects/") ) > 2 ) {

			$modules = array_diff( scandir( SSMC_DIR . "includes/json/objects/" ), array( ".", ".." ) ); // remove . and .. from list of files

			foreach ( $modules as $module ) {

				$$module = json_decode( file_get_contents( SSMC_DIR . "includes/json/objects/{$module}" ), true ); // $project = array( ... )

				if ( isset( $$module["hooks"] ) && !empty( $$module["hooks"] ) ) { // if ( isset( $project["hooks"] ) && !empty( $project["hooks"] ) )
					$this->registerModule( $$module ); // registerModule( $project )
				}

			}

		}

	}

	/**
	 * Receive "unpacked" data from .json file and register corresponding hooks
	 */
	private function registerModule( $module )
	{

		${$module["slug"]} = new $module["class"]; //$plugin_front_setup = new "SSM\Front\FrontSetup"

		foreach ( $module["hooks"] as $hook ) {

			$priority = ( isset( $hook["priority"] ) && $hook["priority"] != "" ) ? $hook["priority"] : 10;
			$arguments = ( isset( $hook["arguments"] ) && $hook["arguments"] != "" ) ? $hook["arguments"] : 1;

			call_user_func_array(
				array( $this->loader, "add_{$hook["type"]}" ), // array( $this->loader, "add_action" )
				array( $hook["name"], ${$module["slug"]}, $hook["function"], $priority, $arguments ) // array( wp_enqueue_scripts, $plugin_front_setup, enqueueStyles )
			);

		}

	}

	private function runSSMCLI() {

		if ( class_exists( 'WP_CLI' ) && defined( 'WP_CLI' ) && WP_CLI ) {

			\WP_CLI::add_command( 'ssm', 'SSM\Includes\SSMCLI' );

		}

	}

	/**
	 * Run the loader to execute all of the registered hooks
	 */
	public function run()
	{
		$this->loader->run();
	}

}

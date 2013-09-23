<?php
/*
Plugin Name: Genesis Left Right Sidebar
Plugin URI: http://www.billerickson.net
Description: Regardless of layout, the left sidebar is always used on left, and right on right
Author: Bill Erickson
Version: 1.0
Requires at least: 3.0
Author URI: http://www.billerickson.net
*/
/*  Copyright 2013 Bill Erickson

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; version 2 of the License (GPL v2) only.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class BE_GLRS {
	var $instance;
	
	function __construct() {
		$this->instance =& $this;
		register_activation_hook( __FILE__, array( $this, 'activation_hook' ) );
		add_action( 'plugins_loaded', array( $this, 'translations' ) );
		add_action( 'genesis_setup', array( $this, 'init' ), 11 );	
		add_action( 'genesis_sidebar', array( $this, 'sidebar_content' ), 5 );
	}
	
	/**
	 * Activation Hook - Confirm site is using Genesis
	 *
	 */
	function activation_hook() {
		if ( 'genesis' != basename( TEMPLATEPATH ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( sprintf( __( 'Sorry, you can&rsquo;t activate unless you have installed <a href="%s">Genesis</a>', 'genesis-title-toggle'), 'http://www.billerickson.net/go/genesis' ) );
		}
	}

	/**
	 * Translations
	 *
	 */
	function translations() {
		load_plugin_textdomain( 'glrs', false, basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Initialize
	 *
	 */
	function init() {
	
		// Change Sidebar Labels
		unregister_sidebar( 'sidebar' );
		unregister_sidebar( 'sidebar-alt' );
		genesis_register_sidebar( array( 'id' => 'sidebar', 'name' => 'Right Sidebar' ) );
		genesis_register_sidebar( array( 'id' => 'sidebar-alt', 'name' => 'Left Sidebar' ) );
	
		// Remove Unused Layouts
		genesis_unregister_layout( 'content-sidebar-sidebar' );
		genesis_unregister_layout( 'sidebar-sidebar-content' );
			
	}

	/**
	 * Left Sidebar on Sidebar Content layout 
	 *
	 */
	function sidebar_content() {
		if( 'sidebar-content' !== genesis_site_layout() )
			return;
	
		// Support for Genesis Simple Sidebars
		if( function_exists( 'ss_do_sidebar_alt' ) ) {
		
			remove_action( 'genesis_sidebar', 'ss_do_sidebar' );
			add_action( 'genesis_sidebar', 'ss_do_sidebar_alt' );
			
		// Normal Genesis sidebar
		} else { 
		
			remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
			add_action( 'genesis_sidebar', 'genesis_do_sidebar_alt' );	
			
		}
	}
}

$BE_GLRS = new BE_GLRS;
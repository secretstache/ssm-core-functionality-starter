<?php

namespace App\Controllers;

use Sober\Controller\Controller;
use SSM\Includes\Walker;
use SSM\Includes\Helpers as SSMH;

class PageBuilder extends Controller
{

    protected $acf = true;

    public function builder() {
        return $this;
    }

    public static function getCustomID( $args )
    {

        $response = '';

        $inline_id = $args->option_html_id;
        $response .= ( $inline_id ) ? ' id="' . sanitize_html_class( strtolower( $inline_id ) ) . '"' : '';

        return $response;

    }

    public static function getCustomClasses( $context, $column_index, $args )
    {

        $response = '';

        $inline_classes = $args->option_html_classes;
        $odd = ( !empty( $column_index ) && $column_index % 2 == 0 ) ? 'even' : 'odd';
        
        switch ( $context ) {

            case 'template':
                $response .= ' class="content-block';
                break;
            case 'hero-unit':
                $response .= ' class="hero-unit';
                break;
            case 'module':
                $response .= ' class="module stack-order-' . $column_index . ' stack-order-' . $odd;
                break;
                
        }
        
        switch ( $args->option_background ) {

            case 'Color':
                $response .= ' ' . sanitize_html_class( $args->option_background_color );
                break;
            case 'Image':
                $response .= ' bg-image bg-dark';
                break;
            case 'Video':
                $response .= ' bg-video';
                break;
        
        }

        if ( $context == 'hero-unit' && !is_null( $args->option_hero_unit_height ) ) {
            
            $response .= ( $args->option_hero_unit_height == 'full' ) ? ' full-height' : ' auto';
        
        }

        $response .= ( !is_null( $inline_classes ) ) ? ' ' . $inline_classes : '';

        $response .= '"';

        return $response;

    }

    public static function getColumnsWidth( $column_index ) {
    
        global $post;
        return get_post_meta( $post->ID, 'custom_columns_width_' . $column_index, true);
    
    }

    public static function getMenuArgs( $context ) {

        $response = array();

        if ( $context == 'offCanvas') {
            
            $response = array( 
                'theme_location' => 'primary_navigation', 
                'container' => FALSE, 
                'items_wrap' => '<ul class="vertical menu accordion-menu" data-accordion-menu>%3$s</ul>', 
                'walker' => new Walker()
            );

        } elseif ( $context == 'primary_navigation' ) {

            $response = array( 
                'theme_location' => 'primary_navigation', 
                'container' => FALSE, 
                'items_wrap' => '<ul class="dropdown menu show-for-medium" data-dropdown-menu>%3$s</ul>', 
                'walker' => new Walker()
            );

        } elseif ( $context == 'footer_navigation' ) {

            $response = array(
                'theme_location' => 'footer_navigation', 
                'container' => FALSE, 
                'items_wrap' => '<ul class="menu vertical">%3$s</ul>', 
                'walker' => new Walker()
            );
            
        }

        return $response;
    
    }

}

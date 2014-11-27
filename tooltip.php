<?php
/**
 * @link              https://github.com/tronsha/wp-tooltip-plugin
 * @since             1.0.0
 * @package           wp-tooltip-plugin
 *
 * @wordpress-plugin
 * Plugin Name:       MPCX Tooltip
 * Plugin URI:        https://github.com/tronsha/wp-tooltip-plugin
 * Description:       Tooltip Plugin
 * Version:           1.0.0
 * Author:            Stefan Hüsges
 * Author URI:        http://www.mpcx.net/
 * Copyright:         Stefan Hüsges
 * License:           MIT
 * License URI:       https://raw.githubusercontent.com/tronsha/wp-tooltip-plugin/master/LICENSE
 */

defined( 'ABSPATH' ) or ( @include_once explode( 'wp-content', __DIR__ )[0] . '/wp-hide.php' ) or die;

add_action(
    'init',
    function () {
        if ( ! is_admin()) {
            wp_register_style(
                'tooltipster',
                plugin_dir_url( __FILE__ ) . 'tooltipster/css/tooltipster.css',
                array(),
                '3.3.0'
            );
            wp_register_script(
                'tooltipster',
                plugin_dir_url( __FILE__ ) . 'tooltipster/js/jquery.tooltipster.js',
                array( 'jquery' ),
                '3.3.0'
            );
            wp_enqueue_style( 'tooltipster' );
            wp_enqueue_script( 'tooltipster' );
        }
    }
);

add_shortcode(
    'tooltip',
    function ( $att = array(), $content = null ) {
        $content  = do_shortcode( $content );
        $tooltips = get_posts( array(
            'offset'         => 0,
            'category_name'  => 'tooltips',
            'posts_per_page' => - 1,
            'orderby'        => 'ID',
            'order'          => 'ASC',
        ) );
        foreach ($tooltips as $tooltip) {
            $content = str_replace( $tooltip->post_title,
                '<span class="tooltip" title="' . $tooltip->post_content . '">' . $tooltip->post_title . '</span>',
                $content );
        }
        $content .= '
		<script>
			jQuery(document).ready(function() {
				jQuery(\'.tooltip\').tooltipster();
			});
		</script>
		';
        return $content;
    }
);

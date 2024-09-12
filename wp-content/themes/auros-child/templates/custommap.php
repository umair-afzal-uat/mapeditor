<?php

/**
 * Template Name: Custom-Map Template
 * Template Post Type: post, page
 *
 * 
 */
?>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<div class="custom_map_config">

    <?php do_action('elementor/editor/wp_head'); ?>
    <?php echo do_shortcode('[elementor-template id="5621"]'); ?>
    <?php echo do_shortcode('[map_editor]'); ?>
    <?php wp_footer(); ?>
</div>
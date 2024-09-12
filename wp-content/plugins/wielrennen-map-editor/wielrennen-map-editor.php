<?php

   /*

   Plugin Name: Wielrennen Map Editor

   Plugin URI: https://www.arhamsoft.com/

   description: A plugin to create awesome maps.

   Version: 1.0

   Author: Arhamsoft 

   Author URI: https://www.arhamsoft.com/

   License: Arhamsoft

   */





if (!defined('ABSPATH')){

  die("Direct access forbidden");

}



define('ASSETS_URL', plugins_url( 'wielrennen-map-editor/assets/' ));

define('PLUGIN_DIR', dirname(__FILE__));



require_once(PLUGIN_DIR.'/lib/vendor/autoload.php');


include('inc/main-functions.php');

include('inc/ajax-functions.php');

include('inc/shortcodes.php');
include('inc/shortcodes2.php');
include('inc/scripts.php');






















































?>
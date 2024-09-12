<?php
/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 3/19/19
 * Time: 10:57 AM
 */
$is_pre_sale_page = WFCH_Common::is_pre_sale_page();
?>
<div id="wfch_root" xmlns="http://www.w3.org/1999/html">
    <label><input type="checkbox" name="_wfch_pre_sale_page" <?php echo $is_pre_sale_page ? 'checked' : '' ?>><?php _e( 'Make this page as pre sale page' ) ?></label>
</div>

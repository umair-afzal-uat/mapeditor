<?php
/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 3/2/19
 * Time: 6:22 PM
 */

/**
 * @var $this WFCH_Admin;
 */
$settings = $this->get_settings();
?>
<div id="wfch_skip_cart_container" style="text-transform: capitalize;">

    <div class="wfch_loader" style="display: block;"><span class="spinner"></span></div>
    <div class=" wfch_page_title">
        <h1><?php echo WFCH_FULL_NAME ?></h1>
        <small style="text-transform:none"><?php _e( 'Set up global skip cart and create cart based rules to send user to specific checkout page', 'woofunnels-carthopper-skip-cart' ) ?></small>
    </div>
    <table class="form-table wc-shipping-zone-settings" id="wfch_admin_settings">
        <tbody>
        <tr valign="top" class="">
            <th scope="row" class="titledesc">
                <label><?php echo __( 'SkipCart', 'woofunnels-carthopper-skip-cart' ); ?></label>
            </th>
            <td class="forminp1">
                <input type="checkbox" name="wfch_global_skip_cart" v-model="skip_cart" v-on:change="changeDetect()"><?php _e( 'Enable SkipCart for all Products', 'woofunnels-carthopper-skip-cart' ) ?>
            </td>
        </tr>
        <tr valign="top" v-if="skip_cart">
            <th scope="row" class="titledesc">
				<?php echo __( 'Exclude Product Category (Optional)', 'woofunnels-carthopper-skip-cart' ); ?>
            </th>
            <td>
                <multiselect v-model="selectedCategory" id="ajax_0" label="title" track-by="title" placeholder="Type to search" open-direction="bottom" :options="category" :multiple="<?php echo( 'true' ); ?>" :searchable="true" :loading="isCategoryLoading" :internal-search="true" :clear-on-select="false" :close-on-select="true" :options-limit="300" :limit="3" :max-height="600" :show-no-results="true" :hide-selected="true" @input="changeDetect">
                    <template slot="clear" slot-scope="props">
                    </template>
                    <span slot="noResult"><?php echo __( 'Oops! No elements found. Consider changing the search query.', 'woofunnels-carthopper-skip-cart' ); ?></span>
                </multiselect>
            </td>
        </tr>
        <tr valign="top" class="" v-if="skip_cart">
            <th scope="row" class="titledesc">
                <label><?php echo __( 'Exclude Products (Optional) ', 'woofunnels-carthopper-skip-cart' ); ?></label>
            </th>
            <td class="forminp">
                <multiselect v-model="selectedExcludes" id="ajax_1" label="title" track-by="title" placeholder="Type to search" open-direction="bottom" :options="excludes" :multiple="<?php echo( 'true' ); ?>" :searchable="true" :loading="isExcludesLoading" :internal-search="true" :clear-on-select="false" :close-on-select="true" :options-limit="300" :limit="3" :max-height="600" :show-no-results="true" :hide-selected="true" @search-change="asyncFindExcludes" @input="changeDetect">
                    <template slot="clear" slot-scope="props">
                    </template>
                    <span slot="noResult"><?php echo __( 'Oops! No elements found. Consider changing the search query.', 'woofunnels-carthopper-skip-cart' ); ?></span>
                </multiselect>
            </td>
        </tr>

        <tr valign="top" class="" v-if="skip_cart">
            <th scope="row" class="titledesc">
                <label><?php echo __( 'Add to Cart Button Text (Optional)', 'woofunnels-carthopper-skip-cart' ); ?></label>
            </th>
            <td class="forminp">
                <input type="text" name="wfch_global_skip_cart" v-model="add_cart_button_text" v-on:change="changeDetect()">
            </td>
        </tr>


        <tr valign="top" class="">
            <th scope="row" class="titledesc">
                <label><?php echo __( 'Cart Item Rules', 'woofunnels-carthopper-skip-cart' ); ?></label>
            </th>
            <td class="">
                <table class="wc-shipping-zone-methods widefat">
                    <thead>
                    <tr>
                        <th class="wc-shipping-zone-sort">
                            <span class="woocommerce-help-tip" data-tip='<?php _e( 'Drag and drop to re-order Product Rules. This is the order in which they will be matched against items in cart.', 'woofunnels-carthopper-skip-cart' ) ?>'></span>
                        </th>
                        <th class="wc-shipping-zone-method-enabled1"><?php _e( 'Status', 'woofunnels-carthopper-skip-cart' ) ?></th>
                        <th class="wc-shipping-zone-method-title"><?php _e( 'Cart Items', 'woofunnels-carthopper-skip-cart' ) ?></th>
                        <th class="wc-shipping-zone-method-description1"><?php _e( 'Match', 'woofunnels-carthopper-skip-cart' ) ?></th>
                        <th class="wc-shipping-zone-method-enabled1"><?php _e( 'Skip Cart', 'woofunnels-carthopper-skip-cart' ) ?></th>
                        <th class="wc-shipping-zone-method-description1"><?php _e( 'Checkout', 'woofunnels-carthopper-skip-cart' ) ?></th>
                        <th class="wc-shipping-zone-method-description1"></th>
                    </tr>
                    </thead>
                    <tbody class="wfch_rules_table_body wc-shipping-zone-method-rows ui-sortable" id="wfch_rules_table_body">


                    <tr v-for="(rule,index) in rules" class="wfch_item_drag" v-bind:index="index" v-bind:rule-id="rule.id">
                        <td width="1%" class="wc-shipping-zone-sort ui-sortable-handle"></td>
                        <td class="wc-shipping-zone-method-enabled1">
                            <div class='wfch_fsetting_table_title'>
                                <div class='offer_state wfch_toggle_btn'>
                                    <input name='wfch_checkout_page_state' v-bind:id="'state_'+rule.id" v-bind:data-id="rule.id" type='checkbox' class='wfch-tgl wfch-tgl-ios wfch_checkout_page_status' :checked="rule.published=='publish'" value="publish" v-on:change="updateRuleStatus(rule,event)">
                                    <label v-bind:for="'state_'+rule.id" class='wfch-tgl-btn wfch-tgl-btn-small'></label>
                                </div>
                            </div>
                        </td>
                        <td class="wc-shipping-zone-method-title">
                            <p v-if="wfch.tools.ol(rule.products)>0" href="javascript::void(0)" v-for="(p_data,pid) in rule.products">{{p_data.title}}</p>
                        </td>

                        <td class="wc-shipping-zone-method-description1">
                            <p v-if="rule.match==0"><?php _e( 'All of these', 'woofunnels-carthopper-skip-cart' ) ?></p>
                            <p v-else=""><?php _e( 'Any of these', 'woofunnels-carthopper-skip-cart' ) ?></p>
                        </td>
                        <td class="wc-shipping-zone-method-enabled1">
                            <p v-if="rule.skip_cart=='true'|| rule.skip_cart==true"><?php _e( 'yes', 'woofunnels-carthopper-skip-cart' ) ?></p>
                            <p v-else=""><?php _e( 'no', 'woofunnels-carthopper-skip-cart' ) ?></p>
                        </td>
                        <td>
                            <a v-if="wfch.tools.ol(rule.checkout)>0 && wfch.tools.ol(wfch_data.aero_checkout_pages)>0" v-bind:href="rule.checkout.permalink" target="_blank">{{rule.checkout.title}}</a>
                            <a v-else="" href="<?php echo wc_get_checkout_url() ?>" target="_blank"><?php _e( 'Global', 'woocommerce' ) ?></a>
                        </td>
                        <td class="wc-shipping-zone-method-description1">
                            <a class="button" href="javascript:void(0)" v-on:click="editRule(index,rule)"><?php _e( 'Edit', 'woofunnels-carthopper-skip-cart' ) ?></a>&nbsp;<a class="button" v-on:click="deleteRule(index,rule)" href="javascript:void(0)"><?php _e( 'Delete', 'woofunnels-carthopper-skip-cart' ) ?></a>
                        </td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="7">
                            <button type="button" class="button wc-shipping-zone-add-method" id="wfch_add_rules" value="Add Rule" v-on:click="addRule()">Add Rule</button>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <span class="wfch_spinner spinner"></span>
                <button name="save" class="button-primary woofunnels-save-button" type="button" value="Save changes" :disabled="!changesHappend" v-on:click="saveRules()">Save changes</button>
            </td>
        </tr>
        </tbody>
    </table>

</div>

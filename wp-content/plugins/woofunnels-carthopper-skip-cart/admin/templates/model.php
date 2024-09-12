<div id="tmpl-wfch-modal-add-edit-rules-method">
    <div class="wc-backbone-modal" id="tmpl-wfch-modal-add-rules-method">
        <div class="wc-backbone-modal-content" style="min-height: 400px;">
            <section class="wc-backbone-modal-main" role="main">
                <header class="wc-backbone-modal-header">
                    <h1 v-if="wfch_id>0"><?php esc_html_e( 'Edit Rule', 'woocommerce' ); ?></h1>
                    <h1 v-else=""><?php esc_html_e( 'Add Rule', 'woocommerce' ); ?></h1>
                    <button class="modal-close modal-close-link dashicons dashicons-no-alt" v-on:click="closeModel()">
                        <span class="screen-reader-text"><?php esc_html_e( 'Close modal panel', 'woocommerce' ); ?></span>
                    </button>
                </header>
                <form id="modal-add-product-form" data-wfoaction="add_product" v-on:submit.prevent="onSubmit">
                    <article>

                        <fieldset>
                            <div class="form-group wfch_fist_wrap">
                                <div class="wfch_label_wrap ">
                                    <label><?php _e( 'Cart Items Match', 'woofunnels-carthopper-skip-cart' ) ?></label>
                                </div>
                                <div class="wfch_content_wrap">
                                    <div class="mataches">
                                        <select v-model="match">
                                            <option value="0"><?php _e( 'All of these', 'woofunnels-carthopper-skip-cart' ) ?></option>
                                            <option value="1"><?php _e( 'Any of these', 'woofunnels-carthopper-skip-cart' ) ?></option>
                                        </select>
                                    </div>
                                    <multiselect v-model="selectedProducts" id="ajax" label="title" track-by="title" placeholder="Type to search" open-direction="bottom" :options="products" :multiple="<?php echo( 'true' ); ?>" :searchable="true" :loading="isLoading" :internal-search="true" :clear-on-select="false" :close-on-select="true" :options-limit="300" :limit="3" :max-height="600" :show-no-results="true" :hide-selected="true" @search-change="asyncFind">
                                        <template slot="clear" slot-scope="props">
                                        </template>
                                        <span slot="noResult"><?php echo __( 'Oops! No elements found. Consider changing the search query.', 'woofunnels-carthopper-skip-cart' ); ?></span>
                                    </multiselect>

                                </div>

                            </div>
                            <div class="form-group">
                                <div class="wfch_label_wrap">
                                    <label><?php _e( 'SkipCart', 'woofunnels-carthopper-skip-cart' ) ?></label>
                                </div>
                                <div class="wfch_content_wrap">
                                    <input type="checkbox" name="wfch_skip" v-model="skip_cart">
                                    <span>Enable SkipCart for selected products</span>
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="wfch_label_wrap">
                                    <label><?php _e( 'Add to cart button text', 'woofunnels-carthopper-skip-cart' ) ?></label>
                                </div>
                                <div class="wfch_content_wrap">
                                    <input type="text" v-model="add_to_cart_text">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="wfch_label_wrap">
                                    <label><?php _e( 'Checkout Page', 'woofunnels-carthopper-skip-cart' ) ?></label>
                                </div>
                                <div class="wfch_content_wrap">
                                    <select v-model="checkout" v-if="wfch.tools.ol(wfch_data.aero_checkout_pages)>0">
                                        <option v-for="(aero_page,id) in wfch_data.aero_checkout_pages" value="all" v-bind:value="aero_page.id">
                                            {{aero_page.name}}
                                        </option>
                                    </select>
                                    <select v-model="checkout" v-else="">
                                        <option value="0"><?php _e( 'Global', 'woofunnels-carthopper-skip-cart' ) ?></option>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                    </article>
                    <footer>
                        <div class="inner">
                            <button v-if="wfch_id>0" id="wfch-btn-ok" type="submit" class="button button-primary button-large" :disabled="isLoading==true"><?php esc_html_e( 'Update Rule', 'woocommerce' ); ?></button>
                            <button v-else="" id="wfch-btn-ok" type="submit" class="button button-primary button-large" :disabled="isLoading==true"><?php esc_html_e( 'Add Rule', 'woocommerce' ); ?></button>
                        </div>
                    </footer>
                </form>
            </section>
        </div>
    </div>
    <div class="wc-backbone-modal-backdrop modal-close" v-on:click="closeModel()"></div>
</div>
<div class="wfch_success_modal" style="display: none" id="modal-saved-data-success" data-iziModal-icon="icon-home"></div>

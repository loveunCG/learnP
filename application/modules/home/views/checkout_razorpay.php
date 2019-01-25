    <!-- >> Blog-->
    <section class="blog-content">
        <div class="container">
            <div class="row row-margin">
                <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">

                    <?php echo $this->session->flashdata('message'); ?>

                    <!-- Select Payment-->
                    <div class="video-description">
                        <h2 class="heading-line"><?php echo $pagetitle; ?></h2>
                            <div class="radio payment-methods-list">
                                <?php 
                                        $system_currency = get_system_settings('Currency_Code');

                                        if(!empty($payment_gateways)) {

                                            foreach($payment_gateways as $gateway) {

                                                if ( in_array( $gateway->type_slug, array( 'Web money' ) ) ) { // Let us check whether the payment gateway supports the currency
                                                    if ( $gateway->type_slug == 'Web money' ) {
                                                        if ( ! in_array( $system_currency, array( 'RUB', 'EUR', 'USD', 'UAH' ) ) ) { // Let us skip if the system not in the supported currency
                                                            continue;
                                                        }
                                                    }
                                                }
                                    ?>

                                    <input type="hidden" name="gateway_id" value="<?php echo $gateway->type_id;?>" />

                                <?php } } ?>

                            </div>

                            <?php if(!empty($record->sc_id)) { ?>
                                <input type="hidden" name="sc_id" value="<?php echo $record->sc_id; ?>" />
                            <?php } ?>

                            <div class="mtop2 ">
                                <div class="mobile-effect">

                                    <form action="<?php echo $razorpay['success_url'];?>" method="POST">
                                      <script
                                        src="https://checkout.razorpay.com/v1/checkout.js"
                                        data-key="<?php echo $razorpay['razorpay_key_id'];?>"
                                        data-amount="<?php echo $razorpay['total_amount'];?>"
                                        data-name="<?php echo $razorpay['product_name'];?>"
                                        data-description="<?php echo $razorpay['product_desc'];?>" 
                                        data-image="<?php echo $razorpay['image'];?>" 
                                        data-netbanking="true"
                                        data-prefill.name="<?php echo $razorpay['firstname'];?> <?php echo $razorpay['lastname'];?>"
                                        data-prefill.email="<?php echo $razorpay['email'];?>"
                                        data-prefill.contact="<?php echo $razorpay['phone'];?>"
                                        data-notes.shopping_order_id="21">
                                      </script>
                                      <!-- Any extra fields to be submitted with the form but not sent to Razorpay -->
                                      <input type="hidden" name="shopping_order_id" value="21">
                                    </form>

                                </div>
                            </div>

                    </div>
                    <!-- /Select Payment-->


                </div>
                <!-- Sidebar/Widgets bar -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <!-- Price Widget -->
                    <div class="get-video-course">
                        <!-- Sigle blog post -->
                        <div class="blog-card">
                            <div class="blog-card-img related-itm-img">
                                <img src="<?php echo get_selling_course_img($record->image); ?>" alt="" class="img-responsive">
                            </div>
                            <p class="related-link"><a href="<?php echo URL_HOME_BUY_COURSE.'/'.$record->slug; ?>"> <?php if(!empty($record->course_name)) echo $record->course_name; ?> - <?php if(!empty($record->course_title)) echo $record->course_title; ?> - <?php if(!empty($record->username)) echo $record->username; ?> </a></p>
                            <ul class="related-videos">
                                <li>
                                    <?php if(!empty($record->updated_at)) echo date('M jS, Y', strtotime($record->updated_at)); ?>
                                </li>
                                <li> <?php if(!empty($record->course_price)) echo $this->config->item('site_settings')->currency_symbol.' '.$record->course_price; ?></li>
                            </ul>
                        </div>
                        <!-- Sigle blog post Ends -->
                        <ul class="list">
                            <?php if(!empty($record->sellingcourse_curriculum)) { ?>
                            <li class="list-item">
                                <span class="list-left"><?php echo get_languageword('lectures'); ?></span>
                                <span class="list-right"><?php echo count($record->sellingcourse_curriculum); ?></span>
                            </li>
                            <?php } ?>
                            <?php if(!empty($record->skill_level)) { ?>
                            <li class="list-item">
                                <span class="list-left"><?php echo get_languageword('Skill_Level'); ?></span>
                                <span class="list-right"><?php echo $record->skill_level; ?></span>
                            </li>
                            <?php } ?>
                            <?php if(!empty($record->languages)) { ?>
                            <li class="list-item">
                                <span class="list-left"><?php echo get_languageword('languages'); ?></span>
                                <span class="list-right">
                                    <?php echo $record->languages; ?>
                                </span>
                            </li>
                            <?php } ?>
                            <?php if(!empty($record->max_downloads)) { ?>
                            <li class="list-item">
                                <span class="list-left"><?php echo get_languageword('Max_Downloads'); ?></span>
                                <span class="list-right"> <?php echo $record->max_downloads; ?> </span>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!-- /Price Widget -->
                </div>
            </div>
        </div>
    </section>
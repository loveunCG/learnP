    <!-- Frequently Asked Questions -->
   <?php if(!empty($faqs)) { ?>
    <div class="container">
        <div class="row-margin">
            <div class="row">
                <div class="col-sm-12 ">
                    <h2 class="heading"><?php echo get_languageword('Frequently'); ?> <span><?php echo get_languageword('Asked_Questions'); ?></span></h2>
                    <p class="heading-tag"><?php echo get_languageword('See_our_frequently_asked_questions'); ?></p>
                </div>
            </div><div class="row">
            <div class="col-lg-12">
                <div class="panel-group cust-panel qns-panel" id="accordion">
                    <?php $i= 1;
                     foreach ($faqs as $row ) { ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i ?>" aria-expanded="false">
                                   <i class="fa fa-question panel-icn"></i> <?php echo $row->question;?>
                                </a>
                            </h4>
                        </div>
                        <!--/.panel-heading -->
                        <div id="collapse<?php echo $i ?>" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                            <div class="panel-body">
                                 <?php echo $row->answer;?>
                            </div>
                            <!--/.panel-body -->
                        </div>
                        <!--/.panel-collapse -->
                    </div>
                    <?php $i++ ;} ?>
                    <!-- /.panel -->
                </div>
                <!-- /.panel-group -->
        </div></div>
        </div>
    </div>
    <?php } else echo get_languageword('Coming_Soon'); ?>
    <!-- Frequently Asked Questions -->
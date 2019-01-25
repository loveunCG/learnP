    <!-- Page Title Wrap  -->
    <div class="page-title-wrap">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                </div>
            </div>
        </div>
    </div>
    <!-- Page Title Wrap  -->

    <!-- News Scroller  -->
    <?php $this->load->view('template/site/scroller'); ?>
    <!-- News Scroller  -->

    <!-- Dashboard Section  -->
    <section class="dashboard-section">
        <div class="container">
            <div class="row offcanvas offcanvas-right row-margin">

                <?php if(!empty($categories)) {

                            $this->load->view('sections/category_section', array('categories' => $categories), false);
                      }
                ?>

                <div class="col-xs-12 col-sm-<?php if(empty($categories)) echo '12'; else echo '9'; ?> dashboard-content ">
                    <!-- Breadcrumb -->
                    <ol class="breadcrumb dashcrumb">
                        <li><a href="<?php echo SITEURL;?>"><?php echo get_languageword("home");?></a></li>
                        <li class="active"><?php if(isset($heading1)) echo $heading1;?></li>
                    </ol>
                    <!-- Breadcrumb ends -->

                    <!-- Dashboard Panel -->
                    <div class="dashboard-panel">
                        <div class="row">
                            <div class="col-sm-12">
                                <h4><?php if(isset($heading1)) echo $heading1;?></h4>
                            </div>
                        </div>
                        <hr>
                            <div id="course_list">

                                <?php 
                                      if(!empty($courses)): 
                                        $this->load->view('sections/course_section', array('courses' => $courses), false);
                                      else:
                                ?>
                                 <p>Course(s) not available.</p>
                                <?php endif; ?>

                            </div>

                            <?php if($total_records > LIMIT_COURSE_LIST) { ?>
                            <div class="row" id="div_load_more">
                                <div class="col-sm-12 text-center">
                                    <div class="load-more" onclick="load_more();">
                                        <input type="hidden" name="limit" id="limit" value="<?php echo LIMIT_COURSE_LIST;?>" />
                                        <input type="hidden" name="offset" id="offset" value="<?php echo LIMIT_COURSE_LIST;?>" />
                                        <input type="hidden" name="category_slug" id="category_slug" value="<?php echo $category_slug;?>" />
                                        <a class="btn-link" id="btn_load_more"> <?php echo get_languageword("load_more");?></a>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>

                    </div>


                    <!-- Ends Dashboard Panel -->
                </div>
            </div>
        </div>
    </section>
    <!-- Ends Dashboard section  -->

    <script>
    function load_more()
    {
        $.ajax({
            url:"<?php echo URL_HOME_LOAD_MORE_COURSES; ?>",
            data:{
              offset        : $('#offset').val(),
              limit         : $('#limit').val(), 
              category_slug : $('#category_slug').val()
            },
            type:"post", 
            beforeSend: function() {
                $('#btn_load_more').html('<i class="fa fa-spinner"></i> <?php echo get_languageword("loading");?>');
            },
            success :function(data){

                $('#btn_load_more').html(' <?php echo get_languageword("load_more");?>');

                dta = $.parseJSON(data);

                if(dta.result == "\n") {

                    $('#div_load_more').html('<?php echo get_languageword("You have reached end of the list.");?>');

                } else {

                    $(dta.result).hide().appendTo("#course_list").fadeIn(1000);
                    $('#offset').val(dta.offset);
                    $('#limit').val(dta.limit);
                }
            }
        })
    }
    </script>


<div class="col-lg-3">
        <div class="panel panel-default theameOfPanle stastic">
          <div class="panel-heading main_small_heding"><?php echo get_languageword('statistics');?> </div>
          <div class="panel-body">
            <div class="stasticList clearfix">
              <ul>
                <li class="active">
                  <div class="cir"><?php echo (isset($statistics) && count($statistics) > 0) ? $statistics[0]->catcount : 0;?></div>
                  <h2><?php echo get_languageword('total_categories_count');?> <br>
                    </h2>
                  <div class="progress">
                    <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%"> <span class="sr-only">80% Complete (danger)</span> </div>
                  </div>
                </li>
                <li>
                  <div class="cir"> <?php echo (isset($statistics) && count($statistics) > 0) ? $statistics[0]->subcatcount : 0;?> </div>
                  <h2><?php echo get_languageword('total_sub_categories_count');?> <br>
             </h2>
                  <div class="progress">
                    <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%"> <span class="sr-only">20% Complete</span> </div>
                  </div>
                </li>
                <li>
                  <div class="cir"> 6522 </div>
                  <h2><?php echo get_languageword('total_coupons_count');?> <br>
             </h2>
                  <div class="progress">
                    <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"> <span class="sr-only">40% Complete (success)</span> </div>
                  </div>
                </li>
                <li>
                  <div class="cir"> 5456 </div>
                  <h2><?php echo get_languageword('total_clicks');?> <br>
             </h2>
                  <div class="progress">
                    <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%"> <span class="sr-only">60% Complete (warning)</span> </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
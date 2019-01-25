<div class="container">
        <div class="row-margin">
            <div class="row">
                <div class="col-sm-12 ">
                <?php foreach($page_info as $row) { ?>
                    <h2 class="heading"><?php echo $row->name ?> </h2>

                    <?php echo $row->description; ?>
                    
                </div>
                <?php } ?>
            </div><div class="row">
            <div class="col-lg-12">
                
                <!-- /.panel-group -->
        </div></div>
        </div>
    </div>
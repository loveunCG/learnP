    <div class="container">
        <div class="row-margin">
            <div class="row">
              <div class="col-lg-12">

                <!-- Why choose  -->
                  <?php foreach($pageTermsAndCondtions as $row){

                      echo $row->description;
                  }

                 ?>
                  <!-- Why choose  -->

                      <!-- How-it-works -->
                     <?php foreach($privacy_and_policy as $row){

                      echo $row->description;
                  }

                 ?>
                      <!-- Ends How-it-works -->


          </div>
        </div>
      </div>
  </div>



    
        
   
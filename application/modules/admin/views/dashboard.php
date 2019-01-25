<script src="<?php echo URL_FRONT_JS; ?>Chart.bundle.js"></script>
<script src="<?php echo URL_FRONT_JS; ?>Chart.bundle.min.js"></script>
<script src="<?php echo URL_FRONT_JS; ?>Chart.js"></script>
<script src="<?php echo URL_FRONT_JS; ?>Chart.min.js"></script>
<style type="text/css">
    div.row{list-style: none; margin: 0 -15px; padding: 0;}
    div.row:before,div.row:after{content: ""; display: block; clear: both}
    div.row div.col{float: left; width: 50%; padding: 15px;}
    div.row div.full{float: left; width: 100%; padding: 15px; background:#fff}
</style>




<div class="row">
    <div class="col-lg-8">
<div class="graph">
            <h3><?php echo get_languageword('Total_Payments');?></h3>
            <canvas id="lineChart"></canvas>
            <script>
            var ctline = document.getElementById("lineChart");
            var barChart = new Chart(ctline, {
                type: 'line',
                data: {
                    labels: <?php if(isset($packageNames)) echo json_encode($packageNames )?>,
                    datasets: [
                        {
                            label: 'Total payments',
                            data: <?php if(isset($packagePayments)) echo json_encode($packagePayments )?>,
                            backgroundColor: "rgba(75,192,192,0.4)",
                            borderColor: "rgba(75,192,192,1)",
                            borderCapStyle: 'butt',
                            borderDash: [],
                            borderDashOffset: 0.0,
                            borderJoinStyle: 'miter',
                            pointBorderColor: "rgba(75,192,192,1)",
                            pointBackgroundColor: "#fff",
                            pointBorderWidth: 1,
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: "rgba(75,192,192,1)",
                            pointHoverBorderColor: "rgba(220,220,220,1)",
                            pointHoverBorderWidth: 2,
                            pointRadius: 1,
                            pointHitRadius: 10

                        }
                    ]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    }
                }
            });

            </script>
        </div>
    </div>

<div class="col-lg-4">
<div class="row">

  <div class="col-lg-12">
        <?php 
            foreach ($usersCount as  $row) {
            $data = array($row->total_users , $row->total_students, $row->total_tutors, $row->total_institutes);
    
        }
    ?>

<div class="graph">
<h3><?php echo get_languageword('Users Information');?></h3>
<canvas id="myChart" style="display: block; width: 100px; height: 100px;" height="66" width="100"></canvas>
<script>
var ctx = document.getElementById("myChart");
var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ["Total Users","Total Students","Total Tutors","Total Institutes"],
        datasets: [{
            data: <?php echo json_encode($data )?>,
            backgroundColor: [
                "#FF6384",
                "#36A2EB",
                "#FFCE56",
                "#D0FFD9"
            ],
            hoverBackgroundColor: [
                "#FF6384",
                "#36A2EB",
                "#FFCE56",
                "#D0FFD9"
            ]
        }]
    },
 });

</script>
</div>
</div>


<div class="col-lg-12">
        
        <div class="graph">
        <h3><?php echo get_languageword('Package Subscriptions');?></h3>
        <canvas id="barMChart" style="display: block; width: 100px; height: 100px;" height="66" width="100"></canvas>
        <script>
        var ctbar = document.getElementById("barMChart");
        var barMChart = new Chart(ctbar, {
                type: 'bar',
                data: {
                labels: <?php if(isset($packageNames)) echo json_encode($packageNames )?>,
                datasets: [
                  
                       {
                    label: "Students",
                    backgroundColor: "blue",
                    data: <?php if(isset($Students)) echo json_encode($Students )?>,
                },
                {
                    label: "Tutors",
                    backgroundColor: "red",
                    data: <?php if(isset($Tutors)) echo json_encode($Tutors )?>,
                },
                {
                    label: "Institutes",
                   backgroundColor: "green",
                    data: <?php if(isset($Institutes)) echo json_encode($Institutes )?>,
                },

                {
                    label: "Total Subscribers",
                   backgroundColor: "yellow",
                    data: <?php if(isset($packageSubscriptions)) echo json_encode($packageSubscriptions )?>,
                }

                    
                    ]
                 },
          options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });

        </script>
        </div></div>

</div>
</div>



 



    </div>

    






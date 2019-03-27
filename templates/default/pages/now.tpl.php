<?php

?>

<div class="row history-cook idno-content">
  <div class="col-md-10 col-md-offset-1">
    <div>
      <div class="row">
        <h1 class="p-name">Current Status</h1>

        <!-- begin imported code -->
        <div id="target-gauge"></div>
        <div id="current-gauge"></div>
        <div id="chartVisualContainer"></div>

        <script>

        var targetTemps = new TempGauge('#target-gauge');

        var currentTemps = new TempGauge('#current-gauge');

        var blowerBar = new Bar("grey");
        var currentChart = new Chart(blowerBar);

        currentChart.configure({ container: "#chartVisualContainer" });

        targetTemps.configure()
        targetTemps.render();

        targetTemps.update();

        currentTemps.configure();
        currentTemps.render();

        currentTemps.update();

        var createdChart = false;
        //update everything
        function updateGaugesAndChart(){
          d3.json("<?= $inhale_endpoint ?>/cooks/current.json", function(error, currentCook){
              var mostRecentUpdate = currentCook.data[currentCook.data.length - 1];
              targetTemps.update({v1 : mostRecentUpdate["cooker-target-temp"], v2 : mostRecentUpdate["meat-target-temp"]});
              currentTemps.update({v1 : mostRecentUpdate["cooker-current-temp"], v2 : mostRecentUpdate["meat-current-temp"]});

          if(createdChart === true){
              return;
           }

          currentChart.render(currentCook.data);
          blowerBar.createCharts();
          createdChart = true;

          });
        }

        setInterval(updateGaugesAndChart, 9000);

        </script>
        <!-- end imported code -->


      </div>
    </div>
  </div>
</div>

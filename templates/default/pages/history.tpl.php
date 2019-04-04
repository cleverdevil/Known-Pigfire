<?php

?>

<div class="row history-cook idno-content">
  <div class="col-md-10 col-md-offset-1">
    <div>
      <div class="row">
        <h1 class="p-name">Cook Summary</h1>
        Content goes here.
        (<?= $cook_id ?>)

        <!--inputted code starts here -->
<div class="container-input">

        <div id="mobile-now">

          <div class="chart-visual-container" id="mobile-summary-visual-container"></div>
          <div class="summary-chart-labels">
            <div class="cooker-minimum-temp-label">cooker-min-temp</div>
            <div class="cooker-maximum-temp-label">cooker-max-temp</div>
            <div class="meat-minimum-temp-label">meat-min-temp</div>
            <div class="meat-maximum-temp-label">meat-max-temp</div>
          </div>
          <div class="chart-visual-container" id="mobile-current-visual-container"></div>
          <div class="chart-labels">
            <div class="cooker-target-temp-label">cooker-target-temp</div>
            <div class="cooker-current-temp-label">cooker-current-temp</div>
            <div class="meat-target-temp-label">meat-target-temp</div>
            <div class="meat-current-temp-label">meat-current-temp</div>
            <div class="blower-on-label">blower-on-status</div>
          </div>
        </div>

        <div id="tablet-now">
          <div class="chart-visual-container" id="tablet-summary-visual-container"></div>
          <div class="summary-chart-labels">
            <div class="cooker-minimum-temp-label">cooker-min-temp</div>
            <div class="cooker-maximum-temp-label">cooker-max-temp</div>
            <div class="meat-minimum-temp-label">meat-min-temp</div>
            <div class="meat-maximum-temp-label">meat-max-temp</div>
          </div>
          <div class="chart-visual-container" id="tablet-current-visual-container"></div>
          <div class="chart-labels">
            <div class="cooker-target-temp-label">cooker-target-temp</div>
            <div class="cooker-current-temp-label">cooker-current-temp</div>
            <div class="meat-target-temp-label">meat-target-temp</div>
            <div class="meat-current-temp-label">meat-current-temp</div>
            <div class="blower-on-label">blower-on-status</div>
          </div>
        </div>

        <div id="desktop-now">
          <div class="chart-visual-container" id="desktop-summary-visual-container"></div>
          <div class="summary-chart-labels">
            <div class="cooker-minimum-temp-label">cooker-min-temp</div>
            <div class="cooker-maximum-temp-label">cooker-max-temp</div>
            <div class="meat-minimum-temp-label">meat-min-temp</div>
            <div class="meat-maximum-temp-label">meat-max-temp</div>
          </div>
          <div class="chart-visual-container" id="desktop-current-visual-container"></div>
          <div class="chart-labels">
            <div class="cooker-target-temp-label">cooker-target-temp</div>
            <div class="cooker-current-temp-label">cooker-current-temp</div>
            <div class="meat-target-temp-label">meat-target-temp</div>
            <div class="meat-current-temp-label">meat-current-temp</div>
            <div class="blower-on-label">blower-on-status</div>
          </div>
        </div>



        <script>

  /*      var getId = function(){
          var srcURL = "https://ui7363dy38.execute-api.us-east-1.amazonaws.com/dev/cooks/";
          var currentURL = window.location.href;
          var cookerID = currentURL.slice(currentURL.indexOf("/history/") + 10);

          cookerID = 

          srcURL = + cookerID + ".json";
        }
*/
        var SvgScreenDeviceGenerator = function(deviceName){
          this.deviceName = deviceName;
        };

        SvgScreenDeviceGenerator.prototype.addVisualElems = function(elemsObj){
          var prop;

          for(prop in elemsObj){
            this[prop] = elemsObj[prop];
          }

        };

        SvgScreenDeviceGenerator.prototype.initializeOrConfigureOrUpdate = function(config){
           var prop;
           var i;
        //Iterate through config object which and loop thorugh array which has names of
        //functions to execute and their configurations
           for (prop in config){
             for (i = 0; i < config[prop].configFuncs.length; ++i){
                 this[prop][config[prop].configFuncs[i].nameOfFunc](config[prop].configFuncs[i].data);
             }
           }
         };


        //create mobile
        var mobileSummaryChart = new SummaryChart();
        var mobileBlowerBar = new Bar("silver", 250);
        var mobileCurrentChart = new Chart(mobileBlowerBar);

        //create tablet
        var tabletSummaryChart = new SummaryChart();
        var tabletBlowerBar = new Bar("silver", 300);
        var tabletCurrentChart = new Chart(tabletBlowerBar);

        //create desktop
        var desktopSummaryChart = new SummaryChart();
        var desktopBlowerBar = new Bar("silver", 300);
        var desktopCurrentChart = new Chart(desktopBlowerBar);

        // add each to screen

        var mobile = new SvgScreenDeviceGenerator("#mobile-now");
        var tablet = new SvgScreenDeviceGenerator("#tablet-now");
         var desktop = new SvgScreenDeviceGenerator("#desktop-now");

        mobile.addVisualElems({
            "mobile-current-visual-container" : mobileCurrentChart,
            "mobile-blower-bar" : mobileBlowerBar,
            "mobile-summary-visual-container" : mobileSummaryChart
        });

        tablet.addVisualElems({
            "tablet-current-visual-container" : tabletCurrentChart,
            "tablet-blower-bar" : tabletBlowerBar,
            "tablet-summary-visual-container" : tabletSummaryChart
        });

        desktop.addVisualElems({
            "desktop-current-visual-container" : desktopCurrentChart,
            "desktop-blower-bar" : desktopBlowerBar,
            "desktop-summary-visual-container" : desktopSummaryChart
        });

        d3.json("https://ui7363dy38.execute-api.us-east-1.amazonaws.com/dev/cooks/current.json", function(error, currentCook){

        mobile.initializeOrConfigureOrUpdate({

            "mobile-current-visual-container" : {
        configFuncs : [{
          nameOfFunc : "configure",
          data : {container: "#mobile-current-visual-container", title : "State", width : 250, xTicks : 3}
        }, {
          nameOfFunc : "render",
          data : currentCook.data
        }]},

        "mobile-blower-bar" : {
        configFuncs : [{
        nameOfFunc : "createCharts",
        data : undefined
        }]},
        "mobile-summary-visual-container" : {
        configFuncs : [{
        nameOfFunc : "configure",
        data : {container: "#mobile-summary-visual-container", title : "State", width : 250, xTicks : 0}
        }, {
        nameOfFunc : "render",
        data : {"v1" : currentCook.summary["cooker-minimum-temp"], "v2" : currentCook.summary["cooker-maximum-temp"], "v3" : currentCook.summary["meat-minimum-temp"], "v4" : currentCook.summary["meat-maximum-temp"]}
        }]
        }});

        /*
        {
        nameOfFunc : "update",
        data : {"v1" : 150, "v2" : 250, "v3" : 250, "v4" : 10, "maxTemp" : 100}
        }]}
        */
        tablet.initializeOrConfigureOrUpdate({

            "tablet-current-visual-container" : {
        configFuncs : [{
          nameOfFunc : "configure",
          data : {container: "#tablet-current-visual-container", title : "State", height : 300,  width : 590, xTicks : 6}
        }, {
          nameOfFunc : "render",
          data : currentCook.data
        }]},

        "tablet-blower-bar" : {
        configFuncs : [{
        nameOfFunc : "createCharts",
        data : undefined
        }]},
        "tablet-summary-visual-container" : {
        configFuncs : [{
        nameOfFunc : "configure",
        data : {container: "#tablet-summary-visual-container", title : "State", width : 580, xTicks : 0}
        }, {
        nameOfFunc : "render",
        data : {"v1" : currentCook.summary["cooker-minimum-temp"], "v2" : currentCook.summary["cooker-maximum-temp"], "v3" : currentCook.summary["meat-minimum-temp"], "v4" : currentCook.summary["meat-maximum-temp"]}
        }]
        }
        });


        desktop.initializeOrConfigureOrUpdate({

            "desktop-current-visual-container" : {
        configFuncs : [{
          nameOfFunc : "configure",
          data : {container: "#desktop-current-visual-container", title : "State", height : 300,  width : 790, xTicks : 10}
        }, {
          nameOfFunc : "render",
          data : currentCook.data
        }]},

        "desktop-blower-bar" : {
        configFuncs : [{
        nameOfFunc : "createCharts",
        data : undefined
        }]},
        "desktop-summary-visual-container" : {
        configFuncs : [{
        nameOfFunc : "configure",
        data : {container: "#desktop-summary-visual-container", title : "State", width : 790, xTicks : 0}
        }, {
        nameOfFunc : "render",
        data : {"v1" : currentCook.summary["cooker-minimum-temp"], "v2" : currentCook.summary["cooker-maximum-temp"], "v3" : currentCook.summary["meat-minimum-temp"], "v4" : currentCook.summary["meat-maximum-temp"]}
        }]
        }

        });
        });


        </script>



        <!-- end inputted code -->
      </div>
    </div>
  </div>
</div>
</div>

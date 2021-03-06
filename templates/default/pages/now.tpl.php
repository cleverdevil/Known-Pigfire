<?php

?>

<div class="row history-cook idno-content">
  <div class="col-md-10 col-md-offset-1">
    <div>
      <div class="row">
        <h1 class="p-name">Current Status</h1>

        <!-- begin imported code -->

<div class="input-html">
        <div class="gauge-labels"><div class="target-label">Target</div><div class="current-label">Current</div><div class="temperature-label">&#x000B0;F</div></div>
        <div id="mobile-now">
          <div class="target-gauge" id="mobile-target-gauge"></div>
          <div class="current-gauge" id="mobile-current-gauge"></div>
          <div class="chart-visual-container" id="mobile-target-visual-container"></div>
        </div>

        <div id="tablet-now">
          <div class="target-gauge" id="tablet-target-gauge"></div>
          <div class="current-gauge" id="tablet-current-gauge"></div>
          <div class="chart-visual-container" id="tablet-target-visual-container"></div>
        </div>

        <div id="desktop-now">
          <div class="target-gauge" id="desktop-target-gauge"></div>
          <div class="current-gauge" id="desktop-current-gauge"></div>
          <div class="chart-visual-container" id="desktop-target-visual-container"></div>
        </div>

        <div class="chart-labels">
          <div class="cooker-target-temp-label">cooker-target-temp</div>
          <div class="cooker-current-temp-label">cooker-current-temp</div>
          <div class="meat-target-temp-label">meat-target-temp</div>
          <div class="meat-current-temp-label">meat-current-temp</div>
          <div class="blower-on-label">blower-on-status</div>
        </div>

        <script>


        var correctTimeForCurrentChart = function(cookData){
          //get 7 hours in miliseconds

          var i;
          var uncorrectedTime;

          for (i = 0; i < cookData.data.length; ++i){
            uncorrectedTime = new Date(cookData.data[i].datetime);
            cookData.data[i].datetime = new Date(uncorrectedTime.valueOf() - 25200000);
          }

        };


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
        var mobileTargetGauge = new TempGauge('#mobile-target-gauge');
        var mobileCurrentGauge = new TempGauge('#mobile-current-gauge');
        var mobileBlowerBar = new Bar("grey", 250);
        var mobileCurrentChart = new Chart(mobileBlowerBar);

        //create tablet
        var tabletTargetGauge = new TempGauge('#tablet-target-gauge');
        var tabletCurrentGauge = new TempGauge('#tablet-current-gauge');
        var tabletBlowerBar = new Bar("grey", 300);
        var tabletCurrentChart = new Chart(tabletBlowerBar);

        //create desktop
        var desktopTargetGauge = new TempGauge('#desktop-target-gauge');
        var desktopCurrentGauge = new TempGauge('#desktop-current-gauge');
        var desktopBlowerBar = new Bar("grey", 300);
        var desktopCurrentChart = new Chart(desktopBlowerBar);

        // add each to screen
        var mobile = new SvgScreenDeviceGenerator("mobile-now");
        var tablet = new SvgScreenDeviceGenerator("tablet-now");
        var desktop = new SvgScreenDeviceGenerator("desktop-now");

        var makeLabelsVisible = function(){
            var i;
            var gaugeLabelsArry = document.getElementsByClassName('gauge-labels');
            var chartLabelsArry = document.getElementsByClassName('chart-labels');

            for (i = 0; i < gaugeLabelsArry.length; ++i){
                gaugeLabelsArry[i].style.display = "block";
            }

            for (i = 0; i < chartLabelsArry.length; ++i){
                chartLabelsArry[i].style.display = "block";
            }
        };

        var updateChart = function(currentCook){

correctTimeForCurrentChart(currentCook);

          mobile.initializeOrConfigureOrUpdate({
              "#mobile-target-gauge" : {
                  configFuncs : [{
                    nameOfFunc : "update",
                    data : {v1 : currentCook.data[currentCook.data.length - 1]["cooker-target-temp"], v2 : currentCook.data[currentCook.data.length - 1]["cooker-current-temp"]}
                  }]
              },
              "#mobile-current-gauge" : {
                  configFuncs : [{
                    nameOfFunc : "update",
                    data : {v1 : currentCook.data[currentCook.data.length - 1]["meat-target-temp"], v2 : currentCook.data[currentCook.data.length - 1]["meat-current-temp"]}
                  }]
              },
              "mobile-current-chart" : {
          configFuncs : [{
            nameOfFunc : "update",
            data : currentCook.data
          }]},
          "mobile-blower-bar" : {
          configFuncs : [{
          nameOfFunc : "createCharts",
          data : undefined
          }]}
          });

          tablet.initializeOrConfigureOrUpdate({
              "#tablet-target-gauge" : {
                  configFuncs : [{
                    nameOfFunc : "update",
                    data : {v1 : currentCook.data[currentCook.data.length - 1]["cooker-target-temp"], v2 : currentCook.data[currentCook.data.length - 1]["cooker-current-temp"]}
                  }]
              },
              "#tablet-current-gauge" : {
                  configFuncs : [{
                    nameOfFunc : "update",
                    data : {v1 : currentCook.data[currentCook.data.length - 1]["meat-target-temp"], v2 : currentCook.data[currentCook.data.length - 1]["meat-current-temp"]}
                  }]
              },
              "tablet-current-chart" : {
          configFuncs : [{
            nameOfFunc : "update",
            data : currentCook.data
          }]},
          "tablet-blower-bar" : {
          configFuncs : [{
          nameOfFunc : "createCharts",
          data : undefined
          }]}
          });

          desktop.initializeOrConfigureOrUpdate({
              "#desktop-target-gauge" : {
                  configFuncs : [{
                    nameOfFunc : "update",
                    data : {v1 : currentCook.data[currentCook.data.length - 1]["cooker-target-temp"], v2 : currentCook.data[currentCook.data.length - 1]["cooker-current-temp"]}
                  }]
              },
              "#desktop-current-gauge" : {
                  configFuncs : [{
                    nameOfFunc : "update",
                    data : {v1 : currentCook.data[currentCook.data.length - 1]["meat-target-temp"], v2 : currentCook.data[currentCook.data.length - 1]["meat-current-temp"]}
                  }]
              },
              "desktop-current-chart" : {
          configFuncs : [{
            nameOfFunc : "update",
            data : currentCook.data
          }]},
          "desktop-blower-bar" : {
          configFuncs : [{
          nameOfFunc : "createCharts",
          data : undefined
          }]}
          });
        };

        mobile.addVisualElems({
            "#mobile-target-gauge" : mobileTargetGauge,
            "#mobile-current-gauge" : mobileCurrentGauge,
            "mobile-current-chart" : mobileCurrentChart,
            "mobile-blower-bar" : mobileBlowerBar
        });

        tablet.addVisualElems({
            "#tablet-target-gauge" : tabletTargetGauge,
            "#tablet-current-gauge" : tabletCurrentGauge,
            "tablet-current-chart" : tabletCurrentChart,
            "tablet-blower-bar" : tabletBlowerBar
        });

        desktop.addVisualElems({
            "#desktop-target-gauge" : desktopTargetGauge,
            "#desktop-current-gauge" : desktopCurrentGauge,
            "desktop-current-chart" : desktopCurrentChart,
            "desktop-blower-bar" : desktopBlowerBar
        });

        d3.json("https://ui7363dy38.execute-api.us-east-1.amazonaws.com/dev/cooks/current.json", function(error, currentCook){

correctTimeForCurrentChart(currentCook);

        mobile.initializeOrConfigureOrUpdate({
            "#mobile-target-gauge" : {
                configFuncs : [{
                  nameOfFunc : "configure",
                  data : {container: "#mobile-target-gauge", title : "Cook", size : 300, clipWidth : 320, clipHeight : 320}
                }, {
                  nameOfFunc : "render",
                  data : undefined
                }, {
                  nameOfFunc : "update",
                  data : undefined
                }, {
                  nameOfFunc : "update",
                  data : {v1 : currentCook.data[currentCook.data.length - 1]["cooker-target-temp"], v2 : currentCook.data[currentCook.data.length - 1]["cooker-current-temp"]}
                }]
            },
            "#mobile-current-gauge" : {
                configFuncs : [{
                  nameOfFunc : "configure",
                  data : {container: "#mobile-current-gauge", title : "Meat", size : 300, clipWidth : 320, clipHeight : 320}
                }, {
                  nameOfFunc : "render",
                  data : undefined
                }, {
                  nameOfFunc : "update",
                  data : undefined
                }, {
                  nameOfFunc : "update",
                  data : {v1 : currentCook.data[currentCook.data.length - 1]["meat-target-temp"], v2 : currentCook.data[currentCook.data.length - 1]["meat-current-temp"]}
                }]
            },
            "mobile-current-chart" : {
        configFuncs : [{
          nameOfFunc : "configure",
          data : {container: "#mobile-target-visual-container", title : "State", width : 250, xTicks : 3}
        }, {
          nameOfFunc : "render",
          data : currentCook.data
        }]},

        "mobile-blower-bar" : {
        configFuncs : [{
        nameOfFunc : "createCharts",
        data : undefined
        }]}
        });

        tablet.initializeOrConfigureOrUpdate({
            "#tablet-target-gauge" : {
                configFuncs : [{
                  nameOfFunc : "configure",
                  data : {container: "#tablet-target-gauge", title : "Cooker", size : 300, clipWidth : 320, clipHeight : 320}
                }, {
                  nameOfFunc : "render",
                  data : undefined
                }, {
                  nameOfFunc : "update",
                  data : undefined
                }, {
                  nameOfFunc : "update",
                  data : {v1 : currentCook.data[currentCook.data.length - 1]["cooker-target-temp"], v2 : currentCook.data[currentCook.data.length - 1]["cooker-current-temp"]}
                }]
            },
            "#tablet-current-gauge" : {
                configFuncs : [{
                  nameOfFunc : "configure",
                  data : {container: "#tablet-current-gauge", title : "Meat", size : 300, clipWidth : 320, clipHeight : 320}
                }, {
                  nameOfFunc : "render",
                  data : undefined
                }, {
                  nameOfFunc : "update",
                  data : undefined
                }, {
                  nameOfFunc : "update",
                  data : {v1 : currentCook.data[currentCook.data.length - 1]["meat-target-temp"], v2 : currentCook.data[currentCook.data.length - 1]["meat-current-temp"]}
                }]
            },
            "tablet-current-chart" : {
        configFuncs : [{
          nameOfFunc : "configure",
          data : {container: "#tablet-target-visual-container", title : "State", height : 300,  width : 590, xTicks : 6}
        }, {
          nameOfFunc : "render",
          data : currentCook.data
        }]},

        "tablet-blower-bar" : {
        configFuncs : [{
        nameOfFunc : "createCharts",
        data : undefined
        }]}
        });


        desktop.initializeOrConfigureOrUpdate({
            "#desktop-target-gauge" : {
                configFuncs : [{
                  nameOfFunc : "configure",
                  data : {container: "#desktop-target-gauge", title : "Cook", size : 370, clipWidth : 390, clipHeight : 390}
                }, {
                  nameOfFunc : "render",
                  data : undefined
                }, {
                  nameOfFunc : "update",
                  data : undefined
                }, {
                  nameOfFunc : "update",
                  data : {v1 : currentCook.data[currentCook.data.length - 1]["cooker-target-temp"], v2 : currentCook.data[currentCook.data.length - 1]["cooker-current-temp"]}
                }]
            },
            "#desktop-current-gauge" : {
                configFuncs : [{
                  nameOfFunc : "configure",
                  data : {container: "#desktop-current-gauge", title : "Meat", size : 370, clipWidth : 390, clipHeight : 390}
                }, {
                  nameOfFunc : "render",
                  data : undefined
                }, {
                  nameOfFunc : "update",
                  data : undefined
                }, {
                  nameOfFunc : "update",
                  data : {v1 : currentCook.data[currentCook.data.length - 1]["meat-target-temp"], v2 : currentCook.data[currentCook.data.length - 1]["meat-current-temp"]}
                }]
            },
            "desktop-current-chart" : {
        configFuncs : [{
          nameOfFunc : "configure",
          data : {container: "#desktop-target-visual-container", title : "State", height : 300,  width : 790, xTicks : 10}
        }, {
          nameOfFunc : "render",
          data : currentCook.data
        }]},

        "desktop-blower-bar" : {
        configFuncs : [{
        nameOfFunc : "createCharts",
        data : undefined
        }]}
        });

        makeLabelsVisible();

        });


        setInterval(function(){

        d3.json("https://ui7363dy38.execute-api.us-east-1.amazonaws.com/dev/cooks/current.json", function(error, currentCook){
          updateChart(currentCook)
        });

        }, 8000);

        </script>
        <!-- end imported code -->


      </div>
    </div>
  </div>
</div>
</div>

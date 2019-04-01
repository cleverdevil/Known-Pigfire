var Bar = function (color, height) {
           this.allTimes = [];
           this.xStartBlowingXPos = undefined;
           this.color = color;
           this.passedSVG = undefined;
           this.config = {};
           this.config.height = height;
       }

       Bar.prototype.assignSVG = function (passedSVG) {
           this.passedSVG = passedSVG;
       };

       Bar.prototype.checkChartStatus = function (propToCheck, d, xPos) {
           if (d[propToCheck] === true && this.xStartBlowingXPos === undefined) {
               this.addXStartBlowingPos(xPos);
           } else if (d[propToCheck] === true && this.xStartBlowingXPos !== undefined) {
               this.drawChart(xPos, this.color);
               this.unsetXStartBlowingPos();
           }
       };

       Bar.prototype.addXStartBlowingPos = function (xStartBlowingXPos) {
           this.xStartBlowingXPos = xStartBlowingXPos;
       };

       Bar.prototype.unsetXStartBlowingPos = function () {
           this.xStartBlowingXPos = undefined;
       };

       Bar.prototype.drawChart = function (endingXPos) {
           this.passedSVG.append("rect")
               .style("fill", this.color)
               .style("opacity", "0.5")
               .attr("x", this.xStartBlowingXPos)
               .attr("width", endingXPos - this.xStartBlowingXPos)
               .attr("y", 0)
               .attr("height", this.config.height);
       };

       Bar.prototype.createCharts = function () {

           var i;

           //blower-on bar
           for (i = 0; i < this.allTimes.length; ++i) {
               this.checkChartStatus("blower-on", this.allTimes[i].d, this.allTimes[i].xPos);
           }

           this.xStartBlowingXPos = undefined;

       };

       Bar.prototype.addToTimes = function (xPos, d) {

           this.allTimes.push({ "xPos": xPos, "d": d });

       };

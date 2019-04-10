var Bar = function (color, height) {
           this.allTimes = [];
           this.xStartBlowingXPos = undefined;
           this.color = color;
           this.passedSVG = undefined;
           this.config = {};
           this.config.height = height;
           this.lastPosition = undefined;
       }

       Bar.prototype.assignSVG = function (passedSVG) {
           this.passedSVG = passedSVG;
       };

    /*   Bar.prototype.checkChartStatus = function (propToCheck, d, xPos, i) {
         //if it is undefined then we are starting from a new position
           if ((d[propToCheck] === true || d[propToCheck] === 1)  && this.xStartBlowingXPos === undefined) {
               this.addXStartBlowingPos(xPos);
           } else if ((d[propToCheck] === true || d[propToCheck] === 1) && this.xStartBlowingXPos !== undefined) {
               this.drawChart(xPos, this.color);
               this.unsetXStartBlowingPos();
           }
       };
*/

   Bar.prototype.checkChartStatus = function (propToCheck, allTimes, i) {
     //if it is undefined then we are starting from a new position
       if ((allTimes[i].d[propToCheck] === true || allTimes[i].d[propToCheck] === 1)  && this.xStartBlowingXPos === undefined) {
           this.addXStartBlowingPos(allTimes[i].xPos);
    // blower-can be off before a starting position is set by a blower being on. As soon as the
    // the blower is off take the position of the draw from the starting position up intil the position last on position
  } else if ((allTimes[i].d[propToCheck] === false || allTimes[i].d[propToCheck] === 0) && this.xStartBlowingXPos !== undefined) {
           this.drawChart(allTimes[i].xPos, this.color);
           this.unsetXStartBlowingPos();
           //unseting start position until blower is back on
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
               .style("opacity", "0.7")
               .attr("x", this.xStartBlowingXPos)
               .attr("width", endingXPos - this.xStartBlowingXPos)
               .attr("y", 0)
               .attr("height", this.config.height);
       };

       Bar.prototype.createCharts = function () {

           var i;
           var startPos;

           this.passedSVG.selectAll('rect').remove();

           if(startPos === undefined){
             startPos = 0;
           }

           //blower-on bar
           for (i = startPos; i < this.allTimes.length; ++i) {
              // this.checkChartStatus("blower-on", this.allTimes[i].d, this.allTimes[i].xPos, i);
              this.checkChartStatus("blower-on", this.allTimes, i);
           }

           //reset bar data after everything is drawn
           this.allTimes = [];

           this.config.lastPosition = i - 1;

           this.xStartBlowingXPos = undefined;

       };

       Bar.prototype.addToTimes = function (xPos, d) {

           this.allTimes.push({ "xPos": xPos, "d": d });

       };

var Chart = function (bar) {

            this.bar = bar;

            this.config = {
                //setting margins
                margin: { top: 30, right: 20, bottom: 30, left: 50 },
                width: (900 - (20 + 50)),
                height: (350 - (30 + 70)),
                parseDate: d3.time.format.iso.parse,
                x: undefined,
                y: undefined,
                xAxis: undefined,
                yAxis: undefined,
                svg: undefined,
            }

        };

        Chart.prototype.configure = function (config) {
            //variables that are not undefined can be immedi

            var that = this;
            var prop = undefined;

            //add properties
            for (prop in config) {
                this.config[prop] = config[prop]
            }

            this.config.x = d3.time.scale().range([0, this.config.width]);
            this.config.y = d3.scale.linear().range([this.config.height, 0]);

            // Define the axes
            this.config.xAxis = d3.svg.axis().scale(this.config.x)
                .orient("bottom").ticks(this.config.xTicks);

            this.config.yAxis = d3.svg.axis().scale(this.config.y)
                .orient("left").ticks(10);

        };


        Chart.prototype.getMaxTemp = function (data) {

            var maxCookerTargetTemp = d3.max(data, function (d) { return d["cooker-target-temp"]; });

            var maxCookerCurrentTemp = d3.max(data, function (d) { return d["cooker-current-temp"]; });

            var maxMeatTargetTemp = d3.max(data, function (d) { return d["meat-target-temp"]; });

            var maxMeatCurrentTemp = d3.max(data, function (d) { return d["meat-current-temp"]; });

            var maxTemp = d3.max([maxCookerTargetTemp, maxCookerCurrentTemp, maxMeatTargetTemp, maxMeatCurrentTemp], function (d) { return d });

            return maxTemp;

        };

        Chart.prototype.render = function (data) {

            var that = this;

            // config.container would be a separate container from the body
            this.config.svg = d3.select(this.config.container)
                .append('svg')
                .attr('id', this.config.container.slice(1) + 'chart-visual')
                .attr('width', this.config.width + this.config.margin.left + this.config.margin.right)
                .attr('height', this.config.height + this.config.margin.top + this.config.margin.bottom)
                .append("g")
                .attr("transform",
                    "translate(" + (this.config.margin.left) + "," + this.config.margin.top + ")");

            this.createFirst(data);

            if (this.bar.passedSVG === undefined) {
                this.bar.assignSVG(this.config.svg);
            }

        };

        //processes processes lines and tracks x
        Chart.prototype.lineProcessorGenerator = function (data, nameOfLine, that, trackX) {
            var lineProcessor;

            if (trackX === true) {
                lineProcessor = d3.svg.line()
                    .x(function (d) {
                        xPos = that.config.x(d.datetime);
                        that.bar.addToTimes(xPos, d);
                        return xPos;
                    })
                    .y(function (d) {
                        var t = that.config.y(d[nameOfLine])
                        return t;
                    })
            } else {
                lineProcessor = d3.svg.line()
                    .x(function (d) {
                        xPos = that.config.x(d.datetime);
                        return xPos;
                    })
                    .y(function (d) {
                        var t = that.config.y(d[nameOfLine])
                        return t;
                    })
            }

            return lineProcessor(data);
        };

        Chart.prototype.textGenerator = function (totalAmountOfTexts, text, pos, fill) {

            var legendSpace = this.config.width / totalAmountOfTexts;

            this.config.svg.append("text")
                .attr("x", (legendSpace / 2) + pos * legendSpace) // spacing
                .attr("y", this.config.height + (this.config.margin.bottom / 2) + 5)
                .attr("class", "legend")    // style the legend
                .style("fill", fill)
                .text(text);

        }

        Chart.prototype.appendLine = function (data, dashedBool, color, name, trackX) {

            if (dashedBool === true) {

                this.config.svg.append("path")
                    .attr("class", "line")
                    .attr("id", name)
                    .style("stroke-dasharray", ("4, 4"))
                    .style("stroke-width", 5)
                    .style("stroke", color)
                    .attr("d", this.lineProcessorGenerator(data, name, this, trackX));

            } else {

                this.config.svg.append("path")
                    .attr("class", "line " + name)
                    .attr("id", name)
                    .style("stroke-width", 5)
                    .style("stroke", color)
                    .attr("d", this.lineProcessorGenerator(data, name, this, trackX));

            }
        };

        Chart.prototype.createFSign = function(){
          this.config.svg.append("text")
                 .attr("transform", "translate(-50,0)")
                 .attr("x", 0)
                 .attr("y", this.config.height/2 )
                 .attr("font-size", "20px")
                 .attr("fill", "red")
                 .html("&#176;F")
        };

        Chart.prototype.createFirst = function (data) {

            var that = this;

            var maxTemp = this.getMaxTemp(data);

            data.forEach(function (d) {
                //d.datetime = that.config.parseDate(d.datetime);
            });

            // Scale the range of the data
            this.config.x.domain(d3.extent(data, function (d) { return d.datetime; }));
            this.config.y.domain([0, maxTemp]);

            this.appendLine(data, true, "red", "cooker-target-temp", true);

            //this.textGenerator(5, "cooker-target-temp", 0, "red");

            this.appendLine(data, false, "orange", "cooker-current-temp", false);

        //    this.textGenerator(5, "cooker-current-temp", 1, "orange");

            this.appendLine(data, true, "blue", "meat-target-temp", false);

        //    this.textGenerator(5, "meat-target-temp", 2, "blue");

            this.appendLine(data, false, "green", "meat-current-temp", false);

          //  this.textGenerator(5, "meat-current-temp", 3, "green");

          //  this.textGenerator(5, "blower-on", 4, "lightgrey");

            this.config.svg.append("g")
                .attr("class", "x axis")
                .attr("transform", "translate(0," + this.config.height + ")")
                .call(this.config.xAxis)

            // Add the Y Axis
            this.config.svg.append("g")
                .attr("class", "y axis")
                .call(this.config.yAxis);

            this.createFSign();

        };


        Chart.prototype.update = function (data) {

          var that = this;

          var maxTemp = this.getMaxTemp(data);

          data.forEach(function (d) {
            //  d.datetime = that.config.parseDate(d.datetime);
          });

          // Scale the range of the data
          this.config.x.domain(d3.extent(data, function (d) { return d.datetime; }));
          this.config.y.domain([0, maxTemp]);

  // Select the section we want to apply our changes to
  var svg = this.config.svg.transition();

  var maxCookerCurrentTemp = d3.max(data, function (d) { return d["cooker-current-temp"]; });

  var maxMeatTargetTemp = d3.max(data, function (d) { return d["meat-target-temp"]; });

  var maxMeatCurrentTemp = d3.max(data, function (d) { return d["meat-current-temp"]; });
  // Make the changes

     //last argument is set to true to redraw bars
      svg.select("#cooker-target-temp")   // change the line
          .duration(750)
          .attr("d", this.lineProcessorGenerator(data, "cooker-target-temp", this, true));

      svg.select("#cooker-current-temp")   // change the line
          .duration(750)
          .attr("d", this.lineProcessorGenerator(data, "cooker-current-temp", this, false));

      svg.select("#meat-target-temp")   // change the line
          .duration(750)
          .attr("d", this.lineProcessorGenerator(data, "meat-target-temp", this, false));

      svg.select("#meat-current-temp")   // change the line
          .duration(750)
          .attr("d", this.lineProcessorGenerator(data, "meat-current-temp", this, false));

      svg.select(".x.axis") // change the x axis
          .duration(750)
          .call(this.config.xAxis);

      svg.select(".y.axis") // change the y axis
          .duration(750)
          .call(this.config.yAxis);

        };

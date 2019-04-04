var SummaryChart = function (bar) {

            this.bar = bar;

            this.config = {
                //setting margins
                margin: { top: 30, right: 20, bottom: 30, left: 50 },
                width: (900 - (20 + 50)),
                height: (350 - (30 + 70)),
                x: undefined,
                y: undefined,
                xAxis: undefined,
                yAxis: undefined,
                svg: undefined,
								barPadding : 1,
                colors : ["green", "blue", "orange", "red"]
            }

        };

SummaryChart.prototype.createFSign = function(){
  this.config.svg.append("text")
         .attr("transform", "translate(-50,0)")
         .attr("x", 0)
         .attr("y", this.config.height/2 )
         .attr("font-size", "20px")
         .attr("fill", "red")
         .html("&#176;F")
};

        SummaryChart.prototype.configure = function (config) {
            //variables that are not undefined can be immedi

            var that = this;
            var prop = undefined;

            //add properties
            for (prop in config) {
                this.config[prop] = config[prop]
            }

            this.config.x = d3.scale.linear().range([0, this.config.width]);
            this.config.y = d3.scale.linear().range([this.config.height, 0]);

            // Define the axes
            this.config.xAxis = d3.svg.axis().scale(this.config.x)
                .orient("bottom").ticks(0);

            this.config.yAxis = d3.svg.axis().scale(this.config.y)
                .orient("left").ticks(10);

        };

        SummaryChart.prototype.render = function (data) {

            // config.container would be a separate container from the body
            this.config.svg = d3.select(this.config.container)
                .append('svg')
                .attr('id', this.config.container.slice(1) + ' summary-chart-visual')
                .attr('width', this.config.width + this.config.margin.left + this.config.margin.right)
                .attr('height', this.config.height + this.config.margin.top + this.config.margin.bottom)
                .append("g")
                .attr("transform",
                    "translate(" + (this.config.margin.left) + "," + this.config.margin.top + ")");

            this.createFirst(data);
        };

/*
"summary": {"id": "30083d4c-d43e-4fb6-b157-3f372f10f09c", "start-datetime": "2019-03-20T21:11:20.614150", "end-datetime": "2019-03-20T21:55:06.461676", "cook-duration": 2625, "cooker-minimum-temp": 70.98, "cooker-maximum-temp": 229.35999999999999, "meat-minimum-temp": 70.39, "meat-maximum-temp": 190.0000000000001}}
*/

SummaryChart.prototype.getMaxTemp = function(data){
    var maxTemp;

    var amtToHundred;

    if (data.v2 >= data.v4){
        maxTemp = data.v2;
    } else {
        maxTemp = data.v4;
    }

    amtToHundred = 100 - (maxTemp % 100);

    if(amtToHundred === 100){
      amtToHundred = 0;
    }

    maxTemp = amtToHundred + maxTemp;

        return maxTemp;

};

        SummaryChart.prototype.createFirst = function (data) {

            var that = this;

            // Scale the range of the data
						//dummy scale
            this.config.x.domain([0,5]);
            this.config.y.domain([0, this.getMaxTemp(data)]);

						var dataset = [data.v1, data.v2, data.v3, data.v4];

						this.config.svg.selectAll("rect")
														   .data(dataset)
														   .enter()
														   .append("rect")
															 .attr("x", function(d, i) {
						    return i * (that.config.width/ dataset.length) ;  //Bar width of 20 plus 1 for padding
						})
														   .attr("y", function(d) {
						    return (that.config.height - d);  //Height minus data value
						})
														   .attr("width", that.config.width / dataset.length)
														   .attr("height", function(d) {
						    return d;  //Height minus data value
						})
            .style("fill", function(d, i){
              return that.config.colors[i];
            })

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

        SummaryChart.prototype.update = function (data) {

          var that = this;

					this.config.x.domain([0,5]);
					this.config.y.domain([0, this.getMaxTemp(data)]);

					var dataset = [data.v1, data.v2, data.v3, data.v4];

											this.config.svg.selectAll("rect")
																		 .data(dataset)
																		 .transition()
																		 .duration(2000)
																		 .attr("x", function(d, i) {
											return i * (that.config.width/ dataset.length) ;  //Bar width of 20 plus 1 for padding
											})
																		 .attr("y", function(d) {
											return (that.config.height - d);  //Height minus data value
											})
																		 .attr("width", that.config.width)
																		 .attr("height", function(d) {
											return d;  //Height minus data value
											})
                      .style("fill", function(d, i){
                        return that.config.colors[i];
                      })


      this.config.svg.select(".x.axis")
			.transition() // change the x axis
          .duration(750)
          .call(this.config.xAxis);

      this.config.svg.select(".y.axis") // change the y axis
			.transition()
          .duration(750)
          .call(this.config.yAxis);

        };

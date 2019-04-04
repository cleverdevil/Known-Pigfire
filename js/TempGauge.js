function TempGauge(container) {
    prop = undefined;

    this.config = {

        size: 300,
        clipWidth: 300,
        clipHeight: 300,
        ringInset: 20,
        ringWidth: 40,

        pointerWidth: 10,
        pointerTailLength: 5,
        pointerHeadLengthPercent: 0.9,

        minValue: 0,
        maxValue: 10,

        minAngle: -90,
        maxAngle: 90,


        majorTicks: undefined,
        labelFormat: d3.format(',g'),
        labelInset: 10,

       arcColorFn: d3.interpolateHsl(d3.rgb("limegreen"), d3.rgb("orangered")),

        range: undefined,
        r: undefined,
        pointerHeadLength: undefined,
        value: 0,
        svg: undefined,
        arc: undefined,
        scale: undefined,
        ticks: undefined,
        tickData: undefined,
        pointer: undefined,
        maxValue: 300,
        transitionMs: 400,
        title : "Default",
      //  labels : [{text : "Target"}, {text : "Current"}]

    }

    this.config.container = container;

}

TempGauge.prototype.setMajorTicks = function deg2rad(max) {
    this.config.majorTicks = max / 50;
}

TempGauge.prototype.deg2rad = function deg2rad(deg) {
    return deg * Math.PI / 180;
}

TempGauge.prototype.newAngle = function newAngle(d) {
    var ratio = this.scale(d);
    var newAngle = this.config.minAngle + (ratio * this.config.range);
    return newAngle;
}

TempGauge.prototype.configure = function configure(config) {
    var that = this;
    var prop = undefined;
    for (prop in config) {
        this.config[prop] = config[prop];
    }

    this.config.range = this.config.maxAngle - this.config.minAngle;
    this.config.r = this.config.size / 2;
    this.config.pointerHeadLength = Math.round(this.config.r * this.config.pointerHeadLengthPercent);

    // a linear scale that maps domain values to a percent from 0..1
    this.config.scale = d3.scale.linear()
    .range([0, 1])

    this.config.scale.domain([0, 5000]);

    this.config.ticks = this.config.scale.ticks(this.config.majorTicks);
    this.config.tickData = d3.range(this.config.majorTicks).map(function () { return 1 / that.config.majorTicks; });

    this.config.arc = d3.svg.arc()
        .innerRadius(this.config.r - this.config.ringWidth - this.config.ringInset)
        .outerRadius(this.config.r - this.config.ringInset)
        .startAngle(function (d, i) {
            var ratio = d * i;
            return that.deg2rad(that.config.minAngle + (ratio * that.config.range));
        })
        .endAngle(function (d, i) {
            var ratio = d * (i + 1);
            return that.deg2rad(that.config.minAngle + (ratio * that.config.range));
        });
}

TempGauge.prototype.centerTranslation = function () {
  // extra 20 px for f sign
  return "translate(" + String((this.config.clipWidth/2) + 0) + ", " + String(this.config.clipHeight/2) + ")";
//    return 'translate(' + this.config.r + ',' + this.config.r + ')';
}

TempGauge.prototype.isRendered = function () {
    return (this.config.svg !== undefined);
}

TempGauge.prototype.getMaxTemp = function (data) {

  var maxTemp = 600;

  var amtToHundred;

  if (data === undefined){

      return maxTemp;

  }

    if(data.v1 >= data.v2){

        maxTemp =  data.v1;

    } else {
  maxTemp =  data.v2;

    }

amtToHundred = 100 - (maxTemp % 100);

if(amtToHundred === 100){

  amtToHundred = 0;
}

maxTemp = amtToHundred + maxTemp;

    return maxTemp;

};

TempGauge.prototype.render = function (newValues) {

    var that = this;

    this.createTitle();

    this.config.svg = d3.select(this.config.container)
        .append('svg:svg')
        .attr('class', 'gauge')
        .attr('width', this.config.clipWidth)
        .attr('height', this.config.clipHeight);

        /*var arcs = this.config.svg.append('text')
            .attr('class', 'tempLabel')
            .attr('transform', "translate(" + 0 + ", " + String(this.config.clipHeight/2) + ")")
            .text("℉");
*/
//this.createBottomLabels();

    var centerTx = this.centerTranslation();

//centerTx =  "translate(" + String(this.config.clipWidth/2) + ", " + String(this.config.clipHeight/2) + ")";

    var arcs = this.config.svg.append('g')
        .attr('class', 'arc')
        .attr('transform', centerTx);

    arcs.selectAll('path')
        .data(this.config.tickData)
        .enter().append('path')
        .attr('fill', function (d, i) {
        //    return that.config.arcColorFn[i]
        if(i === this.config.tickData.length - 1){
          return "orangered";
        } else {
            return that.config.arcColorFn(d * i);
          }
        })
        .attr('d', this.config.arc);

    var lg = this.config.svg.insert('g')
        .attr('class', 'label')
        .attr('transform', centerTx);
    lg.selectAll('text')
        .data(this.config.ticks)
        .enter().append('text')
        .attr('transform', function (d) {
            var ratio = that.config.scale(d);
            var newAngle = that.config.minAngle + (ratio * that.config.range);
            return 'rotate(' + newAngle + ') translate(0,' + (that.config.labelInset - that.config.r) + ')';
        })
        .text(this.config.labelFormat);

    var lineData = [[this.config.pointerWidth / 2, 0],
    [0, -this.config.pointerHeadLength],
    [-(this.config.pointerWidth / 2), 0],
    [0, this.config.pointerTailLength],
    [this.config.pointerWidth / 2, 0]];
    var pointerLine = d3.svg.line().interpolate('monotone');
    var pointerLine2 = d3.svg.line().interpolate('monotone');

    var pg = this.config.svg.append('g').data([lineData])
        .attr('class', 'pointer')
        .attr('transform', centerTx);

    var pg2 = this.config.svg.append('g').data([lineData])
        .attr('class', 'pointerTwo')
        .attr('transform', centerTx);

    this.config.pointer = pg.append('path')
        .attr('d', pointerLine/*function(d) { return pointerLine(d) +'Z';}*/)
        .attr('transform', 'rotate(' + this.config.minAngle + ')');

    this.config.pointer2 = pg2.append('path')
        .attr('d', pointerLine/*function(d) { return pointerLine(d) +'Z';}*/)
        .attr('transform', 'rotate(' + this.config.minAngle + ')');

    this.update(newValues === undefined ? 0 : newValues);
}

TempGauge.prototype.createTitle = function(){
  var parentElem;

  titleElem = document.createElement("div");
  titleElem.setAttribute("class", "arc-title");
  titleElem.textContent = this.config.title;

  parentElem = document.getElementById(this.config.container.slice(1));

  parentElem.appendChild(titleElem)

}

TempGauge.prototype.createBottomLabels = function(){
  var i;
  var containerBottomLabelsElem;
  var currentLabel;
  var parentElem;

  parentElem = document.getElementById(this.config.container.slice(1));

  containerBottomLabelsElem = document.createElement("div");
  containerBottomLabelsElem.setAttribute("class", "arcBottomLabelContainer");

  for (i = 0; i < this.config.labels.length; ++i){
    currentLabel = document.createElement("div");
    currentLabel.setAttribute("class", "arcLabel");
    currentLabel.textContent = this.config.labels[i].text;
    containerBottomLabelsElem.appendChild(currentLabel);
  }

  parentElem.appendChild(containerBottomLabelsElem);

}


TempGauge.prototype.update = function (newValues, newConfiguration) {
    if (newConfiguration !== undefined) {
        this.configure(newConfiguration);
    }

    var ratio;
    var ratio2;
    var newAngle;
    var newAngle2;

   var that = this;
   var nexMaxTemp = this.getMaxTemp(newValues);

   this.setMajorTicks(nexMaxTemp)

    //draw new ticks based on max
    this.config.scale.domain([this.config.minValue, nexMaxTemp]);
    this.config.ticks = this.config.scale.ticks(this.config.majorTicks);
    //how many times then map it
    this.config.tickData = d3.range(this.config.ticks.length - 1).map(function () { return 1 / (that.config.ticks.length  - 1) });

var centerTx = this.centerTranslation();

centerTx =  "translate(" + String(this.config.clipWidth/2) + ", " + String(this.config.clipHeight/2) + ")";
//removes and redraws gauge
d3.select(this.config.container + " .label").remove();

d3.select(this.config.container + " .arc").remove();

var arcs = this.config.svg.insert('g', this.config.container + " .pointer")
    .attr('class', 'arc')
    .attr('transform', centerTx);

arcs.selectAll('path')
    .data(this.config.tickData)
    .enter().append('path')
    .attr('fill', function (d, i) {
        if(i === that.config.tickData.length - 1){
          return "red";
        } else {
            return that.config.arcColorFn(d * i);
        }
    })
    .attr('d', this.config.arc);

    var lg = this.config.svg.insert('g', this.config.container + " .pointer")
        .attr('class', 'label')
        .attr('transform', centerTx);
    lg.selectAll('text')
        .data(this.config.ticks)
        .enter().append('text')
        .attr('transform', function (d) {
            var ratio = that.config.scale(d);
            var newAngle = that.config.minAngle + (ratio * that.config.range);
            return 'rotate(' + newAngle + ') translate(0,' + (that.config.labelInset - that.config.r) + ')';
        })
        .text(this.config.labelFormat);

// if it is undefined or 0 it is first time. Run default
    if (newValues === 0 || newValues === undefined) {
      newAngle = -90;
      newAngle2 = -90;

      this.config.pointer.transition()
          .duration(this.config.transitionMs)
          .ease('linear')
          .attr('transform', 'rotate(' + newAngle + ')');

      this.config.pointer2.transition()
          .duration(this.config.transitionMs)
          .ease('linear')
          .attr('transform', 'rotate(' + newAngle2 + ')');
    } else {
        ratio = this.config.scale(newValues.v1);
        ratio2 = this.config.scale(newValues.v2);
        newAngle = this.config.minAngle + (ratio * this.config.range);
        newAngle2 = this.config.minAngle + (ratio2 * this.config.range);

        this.config.pointer.transition()
            .duration(this.config.transitionMs)
            .ease('linear')
            .attr('transform', 'rotate(' + newAngle + ')');

        this.config.pointer2.transition()
            .duration(this.config.transitionMs)
            .ease('linear')
            .attr('transform', 'rotate(' + newAngle2 + ')');
    }



}

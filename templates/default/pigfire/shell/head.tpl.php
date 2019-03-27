<script src="https://d3js.org/d3.v3.min.js"></script>

<script src="<?= \Idno\Core\site()->config()->url; ?>IdnoPlugins/Pigfire/js/Bar.js"></script>
<script src="<?= \Idno\Core\site()->config()->url; ?>IdnoPlugins/Pigfire/js/Chart.js"></script>
<script src="<?= \Idno\Core\site()->config()->url; ?>IdnoPlugins/Pigfire/js/TempGauge.js"></script>


<style>
  #chartVisualContainer path {
    stroke: steelblue;
    stroke-width: 2;
    fill: none;
  }

  .axis path,
  .axis line {
    fill: none;
    stroke: grey;
    stroke-width: 1;
    shape-rendering: crispEdges;
  }

  .legend {
    font-size: 16px;
    font-weight: bold;
    text-anchor: middle;
  }

  #target-gauge {
    display : inline-block;
    margin-right : 30px;
  }

  #target-gauge g.arc {
    fill: steelblue;
  }

  #target-gauge g.pointer {
    fill: #e85116;
    stroke: #b64011;
  }

  #target-gauge g.pointerTwo {
    fill: blue;
    stroke: #b64011;
  }

  #target-gauge g.label text {
    text-anchor: middle;
    font-size: 14px;
    font-weight: bold;
    fill: #666666;
  }

  #current-gauge {
    display : inline-block;
  }

  #current-gauge g.arc {
    fill: steelblue;
  }

  #current-gauge g.pointer {
    fill: #e85116;
    stroke: #b64011;
  }

  #current-gauge g.pointerTwo {
    fill: blue;
    stroke: #b64011;
  }

  #current-gauge g.label text {
    text-anchor: middle;
    font-size: 14px;
    font-weight: bold;
    fill: #666666;
  }

</style>


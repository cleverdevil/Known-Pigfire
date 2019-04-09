<?php
$utc = new DateTimeZone('UTC');
$local = new DateTimeZone('America/Los_Angeles');
?>

<div class="row history-index idno-content">
  <div class="col-md-10 col-md-offset-1">
    <div>
      <div class="row">
        <h1 class="p-name">Cook History</h1>
        <p>
          Below is a list of all historical cooks that were actively tracked
          with the new tracking system. Click on a cook to view analytics.
        </p>
        <ul>
          <?php
          foreach ($cooks as $cook) {
          ?>
          <li>
          <?php 
              $cook_date = new DateTime($cook->{'start-datetime'}, $utc);
              $cook_date->setTimeZone($local);
              $cook_date_str = $cook_date->format('F d, Y - g:i A');
          ?>
          <a href="/history/<?= $cook->id ?>"><?= $cook_date_str ?></a></li>
          </li>
          <?php
          }
          ?>
        </ul>       
      </div>
    </div>
  </div>
</div>

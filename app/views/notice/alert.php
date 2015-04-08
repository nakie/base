<div class="ui-widget">
  <div class="ui-state-error ui-corner-all" style="padding: 0pt 0.7em;" >
    <p>
      <span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span>
      
<?php 

  $count = 0;
  
  foreach ( $this->alerts as $key => $error ) {
    
    if ( $count > 0 ) {
?>
      <br />
<?php
    }
?>
      <strong><?php print $key; ?> :</strong>
      <?php print $error; ?>
<?php
    $count ++;
  }
?>

    </p>
  </div>
</div>
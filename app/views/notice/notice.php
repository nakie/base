<div class="ui-widget">
  <div style="margin-top: 20px; padding: 0pt 0.7em;" class="ui-state-highlight ui-corner-all"> 
    <p>
      <span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-info"></span>
		<?php 
		
		  $count = 0;
		  
		  foreach ( $this->messages as $key => $msg ) {
		    
		    if ( $count > 0 ) {
		?>
		      <br />
		<?php
		    }
		?>
		      <strong><?php print $key; ?> :</strong>
		      <?php print $msg; ?>
		<?php
		    $count ++;
		  }
		?>
     
    </p>
  </div>
</div>
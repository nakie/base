<div class="contentheading">USER ADMINISTRATION</div>

<ul class="toplinks" >
       <?php //Menu::link( array( "Create New user"   => "user/add"   ) ); ?>
       <?php Menu::link( array( "Upload Member File"   => "user/csvfile"   ) ); ?>

</ul>
  
<div style="clear:both;"></div>
<table id="userTable" class ="display" width="100%">
  <thead>
    <tr>
    <th> Name </th>
    <th> Login </th>
    <th> Member<br/>Number </th>
    <th> Status </th>
    <th>  </th>
    <th>  </th>
    
    </tr>
  </thead>
  <tbody>
<?php 
  foreach ( $this->users as $user ) {
    
?>   
    <tr>
      <td> <?php print $user->name ?> </td>
      <td> <?php print $user->username ?> </td>
      <td> <?php print $user->member_num ?> </td>
      <td> 
<?php 
    if ( $user->capproval == "N" && $user->active == "N" && $user->mapproval == "N"   ){
      // RED Check account needs chamber approval
      //$icon = "r_check.gif";
      $msg = "Pending Chamber Approval";
      
    } elseif ( $user->capproval == "Y" && $user->active == "N" && $user->mapproval == "N"   ){
      // YELLOW Check account needs Member approval
     // $icon = "y_check.gif";
     $msg = "Pending Member Approval";
      
    } elseif ( $user->capproval == "Y" && $user->active == "Y" && $user->mapproval == "Y"   ){
      // GREEN Check account is Active
      //$icon = "g_check.gif";
      $msg = "Active";
      
    } else {
   // if ( $user->capproval == "Y" && $user->active == "N" && $user->mapproval == "Y"   ){
      // RED X CAccount was active but has been disabled
      // $icon = "r_x.gif";
      $msg = "Disabled";
      
    }

?>
<?php /*
 removed in favor of text  <img src= "/m2m/images/assets/<?php //print $icon; ?>" title="Account Status" />
*/
?>
<?php print $msg ?>
 </td>
      <td> 
      
          <a href="/m2m/user/admin/<?php print $user->user_id; ?>" title="Edit" >Edit</a>

      </td>
      <td> 
<?php 
    if ( $user->active != "Y" && $user->mapproval != "Y" && $user->capproval != "Y" ) {
?>
      <a href="/m2m/user/activate/<?php print $user->user_id; ?>" title="Approve" >Activate</a>
  
      
<?php
      } else if ( $user->capproval == "Y" && $user->active == "N" && $user->mapproval == "N" ){
?>
      <a href="/m2m/user/activate/<?php print $user->user_id; ?>" title="Approve" > Re-send </a>
      <?php } ?>
        </td>
    </tr>
    
<?php
  }
?>
  </tbody>
  </table>
<script>
$( document ).ready( function() { 
	
	$( "#userTable" ).dataTable({
		"bJQueryUI": true,
		"iDisplayLength": 25,
		"sPaginationType": "full_numbers" 
	});
    // call the tablesorter plugin 
   // $(".tablesorter")
   //   .tablesorter({
   //     headers: { 
    //      4: { sorter: false },
   //       5: { sorter: false }
   //     },      
   //     widgets: ['zebra']
    //  })

}); 
</script>
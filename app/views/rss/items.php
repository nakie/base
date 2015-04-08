
<?php 
  foreach ( $this->coupons as $coupon ) {
      // skip this coupon in the list IF it has reached its maximum number of prints.
    if ( $coupon->limit_count > 0 ) {
      
      $printNum = new_( "Printed" );
      $printNum->coupon_id = $coupon->coupon_id;

      // Get count and compare to limit set.
      if ( $printNum->count() >= $coupon->limit_count ) {
        
        continue; // Limit met or surpassed Do now show this coupon
        
      }   

      
    }
    
    if ( $coupon->info_select = "U" ) {
      
      $user = new_ ( "User" );
      $user->user_id = $coupon->user_id;
      
      $user->find();
      
      $companyName = $user->name;
      
    } else {
      
      $membership = new_ ( "Membership" );
      $membership->member_num = $coupon->member_num;
      
      $membership->find();
      
      $companyName = $membership->org_name;
      
    }
?>
      <item>
        <title>New Coupon available to Members from <?php print htmlentities($companyName) ?></title>
        <link>https://shoalschamber.com/m2m/coupon/show/<?php print $coupon->coupon_id ?></link>
        <guid>https://shoalschamber.com/m2m/coupon/show/<?php print $coupon->coupon_id ?></guid>
        <pubDate><?php print date( DATE_RSS , $coupon->sub_date );  ?></pubDate>
        <description><?php print htmlentities($coupon->damount) ?></description>
      </item>

<?php } ?>
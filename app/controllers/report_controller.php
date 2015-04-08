<?php
/**
 * COPYRIGHT (C) 1990-2011 
 * Integrated Corporate Solutions, Inc,
 * Florence, AL 35630
 * 
 * reports controller to handle logic for 
 * generated reports.
 * 
 * @author ICS, Nathan 
 * @package Controllers
 * 
 */
class report_controller extends application_controller
{
  	public $private = array("get_index_report");
  	public $privileges = 
            array(  "get_index_report"    => array( "admin" => "Y" ),

  								);

  								

    public function index() {

        $this->get_index_report();

    }
  								
  										
    public function get_index_report() {
      
      $user   = new_( "User" );      
      $coupon = new_( "Coupon" );
      //$coupon->active = 'Y'; // count active coupons.
      $day = date( "Ymd" );
      
      /*
       * if ( $coupon->active == "Y" && ( $coupon->limit_count >= $printed->count() ||$coupon->limit_count == 0 )) {
       * if ( $coupon->show_date <= $today && $coupon->start_date <= $today ) {
       * if ( $coupon->end_date >= $today && $coupon->exp_date >= $today ) {
       */
      
        $coupon->find( 
                      array( 
                             "all"    => "true", 
                             "where"  => "WHERE active = 'Y' AND approved = 'Y' AND show_date <= '$day' AND end_date >='$day' AND start_date <= '$day' AND exp_date >= '$day' AND user_id <> '3'" 
                             //"where"  => "WHERE active = 'Y' AND approved = 'Y' AND show_date <= '$day' AND end_date >='$day' AND start_date <= '$day' AND exp_date >= '$day'" 
                           ) 
                     );
       
 //       if ( data( 'id' ) == '1' ){
 //           echo count( $coupon->find_all );
 //           var_dump($coupon);
 //       }
      $print  = new_( "Printed" );
      $cTrash = new_( "Ctrashbin" );
      
      $this->view->totalPrints    = $print->count();
      $this->view->activeAccounts = $user->count();
      $this->view->activeCoupons  = count( $coupon->find_all );
      $this->view->cTrash = $cTrash->count();
      
      $this->view->totalCoupons =  $this->view->cTrash + $coupon->count() ;
      
    
      
      $this->view->set_content( "main" , "report_index.php" );
			$this->view->render_page();
     
    }
    
    public function get_allcoupon_report() {
  
      $user   = new_( "User" );      
      $coupon = new_( "Coupon" );
      $print  = new_( "Printed" );
      $cTrash = new_( "Ctrashbin" );

      
      $coupon->find( array( "all" => "TRUE" ) );
      $cTrash->find( array( "all" => "TRUE" ) );
      
      $all = array_merge($coupon->find_all , $cTrash->find_all );
      //var_dump( $all );
      

}
    
  

}
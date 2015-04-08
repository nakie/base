<?php
/**
 * COPYRIGHT (C) 1990-2011 
 * Integrated Corporate Solutions, Inc,
 * Florence, AL 35630
 * 
 *
 * System Message Class to act as a container for gerneric reuseable system wide
 * messages
 * 
 * @package Models
 * @subpackage Helpers
 *
 */
class Sysmsg {
  
  
  /**
   * Coupon Disclamer
   */
  
  public $coupon_disclamer = "This coupon has no cash value. See website for terms and conditions.";
  /**
   * System ERROR Messages
   */
  
  
  
  /**
   * Email subject / body for messages sent from the system.
   *
   * @var unknown_type
   */
  public $new_account_subject = "Your account has been created.";
  public $new_account_email   = "Welcome to the Shoals Chamber of Commerce, Member to Member Coupon web site. <br /> <br />
    This account is currently awaiting approval from the Shoals Chamber of Commerce. You will receive notice once 
    this account has been confirmed and approved.<br /> <br />    
    Thank You,<br />
    The Shoals Chamber of Commerce.";
  
  
  public $new_admin_subject = "A new account is awaiting approval.";  
  public $new_admin_email   = 'Hello,  <br /> <br /> 
  
      An account for <!--{%USERNAME}--> has been created on the Shoals Chamber of Commerce, 
      Member to Member Coupon web site.  This account is currently awaiting approval from an 
      Administrator. To approve this account please go to the following link. <br /><br />
      <a href="<!--{%LINK}-->" ><!--{%LINK}--></a> <br /><br />
      or copy and paste this url into your browser: <br /><br /> <!--{%LINK}-->  <br /> <br />
      Thank You,';  
  
  
  public $coupon_approve_subject = "A new coupon is awaiting approval.";   
  public $coupon_approve_email = "A new Coupon has been created on the Shoals Chamber of Commerce, Member to Member
                             discount web site.  This coupon is awaiting approval from an Admninistrator.
                             To preview this coupon and see all coupons waiting for approval please go to. \n  ";
  
  public $member_approval_subject = "An account created at Shoals Chamber needs your approval.";   
  public $member_approval_email = 'Hello, <br /> <br /> An account has been created on the Shoals Chamber of Commerce,
       Member to Member Coupon web site with the following information. <br /> <br />
       Username: <!--{%USERNAME}--> <br /> <br />
       To activate this account you must approve it by following this link<br /><br />. 
       <a href="<!--{%LINK}-->" ><!--{%LINK}--></a> <br /> <br />
       or copy and paste this url into your browser: <br /><br /> <!--{%LINK}-->  <br /> <br />
       If you do not wish to activate this account you can simply ignore this message.  If you are are not sure why
       you are receiving this message please feel free to contact the Shoals Chamber of Commerce.<br /> <br />
       Thank You, <br />
       The Shoals Chamber of Commerce';  
  
  public $user_approved_subject = "Your Account has been Activated.";   
  public $user_approved_email = 'Hello, <br /> <br /> You have received this notice because the account created for 
       you on the Shoals Chamber of Commerce Member to Member Coupon web site as been activated.  The account is 
       for user:  <!--{%USERNAME}--> <br /> <br />
       <br />. Use the following link to login 
       <a href="https://www.shoalschamber.com/m2m/user/login/" >https://www.shoalschamber.com/m2m/user/login/</a>
       <br /> <br /> 
       If you did not create or approve this account\'s creation please contact the Shoals Chamber of Commerce 
       for assistance.
       <br /> <br />
       Thank You, <br />
       The Shoals Chamber of Commerce';  
  
  
}

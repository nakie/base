<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb">

<head>
  
  <base href="https://shoalschamber.com/m2m/" />
  
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="robots" content="index, follow" />
  <meta name="keywords" content="Coupons, Shoals Area, Member Discounts" />
  <meta name="description" content="Member to Member Discounts :Shoals Chamber of Commerce" />

  <link href="/templates/shoals-chamber/favicon.ico" rel="shortcut icon" type="image/x-icon" />
  
  <link rel="stylesheet" href="<?php app()?>/css/template.css" type="text/css" />    
  <link rel="stylesheet" href="<?php app()?>/css/main.css" type="text/css" />
  <link rel="stylesheet" href="<?php app()?>/css/custom-theme/jquery-ui-1.8.11.custom.css" type="text/css" />
  
  <link rel="stylesheet" href="<?php app()?>/css/blue/style.css" type="text/css" />
  
  <link rel="stylesheet" href="<?php app()?>/css/table.css" type="text/css" />
  <link rel="stylesheet" href="<?php app()?>/css/table_jui.css" type="text/css" />
  <link rel="stylesheet" href="<?php app()?>/css/jquery.dataTables.css" type="text/css" />
  <link rel="stylesheet" href="<?php app()?>/css/print.css" media="print" type="text/css" />

  <?php
  	// Jquery upgrade 3/5/2014 
  	// pulled update after testing datTable. it was'nt needed and i wanted to preserve the
  	// custom Jquery UI theme.  	
  	/*
  	keeping these here in cause needed in the future
	  <link rel="stylesheet" href="<?php app()?>/css/ui-lightness/jquery-ui-1.10.4.custom.min.css" type="text/css" />
	  <script type="text/javascript" src="<?php app()?>/js/jquery-1.11.0.min.js" ></script>
	  <script type="text/javascript" src="<?php app()?>/js/jquery-ui-1.10.4.custom.min.js" ></script>  
  	*/
  ?>
  <script type="text/javascript" src="<?php app()?>/js/jquery-1.5.1.min.js" ></script>
   <script type="text/javascript" src="<?php app()?>/js/jquery-ui-1.8.11.custom.min.js" ></script>
  
  <script type="text/javascript" src="<?php app()?>/js/jquery.validate.min.js" ></script>
  <script type="text/javascript" src="<?php app()?>/js/additional-methods.js" ></script>
  <script type="text/javascript" src="<?php app()?>/js/jquery.tablesorter.min.js" ></script>
  <script type="text/javascript" src="<?php app()?>/js/jquery.tablesorter.pager.js" ></script>
    <script type="text/javascript" src="<?php app()?>/js/jquery.dataTables.min.js" ></script>
  
  <title>Shoals Chamber of Commerce Member to Member Coupons</title>

</head>

<body >

  <!--anchor for top-->
  <div id="container" ><!--container goes here-->

    <div id="header" >

      <a href="http://shoalschamber.com" title="Shoals Chamber of Commerce" id="logo"><img src="../images/logo.jpg" title="Shoals Chamber of Commerce" /> </a>
      <div id="top" >
        <div class="moduletable" >
          <ul class="menu" >
            <li id="current" class="active item2" >
              <a href="http://shoalschamber.com/" >
                <img src="<?php app()?>/images/assets/home-icon.png" align="left" alt="home" />
              </a>
            </li>
            <li class="item3" >
              <a href="/member-calendar.html" >
                <img src="<?php app()?>/images/assets/cal-icon.png" align="left" alt="calendar" />
              </a>
            </li>
            <li class="item4" >
              <a href="/contact.html" >
                <img src="<?php app()?>/images/assets/contact-icon.png" align="left" alt="contact" />
              </a>
            </li>
            <li class="item127" >
              <a href="/Join/join-today.html" >
                <img src="<?php app()?>/images/assets/join-icon.png" align="left" alt="join" />
              </a>
            </li>
            <li class="item5" >
              <a href="http://shoalschamber.com/member-test.html#cid=263&amp;did=8" >
                <img src="<?php app()?>/images/assets/directory-icon.png" align="left" alt="member-directory" />
              </a>
            </li>
            <li class="item6" >
              <a href="/media.html" >
                <img src="<?php app()?>/images/assets/news-icon.png" align="left" alt="news" />
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div><!--//header-->
    
    <div id="nav" > </div>

    <!-- Begin #container2 this holds the content and sidebars-->
    <div id="container2" >
    <!-- Begin #container3 keeps the left col and body positioned-->
      <div id="container3" >
        <div id="user4" > 
    		  <div class="moduletable" >
            
    		  </div>
        </div>
        
        <!-- #left sidebar -->  
        <div id="sidebarLT" >
          <ul id="menu" >
            <?php Menu::link( array( "List Coupons"   => "coupon"   ) ); ?>
            
            <?php Menu::link( array( "Manage Users"   => "user/manage"   ) ); ?>
            <?php Menu::link( array( "Manage Coupons" => "coupon/manage" ) ); ?>       
            <?php Menu::link( array( "Reports"        => "report/index"  ) ); ?>
            
            <?php Menu::link( array( "Manage Coupons" => "coupon/mylist"   ) ); ?>
            <?php Menu::link( array( "New Coupon"     => "coupon/create" ) ); ?>
            
            <?php Menu::link( array( "Account"        => "user/update"   ) ); ?>
            <?php Menu::link( array( "Logout"         => "user/logout"   ) ); ?>
            <?php Menu::link( array( "Login"          => "user/login"    ) ); ?>
            <?php Menu::link( array( "Sign up"        => "user/create"   ) ); ?>
          </ul>
          <div id="pushdown"> </div>
    			<div class="moduletable" >
  				  <p> <br /></p>
            <p style="PADDING-LEFT: 30px" mce_style="padding-left: 30px;">
              <a href="http://twitter.com/shoalschamber" target="_blank" mce_href="http://twitter.com/shoalschamber"><img border=0 src="<?php app()?>/images/assets/twitter-img.jpg" mce_src="/images/stories/twitter-img.jpg"></a>
              <a href="https://www.facebook.com/ShoalsChamber" target="_blank" mce_href="https://www.facebook.com/ShoalsChamber"><img border=0 src="<?php app()?>/images/assets/facebook-img.jpg" mce_src="/images/stories/facebook-img.jpg"></a>
              <br mce_bogus="1" />
            </p>		
          </div>
        </div>
        <!--//sidebarLT -->
      
        <!-- Begin #content -->
        <div id="content" >
  
          <div class="pushbottom" ></div>
          <?php $this->render_content( "alerts" ); ?>
          <?php $this->render_content( "notice" ); ?>
          <?php $this->render_content( "main" ); ?>

        </div><!-- //content -->


      </div><!--//container3-->
    </div><!--//container2-->
	<!--copy start-->
	<div style="clear:both"></div>
		<div style="width:100%; height:32px; text-align:center; padding:0px 0px 5px 0px; color:#c9c9c9; font-family: Tahoma,Verdana,Arial,Helvetica,sans-serif; font-size:11px;">&copy; <?php echo date("Y") ?> Shoals Chamber of Commerce <br /> <a href="site/terms">Terms &amp; Conditions</a></div>
	<!--copy end-->
    <div class="pushbottom" ></div>
    
    <div id="footer" class="text" > 
  		<div class="moduletable" >
        <div class="footer-text" >
          Shoals Chamber of Commerce -- 20 Hightower Place -- Florence, AL 35630 -- 256-764-4661
        </div>		
      </div>  	
    </div><!--//footer-->
    
  </div><!--//container-->
  
</body>

</html>
<html>
<head>
<link rel="stylesheet" href="<?php app()?>/css/wireframe.css" media="screen" />
<link rel="stylesheet" href="<?php app()?>/css/main.css" media="screen" />
<link rel="stylesheet" href="<?php app()?>/css/print.css" media="print" />
<link rel="stylesheet" href="<?php app()?>/css/custom-theme/jquery-ui-1.8.11.custom.css" media="screen" />
</head>
<body>

<div id="container">

  <div id="header">
  
  </div>

  <div id="main">
    <div id="mainleft">

    <h3> Menu </h3>
    <ul>
      <?php Menu::link( array( "List Coupons"   => "coupon/list"    ) ); ?>      
      
      <?php Menu::link( array( "Manage Users"   => "user/manage"    ) ); ?>
      <?php Menu::link( array( "Manage Coupons" => "coupon/manage"  ) ); ?>      
      
      <?php Menu::link( array( "Reports"        => "report/index"   ) ); ?>
      
      <?php Menu::link( array( "Account"        => "user/update"    ) ); ?>
      <?php Menu::link( array( "New Coupon"     => "coupon/create"  ) ); ?>
      
      <?php Menu::link( array( "Logout"         => "user/logout"    ) ); ?>
      <?php Menu::link( array( "Login"          => "user/login"     ) ); ?>
      <?php Menu::link( array( "Sign up"        => "user/create"    ) ); ?>
    </ul>
    
    </div>
    
    <div id="mainright">
      <?php $this->render_content("main"); ?>
    
    </div>
  <div class="clearDiv"></div>
	</div>
	
	<div id="footer">
	
	</div>
  <div>
    <pre>
    
      <?php var_dump($_SESSION);?>
    
    </pre>
  </div>	
</div>

</body>



</html>		

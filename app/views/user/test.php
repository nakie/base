<?php


var_dump( $this->pass1 );

echo "<br /> \n/n <br >";

var_dump( $this->pass2);

?>


<form method="post" action="<?php app() ?>/">
<input type="hidden" name="post" value="test_user" />




	   Password:</br>


	   <input type="text" name="pass" id="passwordFld"  class="input" /></br>
	   

  	
  	 <input type="submit" name="submit" value="Submit" />
</br>
	
</form>
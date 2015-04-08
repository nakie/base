<div class="contentheading">CREATE YOUR ACCOUNT</div>

<form id="newUserFrm" method="post" action="<?php app() ?>/">
<input type="hidden" name="post" value="create_user" />
<input type="hidden" name="stop" value="true" />


<fieldset>
  <legend class="ui-corner-all"> Login Information </legend>
  <table > 
    <tr>
      <td class="frmLabels" >
      <label for="usernameFld" > Email address: <strong>*</strong> </lable>
      </td>
      <td>
      <input type="text" name="username" id="usernameFld"  class="input ui-corner-all" value="<?php print $this->user->username; ?>" />
      </td>
    </tr>
    <tr>
      <td class="frmLabels" >
        <label for="passwordFld" > Password: <strong>*</strong> </label> 
      </td>
      <td>
      <input type="password" name="password" id="passwordFld"  class="input ui-corner-all" />
      <br /><span class="helpTxt"> Minimum 6 Characters.</span>
      </td>
    </tr>  
    <tr>
      <td class="frmLabels" >
     <label for="password2Fld" >  Confirm Password: <strong>*</strong> </lable>
      </td>
      <td>
      <input type="password" name="password2" id="password2Fld"  class="input ui-corner-all" />
      </td>
    </tr>
  </table>
  <br />
<!--
 Email address: <input type="text" name="username" id="usernameFld"  class="input" value="<?php print $this->user->username; ?>" />
 <br />
 Password: <input type="password" name="password" id="passwordFld"  class="input" />
 <br />
 re enter Password:  <input type="password" name="password2" id="password2Fld"  class="input" />
 -->
</fieldset>

<fieldset>
  <legend class="ui-corner-all"> Company Information</legend>
  <table>
    <tr>
      <td class="frmLabels" >
       <label for="mnameFld" > Company Name:  <strong>*</strong> </lable>
      </td>
      <td>
        <input type="text" name="name" id="mnameFld"  class="input ui-corner-all" value="<?php print $this->user->name; ?>" />
      </td>
    </tr>
    <tr>
      <td class="frmLabels" >
       <label for="addyFld" > Street Address: </label>
      </td>
      <td>
        <input type="text" name="address" id="addyFld"  class="input ui-corner-all" value="<?php print $this->user->address; ?>" />
      </td>
    </tr>
    <tr>
      <td class="frmLabels" >
        <label for="cityFld" >  City: </label>
      </td>
      <td>
        <input type="text" name="city" id="cityFld"  class="input ui-corner-all" value="<?php print $this->user->city; ?>" />
      </td>
    </tr>
    <tr>
      <td class="frmLabels" >
       <label for="stateFld" > State: </label>
      </td>
      <td>
        <!-- <input type="text" name="state" id="stateFld"  class="input ui-corner-all" value="<?php print $this->user->state; ?>" /> -->
      <select name="state" id="stateFld" class="input ui-corner-all" >
<?php 
  $states = array('AL'=>"Alabama",'AK'=>"Alaska",'AZ'=>"Arizona",'AR'=>"Arkansas",'CA'=>"California",'CO'=>"Colorado",'CT'=>"Connecticut",'DE'=>"Delaware",'DC'=>"District Of Columbia",'FL'=>"Florida",'GA'=>"Georgia",'HI'=>"Hawaii",'ID'=>"Idaho",'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa",  'KS'=>"Kansas",'KY'=>"Kentucky",'LA'=>"Louisiana",'ME'=>"Maine",'MD'=>"Maryland", 'MA'=>"Massachusetts",'MI'=>"Michigan",'MN'=>"Minnesota",'MS'=>"Mississippi",'MO'=>"Missouri",'MT'=>"Montana",'NE'=>"Nebraska",'NV'=>"Nevada",'NH'=>"New Hampshire",'NJ'=>"New Jersey",'NM'=>"New Mexico",'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma", 'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
  foreach ( $states as $key => $st ) {
  
    print '<option value="' . $key . '" >' . $st . '</option>';
  
  }
?>

      </select>
      </td>
    </tr>
    <tr>
      <td class="frmLabels" >
       <label for="zipFld" > Zip: </label>
      </td>
      <td>
        <input type="text" name="zip" id="zipFld"  class="input ui-corner-all" value="<?php print $this->user->zip; ?>" />
      </td>
    </tr>
    <tr>
      <td class="frmLabels" >
     <label for="phoneFld" >   Phone:  <strong>*</strong> </label>
      </td>
      <td>
        <input type="text" name="phone" id="phoneFld"  class="input  ui-corner-all" value="<?php print $this->user->phone; ?>" />
      </td>
    </tr> 
  </table>
</fieldset>
<fieldset>
<table>     
  <tr>
	<td class="frmLabels" >&nbsp;</td>
	<td>
	   <input type="checkbox" name="terms"  />  I Agree to the 
           <a id="termslink" href="#" target="_blank" title ="Terms and Conditions" >
              Terms and Conditions of Use.
           </a>
	</td>
  </tr>

</table>
</fieldset>	 
<fieldset>
<table>     

  <tr>
  	<td class="frmLabels" >&nbsp;</td>
  	<td>
  		<?php print $this->captcha ?>
  		<?php  //$this->captcha->render() ?>
  	</td>  
  </tr>
</table>
</fieldset>	 
<fieldset>
<table>     
  <tr>
	<td class="frmSubmit" ></td>
	<td>
	  <input type="submit" name="submit" value="Submit" />
	</td>
  </tr>
</table>
</fieldset>	 



</form>
<div id="tnc" style="display:none"><?php $this->render_partial('user_terms.php'); ?></div>
<script type="text/javascript">
$( document ).ready(function () {

//$("#newUserFrm").validate(); 

  // launch Terms and condition Preview 
  $( '#termslink' ).click( function() {
    
    $( '#tnc' ).dialog({  
                                 "width":"600",
                                 "height":"400",
                                 "title": "Terms and Conditions"
                                });  
                                return false;
  });
  
// validate signup form on keyup and submit
	$( "#newUserFrm" ).validate( {
		rules: {
			username: {
				required: true,
				email: true
			},
			password: {
				required: true,
				minlength: 6
			},
			password2: {
				required: true,
				minlength: 6,
				equalTo: "#passwordFld"
			},
			name: {
				required: true
			},
			phone: {
				required: true,
				phoneUS: true
			},
			terms: {
				required: true
			}

		},
		messages: {
			username: {
        		required: "Please enter your email address.",
				email: "You must enter a valid email address."
			},
			password: {
				required: "Please enter a password.",
				minlength: "Your password must be at least 6 characters long."
			},
			password2: {
				required: "You must confirm your password.",
				minlength: "Your password must be at least 6 characters long.",
				equalTo: "The Passwords do not match."
			},
			name: {
				required: "Company Name is Required."
			},
			phone: {
				required: "A Phone Number is Required.",
				phoneUS: "Valid formats are 256 555 5555 or 256-555-5555."
			},
			terms: {
				required: "You must accept the Terms and Conditions to create your account."
			}
		}
	});

});

</script>
<!--
<br />
  	 Member Name:

  	
  	 <input type="text" name="name" id="mnameFld"  class="input" value="<?php // print $this->user->name; ?>" />
<br />
  	
  	 Street Address:
  	 

  	
  	 <input type="text" name="address" id="addyFld"  class="input" value="<?php //print $this->user->address; ?>" />
<br />
  	
  	 State:
  	 

  	
  	 <input type="text" name="state" id="stateFld"  class="input" value="<?php //print $this->user->state; ?>" />
  	
<br />
	   
	   Zip:
	   

	 
	   <input type="text" name="zip" id="zipFld"  class="input" value="<?php //print $this->user->zip; ?>" />
	   
<br />
  	
  	 Phone:
  	

  	
  	 <input type="text" name="phone" id="phoneFld"  class="input" value="<?php //print $this->user->phone; ?>" />
  	
 -->




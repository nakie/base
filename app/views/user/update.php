<div class="contentheading" >
UPDATE YOUR ACCOUNT
</div>

<form id="acct_update_form" method="post" action="<?php app() ?>/">
<input type="hidden" name="post" value="update_user" />
<input type="hidden" name="user_id" value="<?php print $this->user->user_id; ?>" />

<fieldset>
<legend class="ui-corner-all"> Login Information </legend>
<table> 
  <tr>
     <td class="frmLabels" >
     Email address:
    </td>
    <td>
    <input type="text" name="username" id="usernameFld"  class="input ui-corner-all" value="<?php print $this->user->username; ?>" />
    </td>
  </tr>
  <tr>
     <td class="frmLabels" >
    Password:
    </td>
      <td>
    <input type="password" name="password" id="passwordFld"  class="input ui-corner-all" />
    <br /><span class="helpTxt"> Minimum 6 Characters.</span>
    </td>
  </tr>  
  <tr>
     <td class="frmLabels" >
     Confirm Password:
    </td>
      <td>
    <input type="password" name="password2" id="password2Fld"  class="input ui-corner-all" />
    </td>
  </tr>
</table>
<br />
</fieldset>

<fieldset>
<legend class="ui-corner-all"> Company Information</legend>
<table>
  <tr>
     <td class="frmLabels" >
    Company Name:
    </td>
    <td>
    <input type="text" name="name" id="mnameFld"  class="input ui-corner-all" value="<?php print $this->user->name; ?>" />
    </td>
  </tr>
  <tr>
     <td class="frmLabels" >
    Street Address:
    </td>
    <td>
    <input type="text" name="address" id="addyFld"  class="input ui-corner-all" value="<?php print $this->user->address; ?>" />
    </td>
  </tr>
  <tr>
     <td class="frmLabels" >
    City:
    </td>
    <td>
    <input type="text" name="city" id="cityFld"  class="input ui-corner-all" value="<?php print $this->user->city; ?>" />
    </td>
  </tr>
   
     <td class="frmLabels" >
    State:
    </td>
    <td>
    <input type="text" name="state" id="stateFld"  class="input ui-corner-all" value="<?php print $this->user->state; ?>" />
    </td>
  </tr>
    <tr>
     <td class="frmLabels" >
    Zip:
    </td>
    <td>
    <input type="text" name="zip" id="zipFld"  class="input ui-corner-all" value="<?php print $this->user->zip; ?>" />
    </td>
  </tr>
    <tr>
     <td class="frmLabels" >
    Phone:
    </td>
    <td>
    <input type="text" name="phone" id="phoneFld"  class="input ui-corner-all" value="<?php print $this->user->phone; ?>" />
    </td>
  </tr>
 
</table>
</fieldset>
<!-- 
<table>	
	<tr>
  	<td align="right">
  	
  	   Username:
  	
   </td>
  	<td>

  	 <input type="text" name="username" id="usernameFld"  class="input" value="<?php print $this->user->username; ?>" />
  	
	 </td>
	</tr>
	<tr>
	 <td align="right">

	   Password:
	   
	 </td>
	 <td>

	   <input type="password" name="password" id="passwordFld"  class="input" />
	   
	 </td>
	</tr>
	<tr>
	 <td align="right">
	 
	   re enter Password:
	   
	 </td>
	 <td>
	 
	   <input type="password" name="password2" id="password2Fld"  class="input" />
	   
	 </td>
	</tr>
	<tr>
  	<td align="right">
  	
  	 Member Name:
  	 
  	</td>
  	<td>
  	
  	 <input type="text" name="name" id="mnameFld"  class="input" value="<?php print $this->user->name; ?>" />
  	 
  	</td>
	</tr>
	<tr>
  	<td align="right">
  	
  	 Street Address:
  	 
  	</td>
  	<td>
  	
  	 <input type="text" name="address" id="addyFld"  class="input" value="<?php print $this->user->address; ?>" />
  	
  	 </td>
	</tr>
	<tr>
  	<td align="right">
  	
  	 state:
  	 
  	</td>
  	<td>
  	
  	 <input type="text" name="state" id="stateFld"  class="input" value="<?php print $this->user->state; ?>" />
  	
  	 </td>
	</tr>
	<tr>
	 <td align="right">
	   
	   zip:
	   
	  </td>
	 <td>
	 
	   <input type="text" name="zip" id="zipFld"  class="input" value="<?php print $this->user->zip; ?>" />
	   
	 </td>
	</tr>
	<tr>
  	<td align="right">
  	
  	 Phone:
  	
  	</td>
  	<td>
  	
  	 <input type="text" name="phone" id="phoneFld"  class="input" value="<?php print $this->user->phone; ?>" />
  	
  	</td>
  </tr>
	<tr>
  	<td colspan="2" align="right">
  	
  	 <input type="submit" name="submit" value="Submit" />
  	 
  	</td>
	</tr>
</table>
-->

<fieldset> 
<table>
<tr>
<td width="35%"></td>
<td> <input type="submit" name="submit" value="Submit" />
</td>
</tr>
</table>
</fieldset>

  	
</form>

<script type="text/javascript">
$( document ).ready(function () {

//$("#newUserFrm").validate(); 

// validate signup form on keyup and submit
	$( "#acct_update_form" ).validate( {
		rules: {
			username: {
				required: true,
				email: true
			},
			password: {
				minlength: 6
			},
			password2: {
				minlength: 6,
				equalTo: "#passwordFld"
			},

		},
		messages: {
			username: {
    		required: "Please enter your email address.",
				email: "You must enter a valid email address."
			},
			password: {
				minlength: "Your password must be at least 6 characters long."
			},
			password2: {
				minlength: "Your password must be at least 6 characters long.",
				equalTo: "The Passwords do not match."
			},
		}
	});

});

</script>
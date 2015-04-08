<div class="contentheading">
CREATE NEW USER
</div>


<form id="newUserFrm" method="post" action="<?php app() ?>/">
<input type="hidden" name="post" value="activate_user" />
<input type="hidden" name="user_id" value="<?php print $this->user->user_id; ?>" />
<input type="hidden" name="org_email" value="" />

<fieldset>
  <legend> Login Information </legend>
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
<legend> Administrator Only </legend>
<table>
	<tr>
  	<td class="frmLabels" >
  	
  	 Chamber ID Number:
  	
  	</td>
  	<td>
  	
  	 <input type="text" name="member_num" id="memberNumFld"  class="input ui-corner-all" value="<?php print $this->user->member_num; ?>" />
  	
  	</td>
  </tr>
	<tr>
  	<td>
  	
  	 Active Account:
  	
  	</td>
  	<td>
  	Yes: 
  	 <input type="radio"  name="active"   class="input" value="Y" <?php if ( $this->user->active == "Y" ) print 'checked=""'; ?> />
  	 No:
  	 <input type="radio"  name="active"   class="input" value="N" <?php if ( $this->user->active == "N" ) print 'checked=""'; ?> />
  	
  	</td>
  </tr>

</table>
</fieldset>


  <fieldset>
    <table>     
      <tr>
        <td class="frmSubmit" ></td>
        <td>
          <input type="submit" name="submit" value="Create Account" />
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
  	<td align="right">
  	
  	 Chamber ID Number:
  	
  	</td>
  	<td>
  	
  	 <input type="text" name="member_num" id="memberNumFld"  class="input" value="<?php print $this->user->member_num; ?>" />
  	
  	</td>
  </tr>
	<tr>
  	<td align="right">
  	
  	 Active Account:
  	
  	</td>
  	<td>
  	Yes: 
  	 <input type="radio"  name="active"   class="input" value="Y" <?php if ( $this->user->active == "Y" ) print 'checked=""'; ?> />
  	 No:
  	 <input type="radio"  name="active"   class="input" value="N" <?php if ( $this->user->active == "N" ) print 'checked=""'; ?> />
  	
  	</td>
  </tr>
	<tr>
  	<td colspan="2" align="right">
  	
  	 <input type="submit" name="submit" value="Create Account" />
  	 
  	</td>
	</tr>
</table>
-->
</form>
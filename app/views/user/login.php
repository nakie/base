<div class="contentheading" >
<?php print $this->title; ?>
</div>

<form id="theloginform" method="post" action="<?php app() ?>/">
  <input type="hidden" name="post" value="login_user" />
  <br />
  <fieldset>
    <table style="margin-top:7px">	
      <tr>
        <td class="frmLabels" >
          Email address:
        </td>
        <td>
          <input type="text" name="username" id="username" <?php oc("input") ?> class="input ui-corner-all">
        </td>
      </tr>
      <tr>
        <td class="frmLabels">
          Password:
        </td>
        <td>
          <input type="password" name="password" id="password" <?php oc("input") ?> class="input ui-corner-all">
        </td>
      </tr>
    </table>
  </fieldset>
  <fieldset>
    <table>     
      <tr>
        <td class="frmSubmit" ></td>
        <td>
          <input type="submit" name="submit" value="Log In" />
        </td>
      </tr>
    </table>
  </fieldset>
  <p style="text-align:center; width:80%;" > 
    Dont have an account? 
    <a href="/m2m/user/create" title="Sign up for an account" > Sign up</a>
  </p>

</form>




<script type="text/javascript">
$( document ).ready(function () {

  $( "#username" ).focus();
//$("#newUserFrm").validate(); 

// validate signup form on keyup and submit
	$( "#theloginform" ).validate( {
		rules: {
			username: {
				required: true,
				email: true
			},
			password: {
				required: true,
				minlength: 6
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
			}
		}
	});

});

</script>
<div class="contentheading">
USER ACCOUNT ACTIVATED
</div>

<form method="post" action="<?php app() ?>/">
<input type="hidden" name="post" value="confirm_user" />
<input type="hidden" name="user_id" value="<?php print $this->user->user_id; ?>" />


<fieldset>
<legend> Login Information </legend>
<table> 
  <tr>
    <td>
     Email address:
    </td>
    <td>
  <?php print $this->user->username; ?>
    </td>
  </tr>
</table>

</fieldset>

<fieldset>
<legend> Company Information</legend>
<table>
  <tr>
    <td>
    Member Name:
    </td>
    <td>
<?php print $this->user->name; ?>
    </td>
  </tr>
  <tr>
    <td>
    Street Address:
    </td>
    <td>
<?php print $this->user->address; ?>
    </td>
  </tr>
   
    <td>
    State:
    </td>
    <td>
<?php print $this->user->state; ?>
    </td>
  </tr>
    <tr>
    <td>
    Zip:
    </td>
    <td>
<?php print $this->user->zip; ?>
    </td>
  </tr>
    <tr>
    <td>
    Phone:
    </td>
    <td>
<?php print $this->user->phone; ?>
    </td>
  </tr>
 
</table>
</fieldset>






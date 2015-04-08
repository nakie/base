<div class="contentheading">
USER ACTIVATION
</div>


<form id="frmActivate" method="post" action="<?php app() ?>/">
<input type="hidden" name="post" value="activate_user" />
<input type="hidden" name="user_id" value="<?php print $this->user->user_id; ?>" />
<input type="hidden" name="org_email" value="" />


<fieldset>
<legend class="ui-corner-all" > Login Information </legend>
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
<legend class="ui-corner-all" > Company Information</legend>
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
<fieldset>
<legend class="ui-corner-all" > Administrator Only </legend>
<table>
	<tr>
  	<td align="right">
  	
  	 Chamber ID Number:
  	
  	</td>
  	<td>
  	
  	 <input type="text" name="member_num" id="memberNumFld"  class="input ui-corner-all required" value="<?php if ($this->user->member_num != 0 ) print $this->user->member_num; ?>" />
  	
  	</td>
  </tr> <!--
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
-->
  <tr id="confirmEmail" ><td></td></tr>
</table>

</fieldset>

<input type="submit" name="submit" value="Activate Account" />
</form>
<script type="text/javascript" >

$(document).ready( function () {
  
  $( "select" ).live( "change", function () {   
    
    $( "input[name='org_email']" ).val( $(this).val() );  
    
    $( 'input[type=submit]' ).removeAttr('disabled');
    
  });
  
  $('#frmActivate').submit( function() {
    var $form = $(this);
    
    $( 'input[type=submit]', this ).attr( 'disabled', 'disabled' );

    if ( $( "#frmActivate" ).validate().form() ) {
      
      if ( $( "input[name='org_email']" ).val() == "" ) {
              
        elem = $( "input[name='member_num']" );
         
        $.ajax({    
            url: '/m2m/user/onfile',
            type: "POST",
            dataType: 'text',
            data: elem.serialize() + "&post=onfile_user",
            success: function( data ) {
               
              data = jQuery.parseJSON( data );

              if ( typeof data.emails != 'undefined' ) {
                
                if ( data.emails.length > 1 ) {
                  inputHtml = '<td align="right" >Choose a Contact: </td><td><select name="confirm_email" class="input required" >';
                  inputHtml += '<option value="" >Select One</option>';
    
                  $.each( data.emails, function() {
                    inputHtml += '<option value="' + this + '">' + this + '</option>';
                  //  alert( this );
                    
                  });
                  
                  inputHtml += '</select> <p class="helpTxt">Multiple Email Addresses were found for this Member. Please <br /> Select one for the confirmation email to be sent to.</p></td>';

                  
                  $( "#confirmEmail" ).html( inputHtml );
                  return false; 
                  
                } else {
                  
                  $( "input[name='org_email']" ).val( data.emails[0] );
                  
                    $.ajax({    
                      url: '/m2m/user/onfile',
                      type: "POST",
                      dataType: 'text',
                      data: $form.serialize() ,
                      success: function( data ) {
                        document.location.href = "/m2m/user/manage";
                      },
                      complete: function() {
                        
                      },
                      error: function () {
                        alert("error:" + textStatus);
                      }
                  });
                  
                }

              }
              
              if ( typeof data.err != 'undefined' ) {
               // $( elem ).attr('disabled', true);
           
                errHtml = '<div class="ui-widget"><div style="padding: 0pt 0.7em;" class="ui-state-error ui-corner-all"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: 0.3em;"></span><strong>Activation Failed :</strong>No record found for this membership number.</p></div></div>';
           
                $( "#content" ).prepend( errHtml );  
                 return false; 
              }
            },
            
            complete: function(){
      
            },
            
            error: function( XMLHttpRequest, textStatus, errorThrown ){
              alert("error:" + textStatus);
              return false;  
            }
          });
        
        
             
         return false; 
        
      } // - End if info_select.val()
      
    } // - End if validate().form()
    //return false;  
  });
  
});
</script>


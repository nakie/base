<div class="contentheading"><?php print $this->member->org_name; ?> Images</div>


<br />
<form id="imgFrm" method="post" action="<?php app() ?>/">
    <input type="hidden" name="post" value="delete_logo" />
    <fieldset>
        <legend class="ui-corner-all" >Select Image(s) to Remove</legend>
        <p class="helpTxt" style="width:70%;"> 
            Select the image(s) you wish to remove and click "Delete". Please 
            note images you select will be completely removed from the server, 
            this action cannot be undone. Take care not to remove images 
            currently in use by one or more of your coupons.
        </p><br />
        <ul>
            <?php

            foreach ($this->logos->find_all as $count => $img) {
                $image = '<img src="' . $img->draw( 52 ) . '" alt="Remove this image?" />';
            ?>
                <li class="deleteImg" >
                    <div class="imgContainer">
                        <input type="checkbox" name="delete[<?php print $count ?>]" class="deleteChk" value="<?php print $img->logo_id; ?>" />
                        <?php print $image ?> 
                    </div>
                </li>

            <?php
            }
            ?>

        </ul>
    </fieldset>
    <br /><br />
    <fieldset> 
    <input type="submit" name="submit" value="Delete" />
    </fieldset>
 
</form>
<br />






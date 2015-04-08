<div class="contentheading">UPDATE MEMBERSHIP DATABASE</div>


<form id="fileFrm" method="post" action="<?php app() ?>/" enctype="multipart/form-data">
  <input type="hidden" name="post" value="csvfile_user" />
  <fieldset>
    <legend>Upload File</legend>
    <input type="file" name="file" class="input" />
    <br />
    <br />
    
    <input type="submit" name="submit" value="Upload" />
  
  </fieldset>
</form>
<?php

?>
<div id="root-mp-voice2app-admin">
  <div class="components-form-token-field" tabindex="-1">
    <button v-on:click="narrateNow()" type="button" class="button button-primary" >Narrate Post 
      <i v-if="is_loading" class="fa fa-spin fa-spinner"></i></button>
    <hr />
    <label for="" class="components-form-token-field__label">
      Link to Naration: {{link_to_narration}}
    </label>
    <hr />
    <div class="components-base-control__field">
      <label class="components-base-control__label" for="">
        Returned Narration:
      </label>
      <textarea v-model="returned_narration" class="components-textarea-control__input" id="inspector-textarea-control-0" rows="4" name="voice2app_narration"></textarea>
    </div>
  </div>
</div>

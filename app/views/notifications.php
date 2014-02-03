<? if($this->messages->count() > 0) { // if we have any notification messages, display them?>	
	<? if($this->messages->count('error') > 0) { // if we have error messages?>
		<? foreach($this->messages->get('error') AS $message) { ?>
    			<div class="alert alert-warning">
    				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    				<?= $message; // display ui notifications ?>			
    			</div>
		<? } // endforeach ?>
	<? } // end if error ?>

	<? if($this->messages->count('success') > 0) { // if we have success messages?>
		<? foreach($this->messages->get('success') AS $message) { ?>
    			<div class="alert alert-success animate bounceInDown">
    			    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    				<?= $message; // display ui notifications ?>			
    			</div>
		<? } // endforeach ?>
	<? } // end if success ?>
	
	<? if($this->messages->count('info') > 0) { // if we have info messages?>
		<? foreach($this->messages->get('info') AS $message) { ?>
    			<div class="alert alert-info">
    			    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    				<?= $message; // display ui notifications ?>			
    			</div>
		<? } // endforeach ?>
	<? } // end if info ?>
<? } ?>
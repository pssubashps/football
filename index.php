 <?php
require_once 'vendor/autoload.php';

require_once 'header.php';
?>

<div class="col-sm-9">
<br/>
<br/>
	<div class="input-group">
		<div class="ui-widget">
			<input type="text" id="search" name="search" class="form-control"
				placeholder="Search ..."> <input type="hidden" id="search_id"
				name="search_id" />
		</div>
		<span class="input-group-btn">
			<button class="btn btn-default" type="button" id="search_go">
				<span class="glyphicon glyphicon-search"></span>
			</button>
		</span>
	</div>


	<h4>Please choose actions from menu</h4>
	
	<br> <br>

</div>
</div>
</div>
</div>

<?php require_once 'footer.php';?>
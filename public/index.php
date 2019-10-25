<?php

require_once('html/header.php');
require_once('startup.php');

$files = get_files();
?>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">

	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
		<h1>Import Generator</h1>
	</div>

	<div class="row">
		<div class="col-md-6">
			<p>
				This generator is very simple. It will generate your choice of single and matrix items<br />
				Each row is going to be unique. <br />
				Matrix items will contain random attribute sets from Color,Size,Color/Size and 3 Attributes (not custom ones yet)
			</p>
			<hr />
			<form method="POST" action="requests/import/GenerateImport.php">
				<div class="form-group">
					<label for="account">Select your retail account</label>
					<select class="form-control" id="account" name="account">
						<option value="-1" selected>None (default)</option>
					
					</select>
				</div>
				<div class="form-group">
					<label for="single_count">Number of single items</label>
					<input type="number" max="50000" min="0" class="form-control" id="single_count" name="single_count" placeholder="0-10000">
					<small id="single_count-help" class="form-text text-muted">How many single items to generate</small>
				</div>
				<div class="form-group">
					<label for="matrix_count">Number of matrix items</label>
					<input type="number" max="50000" min="0" class="form-control" id="matrix_count" name="matrix_count" placeholder="0-10000">
					<small id="matrix_count-help" class="form-text text-muted">How many matrix items to generate</small>
				</div>

				<button type="submit" class="btn btn-primary">Create file</button>
			</form>
		</div>
		<div class="col-md-4 offset-md-1">
			<p>Existing files<br />
				<small>refresh page after each generation</small>
			</p>
			<p>
				To delete files generated. Run : <code>make clean-files</code>
			</p>

			<ul>
				<?php foreach($files as $file): ?>
					<li>
						<a target="_blank" href="./files/<?=$file?>"><?=$file; ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>

</main>

<?php require_once ('html/footer.php') ?>



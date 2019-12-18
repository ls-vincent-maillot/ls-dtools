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
					<label for="filename">Filename</label>
					<input type="text" class="form-control" id="filename" name="filename">
					<small id="filename-help" class="form-text text-muted">Filename (without extension)</small>
				</div>

				<div class="form-row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="single_count"># Single items</label>
							<input type="number" max="50000" min="0" class="form-control" id="single_count" name="single_count" value="0">
							<small id="single_count-help" class="form-text text-muted">How many single items to generate</small>
						</div>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="matrix_count"># Matrix items</label>
								<input type="number" max="50000" min="0" class="form-control" id="matrix_count" name="matrix_count" value="0">
								<small id="matrix_count-help" class="form-text text-muted">How many matrix items to generate</small>
							</div>
							<div class="form-group col-md-6">
								<label for="variants_count"># of Variants</label>
								<input type="number" max="100" min="1" class="form-control" id="variants_count" name="variants_count" value="10">
								<small id="variants_count-help" class="form-text text-muted">Variants will be created randomly per matrix between 1 and this value (max 100)</small>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="brands_count"># Brands</label>
								<input type="number" min="1" class="form-control" id="brands_count" name="brands_count" value="100">
								<small id="brands_count-help" class="form-text text-muted">How many brands to generate</small>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="categories_count"># Categories</label>
								<input type="number" min="1" class="form-control" id="categories_count" name="categories_count" value="50">
								<small id="categories_count-help" class="form-text text-muted">How many categories to generate</small>
							</div>
						</div>
					</div>
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



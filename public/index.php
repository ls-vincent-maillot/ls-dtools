<?php require_once ('html/header.php') ?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">

	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
		<h1>Import Generator</h1>
	</div>

	<div class="row">
		<div class="col-md-6">
			<form method="POST" action="import_generator.php">
				<div class="form-group">
					<label for="exampleInputEmail1">Number of rows</label>
					<input type="number" max="1000" min="2" class="form-control" id="rows" name="rows" placeholder="2-1000">
					<small id="rows-help" class="form-text text-muted">How many rows do you want in your import file</small>
				</div>

				<button type="submit" class="btn btn-primary">Submit</button>
			</form>
		</div>
	</div>

</main>

<?php require_once ('html/footer.php') ?>



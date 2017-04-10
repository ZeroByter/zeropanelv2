<style>
    @media (min-width: 800px) {
		.pull-right-md {
			float: right;
		}
	}
</style>

<div class="form-inline pull-right-md">
	<form id="search">
		<input id='searchText' class="form-control" type='text' name='searchText' value="<?php if (isset($_GET["search"])) echo $_GET["search"] ?>">
		<input style='margin-right: 10px;' class='btn btn-primary' type="submit" value="Search">
	</form>
</div>

<script>
    $("#search").submit(function(){
		window.location = "?search=" + $("#searchText").val()
		return false
	})
</script>

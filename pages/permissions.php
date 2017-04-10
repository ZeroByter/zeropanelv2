<?php

?>

<style>
	.row{
		padding: 0px 19px;
	}
	.well{
		border-color: #c1c1c1;
		padding: 0px;
	}
    .divLink{
		padding: 10px;
		display: block;
		text-align: center;
	}
	.divLink:hover{
		background: #ccc;
		color: #0089ff;
	}
	.custom-panel{
		overflow: hidden;
	}
</style>

<br><br><br><br><br><br>
<div class="custom-panel">
	<h4 style="float:left;margin-left:20px;">Permissions</h4>
	<br><br>
	<hr class="hidden-xs">
	<div class="col-lg-8 col-lg-offset-2 well">
		<?php foreach(permissions::get_all() as $value){ ?>
			<a href="/<?php echo $linksOffset ?>permissions/<?php echo $value->id ?>" class="divLink"><?php echo "$value->name ($value->accesslevel)" ?> <span class="label label-default"><?php echo count(accounts::get_all_by_accesslevel($value->accesslevel)) ?> with this accesslevel</span></a>
		<?php } ?>
	</div>
</div>

<script>

</script>

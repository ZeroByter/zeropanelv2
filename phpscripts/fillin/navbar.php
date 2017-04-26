<div id="navbarDiv">
    <a href="/<?php echo $linksOffset ?>profile"><div id="navbarProfile"><?php echo $currAccount->username ?></div></a>
    <a href="/<?php echo $linksOffset ?>"><div class="navbarItem"><i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard</div></a>
    <a href="/<?php echo $linksOffset ?>players"><div class="navbarItem"><i class="fa fa-users" aria-hidden="true"></i> Players</div></a>
    <a href="/<?php echo $linksOffset ?>vehicles"><div class="navbarItem"><i class="fa fa-car" aria-hidden="true"></i> Vehicles</div></a>
    <a href="/<?php echo $linksOffset ?>houses"><div class="navbarItem"><i class="fa fa-home" aria-hidden="true"></i> Houses</div></a>
	<?php
		if(permissions::user_has_permission("rconpage")){ ?>
			<a data-toggle="collapse" href="#rconDropdown"><div class="navbarItem"><i class="fa fa-terminal" aria-hidden="true"></i> RCon</div></a>
			<div class="collapse" id="rconDropdown">
				<?php
					foreach(servers::get_all_real() as $value){
						if($value->use_rcon){
							echo "<a href='/".$linksOffset."rcon/$value->id'><div class='navbarItemAlt'><i class='fa fa-server' aria-hidden='true'></i> $value->name</div></a>";
						}
					}
				?>
			</div>
		<?php }
		if(permissions::user_has_permission("serverspage")){ ?>
			<a data-toggle="collapse" href="#serversDropdown"><div class="navbarItem"><i class="fa fa-server" aria-hidden="true"></i> Servers</div></a>
			<div class="collapse" id="serversDropdown">
				<a href="/<?php echo $linksOffset ?>servers"><div class="navbarItemAlt">Edit Servers</div></a>
				<a href="/<?php echo $linksOffset ?>servers/createserver"><div class="navbarItemAlt">Create Server</div></a>
			</div>
		<?php }
		echo '<a href="/'.$linksOffset.'profile"><div class="navbarItem"><i class="fa fa-user-circle-o" aria-hidden="true"></i> Profile</div></a>';
		if(permissions::user_has_permission("staffpage")){ ?>
            <a data-toggle="collapse" href="#staffDropdown"><div class="navbarItem"><i class="fa fa-user" aria-hidden="true"></i> Staff</div></a>
			<div class="collapse" id="staffDropdown">
				<a href="/<?php echo $linksOffset ?>staff"><div class="navbarItemAlt"><i class="fa fa-user" aria-hidden="true"></i> Edit Staff</div></a>
				<a href="/<?php echo $linksOffset ?>staff/addnew"><div class="navbarItemAlt"><i class="fa fa-user-plus" aria-hidden="true"></i> Add New Staff</div></a>
                <?php
                    if(permissions::user_has_permission("sessionspage")){
                        echo '<a href="/'.$linksOffset.'staff/sessions"><div class="navbarItemAlt"><i class="fa fa-clock-o" aria-hidden="true"></i> Staff Sessions</div></a>';
                    }
                ?>
			</div>
		<?php }
		if(permissions::user_has_permission("settingspage")){
			echo '<a href="/'.$linksOffset.'settings"><div class="navbarItem"><i class="fa fa-wrench" aria-hidden="true"></i> Settings</div></a>';
		}
		if(permissions::user_has_permission("licensespage")){
			echo '<a href="/'.$linksOffset.'settings/licenses"><div class="navbarItem"><i class="fa fa-id-card" aria-hidden="true"></i> License Names</div></a>';
		}
		if(permissions::user_has_permission("permissionspage")){ ?>
			<a data-toggle="collapse" href="#permissionsDropdown"><div class="navbarItem"><i class="fa fa-check" aria-hidden="true"></i> Permissions</div></a>
			<div class="collapse" id="permissionsDropdown">
				<a href="/<?php echo $linksOffset ?>permissions"><div class="navbarItemAlt">Edit Permissions</div></a>
				<a href="/<?php echo $linksOffset ?>permissions/addnew"><div class="navbarItemAlt">Add New Permissions</div></a>
			</div>
		<?php }
		if(permissions::user_has_permission("logspage")){
			echo '<a href="/'.$linksOffset.'logs"><div class="navbarItem"><i class="fa fa-list" aria-hidden="true"></i> Logs</div></a>';
		}
	?>
	<a href="/<?php echo $resourceLinksOffset ?>about"><div <?php echo compareVersions() == -1 ? "style='color:#e65454;'" : "" ?> class="navbarItem"><i class="fa fa-info" aria-hidden="true"></i> About</div></a>
    <a href="/<?php echo $resourceLinksOffset ?>phpscripts/requests/logout.php"><div class="navbarItem"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</div></a>
</div>

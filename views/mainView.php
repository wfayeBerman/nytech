<?php require_once realpath(dirname(__FILE__).'/../../../..').'/wp-load.php'; ?>
<div class="mainView">
	<div class="headerMenu">
		<div class="menuButton">&#9776;</div>
		<div>
			<div id="logo">
				<a class="logoLink">
					<img src="<?php echo PAGEDIR; ?>/images/graphics/logo.png">
				</a>
			</div>
			<div id="nav" class="omega">
				<?php wp_nav_menu( array( 'theme_location' => 'header-menu' ) ); ?>
			</div>
		</div>
	</div>
</div>
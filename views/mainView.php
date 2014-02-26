<?php require_once realpath(dirname(__FILE__).'/../../../..').'/wp-load.php'; ?>
<div class="mainView">
	<div class="headerMenu">
		<div class="centerContent">
			<div class="menuButton">&#9776;</div>
			<div>
				<div id="logo">
					<a class="logoLink">
						<img src="<?php echo PAGEDIR; ?>/images/graphics/nytech-logo.jpg">
					</a>
				</div>
				<div id="social">
					<ul>
						<li><a target="_blank" href="https://www.facebook.com/pages/NYC-Tech-Forum/258084057674807"><img src="<?php echo PAGEDIR; ?>/images/graphics/facebook.png" alt=""></a></li>
						<li><a target="_blank" href="http://instagram.com/NYCTechForum"><img src="<?php echo PAGEDIR; ?>/images/graphics/instagram.png" alt=""></a></li>
						<li><a target="_blank" href="https://twitter.com/NYCTechForum"><img src="<?php echo PAGEDIR; ?>/images/graphics/twitter.png" alt=""></a></li>
						<!-- <li><a href="#"><img src="<?php echo PAGEDIR; ?>/images/graphics/linkedin.png" alt=""></a></li> -->
					</ul>
				</div>
				<div id="nav" class="omega">
					<?php wp_nav_menu( array( 'theme_location' => 'header-menu' ) ); ?>
				</div>
			</div>
		</div>
	</div>
</div>
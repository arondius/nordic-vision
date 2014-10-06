				</div>
			</div>
		</div>
		<div id="footer">
			<div class="footernav">
				<?php
					wp_nav_menu(
						array(
								'container'			=>	'false',
								'menuclass'			=> 	'footer-left'
							)
						);
					/*wp_nav_menu(
						array(
								'theme_location'	=> 	'footer-menu-center',
								'container'			=>	'false',
								'menuclass'			=> 	'footer-center'
							)
						);
					wp_nav_menu(
						array(
								'theme_location'	=> 	'footer-menu-right',
								'container'			=>	'false',
								'menuclass'			=> 	'footer-right'
							)
						);*/
				?>
			</div>
			<div class="logo-wrapper">
				<a href="<?php echo get_permalink(7);?>"><img src="http://fotoreizen.net/wp-content/themes/simplefolio/images/logo-vvkr.png">
				<img src="http://fotoreizen.net/wp-content/themes/simplefolio/images/logo-ggto.jpeg"></a>
			</div>
			<div class="copyright-wrapper">
				<p class="copyright"><?php copyright(2011); ?></p>
			</div>
		</div>
	</div>
	<?php wp_footer(); ?>
</body>
</html>
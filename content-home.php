<?php // content-home ?> 
			
				<div class="boxes">
					<?php dynamic_sidebar( 'home-boxes' ); ?>
				</div>
				<div class="home-widgets">
					<?php if( harvest_option( 'layout' ) == '66' ): ?>
					<div class="grid-66 home-widget home-widget-left">
					<?php else: ?>
					<div class="grid-33 home-widget home-widget-left"> 
					<?php endif; ?>
						<?php dynamic_sidebar( 'home-left' ); ?>
					</div>
					<?php if( harvest_option( 'layout' ) == '33' ): ?>
					<div class="grid-33 home-widget home-widget-middle">
						<?php dynamic_sidebar( 'home-middle' ); ?>
					</div>
					<?php endif; ?>
					<div class="grid-33 home-widget home-widget-right">
						<?php dynamic_sidebar( 'home-right' ); ?>
					</div>
				</div>
			</div>

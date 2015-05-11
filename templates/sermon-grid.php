<?php
	/* Sermon grid */
	$post_id = get_the_ID();
	$data = harvest_get_sermon_data( $post_id );
?>
				<div class="grid-33 mobile-grid-50  tiny-grid-100 ctc-sermon-grid"> 
					<a href="<?php echo $data[ 'permalink' ]; ?>">
						<div class="ctc-sermon">
<?php if ( $data[ 'img' ] ): ?>
							<div class="ctc-grid-details">
								<h3><?php the_title(); ?></h3>
							</div>
<?php /* if ( $data[ 'default_used' ] ): ?><div class="ctc-sermon-logo accent-background"><?php endif; */ ?>
							<img class="ctc-sermon-img" src="<?php echo $data[ 'img' ]; ?>"/>
<?php /* if ( $data[ 'default_used' ] ): ?></div><?php endif; */ ?>
<?php else: ?>
							<div class="ctc-grid-full accent-background">
								<h1 class="ctc-sermon-name"><?php the_title(); ?></h1>
							</div>
<?php endif; // img ?>
						</div> <!-- .ctc-sermon -->
					</a>
				</div> <!-- .ctc-sermon-grid -->
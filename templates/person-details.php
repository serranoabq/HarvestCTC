<?php
	/* Person details */
	$post_id = get_the_ID();
	$data = harvest_get_person_data( $post_id );
?>
				<div class="grid-30 prefix-10 suffix-10 ctc-person-photo"> 
<?php if( $data[ 'img' ] ): ?>
					<img class="ctc-person-img" src="<?php echo $data[ 'img' ]; ?>"/>
<?php endif; ?>
				</div>
				
				<div class="grid-40 suffix-10 ctc-person-details">
					<h2><?php echo the_title(); ?></h2>
					
<?php if( $data[ 'position' ] ): ?>
					<div class="ctc-person-title"><h3><?php echo $data[ 'position' ]; ?></h3></div>
<?php endif; ?>

<?php if( $data[ 'email' ] ): ?>
					<div class="ctc-person-email"><i class="fa fa-envelope"></i><a href="mailto:<?php echo antispambot( $data[ 'email' ] ); ?>"><?php echo antispambot( $data[ 'email' ] ); ?></a></div>
<?php endif; ?>

<?php if( $data[ 'url' ] ): ?>
					<div class="ctc-person-urls">
<?php	
	foreach( explode("|", str_replace( "\n" , '|', $data[ 'url' ] ) ) as $url): ?>
						<a href="<?php echo $url;?>" target="_blank" class="ctc-url"></a>
<?php endforeach; ?>
					</div>
<?php endif; ?>
				
				<div class="ctc-person-content">
						<?php echo the_content(); ?>
					</div> <!-- .ctc-person-content -->
				
				</div> <!-- .ctc-person-details -->
				
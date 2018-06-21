<?php
/**
 * Plugin Name: House and Land
 * Description: Allows users to Search House and Land
 * Version: 1.0.0
 * Author: Coopso
 */

add_action( 'wp_enqueue_scripts', 'ajax_test_enqueue_scripts' );
function ajax_test_enqueue_scripts() {

	wp_enqueue_script( 'ajax_test', plugins_url( '/script.js', __FILE__ ), array('jquery'), '1.0', true );

	wp_localize_script( 'ajax_test', 'handl', array(
		'ajax_url' => admin_url( 'admin-ajax.php' )
	));

}
add_action( 'wp_ajax_nopriv_house_and_land_post', 'house_and_land_post' );
add_action( 'wp_ajax_house_and_land_post', 'house_and_land_post' );
//house_and_land_post
function house_and_land_post() { ?>
	<!-- results -->
	<div class="results">

		<div class="results-list">
			<div class="grid-container">

				<div class="row">

					<?php

					if ($_GET['price']) {
						$price = explode(',', $_GET['price']);
						$price_min = $price[0];
						$price_max = $price[1];
					} else {
						$price_min = 0;
						$price_max = 700000;
					}

					if ($_GET['lotwidth']) {
						$lotwidth = explode(',', $_GET['lotwidth']);
						$lotwidth_min = $lotwidth[0];
						$lotwidth_max = $lotwidth[1];
					} else {
						$lotwidth_min = 0;
						$lotwidth_max = 100;
					}

					if ($_GET['bedroomrange']) {
						$bedroomrange = explode(',', $_GET['bedroomrange']);
						$bedroomrange_min = $bedroomrange[0];
						$bedroomrange_max = $bedroomrange[1];
					} else {
						$bedroomrange_min = 0;
						$bedroomrange_max = 10;
					}

					if (isset($_GET['bathroomrange'])) {
						$bathroomrange = explode(',', $_GET['bathroomrange']);
						$bathroomrange_min = $bathroomrange[0];
						$bathroomrange_max = $bathroomrange[1];
					} else {
						$bathroomrange_min = 0;
						$bathroomrange_max = 10;
					}

					if (isset($_GET['orderby'])) {
						$orderby = $_GET['orderby'];
					} else {
						$orderby = 'meta_value_num';
					}

					if (isset($_GET['order'])) {
						$order = $_GET['order'];
					} else {
						$order = 'ASC';
					}

					$query = new WP_Query(array(
							'post_type' => 'house_and_land',
							'post_status' => 'publish',
							'posts_per_page' => 6,
							'orderby' => $orderby,
							'order' => $order,
							'paged' => get_query_var('paged'),
							'meta_key' => 'price',
							'meta_query' => array(
									'relation' => 'AND',
									array(
											'key'     => 'price',
											'value' => array( $price_min, $price_max ),
											'type' => 'numeric',
											'compare' => 'BETWEEN'
									),
									array(
											'key'     => 'lot_width',
											'value' => array( $lotwidth_min, $lotwidth_max ),
											'type' => 'numeric',
											'compare' => 'BETWEEN'
									),
									array(
											'key'     => 'bedroom',
											'value' => array( $bedroomrange_min, $bedroomrange_max ),
											'type' => 'numeric',
											'compare' => 'BETWEEN'
									),
									array(
											'key'     => 'bathroom',
											'value' => array( $bathroomrange_min, $bathroomrange_max ),
											'type' => 'numeric',
											'compare' => 'BETWEEN'
									)
							)
					));
					?>
					<div class="col-12 search_result">
						<div class="search_result_title">Search results</div>
						<div class="user_lot_result">
							<?php
							echo 'Price Range $'.number_format($price_min).' - $'.number_format($price_max).', Lot width '.$lotwidth_min.'m - '.$lotwidth_max. 'm, Bedroom '.$bedroomrange_min.'-'.$bedroomrange_max. ', Bathroom '.$bathroomrange_min.'-'.$bathroomrange_max;
							?>
						</div>
					</div>
					<?php
					$special_off='';
					$paged=null;
					if ( $query->have_posts() ) {
						while ($query->have_posts()) {
							$query->the_post();

							$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium' );
							$thumb = $thumb['0'];

							$price = get_field('price');
							$price = number_format($price,0,'.',',');
							?>
							<!-- item -->
							<div class="col-4 results-list-view">

								<div class="results-list-item grid">

									<a href="<?php echo get_field('brochure'); ?>" target="_blank"> <div class="results-list-item-image" style="background-image: url(<?php echo $thumb; ?>)"></div></a>
									<div class="results-list-item-desc equal">

										<div class="results-list-item-desc-head house">
											<div class="title text-center">
												<?php echo get_the_title(); ?>
											</div>
											<div class="price text-center">
												<span class="from">From</span> <?php if($special_off != ''){ ?>
													<span style="color: #d80000;text-decoration:line-through">
												<span style="color: #333;"><?php echo '$'.$price; ?></span>
											</span>
												<?php } else{ ?>
													<?php echo '$'.$price; ?>
												<?php }  ?>
											</div>
										</div>

										<?php if(get_field('lot_text')){ ?>
											<div class="results-list-item-desc-lot">
												<div class="results-list-item-desc-lot-text text-center">
													<?php  the_field('lot_text'); ?>
												</div>
											</div>

										<?php } ?>

										<div class="results-list-item-desc-icons text-center">
											<div class="cell">
												<div class="cell-image">
													<img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon-4.png" alt="" />
												</div>
												<div class="cell-label">
													<?php the_field('bedroom'); ?> Bed
												</div>
											</div>
											<div class="cell">
												<div class="cell-image">
													<img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon-5.png" alt="" />
												</div>
												<div class="cell-label">
													<?php the_field('bathroom'); ?> Bath
												</div>
											</div>
											<div class="cell">
												<div class="cell-image">
													<img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon-3.png" alt="" />
												</div>
												<div class="cell-label">
													<?php the_field('garage'); ?> Car
												</div>
											</div>
										</div>

										<div class="house_buttons">
											<div class="find_land"><a href="<?php echo get_permalink(94); ?>?lotwidth=0, <?php echo get_field('lot_width')?>"><button class="button"><img src="<?php echo get_template_directory_uri() ?>/assets/img/button_home.png"> Find land <i class="fa fa-arrow-right" aria-hidden="true"></i></button></a></div>
											<div class="enquire">
												<a href="#" data-content="<?php if(get_field('lot_text')){ echo 'Lot: '.get_field('lot_text').' House: '.get_the_title(); }else { echo get_the_title(); } ?>" class="js-open-enquire button"><i class="fa fa-commenting-o" aria-hidden="true"></i> Enquire <i class="fa fa-arrow-right" aria-hidden="true"></i> </a>
											</div>

										</div>

										<div class="results-list-item-desc-links">
											<img src=<?php echo get_field('builder_logo')?> >
										</div>

									</div>

								</div>
							</div>
							<!-- /item -->

						<?php } wp_reset_query();

					} else { ?>

						<h2 class="no-match">Nothing Found Sorry, no posts matched your criteria.</h2>

					<?php } ?>

				</div>

				<?php wp_reset_postdata(); ?>
			</div>
		</div>

	</div>
	<!-- /results -->
<?php } ?>
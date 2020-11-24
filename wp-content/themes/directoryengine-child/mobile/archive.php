<?php
et_get_mobile_header();
?>

    <!-- Top bar -->
	<section id="top-bar" class="section-wrapper">
    	<div class="container">
        	<div class="row">
            	<div class="col-xs-12 text-centered">
                	<h1 class="title-page">
                        <?php
                        if ( is_day() ) :
                            printf( __( 'Daily Archives: %s', ET_DOMAIN ), get_the_date() );

                        elseif ( is_month() ) :
                            printf( __( 'Monthly Archives: %s', ET_DOMAIN ), get_the_date( _x( 'F Y', 'monthly archives date format', ET_DOMAIN ) ) );

                        elseif ( is_year() ) :
                            printf( __( 'Yearly Archives: %s', ET_DOMAIN ), get_the_date( _x( 'Y', 'yearly archives date format', ET_DOMAIN ) ) );

                        else :
                            _e( 'Archives', ET_DOMAIN );

                        endif;
                        ?>
                    </h1>
                </div>
            </div>
        </div>
    </section>
    <!-- Top bar / End -->
    
    <?php
        get_template_part( 'mobile/template/publish' , 'posts');
     ?>
<?php
et_get_mobile_footer();
<?php
et_get_mobile_header();
if(have_posts()) { the_post();
?>
    
    <!-- Top bar -->
    <section id="top-bar" class="section-wrapper"> 
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-centered">
                    <h1 class="title-page"><?php the_title(); ?></h1>
                </div>
            </div>
        </div>
    </section>
    <!-- Top bar / End -->
    
    <!-- List News -->
    <section id="list-news" class="section-wrapper"> 
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="news-wrapper-single">
                        <div class="content-news">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- List News / End -->

<?php
}
et_get_mobile_footer();
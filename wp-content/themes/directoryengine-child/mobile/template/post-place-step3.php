<div id="step-post">
    <!-- Top bar step 3 -->
    <section class="top-bar section-wrapper"> 
        <div class="container">
            <div class="row">
                <div class="col-xs-6">
                    <h1 class="title-page"><?php _e("Post a Place", ET_DOMAIN); ?></h1>
                </div>
                <div class="col-xs-6">
                    <span class="title-step-number"><?php printf(__("Step %s of %s", ET_DOMAIN), '<strong>2</strong>', '<strong>3</strong>') ?></span>
                </div>
            </div>
        </div>
    </section>
    <!-- Top bar / End -->

    <!-- Form Post Place -->
    <section id="form-post-place" class="section-wrapper"> 
    	<form class="form-post-wrapper">
        	<ul>
            	<li>
                	<div class="container">
                    	<div class="row">
                        	<div class="col-md-12">
                            	<label>
                                    <?php _e("PLACE NAME", ET_DOMAIN); ?>
                                    <span><?php _e("Keep it short &#38; clear", ET_DOMAIN); ?></span>
                                </label>
                                <input type="text" name="" value="" placeholder=""/>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                	<div class="container">
                    	<div class="row">
                        	<div class="col-md-12">
                            	<label><?php _e("LOCATION", ET_DOMAIN); ?><span><?php _e("Your's place address", ET_DOMAIN); ?></span></label>
                                <input type="text" name="" value="" placeholder=""/>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                	<div class="container">
                    	<div class="row">
                        	<div class="col-md-12">
                            	<label><?php _e("CATEGORIES", ET_DOMAIN); ?><span><?php _e("Select the best one(s)", ET_DOMAIN); ?></span></label>
                                <input type="text" name="" value="" placeholder=""/>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                	<div class="container">
                    	<div class="row">
                        	<div class="col-md-12">
                            	<label>photos<span>Select one picture for your feature image</span></label>
                                <ul class="gallery-img-upload">
                                	<li><span class="img-gallery"></span><input type="radio" name="img-gal"></li>
                                    <li><span class="img-gallery"></span><input type="radio" name="img-gal"></li>
                                    <li><span class="img-gallery"></span><input type="radio" name="img-gal"></li>
                                    <li><span class="img-gallery"></span><input type="radio" name="img-gal"></li>
                                    <li><span class="img-gallery"></span><input type="radio" name="img-gal"></li>
                                    <li><span class="img-gallery"></span><input type="radio" name="img-gal"></li>
                                	<li><span class="img-gallery"><i class="fa fa-plus"></i></span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                	<div class="container">
                    	<div class="row">
                        	<div class="col-md-12">
                            	<label>VIDEO<span>To introduce your place</span></label>
                                <input type="text" name="" value="" placeholder=""/>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                	<div class="container">
                    	<div class="row">
                        	<div class="col-md-12">
                            	<label>description<span>Ideally 3 short paragraphs</span></label>
                                <textarea rows="5" name=""></textarea>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <input type="submit" class="btn-submit-post-place" name="" value="Continue"/>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <!-- Form Post Place / End -->
</div>
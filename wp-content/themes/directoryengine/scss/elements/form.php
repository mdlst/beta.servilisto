<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 10/9/2015
 * Time: 4:43 PM
 */
?>
<h3>Form sign in</h3>
<div class="display">
	<div id="loginModal" class="modal modal-show" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						<i class="fa fa-times"></i>
					</button>
					<h4 class="modal-title">Sign In</h4>
				</div>
				<div class="modal-body">
					<form class="signin-form form-modal-style" novalidate="novalidate">
						<div class="form-field user_name">
				        	<label>Username or Email</label>
				        	<input type="text" class="text-field" name="user_login" id="sig_name">
						</div>
						<div class="form-field user_password">
				            <label>Password</label>
				        	<input type="password" class="text-field" id="sig-pass" name="user_pass">
		                    <a href="#" class="link-forgot-pass">
		                    	Forgot password
		                    	<i class="fa fa-question-circle"></i>
		                    </a>
						</div>
						<div class="form-field submit-signin">
							<input type="submit" class="btn-submit" value="Sign In">
							<a href="#" class="link-sign-up">Sign up</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="display-code">
	<span class="btn-base btn-clipboard" data-toggle="tooltip" title="Copy to clipboard" data-id="form-login">Copy</span>
	<pre id="form-login" class="pre-code brush: js; html-script: true;">
		<div id="loginModal" class="modal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						<i class="fa fa-times"></i>
					</button>
					<h4 class="modal-title modal-title-sign-in">Sign In</h4>
				</div>
				<div class="modal-body">
					<form id="" class="signin-form form-modal-style" novalidate="novalidate">
						<div class="form-field user_name">
				        	<label>Username or Email</label>
				        	<input type="text" class="text-field" name="user_login" id="sig_name">
						</div>
						<div class="form-field user_password">
				            <label>Password</label>
				        	<input type="password" class="text-field" id="sig-pass" name="user_pass">
		                    <a href="#" class="link-forgot-pass">
		                    	Forgot password
		                    	<i class="fa fa-question-circle"></i>
		                    </a>
						</div>
						<div class="form-field submit-signin">
							<input type="submit" class="btn-submit" value="Sign In">
							<a href="#" class="link-sign-up">Sign up</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	</pre>
</div>
<h3>Form forgot password</h3>
<div class="display">
	<div id="forgotpassModal" class="modal modal-show" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						<i class="fa fa-times"></i>
					</button>
					<h4 class="modal-title">Forgot Password</h4>
				</div>
				<div class="modal-body">
					<form class="forgotpass-form form-modal-style" novalidate="novalidate">
						<div class="form-field">
				        	<label>Enter your email here</label>
				        	<input type="text" class="text-field" name="user_login" id="sig_name">
						</div>
						<div class="form-field submit-forgotpass">
							<input type="submit" class="btn-submit" value="Send">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="display-code">
	<span class="btn-base btn-clipboard" data-toggle="tooltip" title="Copy to clipboard" data-id="form-forgotpass">Copy</span>
	<pre id="form-forgotpass" class="pre-code brush: js; html-script: true;">
		<div id="forgotpassModal" class="modal" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							<i class="fa fa-times"></i>
						</button>
						<h4 class="modal-title">Forgot Password</h4>
					</div>
					<div class="modal-body">
						<form class="forgotpass-form form-modal-style" novalidate="novalidate">
							<div class="form-field">
					        	<label>Enter your email here</label>
					        	<input type="text" class="text-field" name="user_login" id="sig_name">
							</div>
							<div class="form-field submit-forgotpass">
								<input type="submit" class="btn-submit" value="Send">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</pre>
</div>
<h3>Form sign up</h3>
<div class="display">
	<div id="signupModal" class="modal modal-show" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						<i class="fa fa-times"></i>
					</button>
					<h4 class="modal-title">Sign Up</h4>
				</div>
				<div class="modal-body">
					<form class="signup-form form-modal-style" id="signup_form" novalidate="novalidate">
						<div class="form-field">
				        	<label>Username</label>
				        	<input type="text" class="text-field" name="user_login" id="reg_name">
						</div>
						<div class="form-field">
				            <label>Email</label>
				        	<input type="text" class="text-field" name="user_email" id="user_email">
			        	</div>
			        					<!-- password -->
						<div class="form-field">
				            <label>Password</label>
				        	<input type="password" class="text-field" id="reg_pass" name="user_pass">
			        	</div>
			        	<div class="form-field">
				            <label>Retype Password</label>
				        	<input type="password" class="text-field" id="re_password" name="re_password">
				        </div>
						<!--// password -->
			            <div class="form-field submit-signup">
				            <input type="submit" name="submit" value="Sign up" class="btn-submit">
				            <a href="#" class="link-sign-in">Sign in</a>
			            </div>
			        </form>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="display-code">
	<span class="btn-base btn-clipboard" data-toggle="tooltip" title="Copy to clipboard" data-id="form-siginup">Copy</span>
	<pre id="form-signup" class="pre-code brush: js; html-script: true;">
		<div id="signupModal" class="modal modal-show" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							<i class="fa fa-times"></i>
						</button>
						<h4 class="modal-title">Sign Up</h4>
					</div>
					<div class="modal-body">
						<form class="signup-form form-modal-style" id="signup_form" novalidate="novalidate">
							<div class="form-field">
					        	<label>Username</label>
					        	<input type="text" class="text-field" name="user_login" id="reg_name">
							</div>
							<div class="form-field">
					            <label>Email</label>
					        	<input type="text" class="text-field" name="user_email" id="user_email">
				        	</div>
				        					<!-- password -->
							<div class="form-field">
					            <label>Password</label>
					        	<input type="password" class="text-field" id="reg_pass" name="user_pass">
				        	</div>
				        	<div class="form-field">
					            <label>Retype Password</label>
					        	<input type="password" class="text-field" id="re_password" name="re_password">
					        </div>
							<!--// password -->
				            <div class="form-field submit-signup">
					            <input type="submit" name="submit" value="Sign up" class="btn-submit">
					            <a href="#" class="link-sign-in">Sign in</a>
				            </div>
				        </form>
					</div>
				</div>
			</div>
		</div>
	</pre>
</div>
<h3>Form edit place</h3>
<div class="display">
	<div id="editPlaceModal" class="modal modal-editplace modal-show" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						<i class="fa fa-times"></i>
					</button>
					<h4 class="modal-title">Place Info</h4>
				</div>
				<div class="modal-body">
					<form class="editplace-form form-modal-style" id="editplace_form" novalidate="novalidate">
						<ul class="nav nav-tabs nav-tabs-list" role="tablist">
							<li class="active">
								<a href="#tabs_information" role="tab" data-toggle="tab">Information</a>
							</li>
							<li>
								<a href="#tabs_header" role="tab" data-toggle="tab">Header</a>
							</li>
							<li>
								<a href="#tabs_gallery" role="tab" data-toggle="tab">Gallery</a>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade body-tab active in" id="tabs_information">
								<div class="form-field">
                                    <label>NAME<span class="alert-icon">*</span></label>
                                    <input type="text" class="text-field required" name="post_title" id="post_title">
                                </div>
                                <div class="form-field">
                                	<label>Address<span class="alert-icon">*</span></label>
                                	<input type="text" class="text-field required" name="et_full_location" id="et_full_location">
                                </div>
                                <div class="form-field">
                                	<label>Location<span class="alert-icon">*</span></label>
                                	<select name="location" id="location" class="chosen-single tax-item required">
										<option value="" selected="selected">Select your location</option>
										<option class=" catania cat-37 level-0" value="37">Catania</option>
										<option class=" england cat-7 level-0" value="7">England</option>
										<option class=" birmingham-west-mids cat-32 level-1" value="32">Birmingham / West mids</option>
										<option class=" cambridge-uk cat-35 level-1" value="35">Cambridge, UK</option>
										<option class=" cardiff-wales cat-36 level-1" value="36">Cardiff / Wales</option>
										<option class=" devon-cornwall cat-39 level-1" value="39">Devon &amp; Cornwall</option>
										<option class=" east-midlands cat-40 level-1" value="40">East Midlands</option>
										<option class=" manchester cat-16 level-1" value="16">Manchester</option>
										<option class=" newcastle-ne-england cat-20 level-1" value="20">Newcastle / NE england</option>
										<option class=" nottingham cat-21 level-1" value="21">Nottingham</option>
									</select>
                                </div>	
                                <div class="form-field">
                                	<label>PHONE</label>
                                    <input type="text" class="text-field" name="et_phone" id="et_phone">
                                </div>
                                <div class="form-field icon-input">
                                    <label>FACEBOOK</label>
                                    <input type="text" class="text-field" name="et_fb_url" id="et_fb_url">
                                </div>
                                <div class="form-field icon-input">
                                    <label>GOOGLE PLUS</label>
                                    <input type="text" class="text-field" name="et_google_url" id="et_google_url">
                                </div>
                                <div class="form-field icon-input">
                                    <label>TWITTER</label>
                                    <input type="text" class="text-field" name="et_twitter_url" id="et_twitter_url">
                                </div>	
							</div>
							<div class="tab-pane fade body-tab" id="tabs_header">
								Content tabs two
							</div>
							<div class="tab-pane fade body-tab" id="tabs_gallery">
								Content tabs three
							</div>
							<div class="form-field submit-editplace">
								<input class="btn-submit" type="submit" value="Submit">
							</div>
						</div>
			        </form>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="display-code">
	<span class="btn-base btn-clipboard" data-toggle="tooltip" title="Copy to clipboard" data-id="form-editplace">Copy</span>
	<pre id="form-editplace" class="pre-code brush: js; html-script: true;">
		
	</pre>
</div>



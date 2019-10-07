	<?php
		if (isset($_POST['sendMessage'])) {
			
			$name = $_POST['name'];
			$email = $_POST['email'];
			$message = $_POST['message'];
		}
	?>
	<section id="inner-headline">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<h2 class="pageTitle">Contact Us</h2>
			</div>
		</div>
	</div>
	</section>
	<section id="content">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<p></p>
										  	
				   	<!-- Form itself -->
		          	<form name="sentMessage" id="contactForm" action="#" method="post" novalidate>
			       		<h3>We want to hear from you...</h3>
						<div class="control-group">
				            <div class="controls">
								<input type="text" class="form-control" placeholder="Full Name" id="name" required data-validation-required-message="Please enter your name" />
							  <p class="help-block"></p>
						   	</div>
					    </div> 

		                <div class="control-group">
		                  	<div class="controls">
								<input type="email" class="form-control" placeholder="Email" id="email" required data-validation-required-message="Please enter your email" />
							</div>
			    		</div> 	
					  
		               	<div class="control-group" style="margin-top: 19px;">
		                 	<div class="controls">
						 		<textarea rows="10" cols="100" class="form-control" placeholder="Message" id="message" name="message" required data-validation-required-message="Please enter your message" minlength="5" data-validation-minlength-message="Min 5 characters" maxlength="999" style="resize:none"></textarea>
							</div>
		               </div>

			     		<div id="success"> </div> <!-- For success/fail messages -->
			    		<button type="submit" name="sendMessage" class="btn btn-primary pull-right">Send</button><br/>
		          	</form>
				</div>
			</div>
		</div>
	</section>
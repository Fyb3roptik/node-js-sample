<?php echo $MS->messages(); ?>

<div class="container">
  <section id="hero" class="hero-section hero-layout-simple hero-fullscreen section section-dark">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Beast Franchise Registration</h3>
            </div>
            <div class="panel-body">
              <form role="form" action="/login/processRegister" method="post">
                  <div class="form-group">
                      <label for="name">Name (This needs to be your full legal name for Withdrawal Purposes. We keep the name Private. <span class="text-danger">THIS CANNOT BE CHANGED!!!</span>)</label>
                      <input type="text" class="form-control" id="name" name="name" placeholder="Full Legal Name (Remains Private)">
                  </div>
                  <div class="form-group has-feedback">
                      <label class="control-label" for="username">Username</label>
                      <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                      
                  </div>
                  <div class="form-group">
                      <label for="email">Email</label>
                      <input type="text" class="form-control" id="email" name="email" placeholder="Email Address">
                  </div>
                  <div class="form-group">
                      <label for="new_password">Password</label>
                      <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Password">
                  </div>
                  <div class="form-group">
                      <label for="new_password_confirm">Confirm Password</label>
                      <input type="password" class="form-control" id="new_password_confirm" name="new_password_confirm" placeholder="Confirm Password">
                  </div>
                  <div class="checkbox">
                      <label>
                          <input type="checkbox" name="acknowledge" value="agreed" />
                          I acknowledge that I do not live any of the following states.  Arizona, Illinois, Iowa, Louisiana, Maryland, Montana, North Dakota, Puerto Rico, Tennesse, Vermont, and Washington State.  Furthermore, I understand that I cannot participate in a "fee" based Beast Franchise match if I do live in one of the states listed here.  However, I understand I can play in "Free" based Beast Franchise matches, but cannot recieve any type of prizes. By checking this box I also agree to the <a href="/terms" data-toggle="modal" data-target="#termsModal">terms</a>
                      </label>
                  </div>
                  <button type="submit" class="btn btn-success pull-right">Register</button>
                  <div class="clearfix"></div>
              </form>
            </div>
          </div>          
        </div>
    </div>
  </section>
</div>

<!-- Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Beast Franchise Terms and Conditions</h4>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
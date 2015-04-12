<?php echo $MS->messages(); ?>
<div class="container">
  <section id="hero" class="hero-section hero-layout-simple hero-fullscreen section">
          <div class="row">
              <div class="col-md-12">
                  <div class="panel panel-primary">
                      <div class="panel-heading">
                          <h3 class="panel-title">Beast Franchise Login</h3>
                      </div>
                      <div class="panel-body">
                          <form role="form" action="/login/processLogin" method="post">
                              <div class="form-group">
                                  <label for="email">Email</label>
                                  <input type="text" class="form-control" id="email" name="email" placeholder="Email Address">
                              </div>
                              <div class="form-group">
                                  <label for="password">Password</label>
                                  <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                              </div>
                              <span class="pull-left"><a href="/recover_password" class="forgot-password">Forgot Password</a></span>
                              <button type="submit" class="btn btn-purple pull-right">Login</button>
                              <div class="clearfix"></div>
                          </form>
                      </div>
                  </div>
              </div>
          </div>
  </section>
</div>
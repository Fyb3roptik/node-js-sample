<div class="row-fluid">
    <div class="col-lg-6 col-sm-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Fyberstudios Client Login</h3>
            </div>
            <div class="panel-body">
                <form role="form" action="/login/processLogin" method="post">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                    </div>
                    <span class="pull-left"><a href="/recover_password">Forgot Password</a></span>
                    <button type="submit" class="btn btn-primary pull-right">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php echo $MS->messages(); ?>

<div class="page-header">
    <h3>Beast Franchise Registration</h3>
</div>

<div class="row">
    <div class="col-lg-6">
        <form role="form" action="/login/processRegister" method="post">
            <div class="form-group">
                <label for="name">Name (This needs to be your full legal name for Withdrawal Purposes. We keep the name Private)</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Full Legal Name (Remains Private)">
            </div>
            <div class="form-group">
                <label for="username">Username</label>
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
                <label for="acknowledge">
                    <input type="checkbox" id="acknowledge" name="acknowledge" value="agreed" />
                    I acknowledge that I do not live any of the following states.  Arizona, Illinois, Iowa, Louisiana, Maryland, Montana, North Dakota, Puerto Rico, Tennesse, Vermont, and Washington State.  Furthermore, I understand that I cannot participate in a "fee" based Beast Franchise match if I do live in one of the states listed here.  However, I understand I can play in "Free" based Beast Franchise matches, but cannot recieve any type of prizes.
                </label>
            </div>
            <button type="submit" class="btn btn-success pull-right">Register</button>
        </form>
    </div>
</div>
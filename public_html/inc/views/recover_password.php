<div class="page-header">
    <h3>Beast Franchise Recover Password</h3>
</div>

<div class="row">
    <?php if($MS->count('recover_password') > 0): ?>
	<div class="alert ">
		<?php echo $MS->messages('recover_password'); ?>
	</div>
	<?php endif; ?>
    <div class="col-lg-6 col-sm-6">
        <div class="panel panel-primary">
            <div class="panel-body">
                <form role="form" action="" method="post">
                    <input type="hidden" name="action" value="process_recover_password" />
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email Address">
                    </div>
                    <button type="submit" class="btn btn-danger pull-right">Recover</button>
                </form>
            </div>
        </div>
    </div>
</div>
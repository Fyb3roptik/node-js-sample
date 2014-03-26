<div class="page-header">
    <h1>Edit Customer</h1>
</div>

<div class="col-lg-6">
    <div class="box blue">
        <div class="box-header">
            <h2>&nbsp;</h2>
        </div>
        <div class="box-content">
            <form role="form" action="/admin/customer/process" method="post">
                <input type="hidden" id="customer_id" name="customer_id" value="<?php echo $C->ID; ?>" />
                <div class="form-group">
                    <label for="customer[name]">Name</label>
                    <input type="text" class="form-control" id="customer[name]" name="customer[name]" placeholder="Name" value="<?php echo $C->name; ?>">
                </div>
                <div class="form-group">
                    <label for="customer[email]">Email</label>
                    <input type="text" class="form-control" id="customer[email]" name="customer[email]" placeholder="Email" value="<?php echo $C->email; ?>">
                </div>
                <button class="btn btn-success pull-right">Update</button>
            </form>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
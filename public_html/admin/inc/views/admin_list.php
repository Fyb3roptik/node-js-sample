<div class="messages"><?php echo $MS->messages('admin'); ?></div>
<div class="col-lg-4">
    <p><a class="btn btn-success" href="<?php echo LOC_ADMIN_MANAGE; ?>newAdmin/">Add new Admin</a></p>
    
    <?php if(count($ADMIN_LIST) > 0): ?>
    <div class="box blue">
        <div class="box-header">
            <h2>Manage Administrators</h2>
        </div>
        <div class="box-content">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <th>Name</th>
                        <th></th>
                        <th></th>
                    </thead>
                    <tbody>
                        <?php foreach($ADMIN_LIST as $i => $A): ?>
                    	<tr>
                    		<td>
                    			<a href="/admin/admin/edit/<?php echo $A->ID; ?>"><?php echo $A->name; ?></a>
                    		</td>
                    		<td>
                    			<a class="btn btn-default" href="/admin/admin/editPermissions/<?php echo $A->ID; ?>">Edit Permissions</a>
                    		</td>
                    		<td>
                    			<a class="btn btn-danger" href="/admin/admin/confirmDelete/<?php echo $A->ID; ?>">Delete</a>
                    		</td>
                    	</tr>
                    	<?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

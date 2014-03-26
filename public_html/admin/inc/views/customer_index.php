<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css">

<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    $("#customer_table").dataTable({
        "iDisplayLength": 25,
        "aoColumns": [
            null,
            { "asSorting": [ "asc" ] }            
        ]
    });
});
</script>

<div class="page-header">
    <h1>Manage Customers</h1>
</div>

<div class="col-lg-12">
    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped table-hover" id="customer_table">
        <thead>
            <th>Name</th>
            <th>Email</th>
        </thead>
        <tbody>
            <?php foreach($CUSTOMER_LIST as $CUSTOMER): ?>
                <tr>
                    <td><a href="/admin/customer/edit/<?php echo $CUSTOMER->ID; ?>"><?php echo $CUSTOMER->name; ?></a></td>
                    <td><?php echo $CUSTOMER->email; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
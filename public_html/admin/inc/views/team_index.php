<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="//ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css">

<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="//ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    $("#team_table").dataTable({
        "iDisplayLength": 25
    });
    
    $('a.delete').click(function(event){
        if(!confirm('Are you sure you want to delete this match?')){
            event.preventDefault();
        }
        
    });
});
</script>

<div class="page-header">
    <h1>Manage Teams</h1>
</div>
<p><a class="btn btn-success" href="/admin/team/add">Add Test Team</a></p>
<div class="col-lg-12">
    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped table-hover" id="team_table">
        <thead>
            <th>Customer Name</th>
            <th>Match Name</th>
            <th>Score</th>
            <th>&nbsp;</th>
        </thead>
        <tbody>
            <?php foreach($TEAM_LIST as $TEAM): ?>
            <?php
                $MATCH = new Match($TEAM->match_id);
                $C = new Customer($TEAM->customer_id);
            ?>
                <tr>
                    <td><a href="/admin/team/edit/<?php echo $TEAM->ID; ?>"><?php echo $C->name; ?></a></td>
                    <td><?php echo $MATCH->name; ?></td>
                    <td><a href="/admin/team/score/<?php echo $TEAM->ID; ?>">View Score</a></td>
                    <td><a class="btn btn-danger delete" href="/admin/team/remove/<?php echo $TEAM->ID; ?>">Delete</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
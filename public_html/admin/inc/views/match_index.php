<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="//ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css">

<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="//ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    $("#match_table").dataTable({
        "iDisplayLength": 25,
        "aoColumns": [
            null,
            null,
            { "asSorting": [ "asc" ] },
            null,
            null
        ]
    });
    
    $('a.delete').click(function(event){
        if(!confirm('Are you sure you want to delete this match?')){
            event.preventDefault();
        }
        
    });
    
    $('a.delete-cache').click(function(event){
        if(!confirm('Are you sure you want to delete team cache? This is dangerous if matches are already live!!!!!')){
            event.preventDefault();
        }
        
    });
});
</script>
<?php echo $MS->messages('match'); ?>
<div class="page-header">
    <h1>Manage Matches</h1>
</div>
<p><a class="btn btn-success" href="/admin/match/add">Add Match</a> <a class="btn btn-danger delete-cache" href="/admin/match/deleteTeams">Delete Team Cache</a></p>
<div class="col-lg-12">
    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped table-hover" id="match_table">
        <thead>
            <th>Name</th>
            <th>Start Date</th>
            <th>Active</th>
            <th>Locked</th>
            <th>&nbsp;</th>
        </thead>
        <tbody>
            <?php foreach($MATCH_LIST as $MATCH): ?>
                <tr>
                    <td><a href="/admin/match/edit/<?php echo $MATCH->ID; ?>"><?php echo $MATCH->name; ?></a></td>
                    <td><?php echo date("m/d/Y h:i A", $MATCH->start_date); ?></td>
                    <td>
                        <?php if($MATCH->active == "1"): ?>
                            <span class="label label-success">Active</span>
                        <?php else: ?>
                            <span class="label label-default">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($MATCH->locked == "1"): ?>
                            <span class="label label-danger">Locked</span>
                        <?php else: ?>
                            <span class="label label-default">Unlocked</span>
                        <?php endif; ?>
                    </td>
                    <td><a class="btn btn-danger delete" href="/admin/match/remove/<?php echo $MATCH->ID; ?>">Delete</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="//ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css">

<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="//ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    $("#player_table").dataTable({
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
        if(!confirm('Are you sure you want to delete this player?')){
            event.preventDefault();
        }
        
    });
});
</script>

<div class="page-header">
    <h1>Manage Players</h1>
</div>
<p><a class="btn btn-success" href="/admin/player/add">Add Player</a></p>
<div class="col-lg-12">
    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped table-hover" id="player_table">
        <thead>
            <th>MLB ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Position</th>
            <th>&nbsp;</th>
        </thead>
        <tbody>
            <?php foreach($PLAYER_LIST as $PLAYER): ?>
                <tr>
                    <td><a href="/admin/player/edit/<?php echo $PLAYER->ID; ?>"><?php echo $PLAYER->mlb_id; ?></a></td>
                    <td><?php echo $PLAYER->first_name; ?></td>
                    <td><?php echo $PLAYER->last_name; ?></td>
                    <td><?php echo $PLAYER->position; ?></td>
                    <td><a class="btn btn-danger delete" href="/admin/player/remove/<?php echo $PLAYER->ID; ?>">Delete</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
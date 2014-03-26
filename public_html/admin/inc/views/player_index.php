<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css">

<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    $("#player_table").dataTable({
        "iDisplayLength": 25,
        "aoColumns": [
            null,
            null,
            null,
            { "asSorting": [ "asc" ] },
            null
        ]
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
            <th>ID</th>
            <th>MLB ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Position</th>
        </thead>
        <tbody>
            <?php foreach($PLAYER_LIST as $PLAYER): ?>
                <tr>
                    <td><a href="/admin/player/edit/<?php echo $PLAYER->ID; ?>"><?php echo $PLAYER->ID; ?></a></td>
                    <td><?php echo $PLAYER->mlb_id; ?></td>
                    <td><?php echo $PLAYER->first_name; ?></td>
                    <td><?php echo $PLAYER->last_name; ?></td>
                    <td><?php echo $PLAYER->position; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
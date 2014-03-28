<script>
$(function() {
    $( "ul.droptrue" ).sortable({
      connectWith: "ul",
      placeholder: "ui-state-highlight"
    });
    
    $( "#sortable1, #sortable2, #sortable3" ).disableSelection();
    
    $("#save").click(function() {
        
        var players = [];
        
        $("#sortable2 li").each(function(i) {
            players[i] = $(this).attr('id');
        });
        
        $.post("/admin/player/saveAvailable/", {players: JSON.stringify(players)}, function(data) {
            if(data == "Success") {
                $("#message").html('<div class="alert alert-success">Available Players Saved</div>').slideDown();
                window.setTimeout(function() { $("#message").slideUp(); }, 3000);
            } 
        });
    });
});
</script>
<style>
  #sortable1, #sortable2, #sortable3 { list-style-type: none; margin: 0; padding: 0; float: left; margin-right: 10px; background: #CCC; padding: 5px; width: 100%;}
  #sortable1 li, #sortable2 li, #sortable3 li { margin: 5px; padding: 5px; font-size: 1.2em; width: 94%; cursor: move; }
  h2 {color: #333}
  .ui-state-highlight { height: 1.5em; line-height: 1.2em; }
  </style>
<div class="page-header">
    <h1><?php echo $TITLE; ?></h1>
</div>

<div class="row">
    <div class="col-lg-9">
        <a class="btn btn-danger pull-right" href="/admin/player/resetAvailable">Reset Available</a>
        <div class="clearfix"></div>
        <div id="message" style="display:none;"></div>
    </div>
    <br />
    <br />
</div>

<div class="row">
    <div class="col-lg-7">
        
        <div class="col-lg-5">
            <ul id="sortable1" class="droptrue">
                <?php foreach($PLAYER_LIST as $PLAYER): ?>
                    <li class="ui-state-default" id="<?php echo $PLAYER->ID; ?>"><?php echo $PLAYER->first_name . " " . $PLAYER->last_name; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-lg-2">
            <h1>Drag to right column</h1>
            <button class="btn btn-success" id="save">Save</button>
        </div>
        <div class="col-lg-5">
            <ul id="sortable2" class="droptrue">
                <?php foreach($AVAILABLE_PLAYER_LIST as $A): ?>
                    <?php $PLAYER = new Player($A->player_id); ?>
                    <li class="ui-state-default" id="<?php echo $PLAYER->ID; ?>"><?php echo $PLAYER->first_name . " " . $PLAYER->last_name; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<div class="page-header">
    <h1><?php echo $TITLE; ?></h1>
</div>

<div class="row">
    <div class="col-lg-2">
        <form role="form" action="/admin/settings/saveScoreSettings" method="post">
            <?php foreach($SETTINGS as $S): ?>
            <div class="col-lg-8 form-group">
                <label for="<?php echo $S->key; ?>"><?php echo $S->key; ?> Score</label>
                <input type="text" class="form-control" id="<?php echo $S->key; ?>" name="<?php echo $S->key; ?>" placeholder="<?php echo $S->key; ?> Score" value="<?php echo $S->value; ?>">
            </div>
            <?php endforeach; ?>
            <div class="clearfix"></div>
            
            <button class="btn btn-success">Save</button>
        </form>
    </div>
</div>
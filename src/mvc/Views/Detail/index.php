<div class="container">
    <div id="legend">
        <legend class="">Unsubscribe</legend>
    </div>
    <div class="control-group">
        <div class="controls">
            <button id="unsubscribe" class="btn btn-danger">Unsubscribe</button>
        </div>
    </div>
    <form id="form-detail" action="<?=WEB.'detail'?>" method="POST">
        <input type="hidden" id="_id" name="_id" value="<?=$data['id']?>">
        <input type="hidden" id="_csrf_token_detail" name="_csrf_token_detail" value="<?=$data['csrf_token_detail']?>">
    </form>
</div>
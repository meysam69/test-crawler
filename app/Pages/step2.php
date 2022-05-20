<div class="row">
    <div class="col">
        <p>Enter The SMS code you received:</p>
    </div>
</div>
<form class="row" action="" method="post">
    <input type="hidden" name="step" value="2">
    <div class="row mb-3">
        <label for="Code" class="col-sm-2 col-form-label">Code</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="Code" value="<?= $code; ?>">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-sm-2"></div>
        <div class="col-sm-9">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
</form>
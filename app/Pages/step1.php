<div class="row">
    <div class="col">
        <p>Enter Your Pasargad's Bank username and password below:</p>
    </div>
</div>
<form class="row" action="" method="post">
    <input type="hidden" name="step" value="2">
    <input type="hidden" name="__VIEWSTATE" value="<?= $viewState; ?>">
    <input type="hidden" name="__VIEWSTATEGENERATOR" value="<?= $viewStateGenerator; ?>">
    <input type="hidden" name="__EVENTVALIDATION" value="<?= $eventValidation; ?>">
    <div class="row mb-3">
        <label for="Username" class="col-sm-2 col-form-label">Username</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="username" value="<?= $username; ?>">
        </div>
    </div>
    <div class="row mb-3">
        <label for="password" class="col-sm-2 col-form-label">Password</label>
        <div class="col-sm-9">
            <input type="password" class="form-control" name="password" value="<?= $username; ?>">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-sm-2">
            <img alt="Captcha" width="110" height="30" src="<?= $captchaImg; ?>">
        </div>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="captcha" value="<?= $captcha; ?>">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-sm-2"></div>
        <div class="col-sm-9">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
</form>
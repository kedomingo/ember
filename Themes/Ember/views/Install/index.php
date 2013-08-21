<div class="container">
    
    <h3>Initial Checks</h3>
    
    <?php $pass = true; ?>
    
    <?php if(is_writable(DOCROOT.'/.htaccess')): ?>
        <div class="alert alert-success"><span class="icon-check"></span> fuel/public/.htaccess file is writable</div>
    <?php else: $pass = false ?>
        <div class="alert alert-danger"><span class="icon-exclamation-sign"></span> fuel/public/.htaccess file is not writable</div>
    <?php endif; ?>
    
    <?php if(is_writable(APPPATH.'/config')): ?>
        <div class="alert alert-success"><span class="icon-check"></span> fuel/app/config directory is writable</div>
    <?php else: $pass = false ?>
        <div class="alert alert-danger"><span class="icon-exclamation-sign"></span> fuel/app/config directory is not writable</div>
    <?php endif; ?>
    
    <?php if(is_writable(APPPATH.'/config/db.php')): ?>
        <div class="alert alert-success"><span class="icon-check"></span> fuel/app/config/db.php file is writable</div>
    <?php else: $pass = false ?>
        <div class="alert alert-danger"><span class="icon-exclamation-sign"></span> fuel/app/config/db.php file is not writable</div>
    <?php endif; ?>
    
    <a class="btn btn-primary <?php echo !$pass ? 'disabled' : '' ?>" href="/install/database">Continue</a>
    
</div>
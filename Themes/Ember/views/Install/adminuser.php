<div class="container">
    <h3>What is your Administrator user and password?</h3>

    <p>
        By default, this user will have access to all parts of the system. Use this to setup your configurations on the admin site.
    </p>
    
    <form class="form-horizontal" action="" method="POST">
        
        <div class="form-group">
            <label class="col-lg-2 col-sm-2" for="username">Username</label>
            <div class="col-lg-3 col-sm-3">
                <input type="text" name="username" class="form-control" id="username" />
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-2 col-sm-2" for="email">Email Address</label>
            <div class="col-lg-3 col-sm-3">
                <input type="text" name="email" class="form-control" id="email" />
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-2 col-sm-2" for="password">Password</label>
            <div class="col-lg-3 col-sm-3">
                <input type="password" name="password" class="form-control" id="username" />
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-2 col-sm-2" for="password_confirm">Password (again)</label>
            <div class="col-lg-3 col-sm-3">
                <input type="password" name="password_confirm" class="form-control" id="username" />
            </div>
        </div>
        
        <br />
        <div class="btn-group">
            <a class="btn btn-info" href="/install/database"><span class="icon-chevron-left"></span> Back</a>
            <button type="submit" class="btn btn-info">Continue <span class="icon-chevron-right"></span></button>
        </div>
        
    </form>
        
</div>
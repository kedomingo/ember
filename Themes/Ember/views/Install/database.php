<div class="container">
    <h3>Configure Your Database Connection</h3>
    
    <form class="form-horizontal" action="" method="POST">
        
        <div class="form-group">
            <label class="col-lg-2 col-sm-2" for="environment">Environment</label>
            <div class="col-lg-3 col-sm-3">
                <select class="form-control" name="environment" id="environment">
                    <option value="development">Development</option>
                    <option value="test">Test</option>
                    <option value="staging">Staging</option>
                    <option value="production">Production</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-2 col-sm-2" for="type">DBMS</label>
            <div class="col-lg-3 col-sm-3">
                <select class="form-control" name="type" id="type">
                    <option value="mysql">MySQL</option>
                    <!--<option value="pgsql">PostgreSQL</option>-->
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-2 col-sm-2" for="username">Username</label>
            <div class="col-lg-3 col-sm-3">
                <input type="text" name="username" class="form-control" id="username" />
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-2 col-sm-2" for="password">Password</label>
            <div class="col-lg-3 col-sm-3">
                <input type="password" name="password" class="form-control" id="password" />
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-2 col-sm-2" for="database">Database</label>
            <div class="col-lg-3 col-sm-3">
                <input type="text" name="database" class="form-control" id="database" />
            </div>
        </div>
        
        <div class="panel-group" id="accordion">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <a class="accordion-toggle collapsed block" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                            <?php echo ___('Advanced Options') ?>
                        </a>
                    </h3>
                </div>
                
                <div id="collapseOne" class="panel-collapse collapse">
                    <div class="panel-body" style="padding: 20px">
                        
                        <div class="form-group">
                            <label class="col-lg-2 col-sm-2" for="charset">CharSet</label>
                            <div class="col-lg-3 col-sm-3">
                                <input type="text" name="charset" class="form-control" id="charset" placeholder="put your character set" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 col-sm-2" for="hostname">Host</label>
                            <div class="col-lg-3 col-sm-3">
                                <input type="text" name="hostname" class="form-control" id="hostname" value="localhost" />
                            </div>
                            <div class="col-lg-3 col-sm-3 help">
                                <span class="icon-info-sign"></span> Usually, you don't have to change this
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 col-sm-2" for="port">Port</label>
                            <div class="col-lg-3 col-sm-3">
                                <input type="text" name="port" class="form-control" id="port" placeholder="3306" />
                            </div>
                            <div class="col-lg-3 col-sm-3 help">
                                <span class="icon-info-sign"></span> Usually, you don't have to change this
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-lg-2 col-sm-2" for="caching">Caching</label>
                            <div class="col-lg-3 col-sm-3">
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-info">
                                        <input type="radio" name="caching" value="1" /> Yes
                                    </label>
                                    <label class="btn btn-info active">
                                        <input type="radio" name="caching" value="0" checked="checked" /> No
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 col-sm-2" for="profiling">Profiling</label>
                            <div class="col-lg-3 col-sm-3">
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-info">
                                        <input type="radio" name="profiling" value="1" /> Yes
                                    </label>
                                    <label class="btn btn-info active">
                                        <input type="radio" name="profiling" value="0" checked="checked" /> No
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-3 help">
                                <span class="icon-info-sign"></span> This will only work if profiling is manually enabled in the configuration file (app/config/config.php)
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 col-sm-2" for="table_prefix">Table Prefix</label>
                            <div class="col-lg-3 col-sm-3">
                                <input type="text" name="table_prefix" class="form-control" id="table_prefix"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <br />
        <div class="btn-group">
            <a class="btn btn-info" href="/install"><span class="icon-chevron-left"></span> Back</a>
            <a class="btn btn-info test-connection" href="/install/test"><span class="icon-refresh"></span> Test Connection</a>
            <button type="submit" class="btn btn-info">Continue <span class="icon-chevron-right"></span></button>
        </div>
        
    </form>
    
</div>


<?php \Ember\Controller_Theme::append_js('inline_js') ?>
<script type="text/javascript">
    
    $(function(){
        
        $('.test-connection').click(function(e) {
            
            $form = $(this).closest('form');
            
            $ins = $form.serializeArray();
            $data = {};
            for (i in $ins) {
                $data[$ins[i]['name']] = $ins[i]['value'];
            }
            
            $form.clone().ajaxForm({
                url: '<?php echo \Uri::base() ?>install/rest/install/dbtest.json',
                data: $data,
                
                success: function(data) {
                    if (response_successful(data)) {
                        alert('Connection successful');
                    }
                    else {
                        if (typeof data == 'string') {
                            try {
                                data = $.parseJSON(data);
                            } catch(ex) {
                                alert('Connection failed: '+data);
                            }
                        }
                        alert('Connection failed: '+data.response.status.message);
                    }
                    
                },
                error: function() {
                    alert('Connection failed: Server error');
                }
                
            }).submit();
            
            
            e.preventDefault();
            e.stopPropagation();
            return false;
            
        });
        
    });
    
</script>
<?php \Ember\Controller_Theme::end() ?>
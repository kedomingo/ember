
<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $theme->title ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">

        <?php echo $asset->css('bootstrap.min.css') ?>
        <?php echo $asset->css('font-awesome.min.css') ?>
        <?php echo $asset->css('bootswatch.less', array('type' => 'text/css', 'rel' => 'stylesheet/less')); ?>
        <?php echo $asset->css('variables.less', array('type' => 'text/css', 'rel' => 'stylesheet/less')); ?>

    </head>

    <body>

        <div class="navbar navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <a href="./" class="navbar-brand">Ember</a>
                    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="navbar-collapse collapse" id="navbar-main">
                    <ul class="nav navbar-nav">
                        <li>
                            <a href="./help/">Help</a>
                        </li>
                        <li>
                            <a href="http://news.bootswatch.com">Blog</a>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="download">Settings <span class="caret"></span></a>
                            <ul class="dropdown-menu" aria-labelledby="download">
                                <li><a tabindex="-1" href="./bootstrap.min.css">bootstrap.min.css</a></li>
                                <li><a tabindex="-1" href="./bootstrap.css">bootstrap.css</a></li>
                                <li class="divider"></li>
                                <li><a tabindex="-1" href="./variables.less">variables.less</a></li>
                                <li><a tabindex="-1" href="./bootswatch.less">bootswatch.less</a></li>
                            </ul>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
        <div class="spacer" style="height: 70px;"></div>
        <div class="container">
            <?php foreach (array('alert-success' => 'success', 'alert-danger' => 'error') as $class => $flash): if (($s = \Session::get_flash($flash)) and !empty($s)): ?>
                    <br />
                    <div class="alert <?php echo $class ?>">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong><?php echo ucfirst($flash) ?></strong>: <?php echo $s ?>
                    </div>
                <?php endif;
            endforeach; ?>
        </div>

        <?php echo $partials['content_for_layout']; ?>

        <!--    <div class="container">
              <footer>
                <div class="row">
                  <div class="col-lg-12">
                    
                    <ul class="list-unstyled">
                      <li class="pull-right"><a href="#top">Back to top</a></li>
                      <li><a href="http://news.bootswatch.com" onclick="pageTracker._link(this.href); return false;">Blog</a></li>
                      <li><a href="http://feeds.feedburner.com/bootswatch">RSS</a></li>
                      <li><a href="https://twitter.com/thomashpark">Twitter</a></li>
                      <li><a href="https://github.com/thomaspark/bootswatch/">GitHub</a></li>
                      <li><a href="./help/#api">API</a></li>
                      <li><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=F22JEM3Q78JC2">Donate</a></li>
                    </ul>
                    <p>Made by <a href="http://thomaspark.me">Thomas Park</a>. Contact him at <a href="mailto:hello@thomaspark.me">hello@thomaspark.me</a>.</p>
                    <p>Code licensed under the <a href="http://www.apache.org/licenses/LICENSE-2.0">Apache License v2.0</a>.</p>
                    <p>Based on <a href="http://getbootstrap.com">Bootstrap</a>. Icons from <a href="http://fortawesome.github.io/Font-Awesome/">Font Awesome</a>. Web fonts from <a href="http://www.google.com/webfonts">Google</a>. Favicon by <a href="https://twitter.com/geraldhiller">Gerald Hiller</a>.</p>
        
                  </div>
                </div>
                
              </footer>
            
        
            </div>-->

        <!-- SCRIPTS ******************************************************* -->


        <?php
        echo $asset->js(array(
            'app.js',
            'jquery.min.js',
            'jquery.form.min.js',
            'bootstrap.min.js',
            'less-1.4.1.min.js',
        ));

        // inline_js block
        echo $asset->render('inline_js') . "\n\n";

        // inline_css block
        echo $asset->render('inline_css') . "\n\n";
        ?>        

    </body>
</html>
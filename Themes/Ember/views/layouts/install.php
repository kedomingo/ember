
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
          <a href="./" class="navbar-brand">Fuel Install</a>
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
          </ul>

        </div>
      </div>
    </div>

    <div class="splash" style="margin-top: 50px">
      <div class="container">

        <div class="row">
          <div class="col-lg-12">
            <h1>You are installing on http://<?php echo $_SERVER['HTTP_HOST'] ?>/</h1>

<!--            <div class="alert alert-block alert-info">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <p>You're currently previewing the themes for Bootstrap 3.0.0-rc2. These are still a work in progress. If you want to see version 2.3.2, visit <a class="alert-link" href="http://bootswatch.com/2/">bootswatch.com/2/</a>.
            </div>-->
            
            <div id="social">
            </div>
          </div>
        </div>

        <?php foreach (array('alert-success' => 'success', 'alert-danger' => 'error') as $class => $flash): if (($s = \Session::get_flash($flash)) and !empty($s)): ?>
            <br />
            <div class="alert <?php echo $class ?>">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong><?php echo ucfirst($flash) ?></strong>: <?php echo $s ?>
            </div>
        <?php endif; endforeach; ?>
            
      </div>
    </div>

<!--    <div class="section-tout">
      <div class="container">

        <div class="row">
          <div class="col-lg-4 col-sm-6">
            <h3><i class="icon-file-alt"></i> Easy to Install</h3>
            <p>Simply download a CSS file and replace the one in Bootstrap. No messing around with hex values.</p>
          </div>
          <div class="col-lg-4 col-sm-6">
            <h3><i class="icon-github"></i> Open Source</h3>
            <p>Bootstrap themes are licensed under Apache 2.0 and maintained by the community on <a target="_blank" href="https://github.com/thomaspark/bootswatch">GitHub</a>.</p>
          </div>
          <div class="col-lg-4 col-sm-6">
            <h3><i class="icon-wrench"></i> Tuned for 3.0.0</h3>
            <p>Themes are built for the latest version of Bootstrap. <a target="_blank" href="https://github.com/thomaspark/bootswatch/tags">Older versions</a> are also available to download.</p>  
          </div>
          <div class="col-lg-4 col-sm-6">
            <h3><i class="icon-cogs"></i> Modular</h3>
             <p>Changes are contained in just two LESS files, enabling modification and ensuring forward compatibility.</p>
          </div>
          <div class="col-lg-4 col-sm-6">
            <h3><i class="icon-cloud"></i> Get Plugged In</h3>
            <p>An <a href="./help/#api" onclick="pageTracker._link(this.href); return false;">API</a> is available for integrating with your platform. In use by <a href="http://320press.com/wpbs/" target="_blank">WPBS</a>, <a href="http://www.fusionleaf.com/" target="_blank">FusionLeaf</a>, <a href="http://yabdab.com/stacks/snaps/bootsnap/" target="_blank">BootSnap</a>, and others.</p>
          </div>
          <div class="col-lg-4 col-sm-6">
            <h3><i class="icon-bullhorn"></i> Stay Updated</h3>
            <p>Be notified about updates by subscribing via <a href="http://feeds.feedburner.com/bootswatch">RSS feed</a>, <a href="http://feedburner.google.com/fb/a/mailverify?uri=bootswatch&loc=en_US">email</a>, or <a href="http://news.bootswatch.com" onclick="pageTracker._link(this.href); return false;">Tumblr</a>.</p>
          </div>
        </div>

      </div>
    </div>-->

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
<body <?= body_classes() ?>>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
        <a class="brand" href="#"><img src="<?= site_url('/assets/images/logo.png')?>"/></a>
    	<ul class="nav pull-right">                        
    	<? if(logged_in()) { ?>                                                                 
    	    <li><a href="<?= site_url('/admin/events/browse') ?>">Events</a></li>
    	    <li><a href="<?= site_url('/admin/bands/browse') ?>">Bands</a></li>
    	    <li><a href="<?= site_url('/admin/venues/browse') ?>">Venues</a></li>
    	    <li><a href="<?= site_url('/admin/regions/browse') ?>">Regions</a></li>
    	    <li><a href="<?= site_url('/admin/genres/browse') ?>">Genres</a></li>
    	    <li><a href="<?= site_url('/user/users/browse') ?>">Users</a></li>
    	    <li><a href="<?= site_url('/admin/reports/events') ?>">Reports</a></li>
    	    <li><a href="<?= site_url('/admin/cron') ?>" target="_BLANK">Sync Events</a></li>
    	    <li><a href="<?= site_url('/user/logout') ?>">Logout</a></li> 
    	<? } ?>
    	</ul>
        </div>
      </div>
    </div>
    
    <?php $this->load->view('notifications') // display the notitification partial ?>
    
    <div class="main">
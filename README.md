aegis
=====

Starter framework for Codeigniter, including Datamapper, HMVC, easy CRUD implementation and lots more.

installation
============

1. Copy files to working directory
2. Edit the following app/config file variables as per requirements:

	database.php
		$db['default']['hostname'] = 'localhost';
		$db['default']['username'] = 'root';
		$db['default']['password'] = 'root';
		$db['default']['database'] = 'db_aegis';

	datamapper.php
		$config['prefix'] = 'ae_';
	
	.htaccess
		RewriteBase /aegis
		
3. Edit "users_setup.sql" script in modules/user/installation to include chosen prefix (as per above).
4. Run "users_setup.sql" to create tables (users, user_logins, user_groups) with default creds (aegis@bigspring.co.uk:aegis13)

That should get the basic site up and running but if it doesn't then come tell me to stop being a dick and to sort it out - Dave

changelog
=====

0.1 – hello cleveland!
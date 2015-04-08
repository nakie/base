#!/usr/bin/env bash

###############################
## Drupal Initialization Script
## This file downloads drupal for the
## Provided version number
## Creates a test database given the
## Provided db name / user / password.
##

#Drupal Version
version=7.34

# Database User to setup
db_user=uname

# Database Name to setup
db_name=dbname

# Password for Database User.
db_pass=pass


# If no index.php exists in the public folder start the Drupal download.
if [ ! -f /vagrant/public/index.php ];
then

	# Download and extract Drupal files.
	cd /vagrant/public
	wget https://www.drupal.org/files/projects/drupal-$version.tar.gz
	tar -zxvf drupal-$version.tar.gz
	
	# Change directory to extracted folder	
	cd ./drupal-$version
	
	# Move extracted files into Document root.
	mv * ../
	
	# Clean up original download file.
	cd /vagrant/public
	rm -rf drupal-*
	
	echo "Drupal downloaded... load localhost in a browser to start the installation."
	
else
	echo "There is already an index.php file in your document root... Please clear your document root and try again."
fi

if [ ! -f /var/log/databasesetup ];
then
    echo "CREATE USER '$db_user'@'localhost' IDENTIFIED BY '$db_pass'" | mysql -uroot -p12passwd34
    echo "CREATE DATABASE $db_name" | mysql -uroot -p12passwd34
    echo "GRANT ALL ON $db_name.* TO '$db_user'@'localhost'" | mysql -uroot -p12passwd34
    echo "flush privileges" | mysql -uroot -p12passwd34

    sudo touch /var/log/databasesetup

    if [ -f /vagrant/data/initial.sql ];
    then
        mysql -uroot -p12passwd34 dev_db < /vagrant/data/initial.sql
    fi
	
else
	echo "/var/log/databasesetup file exists! NO database or user created"
fi


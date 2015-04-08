#!/usr/bin/env bash

###############################
## Wordpress Initialization Script
## This file downloads Wordpress for the
## Provided version number
## Creates a test database given the
## Provided db name / user / password.
##

#WordPress Version
version=https://wordpress.org/latest.tar.gz

# Database User to setup
db_user=uname

# Database Name to setup
db_name=dbname

# Password for Database User.
db_pass=pass


# If no index.php exists in the public folder start the WordPress download.
if [ ! -f /vagrant/public/index.php ];
then


	# Download and extract WordPress files.
	cd /vagrant/public

	wget $version
	tar -zxvf latest.tar.gz

	# Change directory to extracted folder
	cd ./wordpress

	# Move extracted files into Document root.
	mv * ../

	# Clean up original download file.
	cd /vagrant/public
	rm -rf latest.tar.gz
	rm -rf wordpress

	echo "WordPress downloaded... load localhost in a browser to start the installation."

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


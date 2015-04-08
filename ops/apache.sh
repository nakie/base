#!/usr/bin/env bash

sudo apt-get -y install  apache2

if [ ! -h /var/www ];
then 
    sudo rm -rf /var/www/html
    sudo ln -s /vagrant/public /var/www/html

    sudo a2enmod rewrite

    #sed -i '/AllowOverride None/c AllowOverride All' /etc/apache2/sites-available/default

	sudo mv /vagrant/env_config/envvars /etc/apache2/envvars
	sudo chown vagrant /var/lock/apache2
    sudo usermod -a -G adm vagrant
	
    sudo service apache2 restart
fi
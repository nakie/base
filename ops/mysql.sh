#!/usr/bin/env bash

sudo debconf-set-selections <<< 'mysql-server-5.5 mysql-server/root_password password 12passwd34'
sudo debconf-set-selections <<< 'mysql-server-5.5 mysql-server/root_password_again password 12passwd34'


sudo apt-get -y install mysql-server-5.5
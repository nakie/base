#!/usr/bin/env bash

#####################
## Mongo Database Installer
#####################


# steps from http://docs.mongodb.org/manual/tutorial/install-mongodb-on-ubuntu/
## sudo apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv 7F0CEB10
## echo "deb http://repo.mongodb.org/apt/ubuntu "$(lsb_release -sc)"/mongodb-org/3.0 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-3.0.list
##  sudo apt-get update

# Install latest stable
## sudo apt-get install -y mongodb-org
# or for a specific version
## sudo apt-get install -y mongodb-org=3.0.0 mongodb-org-server=3.0.0 mongodb-org-shell=3.0.0 mongodb-org-mongos=3.0.0 mongodb-org-tools=3.0.0
# The MongoDB instance stores its data files in /var/lib/mongodb and its log files in /var/log/mongodb by default, and runs using the mongodb user account. You can specify alternate log and data file directories in /etc/mongod.conf.
# If you change the user that runs the MongoDB process, you must modify the access control rights to the /var/lib/mongodb and /var/log/mongodb directories to give this user access to these directories.
## sudo service mongod start


## Have'nt tested yet, but i was able to install using plain'ole apt-get 

sudo apt-get -y install mongodb mongodb-server
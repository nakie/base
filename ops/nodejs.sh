#!/usr/bin/env bash
#####################
## Node Installer
#####################

#sudo apt-get install nodejs npm



## Changing this again to follow the instructions at
## https://github.com/joyent/node/wiki/installing-node.js-via-package-manager
## 
## 

curl -sL https://deb.nodesource.com/setup | sudo bash -

sudo apt-get install -y nodejs

# sudo npm install --no-bin-links --no-bin-links  <name of module to install >
# use --no-bin-links to prevent symlink issues on vagrant/windows.

## Updating Node to latest version
# using instructions found at http://davidwalsh.name/upgrade-nodejs

sudo npm cache clean -f 
sudo npm install -g n
sudo n stable

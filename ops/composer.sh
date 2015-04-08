#!/usr/bin/env bash
#
###############################
# Composer Install Script
#

# Get composer.. 

curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
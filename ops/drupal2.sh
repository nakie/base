#!/usr/bin/env bash
#
###############################
# Drupal Initialization Script
# this time taking a different route
# download composer and drush to 
# hopefully automate creating drupal
# instillations from simple make files.
#


## now go and get Drush current version 6.x
composer global require drush/drush:6.*

sudo ln -s /home/vagrant/.composer/vendor/bin/drush /usr/local/bin/drush
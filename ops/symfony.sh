#!/usr/bin/env bash
#
###############################
# Symfony Framework installation
#
# Following Instructions found at :
# http://symfony.com/doc/current/quick_tour/the_big_picture.html

# Download symfony installer program/script
curl -LsS http://symfony.com/installer > symfony.phar
sudo mv symfony.phar /usr/local/bin/symfony
chmod a+x /usr/local/bin/symfony

cd /home/vagrant

symfony new symfonytmp
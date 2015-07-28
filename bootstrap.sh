#!/usr/bin/env bash

# Use single quotes instead of double quotes to make it work with special-character passwords
PASSWORD='password'
PROJECTFOLDER='toy_api'

# create project folder
sudo mkdir "/var/www/html/${PROJECTFOLDER}"

# update / upgrade
sudo apt-get update
sudo apt-get -y upgrade

# install apache 2.5 and php 5.5
sudo apt-get install -y apache2
sudo apt-get install -y php5

# mbstring
sudo apt-get install libapache2-mod-php5

# install mysql and give password to installer
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password $PASSWORD"
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $PASSWORD"
sudo apt-get -y install mysql-server
sudo apt-get install php5-mysql

# install phpmyadmin and give password(s) to installer
# for simplicity I'm using the same password for mysql and phpmyadmin
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/dbconfig-install boolean true"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/app-password-confirm password $PASSWORD"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/admin-pass password $PASSWORD"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/app-pass password $PASSWORD"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2"
sudo apt-get -y install phpmyadmin

# setup hosts file
VHOST=$(cat <<EOF
<VirtualHost *:80>
    DocumentRoot "/var/www/html/${PROJECTFOLDER}/public"
    <Directory "/var/www/html/${PROJECTFOLDER}">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
EOF
)
echo "${VHOST}" > /etc/apache2/sites-available/000-default.conf

# enable mod_rewrite
sudo a2enmod rewrite

# restart apache
service apache2 restart

# install git
sudo apt-get -y install git

# install Composer
curl -s https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# install PHPUnit (as vagrant user)
sudo -H -u vagrant bash -c 'composer global require "phpunit/phpunit=4.1.*"'
sudo -H -u vagrant bash -c 'composer global require "phpunit/php-invoker=~1.1."'
sudo -H -u vagrant bash -c 'sudo ln -s  ~/.composer/vendor/phpunit/phpunit/phpunit   /usr/bin/'

# install Lumen
composer global require "laravel/lumen-installer=~1.0"
echo PATH="$HOME/.composer/vendor/bin:$PATH" >> $HOME/.profile
. $HOME/.profile

# setup DB
DB_CONF=$(cat <<EOF
CREATE DATABASE ${PROJECTFOLDER} CHARACTER SET utf8;
USE ${PROJECTFOLDER};
GRANT select, insert, update, delete, lock tables, drop, create, create temporary tables, execute on ${PROJECTFOLDER}.* to '${PROJECTFOLDER}' identified by '${PASSWORD}';
EOF
)
echo "${DB_CONFIG}" | mysql -u root -p$PASSWORD

php artisan migrate
php artisan db:seed
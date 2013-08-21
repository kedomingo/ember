#!/bin/bash

# Activate the install module
cp -r Modules/.install Modules/install


platform='unknown'
unamestr=`uname`
if [[ "$unamestr" == 'Linux' ]]; then
   platform='linux'
elif [[ "$unamestr" == 'Darwin' ]]; then
   platform='mac'
fi

docomposer=0

if [[ $docomposer == 1 ]]; then
    echo "Getting dependencies..."
    php composer.phar self-update
    php composer.phar update
fi

# Create symlinks in vendor directory

# core to fuel
if [ -h vendor/fuel/fuel/fuel/core ]
then
    echo " > vendor/fuel/fuel/fuel/core is already symlink. skipping."
else
    if [ -e vendor/fuel/fuel/fuel/core ]
    then
        rm -rf vendor/fuel/fuel/fuel/core
    fi
    ln -s `pwd`/vendor/fuel/core vendor/fuel/fuel/fuel/core
fi


# Modules to app
if [ -h vendor/fuel/fuel/fuel/app/modules ]
then
    echo " > vendor/fuel/fuel/fuel/app/modules is already symlink. skipping."
else
    if [ -e vendor/fuel/fuel/fuel/app/modules ]
    then
        rm -rf vendor/fuel/fuel/fuel/app/modules
    fi
    ln -s `pwd`/Modules vendor/fuel/fuel/fuel/app/modules
fi



# Themes to app
if [ -h vendor/fuel/fuel/fuel/app/themes ]
then
    echo " > vendor/fuel/fuel/fuel/app/themes is already symlink. skipping."
else
    if [ -e vendor/fuel/fuel/fuel/app/themes ]
    then
        rm -rf vendor/fuel/fuel/fuel/app/themes
    fi
    ln -s `pwd`/Themes vendor/fuel/fuel/fuel/app/themes
fi



# Themes to public 
if [ -h vendor/fuel/fuel/public/themes ]
then
    echo " > vendor/fuel/fuel/public/themes is already symlink. skipping."
else
    if [ -e vendor/fuel/fuel/public/themes ]
    then
        rm -rf vendor/fuel/fuel/public/themes
    fi
    ln -s `pwd`/Themes vendor/fuel/fuel/public/themes
fi



# data to public 
if [ -h vendor/fuel/fuel/public/data ]
then
    echo " > vendor/fuel/fuel/public/data is already symlink. skipping."
else

    # remove data folder if existing
    if [ -e vendor/fuel/fuel/public/data ]
    then
        rm -rf vendor/fuel/fuel/public/data
    fi

    # Create data folder if does not exist
    if [ ! -e `pwd`/data ]
    then
        mkdir `pwd`/data
        chmod 777 data
    fi
    ln -s `pwd`/data vendor/fuel/fuel/public/data
fi


# orm to packages
if [ -h vendor/fuel/fuel/fuel/packages/orm ]
then
    echo " > vendor/fuel/fuel/fuel/packages/orm is already symlink. skipping."
else
    if [ -e vendor/fuel/fuel/fuel/packages/orm ]
    then
        rm -rf vendor/fuel/fuel/fuel/packages/orm
    fi
    ln -s `pwd`/vendor/fuel/orm vendor/fuel/fuel/fuel/packages/orm
fi

# auth to packages
if [ -h vendor/fuel/fuel/fuel/packages/auth ]
then
    echo " > vendor/fuel/fuel/fuel/packages/auth is already symlink. skipping."
else
    if [ -e vendor/fuel/fuel/fuel/packages/auth ]
    then
        rm -rf vendor/fuel/fuel/fuel/packages/auth
    fi
    ln -s `pwd`/vendor/fuel/authentication vendor/fuel/fuel/fuel/packages/auth
fi

# oil to packages
if [ -h vendor/fuel/fuel/fuel/packages/oil ]
then
    echo " > vendor/fuel/fuel/fuel/packages/oil is already symlink. skipping."
else
    if [ -e vendor/fuel/fuel/fuel/packages/oil ]
    then
        rm -rf vendor/fuel/fuel/fuel/packages/oil
    fi
    ln -s `pwd`/vendor/fuel/oil vendor/fuel/fuel/fuel/packages/oil
fi



if [[ $docomposer == 1 ]]; then

    echo "Updating FuelPHP Dependencies"
    cd vendor/fuel/fuel
    php composer.phar self-update
    php composer.phar update
    cd ../../..
fi

echo "Editing core files "


# Set permissions and modes
chmod -R 777 vendor/fuel/fuel/fuel/app/cache
chmod -R 777 vendor/fuel/fuel/fuel/app/logs
chmod -R 777 vendor/fuel/fuel/fuel/app/tmp


# due to difference in sed syntax between platforms...
# TODO: SED THE ROUTES
if [[ $platform == 'mac' ]]; then

    # Point to the correct vendor path (erase occurrences and re-apply)
    # define gettext function __ so fuel will not use it
    sed -i '' "s/define('VENDORPATH', realpath(__DIR__.'\/..\/..\/..\/').DIRECTORY_SEPARATOR);//g" vendor/fuel/fuel/public/index.php
    sed -i '' "s/require VENDORPATH.'fuel\/fuel\/fuel\/vendor\/autoload.php';//g" vendor/fuel/fuel/public/index.php
    sed -i '' "s/function __(){return call_user_func_array('___', func_get_args());}//g" vendor/fuel/fuel/public/index.php
    sed -i '' "s/require APPPATH.'bootstrap.php';/define('VENDORPATH', realpath(__DIR__.'\/..\/..\/..\/').DIRECTORY_SEPARATOR);\
    require VENDORPATH.'fuel\/fuel\/fuel\/vendor\/autoload.php';\
    function __(){return call_user_func_array('___', func_get_args());}\
    require APPPATH.'bootstrap.php';\
    /g" vendor/fuel/fuel/public/index.php


    # in oil console script, Point to the correct vendor path (erase occurrences and re-apply)
    # define gettext function __ so fuel will not use it
    sed -i '' "s/define('VENDORPATH', realpath(__DIR__.'\/..\/..\/').DIRECTORY_SEPARATOR);//g" vendor/fuel/fuel/oil
    sed -i '' "s/require VENDORPATH.'fuel\/fuel\/fuel\/vendor\/autoload.php';//g" vendor/fuel/fuel/oil
    sed -i '' "s/function __(){return call_user_func_array('___', func_get_args());}//g" vendor/fuel/fuel/oil
    sed -i '' "s/require APPPATH.'bootstrap.php';/define('VENDORPATH', realpath(__DIR__.'\/..\/..\/').DIRECTORY_SEPARATOR);\
    require VENDORPATH.'fuel\/fuel\/fuel\/vendor\/autoload.php';\
    function __(){return call_user_func_array('___', func_get_args());}\
    require APPPATH.'bootstrap.php';\
    /g" vendor/fuel/fuel/oil

# For other platforms
else

    # Point to the correct core directory
    sed -i "s/'\/..\/fuel\/core\/'/'\/..\/..\/core\/'/g" vendor/fuel/fuel/public/index.php

    # Point to the correct vendor path (erase occurrences and re-apply)
    sed -i "s/define('VENDORPATH', realpath(__DIR__.'\/..\/..\/..\/').DIRECTORY_SEPARATOR);//g" vendor/fuel/fuel/public/index.php
    sed -i "s/require VENDORPATH.'fuel\/fuel\/fuel\/vendor\/autoload.php';//g" vendor/fuel/fuel/public/index.php
    sed -i "s/function __(){return call_user_func_array('___', func_get_args());}//g" vendor/fuel/fuel/public/index.php
    sed -i "s/require APPPATH.'bootstrap.php';/define('VENDORPATH', realpath(__DIR__.'\/..\/..\/..\/').DIRECTORY_SEPARATOR);\
    require VENDORPATH.'fuel\/fuel\/fuel\/vendor\/autoload.php';\
    function __(){return call_user_func_array('___', func_get_args());}\
    require APPPATH.'bootstrap.php';\
    /g" vendor/fuel/fuel/public/index.php


    # in oil console script, Point to the correct vendor path (erase occurrences and re-apply)
    sed -i "s/define('VENDORPATH', realpath(__DIR__.'\/..\/..\/').DIRECTORY_SEPARATOR);//g" vendor/fuel/fuel/oil
    sed -i "s/require VENDORPATH.'fuel\/fuel\/fuel\/vendor\/autoload.php';//g" vendor/fuel/fuel/oil
    sed -i "s/function __(){return call_user_func_array('___', func_get_args());}//g" vendor/fuel/fuel/oil
    sed -i "s/require APPPATH.'bootstrap.php';/define('VENDORPATH', realpath(__DIR__.'\/..\/..\/').DIRECTORY_SEPARATOR);\
    require VENDORPATH.'fuel\/fuel\/fuel\/vendor\/autoload.php';\
    function __(){return call_user_func_array('___', func_get_args());}\
    require APPPATH.'bootstrap.php';\
    /g" vendor/fuel/fuel/oil

fi


# copy bootstrap.php in module/admin/cofig to app/
if [[ ! -e vendor/fuel/fuel/fuel/app/bootstrap.php.bak ]]; then
    cp vendor/fuel/fuel/fuel/app/bootstrap.php vendor/fuel/fuel/fuel/app/bootstrap.php.bak
fi
cp Modules/install/configs/bootstrap.tpl.php vendor/fuel/fuel/fuel/app/bootstrap.php

# copy session.php in module/admin/config to app/config. CREATE BACKUPS ONLY ONCE
if [[ -e vendor/fuel/fuel/fuel/app/config/session.php ]]; then
    if [[ ! -e vendor/fuel/fuel/fuel/app/config/session.php.bak ]]; then
        cp vendor/fuel/fuel/fuel/app/config/session.php vendor/fuel/fuel/fuel/app/config/session.php.bak
    fi
fi
cp Modules/install/configs/session.tpl.php vendor/fuel/fuel/fuel/app/config/session.php


# copy crypt.php in module/admin/cofig to app/config. CREATE BACKUPS ONLY ONCE
if [[ -e vendor/fuel/fuel/fuel/app/config/crypt.php ]]; then
    if [[ ! -e vendor/fuel/fuel/fuel/app/config/crypt.php.bak ]]; then
        cp vendor/fuel/fuel/fuel/app/config/crypt.php vendor/fuel/fuel/fuel/app/config/crypt.php.bak
    fi
fi
cp Modules/install/configs/crypt.tpl.php vendor/fuel/fuel/fuel/app/config/crypt.php

# copy auth configuration files in module/admin/cofig to app/config
if [[ -e vendor/fuel/fuel/fuel/app/config/auth.php ]]; then
    if [[ ! -e vendor/fuel/fuel/fuel/app/config/auth.php.bak ]]; then
        cp vendor/fuel/fuel/fuel/app/config/auth.php vendor/fuel/fuel/fuel/app/config/auth.php.bak
    fi
fi
if [[ -e vendor/fuel/fuel/fuel/app/config/ormauth.php ]]; then
    if [[ ! -e vendor/fuel/fuel/fuel/app/config/ormauth.php.bak ]]; then
        cp vendor/fuel/fuel/fuel/app/config/ormauth.php vendor/fuel/fuel/fuel/app/config/ormauth.php.bak
    fi
fi
cp Modules/install/configs/auth.tpl.php vendor/fuel/fuel/fuel/app/config/auth.php
cp Modules/install/configs/ormauth.tpl.php vendor/fuel/fuel/fuel/app/config/ormauth.php





echo "Finished."
if [ -e credentials.php ]
then
    echo "";
    echo "IMPORTANT: You may want to move credentials.php outside your server's document root. Please see that file for instructions.";
    echo "";
fi
echo "";
echo "";
echo "Edit vendor/fuel/fuel/public/.htaccess to set the FUEL_ENV environment variable";

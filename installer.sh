#!/bin/bash

# Install Git
sudo yum install curl-devel expat-devel gettext-devel openssl-devel zlib-devel -y
sudo yum install gcc perl-ExtUtils-MakeMaker -y
cd /usr/src
wget https://www.kernel.org/pub/software/scm/git/git-2.9.3.tar.gz
tar xzf git-2.9.3.tar.gz 
cd git-2.9.3
make prefix=/usr/local/git all
make prefix=/usr/local/git install
export PATH=$PATH:/usr/local/git/bin
source /etc/bashrc

# Install PHP
sudo yum install php php-fpm -y
sudo chkconfig --levels 235 php-fpm on

# Updae Configs
sudo sed -i "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/" /etc/php.ini
sudo sed -i "s/user = apache/user = msysnginx/" /etc/php-fpm.d/www.conf
sudo sed -i "s/group = apache/group = msysnginx/" /etc/php-fpm.d/www.conf

# Build the web service
sudo mkdir -p /var/www/momostatus

sudo echo "
server {
  listen 80;
  server_name _;
  root /var/www/momostatus;

  location / {
    index index.html index.php;
  }

  location ~*  \.(jpg|jpeg|png|gif|ico|css|js|woff|eot|svg|otf|ttf)$ {
    expires 1y;
  }
  location ~ \.php$ {
        root           /var/www/momostatus;
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include        fastcgi_params;
  }

}
" >/opt/msys/3rdParty/nginx/conf.d/webui.conf

# Populate the WebUI pulling from Git
cd /var/www/momostatus
git init
# You should probably clone this to your own repo and will need to enter your credentials here 
git pull https://github.com/botbuilder2000/mui.git

 
# Restart the services
sudo service php-fpm restart
sudo service msys-nginx restart

echo 
echo 
echo "now just point your browser to http://$PUBLICIP"
echo 
echo 



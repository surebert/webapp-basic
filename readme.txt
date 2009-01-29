Welcome to a new surebert framework.  If you have questions you can email paul.visco@roswellpark.org

1.  In order to get this up and running you will need to set up the following in your httpd.conf or httpd-vhosts.conf

//and add a virtual host for your site.  Note that it must point directly to the public folder as nothing else is served
<VirtualHost *:80>
        ServerName YOUR_APP.YOUR_EXT #e.g. mysite.roswellpark.org
       
        DocumentRoot /PATH/TO/YOUR_APP/public #.e.g /var/www/html/mysite
        CustomLog /PATH/TO/YOUR_SITE_access.log combined
        ErrorLog /PATH/TO/YOUR_SITE_error.log
</VirtualHost>

2.  Change the ownership of your project directory to something your web server can read

3.  Make sure that the /private/cache, /private/logs and /public/content directory are all writable by apache

4.  If you do not yet have a real DNS entry for your app, make sure to add you site's addres to your hosts file.  On mac/linux it is in /etc/hosts
 and on a PC it it is c:\windows\system32\drivers\etc\hosts ( I think)
 
//add your host
#SERVER_IP       YOURAPP
127.0.0.1		 myapp

At that point you should be ready to go
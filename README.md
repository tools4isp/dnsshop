dnsshop
=======

# Installation instructions

git clone https://github.com/tools4isp/dnsshop.git

mv dnsshop/* <location where you want the files>

cd <location where you want the files>

mysql -u root -p

 # Create database for dnsshop and create a user for it

mysql -u <dnsshop mysql user> -p <dnsshop mysql database> < dnsshop.sql

mysql -u <powerdns mysql user> -p <powerdns mysql database> < powerdns.sql # Includes changes based on the default PowerDNS scheme with DNSsec support

Update settings in config.php, look for $lang_dir, $layout_dir, $template_dir and for $config. In $config you need to set 

the "central" database to <dnsshop mysql database> and "dns" to <powerdns database>

# Default login details

Username: admin

Password: dnsshop

dnsshop
=======

[![DNS Shop v2.2.7](https://img.shields.io/badge/DNS%20Shop-v2.2.7-brightgreen.svg)](https://github.com/tools4isp/dnsshop/?shop=2.2.7) 


# Installation Instructions

<p>Note: Change the path for the default folder location</p>

1) `git clone https://github.com/tools4isp/dnsshop.git`

2) `mv dnsshop/* <location where you want the files>`

3) `cd <location where you moved the files>`

# MySQL Setup

* Login to MYSQL --> `mysql -u root -p`

# Import DNS Shop database and PowerDNS schemas to MySQL

* `mysql -u <dnsshop mysql user> -p <dnsshop mysql database> < dnsshop.sql`

* `mysql -u <powerdns mysql user> -p <powerdns mysql database> < powerdns.sql` 

<p>The schema(s) includes changes based on the default PowerDNS scheme with DNSsec support.</p>

<p>Note: Do not forget to create user for the same.</p>


4) `cp config.new.php config.php`

5) Update settings in config.php, look for: 
<ol>
<li>$lang_dir,</li>
<li>$layout_dir,</li>
<li>$template_dir,</li>
</ol>

6) For $config. In $config you need to set: 
<ol>
<li>"central" database to `<dnsshop mysql database>`</li>
<li>"dns" database to `<powerdns database>`</li>
</ol>

# Default Login Details

Username: `admin`
Password: `dnsshop`

# Commercial support

Commercial support is available via info@tools4isp.com

# Non-commercial/free support

Free support is available on irc.<br />
Server: irc.ircunited.com<br />
Channel: #tools4isp

# New version

We are working on a new version. Issues, bug reports and feature requests are welcome so we can fix them in the new version before releasing the new version.

Thanks,<br />
The Management<br />
DNS Shop

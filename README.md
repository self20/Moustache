## Moustache ##
##### A multi-client, multi-user torrent web application #####
[![Code Quality](https://scrutinizer-ci.com/g/gui-don/Moustache/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/gui-don/Moustache/?branch=master)
[![CircleCI branch](https://img.shields.io/circleci/project/github/gui-don/Moustache/master.svg)](https://circleci.com/gh/gui-don/Moustache)

| PHP 7.1 Support                              | PHP 7.0 Support                              | PHP 5.6 Support                              | Older PHP Support                            |
|:--------------------------------------------:|:--------------------------------------------:|:--------------------------------------------:|:--------------------------------------------:|
| [✔](https://travis-ci.org/gui-don/Moustache) | [✔](https://travis-ci.org/gui-don/Moustache) | [✖](https://travis-ci.org/gui-don/Moustache) | [✖](https://travis-ci.org/gui-don/Moustache) |

<p align="center"><img width="120" src="moustache.png" /></p>

It’s KISS. It’s elegant. Like a Moustache.

Moustache is a web application that act as a front-end for a back-end torrent client (transmission, deluge…).

It brings:
- Multi-users on a single instance, with separation of torrents;
- limitation of a simple user’s permission, only admin can administrate their back-end torrent client;
- direct-download torrent files from Moustache to user’s browsers;
- simple UI with a single click download.

Moustache loves you (however it’s still a software).

### Installation ###

- Download this project wherever you want it to be installed:


        $ cd /var/www/
        $ git clone git@github.com:gui-don/Moustache.git moustache

- Run composer to install dependencies inside the application directory (depending on your permission system, you may want to run this command as www-data user).


        $ cd /var/www/moustache
        $ SYMFONY_ENV=prod composer install -o --no-dev # If composer is installed on your system
        $ SYMFONY_ENV=prod php bin/composer.phar install -o --no-dev # Alternative

- If it is the first time, composer will ask for every values in the Configuration section.

- Create database the first time:

        $ bin/console doctrine:database:create --env=prod -v --if-not-exists
        $ bin/console doctrine:schema:create --env=prod -v

### Configuration ###

At the end of the composer process, you will be asked for some configuration.

- **database_name**: Name of the database.
- **database_path**: The database path (you can use the `%kernel.root_dir%` variable to relate to the installation path/app).
- **secret**: A secret use to hash/password generation. Choose a random value.
- **torrent_rpc_host**: The torrent RPC host. It’s likely to be *127.0.0.1*.
- **torrent_rpc_port**: The torrent RPC port.
- **torrent_storage**: The path where to store downloaded torrents. You can use a special variable `:username:` (careful semicolons **:** not %) within the path, which will be replace dynamically by a Moustache user’s username. Careful, the path(s) must be RW for the system webserver user as well as the torrent RPC client or an error will occur at runtime.
- **maintenance_lock_file**: The path and filename of a file which, if present, will redirects all users to a 503 maintenance page.
- **allow_direct_download**: Determines whether or not users are allowed to direct download their torrent.
- **compass_path**: Compass path. Ignore this if you are not a developer.
- **sass_path**: SASS path. Ignore this if you are not a developer.
- **uglifycss_path**: Uglify CSS path. Ignore this if you are not a developer.
- **uglifyjs_path**: Uglify JS path. Ignore this if you are not a developer.

The configuration file is stored in `app/config/parameters.yml`.
If your PHP installation enables it, a symlink has been created in `/etc/moustache/parameters.yml` on your system. We suppose you use Linux.

### Server administration ###

- You’ll need PHP **7.0 or more** with the following extensions: *iconv*, *mbstring*, *intl*, *dom* and *sqlite*.
- PHP configuration: *opcache* is recommended and should be enabled by default. *8M* for *memory_limit* should be sufficient, if not *16M* will do.
- HTTP server: nginx, apache2 or lighthttpd are great solutions for a public server use.
- If you use Moustache at home, in a local NAS for example, you can use PHP internal server:

        $ cd /path/to/moustache
        $ php -S your_ip:a_random_port -t web web/app.php

- Security: **HTTPS** is mandatory to secure Moustache. If not, consider your and other users passwords public.
- Security: Moustache does not provide built-in security against brute force attacks on login form.
- Security: please, refer to best security practices (web, PHP, IDS…) if you decide to put Moustache online.


### User creation ###

Because this application was not made to be available for anyone to register, it implements a unique way to create new users.
Administrator has to run the following command:

        $ bin/console moustache:generate:signup newUser --env=prod

Where `newUser` is the username of the person you want to be able to signup.
The command generates a unique signup link you can share with your friend so they can set a password and be able to log in.

Note: for now, it is not possible for the new user to change their username, because it might be dynamically used for torrent pathes.
Ask your friend’s preference before chosing a name.

### KNOWN ISSUES ###

- TODO: Only handle transmission client for now. Ask for new client or make a PR.
- TODO: There is no UI function to remove a torrent. `/remove/{id}` is the URL. It’ll come later.
- TODO: Download buttons does not work.
- TODO: It’s not possible to download a torrent with a magnet.
-
- Torrent does not appear stopped or started after doing the action, the page has to be reloaded. It happens because Moustache is asynchronous and the torrent client is slower.

### Security ###

#### Download button disabled ####

To prevent malicious torrents to become remotely executable, some file are not downloadable from the web interface (download button is disabled).
For now, authorized download files are whitelisted within Moustache code.

#### Web server configuration ####

Concerning previous point, it’s essential for the web server administrator of Moustache **NOT** to let the Apache, Nginx or whatever interpret fancy file extensions.
Moustache needs web server software to handle `.php`, that is all.
Of course, if you run other web applications with the same server, it can also handle `.py`, `.asp` or whatever genuine script you want.

Here is an exemple of a **BAD CONFIGURATION**: making your server interpret `.ogg` files. It’s very common sens. Ignoring this warning might make Moustache highly vulnerable as it can expose such files.

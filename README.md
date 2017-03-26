## Moustache ##
##### Another Torrent Web Client #####

<p align="center"><img width="150" src="moustache.png" /></p>

It’s KISS. It’s elegant. Like a Moustache.

Moustache loves you (however it’s still a software).

### Installation ###

- Download this project wherever you want it to be installed:


    $ cd /var/www/
    $ git clone git@github.com:gui-don/Moustache.git moustache

- Run composer to install dependencies inside the application directory (depending on your permission system, you may want to run this command as www-data user).


    $ cd /var/www/moustache
    $ composer install -o --no-dev # If composer is installed on your system
    $ php composer.phar install -o --no-dev # Alternative

### KNOWN ISSUES ###

- TODO: There is no UI function to remove a torrent. `/remove/{id}` is the URL. It’ll come later.
- TODO: If torrent storage path does not exists, an error occurred. Moustache must be able to handle that itself.
- TODO: Download buttons does not work.
- TODO: It’s not possible to download a torrent with a direct URL or a magnet.
-
- Sometimes, mime type guessing is wrong.
- Torrent does not appear stopped or started after doing the action, the page has to be reloaded. It happens because Moustache is asynchronous and the torrent client is slower.

### Configuration ###

At the end of the composer process, you will be asked for some configuration.

- **database_name**: Name of the database.
- **database_path**: The database path (you can use %kernel.root_dir% variable to relate to the installation path/app).
- **secret**: A secret use to hash/password generation. Choose a random value.
- **torrent_rpc_host**: The torrent RPC host. It’s likely to be 127.0.0.1.
- **torrent_rpc_port**: The torrent RPC port.
- **torrent_storage**: The path where to store downloaded torrent. You can use a special variable `:username:` within the pass, which will be replace dynamically by a Moustache user’s username. Careful, the path(s) must be RW for the system webserver user as well as the torrent RPC client or an error will occur at runtime.
- **compass_path**: Compass path. Ignore this if you are not a developer.
- **sass_path**: SASS path. Ignore this if you are not a developer.
- **uglifycss_path**: Uglify CSS path. Ignore this if you are not a developer.
- **uglifyjs_path**: Uglify JS path. Ignore this if you are not a developer.

The configuration file is stored in *application_path*`app/config/parameters.yml`.
If your PHP installation enables it, a symlink has been created in `/etc/moustache/parameters.yml`.

### Server administration ###



### Security ###

#### Download button disabled ####

To prevent malicious torrents to become remotely executable, some file are not downloadable from the web interface (download button is disabled).
For now, authorized download files are whitelisted within Moustache code.

#### Web server configuration ####

Concerning previous point, it’s essential for the web server administrator of Moustache **NOT** to let the Apache, Nginx or whatever interpret fancy file extensions.
Moustache needs web server software to handle `.php`, that is all.
Of course, if you run other web applications with the same server, it can also handle `.py`, `.asp` or whatever genuine script you want.

Here is an exemple of a **BAD CONFIGURATION**: making your server interpret `.ogg` files. It’s very common sens. Ignoring this warning might make Moustache highly vulnerable as it can expose such files.

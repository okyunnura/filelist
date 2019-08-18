https://github.com/phpbrew/phpbrew/wiki/Requirement

https://gotohayato.com/content/154
https://github.com/thephpleague/flysystem

https://re-engines.com/2018/10/04/単体のormライブラリとしてeloquentを使う/
https://github.com/illuminate/database

https://phinx.org/

---
```bash
xcode-select --install
brew install automake autoconf curl pcre bison re2c mhash libtool icu4c gettext jpeg openssl libxml2 mcrypt gmp libevent bzip2 zlib

vi ~/.bashrc
+ export EDITOR=vim
+ export PHPBREW_SET_PROMPT=0
+ source /Users/okyunnura/.phpbrew/bashrc

vi ~/.bash_profile
+ if [ -f ~/.bashrc ] ; then
+ . ~/.bashrc
+ fi
+ #php
+ export PATH="/usr/local/opt/curl/bin:$PATH"
+ export PATH="/usr/local/opt/bison/bin:$PATH"
+ export PATH="/usr/local/opt/icu4c/bin:$PATH"
+ export PATH="/usr/local/opt/icu4c/sbin:$PATH"
+ export PATH="/usr/local/opt/openssl/bin:$PATH"
+ export PATH="/usr/local/opt/libxml2/bin:$PATH"
+ export PATH="~/.composer/vendor/bin:$PATH"

$ phpbrew install 7.2 +default +mysql +fpm \
    +bz2="$(brew --prefix bzip2)" \
    +zlib="$(brew --prefix zlib)"
$ phpbrew ext install intl

$ docker-compose up -d
$ mysql -h 127.0.0.1 -u root
$ ALTER USER 'root'@'%' IDENTIFIED WITH mysql_native_password by 'password';
$ mysqladmin -h 127.0.0.1 -u root create filelist --default-character-set=utf8 -p

$ composer install

$ mkdir -p db/migrations db/seeds
$ vendor/bin/phinx init .
$ vendor/bin/phinx create FirstMigration
$ vendor/bin/phinx migrate

$ psysh List.php
```

```
update contents set visible = true;
update contents set author_name = basename;
update contents set visible = false from contents where basename like '.DS_Store';
update contents set author_name = null where type = 'dir' or visible = false;
```

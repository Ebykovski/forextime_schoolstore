# forextime_schoolstore

### for install

clone project

project use fulltext index for search and need set variable ft_min_word_len = 2 in your my.cnf

on your mysql server create database for project

import file project_dir/sql/*.sql

change connection settings in project_dir/src/settings.php

cd project_dir

composer install

composer start

go to http://localhost:8080

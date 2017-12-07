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


### API methods

GET /api/v1/ - get auth token


GET /api/v1/goods - list goods

GET /api/v1/goods/page/1 - get 1 page goods

GET /api/v1/goods/1 - get detail of goods by id


POST /api/v1/goods - create new goods

POST /api/v1/goods/1 - update goods

DELETE /api/v1/goods/1 - delete goods. exists. not implemented


GET /api/v1/goods/search?q=&category= - search

GET /api/v1/goods/search/page/1?q=&category= - search paging


GET /api/v1/categories - get categories list

GET /api/v1/categories/1/options - get options for category


GET /api/v1/tags - get top 50 search tags


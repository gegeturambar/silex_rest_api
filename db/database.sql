create database if not exists silex_api character set utf8 collate utf8_unicode_ci;
use silex_api; drop table if exists user;
create table user ( id integer not null primary key auto_increment, firstname varchar(255) not null, lastname varchar(255) not null ) engine=innodb character set utf8 collate utf8_unicode_ci;
insert into user values (1, 'Jean', 'Duchmol');
insert into user values (2, 'Sophie', 'Tartempion');
insert into user values (3, 'Bob', 'Bobby');
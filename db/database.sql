DROP database if EXISTS traductions;
create database if not exists traductions character set utf8 collate utf8_unicode_ci;
use traductions;

drop table if exists user;
create table user ( id integer not null primary key auto_increment, email varchar(255) not null, password varchar(255) not null, salt varchar(50) not null,roles varchar(255) not null  ) engine=innodb character set utf8 collate utf8_unicode_ci;
insert into user values (1, 'Jean', 'Duchmol');
insert into user values (2, 'Sophie', 'Tartempion');
insert into user values (3, 'Bob', 'Bobby');

drop table if exists langue;
create table langue ( id integer not null primary key auto_increment, name varchar(255) not null, code varchar(255) not null , UNIQUE ( name), UNIQUE (code) ) engine=innodb character set utf8 collate utf8_unicode_ci;
insert into langue values (1, 'français', 'fr' );
insert into langue values (2, 'english', 'en' );
insert into langue values (3, 'chinese', 'zh' );
insert into langue values (4, 'arabe', 'ar' );
insert into langue values (5, 'hébreu', 'he' );

drop table if exists version;
create table version ( id integer not null primary key auto_increment, numero varchar(255) not null, dateCreated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  ) engine=innodb character set utf8 collate utf8_unicode_ci;
insert into version values (1, '1.0.0', CURRENT_TIMESTAMP );

drop table if exists traduction;
create table traduction ( id integer not null primary key auto_increment, tag varchar(255) not null, langueId integer not null, value TEXT, UNIQUE ( tag, langueId ), FOREIGN KEY ( langueId) REFERENCES langue(id) ) engine=innodb character set utf8 collate utf8_unicode_ci;


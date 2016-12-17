create database to_do_list;
use database to_do_list;
create table tasks (
   id int primary key auto_increment,
   name text not null,
   due text not null,
   priority text not null
); 
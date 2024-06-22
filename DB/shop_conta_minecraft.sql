/*==============================================================*/
/* Nom de SGBD :  MySQL 5.0                                     */
/* Date de cr�ation :  22/06/2024 16:22:33                      */
/*==============================================================*/


drop table if exists item_membre;

drop table if exists item;

drop table if exists category_membre;

drop table if exists category;

drop table if exists membre;

drop table if exists admin;

drop table if exists team;

/*==============================================================*/
/* Table : admin                                                */
/*==============================================================*/
create table admin
(
   id             int not null Auto_increment,
   id_team              int not null,
   login_admin          varchar(50) not null,
   password_admin       varchar(255) not null,
   primary key (id)
)Engine = InnoDB;

/*==============================================================*/
/* Table : category                                             */
/*==============================================================*/
create table category
(
   id          int not null Auto_increment,
   libelle_category     varchar(50) not null,
   primary key (id)
)Engine = InnoDB;

/*==============================================================*/
/* Table : item                                                 */
/*==============================================================*/
create table item
(
   id              int not null Auto_increment,
   id_category          int not null,
   libelle_item         varchar(50) not null,
   price_item           decimal not null,
   primary key (id)
)Engine = InnoDB;

/*==============================================================*/
/* Table : category_membre                                               */
/*==============================================================*/
create table category_membre
(
   id_category          int not null,
   id_membre            int not null,
   role                 varchar(20) not null,
   primary key (id_category, id_membre)
)Engine = InnoDB;

/*==============================================================*/
/* Table : membre                                               */
/*==============================================================*/
create table membre
(
   id            int not null Auto_increment,
   id_team              int not null,
   name_membre          varchar(50) not null,
   primary key (id)
)Engine = InnoDB;

/*==============================================================*/
/* Table : item_membre                                                 */
/*==============================================================*/
create table item_membre
(
   id_membre            int not null,
   id_item              int not null,
   quantity             int not null,
   primary key (id_membre, id_item)
)Engine = InnoDB;

/*==============================================================*/
/* Table : team                                                 */
/*==============================================================*/
create table team
(
   id              int not null Auto_increment,
   login_team           varchar(50) not null,
   password_team        varchar(255) not null,
   primary key (id)
)Engine = InnoDB;

alter table admin add constraint FK_CREATE foreign key (id_team)
      references team (id) on delete cascade on update restrict;

alter table item add constraint FK_SORT foreign key (id_category)
      references category (id) on delete cascade on update restrict;

alter table category_membre add constraint FK_category_membre foreign key (id_membre)
      references membre (id) on delete cascade on update restrict;

alter table category_membre add constraint FK_category_membre2 foreign key (id_category)
      references category (id) on delete cascade on update restrict;

alter table membre add constraint FK_HIRE foreign key (id_team)
      references team (id) on delete cascade on update restrict;

alter table item_membre add constraint FK_item_membre foreign key (id_item)
      references item (id) on delete cascade on update restrict;

alter table item_membre add constraint FK_item_membre2 foreign key (id_membre)
      references membre (id) on delete cascade on update restrict;


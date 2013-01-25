

drop database if exists daw2;
create database daw2;


create user daw2_user identified by 'daw2_user';
	/* Concedemos al usuario daw2_user todos los permisos sobre esa base de datos*/
grant all privileges on daw2.* to daw2_user;


use daw2;

CREATE TABLE foro_usuarios (
id int(11) NOT NULL AUTO_INCREMENT,
login varchar(30) NOT NULL,
email varchar(100) NOT NULL,
password char(32) NOT NULL,
fecha_alta timestamp not null default current_timestamp(),
fecha_confirmacion_alta datetime default null,
clave_confirmacion char(30) null,
PRIMARY KEY (id),
UNIQUE KEY login (login),
UNIQUE KEY email (email)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8
;

insert into foro_usuarios values
  (default, 'admin', 'admin@email.com', md5('admin00'), default, now(), null)
, (default, 'juan', 'juan@email.com', md5('juan00'), default, now(), null)
, (default, 'anais', 'anais@email.com', md5('anais00'), default, null, '1234567890')
;

/*
alter table usuarios add column
fecha_alta timestamp default current_timestamp(),
fecha_confirmacion_alta datetime default null
;
*/
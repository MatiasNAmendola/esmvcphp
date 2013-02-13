

drop database if exists daw2;
create database daw2;


create user daw2_user identified by 'daw2_user';
	/* Concedemos al usuario daw2_user todos los permisos sobre esa base de datos*/
grant all privileges on daw2.* to daw2_user;


use daw2;


/*
alter table usuarios add column
fecha_alta timestamp default current_timestamp(),
fecha_confirmacion_alta datetime default null
;
*/

drop table if exists foro_articulos;
create table if not exists foro_articulos
( id integer auto_increment
, nombre varchar(100) not null
, precio decimal(12,2) null default null
, unidades_stock decimal(12,2) null default null
, primary key (id)
, unique (nombre)
)
engine=innodb default charset=utf8
;

insert into foro_articulos
  ( nombre,precio ) values
  ('leche', null)
, ('mantequilla', 12.1234)
, ('arroz', 1234567890.12)
, ('zurracapote', 10)
;

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



drop table if exists foro_recursos;
create table foro_recursos
( id integer unsigned auto_increment not null
, controlador varchar(50) not null comment 'Clase controlador'
, metodo varchar(50) not null comment 'Método de la clase controlador, si está a nulo es porque en la fila se define una sección'
, destino  varchar(50) null comment "Utilización de este recurso en el negocio"
, texto_menu varchar(100) null comment "Texto que aparecerá en el menú desplegable y en los botones"
, descripcion varchar(255) null comment "Explicación de la acción"
, primary key (id)
, unique (controlador, metodo)

)
CHARACTER SET utf8 COLLATE utf8_general_ci
engine=innodb;

/*
 * Un rol es igual que un grupo de trabajo o grupo de usuarios.
 * Todos los usuarios serán miembros del rol usuario.
 */


drop table if exists foro_roles;
create table foro_roles
( id integer unsigned auto_increment not null
, rol varchar(50) not null
, descripcion varchar(255) null
, primary key (id)
, unique (rol)
)
CHARACTER SET utf8 COLLATE utf8_general_ci
engine=innodb;


/* seccion y subseccion se validarán en v_negocios_permisos */
drop table if exists foro_roles_permisos;
create table foro_roles_permisos
( id integer unsigned auto_increment not null
, rol varchar(50) not null
, controlador varchar(50) not null comment "seccion y subseccion se validarán en v_negocios_permisos"
, metodo varchar(100) null comment "si está a nulo es porque en la fila se define una sección"
, primary key (id)
, unique(rol, controlador, metodo) -- Evita que a un rol se le asinge más de una vez un mismo permiso
, foreign key (rol) references foro_roles(rol) on delete cascade on update cascade
/*, foreign key (controlador, metodo) references foro_recursos(controlador, metodo) on delete cascade on update cascade*/
)
CHARACTER SET utf8 COLLATE utf8_general_ci
engine=innodb;


create table foro_usuarios_roles
( id integer unsigned auto_increment not null
, login varchar(20) not null
, rol varchar(50) not null

, primary key (id)
, unique (login, rol) -- Evita que a un usuario se le asigne más de una vez el mismo rol
, foreign key ( login) references foro_usuarios(login) on delete cascade on update cascade
, foreign key ( rol) references foro_roles(rol) on delete cascade on update cascade
)
CHARACTER SET utf8 COLLATE utf8_general_ci
engine=innodb;



drop trigger if exists foro_t_usuarios_ai;
delimiter //
create trigger foro_t_usuarios_ai after insert on foro_usuarios
for each row
begin
	insert into foro_usuarios_roles (login, rol) values ( new.login, 'usuarios');
	if (new.login != "anonimo") then
		insert into foro_usuarios_roles (login,  rol) values ( new.login, 'usuarios_logueados');
	end if;
end;

//
delimiter ;


/* empleado - tiene + colección_permisos */
drop table if exists foro_usuarios_permisos;

create table foro_usuarios_permisos
( id integer unsigned auto_increment not null
, login varchar(20) not null
, controlador varchar(50) not null comment "seccion y subseccion se validarán en v_negocios_permisos"
, metodo varchar(100) null comment "si está a nulo es porque en la fila se define una sección"

, primary key (id)
, unique(login, controlador, metodo) -- Evita que a un usuario se le asignen más de una vez un permiso
, foreign key (login) references foro_usuarios(login) on delete cascade on update cascade
/*, foreign key (controlador, metodo) references foro_recursos(controlador, metodo) on delete cascade on update cascade*/

)
CHARACTER SET utf8 COLLATE utf8_general_ci
engine=innodb;



insert into foro_roles
  (rol					, descripcion) values
  ('administradores'	,'Administradores de la aplicación')
, ('usuarios'			,'Todos los usuarios incluido anonimo')
, ('usuarios_logueados'	,'Todos los usuarios excluido anonimo')
;


insert into foro_usuarios values
  (default, 'admin', 'admin@email.com', md5('admin00'), default, now(), null)
, (default, 'anonimo', 'anonimo@email.com', md5(''), default, now(), null)
, (default, 'juan', 'juan@email.com', md5('juan00'), default, now(), null)
, (default, 'anais', 'anais@email.com', md5('anais00'), default, now(), null)
;



insert into foro_recursos
  (controlador,		metodo) values
  ('*'				,'*')
, ('articulos'		,'*')
, ('inicio'			,'index')
, ('usuarios'		,'*')
, ('usuarios'		,'desconectar')
, ('usuarios'		,'form_login')
, ('usuarios'		,'validar_form_login')



;

insert into foro_roles_permisos
  (rol					,controlador		,metodo) values
  ('administradores'	,'*'				,'*')
, ('usuarios'			,'inicio'			,'index')
, ('usuarios'			,'mensajes'			,'*')
, ('usuarios_logueados','usuarios'			,'desconectar')
, ('usuarios_logueados','inicio'			,'logueado')
;

insert into foro_usuarios_roles
  (login		,rol) values
  ('admin'		,'administradores')
;


insert into foro_usuarios_permisos
  (login			,controlador			,metodo) values
  ('anonimo'		,'usuarios'				,'form_login')
, ('anonimo'		,'usuarios'				,'validar_form_login')
;

use blockdatabase;

create table usuario (
    idusuario int auto_increment primary key,
    email varchar(150) not null unique,
    password varchar(256) not null,
    nombre varchar(16),
    falta date not null,
    tipo enum('administrador', 'avanzado', 'usuario') not null default 'usuario',
    estado tinyint
) engine=innodb  default charset=utf8 collate=utf8_unicode_ci;

create table nota (
    idnota int auto_increment primary key,
    idusuario int,
    asunto varchar(30),
    texto text,
    video longBlob,
    sonido longBlob,
    fmodificacion date not null,
    CONSTRAINT FK_idusuario FOREIGN KEY (idusuario)     
    REFERENCES usuario (idusuario)     
    	ON DELETE CASCADE    
    	ON UPDATE CASCADE    
) engine=innodb  default charset=utf8 collate=utf8_unicode_ci;

create table imagen (
    idimagen int auto_increment primary key,
    idnota int,
    imagen blob,
    CONSTRAINT FK_idnota FOREIGN KEY (idnota)     
    REFERENCES nota (idnota)     
    	ON DELETE CASCADE    
    	ON UPDATE CASCADE 
) engine=innodb  default charset=utf8 collate=utf8_unicode_ci;

create table contacto(
    idcontacto int auto_increment primary key,
    asunto varchar(30),
    email varchar(50) not null,
    comentario varchar(300),
    fentrada date not null,
    estado tinyint
)engine=innodb  default charset=utf8 collate=utf8_unicode_ci;

INSERT INTO  `usuario` (  `idusuario` ,  `email` ,  `password` ,  `nombre` ,  `falta` ,  `tipo` ,  `estado` ) 
VALUES (
NULL ,  "daniyales@daniyales.com",  "123123",  "Admin", CURRENT_DATE,  "administrador", 1);
create table users(
	id_user serial not null,
	nick varchar(25) unique,
	passwd varchar(50) not null,
	email varchar(25) not null,
	nombre varchar(30) not null,
	primary key (id_user)
);



create table contacts(
	usuario int unique,
	amigo int unique,
	primary key (usuario,amigo),
	foreign key (usuario) references users(id_user),
	foreign key (amigo) references users(id_user)
);


create table rooms(
	nsala varchar(20) unique,
	topic varchar(50),
	primary key (nsala)
);

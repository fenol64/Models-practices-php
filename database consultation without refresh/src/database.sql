create database consulta;
use consulta;


create table alunos (
		id int auto_increment primary key,
        matricula varchar(4),
        nome varchar(100),
        nascimento date,
        turma varchar (4),
        serie char(1)
);
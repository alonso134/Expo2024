CREATE TABLE `tbl_grados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `edad` int(11) DEFAULT NULL,
  `sexo` varchar(10) DEFAULT NULL,
  `seccion` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `tbl_grados` (`id`, `nombre`, `edad`, `sexo`, `seccion`)
VALUES
	(3,'Any somosa',23,'Femenino','Asistente'),
	(4,'Urian',31,'Masculino','Asistente'),
	(6,'Abelado P',39,'Masculino','Desarrollador'),
	(7,'Camilo',30,'Masculino','Contador'),
	(8,'Fabio',49,'Masculino','Secretario'),
	(9,'Brenda Cataleya',18,'Masculino','Desarrollador Web');
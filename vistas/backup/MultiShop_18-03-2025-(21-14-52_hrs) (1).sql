-- MultiShop Backup

SET FOREIGN_KEY_CHECKS=0;

CREATE DATABASE IF NOT EXISTS dbsistema;

USE dbsistema;

DROP TABLE IF EXISTS articulo;

CREATE TABLE `articulo` (
  `idarticulo` int(11) NOT NULL AUTO_INCREMENT,
  `idcategoria` int(11) NOT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `stock` int(11) NOT NULL,
  `precio` int(240) NOT NULL,
  `descripcion` varchar(256) DEFAULT NULL,
  `imagen` varchar(50) DEFAULT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT 1,
  `precio_compra` decimal(10,2) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  PRIMARY KEY (`idarticulo`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`),
  KEY `fk_articulo_categoria_idx` (`idcategoria`),
  CONSTRAINT `fk_articulo_categoria` FOREIGN KEY (`idcategoria`) REFERENCES `categoria` (`idcategoria`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO articulo VALUES("2","13","122974987234","Stitch Azul","11","0","Peluche Stitch Grande 35 Cm Azul","1727046155.png","1","10.00","20.00");
INSERT INTO articulo VALUES("3","10","324812423423","Letras 3D Personalizadas","0","0","","1727045782.png","1","50.00","45.00");
INSERT INTO articulo VALUES("4","10","0000340234","Cintas Colores Metalicas","15","0","Cintas Colores Metalicas 30 Mm X 25 Mtr","1727041881.png","1","50.00","45.00");
INSERT INTO articulo VALUES("8","12","32432423777","Cajitas Regalo Dia Del Padre","25","0","Cajitas Decoradas De Regalo Para El Día Del Padre","1727041036.png","1","12.00","18.00");
INSERT INTO articulo VALUES("9","12","32452423777","Caja Personalizados Con Frase","10","0","Caja Rígida Para Regalos Personalizados Con Frase","1727041500.png","1","11.00","22.00");
INSERT INTO articulo VALUES("10","13","12352423777","Stitch Rosado","28","0","Peluche Stitch Grande 35 Cm Rosado","1727042173.png","1","77.00","33.00");
INSERT INTO articulo VALUES("11","12","32438883777","Globos  En Forma De Corazon","31","0","Globos De Helio En Forma De Corazón Rojo","1727042629.png","1","40.00","45.00");
INSERT INTO articulo VALUES("12","12","324822243423","Caja  Forma De Corazon Y Cuadrado","50","0","Caja Pequeña Visor En Acetato Forma De Corazón Y Cuadrado","1727042737.png","1","33.00","45.00");



DROP TABLE IF EXISTS categoria;

CREATE TABLE `categoria` (
  `idcategoria` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(256) DEFAULT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`idcategoria`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO categoria VALUES("2","Cintas","","0");
INSERT INTO categoria VALUES("10","Decoración","","0");
INSERT INTO categoria VALUES("12","Cajas de Regalo","","1");
INSERT INTO categoria VALUES("13","Peluches","Peluche","1");
INSERT INTO categoria VALUES("14","Globos","","0");
INSERT INTO categoria VALUES("16","Cinta","","1");
INSERT INTO categoria VALUES("17","Lazos","lazos largos","0");



DROP TABLE IF EXISTS delivery;

CREATE TABLE `delivery` (
  `iddelivery` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `stock` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,  
  `descripcion` varchar(256) DEFAULT NULL,
  `imagen` varchar(50) DEFAULT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT 1,
  `telefono` varchar(20) DEFAULT NULL,  
  `direccion` varchar(255) DEFAULT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,  
  PRIMARY KEY (`iddelivery`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Inserciones actualizadas, se omite el valor para 'iddelivery' ya que es AUTO_INCREMENT
INSERT INTO delivery (codigo, nombre, stock, precio, descripcion, imagen, condicion, telefono, direccion, precio_compra, precio_venta) 
VALUES
("", "Josber Hernández", 0, 0.00, "Peluche Stitch Grande 35 Cm Rosado", "1728007636.jpeg", 1, NULL, "Castaño", 0.00, 0.00),
("", "Yostyn Medina", 12, 0.00, "12", "1728007940.jpeg", 1, NULL, "La Chapa", 0.00, 0.00),
("", "Samuel Paraco", 12, 0.00, "33", "1728008274.jpg", 1, NULL, "Maracay", 0.00, 0.00);



DROP TABLE IF EXISTS detalle_ingreso;

CREATE TABLE `detalle_ingreso` (
  `iddetalle_ingreso` int(11) NOT NULL AUTO_INCREMENT,
  `idingreso` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_compra` decimal(11,2) NOT NULL,
  `precio_venta` decimal(11,2) NOT NULL,
  PRIMARY KEY (`iddetalle_ingreso`),
  KEY `fk_detalle_ingreso_ingreso_idx` (`idingreso`),
  KEY `fk_detalle_ingreso_articulo_idx` (`idarticulo`),
  CONSTRAINT `fk_detalle_ingreso_articulo` FOREIGN KEY (`idarticulo`) REFERENCES `articulo` (`idarticulo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalle_ingreso_ingreso` FOREIGN KEY (`idingreso`) REFERENCES `ingreso` (`idingreso`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO detalle_ingreso VALUES("13","9","4","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("14","9","4","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("15","10","4","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("16","10","4","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("17","10","4","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("18","10","4","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("19","11","4","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("20","11","4","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("21","11","4","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("22","11","4","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("23","11","4","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("24","11","4","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("25","11","4","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("26","11","4","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("27","11","4","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("28","11","4","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("29","11","4","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("30","11","4","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("31","11","4","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("32","11","4","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("33","11","4","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("34","11","4","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("35","12","3","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("36","12","3","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("37","12","3","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("38","12","3","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("39","12","3","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("40","13","3","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("41","13","3","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("42","13","3","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("43","14","4","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("44","14","3","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("45","14","4","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("46","14","3","1","1.00","1.00");
INSERT INTO detalle_ingreso VALUES("47","15","10","1","66.00","66.00");
INSERT INTO detalle_ingreso VALUES("48","16","2","4","10.00","0.00");
INSERT INTO detalle_ingreso VALUES("49","16","10","4","10.00","0.00");



DROP TABLE IF EXISTS detalle_pedido;

CREATE TABLE `detalle_pedido` (
  `iddetalle` int(11) NOT NULL AUTO_INCREMENT,
  `idpedido` int(11) DEFAULT NULL,
  `idarticulo` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`iddetalle`),
  KEY `idpedido` (`idpedido`),
  KEY `idarticulo` (`idarticulo`),
  CONSTRAINT `detalle_pedido_ibfk_1` FOREIGN KEY (`idpedido`) REFERENCES `pedidos` (`idpedido`),
  CONSTRAINT `detalle_pedido_ibfk_2` FOREIGN KEY (`idarticulo`) REFERENCES `articulo` (`idarticulo`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO detalle_pedido VALUES("3","2","0","5","20.00");
INSERT INTO detalle_pedido VALUES("4","2","0","15","45.00");
INSERT INTO detalle_pedido VALUES("5","3","2","5","20.00");
INSERT INTO detalle_pedido VALUES("6","4","4","1","45.00");
INSERT INTO detalle_pedido VALUES("7","5","2","1","20.00");
INSERT INTO detalle_pedido VALUES("8","5","4","1","45.00");
INSERT INTO detalle_pedido VALUES("9","5","8","1","18.00");
INSERT INTO detalle_pedido VALUES("10","5","11","1","45.00");
INSERT INTO detalle_pedido VALUES("11","5","12","1","45.00");
INSERT INTO detalle_pedido VALUES("12","5","10","1","33.00");
INSERT INTO detalle_pedido VALUES("13","5","9","1","22.00");
INSERT INTO detalle_pedido VALUES("14","6","8","2","18.00");
INSERT INTO detalle_pedido VALUES("15","7","2","3","20.00");
INSERT INTO detalle_pedido VALUES("16","7","9","1","22.00");
INSERT INTO detalle_pedido VALUES("17","8","4","2","45.00");
INSERT INTO detalle_pedido VALUES("18","8","3","1","45.00");
INSERT INTO detalle_pedido VALUES("19","9","4","1","45.00");
INSERT INTO detalle_pedido VALUES("20","9","8","1","18.00");
INSERT INTO detalle_pedido VALUES("21","10","3","1","45.00");
INSERT INTO detalle_pedido VALUES("22","10","4","1","45.00");
INSERT INTO detalle_pedido VALUES("23","10","8","1","18.00");
INSERT INTO detalle_pedido VALUES("24","13","2","2","20.00");
INSERT INTO detalle_pedido VALUES("25","14","4","62","45.00");
INSERT INTO detalle_pedido VALUES("26","15","2","3","20.00");
INSERT INTO detalle_pedido VALUES("27","16","0","1","45.00");
INSERT INTO detalle_pedido VALUES("28","16","0","1","18.00");
INSERT INTO detalle_pedido VALUES("29","16","9","1","22.00");
INSERT INTO detalle_pedido VALUES("30","16","10","1","33.00");
INSERT INTO detalle_pedido VALUES("31","16","11","3","45.00");
INSERT INTO detalle_pedido VALUES("32","17","8","2","18.00");
INSERT INTO detalle_pedido VALUES("33","18","4","2","45.00");
INSERT INTO detalle_pedido VALUES("34","19","4","1","45.00");
INSERT INTO detalle_pedido VALUES("35","19","8","2","18.00");
INSERT INTO detalle_pedido VALUES("36","20","4","2","45.00");
INSERT INTO detalle_pedido VALUES("37","21","2","2","20.00");
INSERT INTO detalle_pedido VALUES("38","22","4","3","45.00");
INSERT INTO detalle_pedido VALUES("39","23","2","1","20.00");
INSERT INTO detalle_pedido VALUES("40","23","3","1","45.00");
INSERT INTO detalle_pedido VALUES("41","23","4","1","45.00");
INSERT INTO detalle_pedido VALUES("42","23","8","1","18.00");
INSERT INTO detalle_pedido VALUES("43","23","9","1","22.00");
INSERT INTO detalle_pedido VALUES("44","23","10","1","33.00");
INSERT INTO detalle_pedido VALUES("45","23","11","1","45.00");
INSERT INTO detalle_pedido VALUES("46","24","2","1","20.00");
INSERT INTO detalle_pedido VALUES("47","25","2","1","20.00");
INSERT INTO detalle_pedido VALUES("48","26","2","1","20.00");
INSERT INTO detalle_pedido VALUES("49","27","2","1","20.00");
INSERT INTO detalle_pedido VALUES("50","27","8","1","18.00");
INSERT INTO detalle_pedido VALUES("51","28","2","1","20.00");
INSERT INTO detalle_pedido VALUES("52","28","8","1","18.00");
INSERT INTO detalle_pedido VALUES("53","29","2","1","20.00");
INSERT INTO detalle_pedido VALUES("54","29","8","1","18.00");
INSERT INTO detalle_pedido VALUES("55","30","8","2","18.00");
INSERT INTO detalle_pedido VALUES("56","31","8","3","18.00");
INSERT INTO detalle_pedido VALUES("57","32","3","2","45.00");
INSERT INTO detalle_pedido VALUES("58","33","4","1","45.00");
INSERT INTO detalle_pedido VALUES("59","33","8","1","18.00");
INSERT INTO detalle_pedido VALUES("60","34","4","1","45.00");
INSERT INTO detalle_pedido VALUES("61","35","8","4","18.00");
INSERT INTO detalle_pedido VALUES("62","36","4","4","45.00");
INSERT INTO detalle_pedido VALUES("63","37","4","1","45.00");
INSERT INTO detalle_pedido VALUES("64","38","3","8","45.00");
INSERT INTO detalle_pedido VALUES("65","38","4","1","45.00");
INSERT INTO detalle_pedido VALUES("66","39","12","1","45.00");
INSERT INTO detalle_pedido VALUES("67","39","11","1","45.00");
INSERT INTO detalle_pedido VALUES("68","39","10","1","33.00");
INSERT INTO detalle_pedido VALUES("69","40","9","1","22.00");
INSERT INTO detalle_pedido VALUES("70","41","4","3","45.00");
INSERT INTO detalle_pedido VALUES("71","41","8","1","18.00");
INSERT INTO detalle_pedido VALUES("72","44","4","1","45.00");
INSERT INTO detalle_pedido VALUES("73","44","8","1","18.00");
INSERT INTO detalle_pedido VALUES("74","45","11","1","45.00");
INSERT INTO detalle_pedido VALUES("75","45","12","1","45.00");
INSERT INTO detalle_pedido VALUES("76","45","10","1","33.00");
INSERT INTO detalle_pedido VALUES("77","46","4","1","45.00");
INSERT INTO detalle_pedido VALUES("78","47","8","1","18.00");



DROP TABLE IF EXISTS detalle_venta;

CREATE TABLE `detalle_venta` (
  `iddetalle_venta` int(11) NOT NULL AUTO_INCREMENT,
  `idventa` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_venta` decimal(11,2) NOT NULL,
  `descuento` decimal(11,2) NOT NULL,
  PRIMARY KEY (`iddetalle_venta`),
  KEY `fk_detalle_venta_venta_idx` (`idventa`),
  KEY `fk_detalle_venta_articulo_idx` (`idarticulo`),
  CONSTRAINT `fk_detalle_venta_articulo` FOREIGN KEY (`idarticulo`) REFERENCES `articulo` (`idarticulo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalle_venta_venta` FOREIGN KEY (`idventa`) REFERENCES `venta` (`idventa`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO detalle_venta VALUES("6","4","3","1","10.00","0.00");
INSERT INTO detalle_venta VALUES("7","4","2","1","120.00","0.00");
INSERT INTO detalle_venta VALUES("10","7","3","1","2.00","2.00");
INSERT INTO detalle_venta VALUES("11","7","3","1","2.00","2.00");
INSERT INTO detalle_venta VALUES("12","7","3","1","2.00","2.00");
INSERT INTO detalle_venta VALUES("15","9","3","1","0.00","0.00");
INSERT INTO detalle_venta VALUES("16","9","3","1","0.00","0.00");
INSERT INTO detalle_venta VALUES("17","10","4","1","1.00","0.00");
INSERT INTO detalle_venta VALUES("18","10","4","1","1.00","0.00");
INSERT INTO detalle_venta VALUES("19","11","9","1","22.00","0.00");
INSERT INTO detalle_venta VALUES("20","11","9","1","22.00","0.00");



DROP TABLE IF EXISTS ingreso;

CREATE TABLE `ingreso` (
  `idingreso` int(11) NOT NULL AUTO_INCREMENT,
  `idproveedor` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `tipo_comprobante` varchar(20) NOT NULL,
  `serie_comprobante` varchar(7) DEFAULT NULL,
  `num_comprobante` varchar(10) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `impuesto` decimal(4,2) NOT NULL,
  `total_compra` decimal(11,2) NOT NULL,
  `estado` varchar(20) NOT NULL,
  PRIMARY KEY (`idingreso`),
  KEY `fk_ingreso_persona_idx` (`idproveedor`),
  KEY `fk_ingreso_usuario_idx` (`idusuario`),
  CONSTRAINT `fk_ingreso_persona` FOREIGN KEY (`idproveedor`) REFERENCES `persona` (`idpersona`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ingreso_usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO ingreso VALUES("9","13","1","Factura","2","2","2024-09-22 00:00:00","12.00","2.00","Aceptado");
INSERT INTO ingreso VALUES("10","14","1","Factura","1","","2024-09-22 00:00:00","1.00","4.00","Aceptado");
INSERT INTO ingreso VALUES("11","14","1","Factura","2","3","2002-05-22 00:00:00","0.00","16.00","Aceptado");
INSERT INTO ingreso VALUES("12","14","1","Factura","2","2","2024-09-22 00:00:00","0.00","5.00","Aceptado");
INSERT INTO ingreso VALUES("13","13","1","Factura","2","2","2024-09-22 00:00:00","0.00","3.00","Aceptado");
INSERT INTO ingreso VALUES("14","7","1","Factura","12","12","2024-09-22 00:00:00","0.00","4.00","Aceptado");
INSERT INTO ingreso VALUES("15","13","1","Factura","","","2024-09-23 00:00:00","0.00","1.00","Aceptado");
INSERT INTO ingreso VALUES("16","14","1","Factura","","","2024-09-23 00:00:00","0.00","80.00","Aceptado");



DROP TABLE IF EXISTS notificaciones;

CREATE TABLE `notificaciones` (
  `idnotificacion` int(11) NOT NULL AUTO_INCREMENT,
  `idusuario` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `leido` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`idnotificacion`),
  KEY `idusuario` (`idusuario`),
  CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO notificaciones VALUES("1","1","Tu pedido #45 ha sido ACEPTADO.","2024-12-08 18:31:11","1");
INSERT INTO notificaciones VALUES("2","1","Tu pedido #44 ha sido ACEPTADO.","2024-12-08 18:31:32","1");
INSERT INTO notificaciones VALUES("3","1","Tu pedido #44 ha sido ACEPTADO.","2024-12-08 18:35:00","1");
INSERT INTO notificaciones VALUES("4","1","Tu pedido #45 ha sido ACEPTADO.","2024-12-08 18:45:02","1");
INSERT INTO notificaciones VALUES("5","1","Tu pedido #45 ha sido ACEPTADO.","2024-12-08 18:55:36","1");
INSERT INTO notificaciones VALUES("6","1","Tu pedido #44 ha sido ACEPTADO.","2024-12-08 19:22:51","1");
INSERT INTO notificaciones VALUES("7","1","Tu pedido N45 ha sido ACEPTADO.","2024-12-08 19:29:28","1");
INSERT INTO notificaciones VALUES("8","1","Tu pedido N41 ha sido RECHAZADO.","2024-12-08 19:43:26","1");
INSERT INTO notificaciones VALUES("9","1","Tu pedido N45 ha sido ACEPTADO.","2024-12-08 19:46:03","1");
INSERT INTO notificaciones VALUES("10","1","Tu pedido N45 ha sido RECHAZADO.","2024-12-08 19:47:14","1");
INSERT INTO notificaciones VALUES("11","1","Estado del pedido actualizado.","2024-12-08 19:47:26","1");
INSERT INTO notificaciones VALUES("12","1","Tu pedido N45 ha sido ACEPTADO.","2024-12-08 19:49:04","1");
INSERT INTO notificaciones VALUES("13","1","Tu pedido N45 ha sido ACEPTADO.","2024-12-08 19:49:24","1");
INSERT INTO notificaciones VALUES("14","1","Tu pedido N45 ha sido ACEPTADO.","2024-12-08 19:57:15","1");
INSERT INTO notificaciones VALUES("15","1","Tu pedido N45 ha sido RECHAZADO.","2024-12-08 19:57:30","1");
INSERT INTO notificaciones VALUES("16","1","Tu pedido N45 ha sido ACEPTADO.","2024-12-08 19:57:40","1");
INSERT INTO notificaciones VALUES("17","1","Tu pedido N45 ha sido ACEPTADO.","2024-12-08 19:58:08","1");
INSERT INTO notificaciones VALUES("18","1","Tu pedido N44 ha sido ACEPTADO.","2024-12-08 19:58:12","1");
INSERT INTO notificaciones VALUES("19","1","Tu pedido N45 ha sido RECHAZADO.","2024-12-08 20:58:54","1");
INSERT INTO notificaciones VALUES("20","1","Tu pedido N45 ha sido ACEPTADO.","2024-12-08 20:59:05","1");
INSERT INTO notificaciones VALUES("21","1","Tu pedido N45 ha sido RECHAZADO.","2024-12-08 21:46:24","1");
INSERT INTO notificaciones VALUES("22","1","Tu pedido N45 ha sido ACEPTADO.","2024-12-08 22:42:32","1");
INSERT INTO notificaciones VALUES("23","1","Tu pedido N45 ha sido RECHAZADO.","2024-12-08 22:49:54","1");
INSERT INTO notificaciones VALUES("24","1","Tu pedido N45 ha sido ACEPTADO.","2024-12-08 22:59:25","1");
INSERT INTO notificaciones VALUES("25","1","Tu pedido N45 ha sido RECHAZADO.","2024-12-08 23:10:57","1");
INSERT INTO notificaciones VALUES("26","1","Tu pedido N44 ha sido ACEPTADO.","2024-12-08 23:11:07","1");
INSERT INTO notificaciones VALUES("27","1","Tu pedido N45 ha sido ACEPTADO.","2024-12-08 23:22:45","1");
INSERT INTO notificaciones VALUES("28","1","Tu pedido N45 ha sido RECHAZADO.","2024-12-08 23:45:00","1");
INSERT INTO notificaciones VALUES("29","1","Tu pedido N45 ha sido ACEPTADO.","2024-12-08 23:54:47","1");
INSERT INTO notificaciones VALUES("30","1","Tu pedido N45 ha sido RECHAZADO.","2024-12-09 00:19:52","0");
INSERT INTO notificaciones VALUES("31","1","Tu pedido N45 ha sido ACEPTADO.","2024-12-09 00:26:47","0");
INSERT INTO notificaciones VALUES("32","1","Tu pedido N36 ha sido ACEPTADO.","2024-12-09 00:27:08","0");
INSERT INTO notificaciones VALUES("33","1","Tu pedido N34 ha sido RECHAZADO.","2024-12-09 00:27:40","0");
INSERT INTO notificaciones VALUES("34","1","Tu pedido N45 ha sido RECHAZADO.","2024-12-09 00:33:22","0");
INSERT INTO notificaciones VALUES("35","1","Tu pedido N45 ha sido ACEPTADO.","2024-12-09 00:37:25","0");
INSERT INTO notificaciones VALUES("36","1","Tu pedido N45 ha sido RECHAZADO.","2024-12-09 00:37:51","0");
INSERT INTO notificaciones VALUES("37","1","Tu pedido N45 ha sido ACEPTADO.","2024-12-09 00:38:18","0");
INSERT INTO notificaciones VALUES("38","1","Tu pedido N44 ha sido RECHAZADO.","2024-12-09 00:41:24","0");
INSERT INTO notificaciones VALUES("39","1","Tu pedido N44 ha sido ACEPTADO.","2024-12-09 00:44:20","0");
INSERT INTO notificaciones VALUES("40","1","Tu pedido N44 ha sido RECHAZADO.","2024-12-09 00:47:17","0");
INSERT INTO notificaciones VALUES("41","1","Tu pedido N44 ha sido ACEPTADO.","2024-12-09 00:48:29","0");
INSERT INTO notificaciones VALUES("42","1","Tu pedido N30 ha sido ACEPTADO.","2024-12-09 00:49:42","0");
INSERT INTO notificaciones VALUES("43","1","Tu pedido N17 ha sido ACEPTADO.","2024-12-09 00:50:16","0");
INSERT INTO notificaciones VALUES("44","1","Tu pedido N37 ha sido RECHAZADO.","2024-12-09 00:52:07","0");
INSERT INTO notificaciones VALUES("45","1","Tu pedido N45 ha sido RECHAZADO.","2024-12-09 00:52:42","0");
INSERT INTO notificaciones VALUES("46","1","Tu pedido N45 ha sido ACEPTADO.","2024-12-09 00:56:51","0");
INSERT INTO notificaciones VALUES("47","1","Tu pedido N45 ha sido RECHAZADO.","2024-12-09 00:57:51","0");
INSERT INTO notificaciones VALUES("48","1","Tu pedido N45 ha sido ACEPTADO.","2024-12-09 01:01:30","0");
INSERT INTO notificaciones VALUES("49","1","Tu pedido N41 ha sido RECHAZADO.","2024-12-09 01:01:51","0");
INSERT INTO notificaciones VALUES("50","1","Tu pedido N47 ha sido ACEPTADO.","2024-12-09 06:33:07","0");
INSERT INTO notificaciones VALUES("51","1","Tu pedido N47 ha sido ACEPTADO.","2024-12-09 06:36:20","0");
INSERT INTO notificaciones VALUES("52","1","Tu pedido N47 ha sido ACEPTADO.","2024-12-09 06:39:40","0");
INSERT INTO notificaciones VALUES("53","1","Tu pedido N47 ha sido ACEPTADO.","2024-12-09 06:40:13","0");



DROP TABLE IF EXISTS pedidos;

CREATE TABLE `pedidos` (
  `idpedido` int(11) NOT NULL AUTO_INCREMENT,
  `idusuario` varchar(50) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `referencia_pago` varchar(255) DEFAULT NULL,
  `entrega` tinyint(1) DEFAULT NULL,
  `id_delivery` int(11) DEFAULT NULL,
  PRIMARY KEY (`idpedido`),
  KEY `fk_pedido_delivery` (`id_delivery`),
  CONSTRAINT `fk_pedido_delivery` FOREIGN KEY (`id_delivery`) REFERENCES `delivery` (`iddelivery`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO pedidos VALUES("2","17","2024-10-14 08:17:17","Aceptado","","0","0");
INSERT INTO pedidos VALUES("3","17","2024-10-14 09:22:43","Pendiente","","0","0");
INSERT INTO pedidos VALUES("4","17","2024-10-14 09:23:02","Pendiente","","0","0");
INSERT INTO pedidos VALUES("5","17","2024-10-14 09:42:20","Pendiente","","0","0");
INSERT INTO pedidos VALUES("6","12","2024-10-14 10:15:40","Aceptado","","0","0");
INSERT INTO pedidos VALUES("7","12","2024-10-17 00:37:32","Finalizado","","0","0");
INSERT INTO pedidos VALUES("8","12","2024-10-17 20:45:13","Rechazado","","1","0");
INSERT INTO pedidos VALUES("9","12","2024-10-17 20:45:33","Finalizado","","0","0");
INSERT INTO pedidos VALUES("10","12","2024-10-17 20:46:14","Finalizado","","1","0");
INSERT INTO pedidos VALUES("13","12","2024-10-20 18:11:37","Finalizado","","0","0");
INSERT INTO pedidos VALUES("14","12","2024-10-20 18:12:09","Aceptado","","0","0");
INSERT INTO pedidos VALUES("15","12","2024-10-20 18:12:57","Finalizado","","0","0");
INSERT INTO pedidos VALUES("16","12","2024-10-20 19:24:35","Aceptado","","0","0");
INSERT INTO pedidos VALUES("17","1","2024-10-21 03:05:52","Aceptado","","0","0");
INSERT INTO pedidos VALUES("18","17","2024-11-01 16:46:01","Pendiente","","0","0");
INSERT INTO pedidos VALUES("19","17","2024-11-01 16:46:26","Pendiente","","1","0");
INSERT INTO pedidos VALUES("20","17","2024-11-01 16:56:00","Aceptado","","1","0");
INSERT INTO pedidos VALUES("21","1","2024-11-01 16:58:31","Rechazado","","1","0");
INSERT INTO pedidos VALUES("22","1","2024-11-04 04:55:13","Finalizado","","1","0");
INSERT INTO pedidos VALUES("23","1","2024-11-04 08:07:34","Finalizado","","1","0");
INSERT INTO pedidos VALUES("24","8","2024-11-09 04:17:30","Pendiente","","1","0");
INSERT INTO pedidos VALUES("25","8","2024-11-09 04:23:01","Pendiente","","1","0");
INSERT INTO pedidos VALUES("26","8","2024-11-09 04:26:03","Pendiente","","1","0");
INSERT INTO pedidos VALUES("27","8","2024-11-09 04:38:56","Pendiente","","1","0");
INSERT INTO pedidos VALUES("28","8","2024-11-09 04:42:20","Pendiente","","1","0");
INSERT INTO pedidos VALUES("29","8","2024-11-09 04:47:15","Pendiente","","1","10");
INSERT INTO pedidos VALUES("30","1","2024-11-09 06:00:36","Aceptado","","1","14");
INSERT INTO pedidos VALUES("31","1","2024-11-09 06:50:40","Rechazado","","1","10");
INSERT INTO pedidos VALUES("32","17","2024-11-09 07:40:59","Finalizado","","1","15");
INSERT INTO pedidos VALUES("33","1","2024-11-10 20:16:06","Aceptado","","1","0");
INSERT INTO pedidos VALUES("34","1","2024-11-11 12:31:52","Rechazado","","1","0");
INSERT INTO pedidos VALUES("35","1","2024-11-16 23:22:11","Aceptado","","1","10");
INSERT INTO pedidos VALUES("36","1","2024-11-17 08:02:48","Aceptado","","1","0");
INSERT INTO pedidos VALUES("37","1","2024-11-18 12:12:09","Rechazado","","0","0");
INSERT INTO pedidos VALUES("38","23","2024-11-19 11:24:29","Pendiente","","0","0");
INSERT INTO pedidos VALUES("39","1","2024-11-20 10:30:16","Aceptado","","0","0");
INSERT INTO pedidos VALUES("40","1","2024-11-20 12:22:25","Aceptado","","1","14");
INSERT INTO pedidos VALUES("41","1","2024-12-07 22:33:36","Rechazado","","0","0");
INSERT INTO pedidos VALUES("43","","0000-00-00 00:00:00","","","0","0");
INSERT INTO pedidos VALUES("44","1","2024-12-08 06:26:14","Aceptado","555555555555","0","0");
INSERT INTO pedidos VALUES("45","1","2024-12-08 07:13:20","Aceptado","643176585675","0","0");
INSERT INTO pedidos VALUES("46","1","2024-12-09 10:53:35","Pendiente","444444444444","1","14");
INSERT INTO pedidos VALUES("47","1","2024-12-09 11:05:23","Aceptado","111111111111","1","0");



DROP TABLE IF EXISTS permiso;

CREATE TABLE `permiso` (
  `idpermiso` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  PRIMARY KEY (`idpermiso`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO permiso VALUES("1","Escritorio");
INSERT INTO permiso VALUES("2","Almacen");
INSERT INTO permiso VALUES("3","Compras");
INSERT INTO permiso VALUES("4","Ventas");
INSERT INTO permiso VALUES("5","Acceso");
INSERT INTO permiso VALUES("6","Consultas Compras");
INSERT INTO permiso VALUES("7","Consulta Ventas");



DROP TABLE IF EXISTS persona;

CREATE TABLE `persona` (
  `idpersona` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_persona` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo_documento` varchar(20) DEFAULT NULL,
  `num_documento` varchar(20) DEFAULT NULL,
  `direccion` varchar(70) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idpersona`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO persona VALUES("6","Cliente","Josber","CEDULA","30428838","sdsdsds","04125267690","nietoj.1128@gmail.com");
INSERT INTO persona VALUES("7","Proveedor","Yulieth nieto","CEDULA","30428838","sdsdsds","04125267690","nietoj.1128@gmail.com");
INSERT INTO persona VALUES("13","Proveedor","Samuel Paraco","CEDULA","3999999","Ni idea","04120444652","sam1@hotmail.com");
INSERT INTO persona VALUES("14","Proveedor","Josber","CEDULA","12","12","1212","josberhernandez1@gmail.com");
INSERT INTO persona VALUES("15","Cliente","Samuel","CEDULA","12","12","12","josberhernandez1@gmail.com");
INSERT INTO persona VALUES("16","Cliente","Josber","CEDULA","12121","12","1212","1212@gmail.com");



DROP TABLE IF EXISTS suscripciones;

CREATE TABLE `suscripciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `fecha_suscripcion` timestamp NOT NULL DEFAULT current_timestamp(),
  `cedula` varchar(10) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `expira_token` datetime DEFAULT NULL,
  `imagen_perfil` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO suscripciones VALUES("1","Admin","","04125267690","2024-09-06 20:05:38","29899544","$2y$10$pw4.8qnY0TM8.TDFgVMaA.3KwFGLrz61dWiHkdQ.BEpGukG8deM/K","","0000-00-00 00:00:00","");
INSERT INTO suscripciones VALUES("3","yulieth nieto","coreliznieto@gmail.com","04125267690","2024-09-06 20:12:04","29899544","$2y$10$77EWfVGvmXXaWKcbUFYzmOQrKuuuw3UUJCXaw30tPpA/jHv9A/ko.","","0000-00-00 00:00:00","");
INSERT INTO suscripciones VALUES("4","jjhsuhkd ijsoasma","michelnieto@gmail.com","04243556748","2024-09-06 20:19:54","29899544","$2y$10$1AWQ2AgcXo1zrKtMwpXYJeSodFU1IgQr.X289.181LP4kpbb2U.TG","","0000-00-00 00:00:00","");
INSERT INTO suscripciones VALUES("5","zddd  ssadsfs","miejshs@gmail.com","04125267690","2024-09-06 20:22:34","3373916","$2y$10$yknqpGK0Q1rNvsmeJXtuOOveHvHxwAti/qqELLPNWtkq6gQqVAb9.","","0000-00-00 00:00:00","");
INSERT INTO suscripciones VALUES("6","juan","yostyn_medina@hotmail.com","04121345204","2024-09-12 17:38:01","1234567","$2y$10$c84UWA2HHKc1/REzlcH/MuTx14Ff3rAcEtfkm7z9YW9IlXnFi11Sy","","0000-00-00 00:00:00","");
INSERT INTO suscripciones VALUES("7","Yostyn","yostynmedina550@gmail.com","04121345204","2024-09-17 14:06:10","1234567","$2y$10$h66Uf/.JciMCNiFoAgmN1eojufEpVBiFOydmgq4iHOtY/gfMxs94u","86c2d8cc9d25b3816e46c80635e8514d0bc04749207c333c3c798da0a50620ea","2024-10-07 08:34:14","");
INSERT INTO suscripciones VALUES("8","Josber Arceniz Hernández Pernia","josberhernandez1@gmail.com","04120444652","2024-09-22 17:34:55","29554820","$2y$10$N8HFhvgWOl276/aqecZiFuHkv.FLHAdv3f60nXD61lWPX/JAS.KTy","d393f13cc97f6243389384842543b274f6e5ddbc6224987fe6ab998426408e69","2024-10-07 08:40:03","");
INSERT INTO suscripciones VALUES("9","Samuel ","Samu1@gmail.com","04120444652","2024-09-29 20:07:20","29554820","$2y$10$vswQHf1p1Sp.QHH4W/H0VuQExhDYKUoXOhBKKv6CTVyXgddDClhq6","","0000-00-00 00:00:00","");
INSERT INTO suscripciones VALUES("10","Samuel44","Samu12@gmail.com","04120444652","2024-09-29 20:20:25","29554820","$2y$10$T/fuLTdyzJubUiiqvDhIbuGzOIZmmvhDCxgGvrrHcvwNEa26VRjYS","","0000-00-00 00:00:00","");
INSERT INTO suscripciones VALUES("11","Samuel45","samu45@gmail.com","04243576091","2024-09-29 20:27:28","29554820","$2y$10$xN.cIq1xdjwylbpVbgbZ/.ZUy5zQ5Cdb.eGoCWeH.SsNPTgQxD5sW","","0000-00-00 00:00:00","");
INSERT INTO suscripciones VALUES("12","Jos12","Jos12@gmail.com","04120444652","2024-09-29 20:29:25","29554820","$2y$10$HyXyvFEKQAF55/1BX8Vfeu/oF52X4VVCVv1/gd3QJD0KbJrz6DGpK","","0000-00-00 00:00:00","assets/img/uploads/1740383964_WhatsApp Image 2023-09-04 at 10.47.24 AM.jpeg");
INSERT INTO suscripciones VALUES("15","Josber23","josberhernandez2@gmail.com","04243576091","2024-10-06 18:24:32","29554820","$2y$10$Wq5WXdzTO2iAGQ5QGbv06OXMxZDQLYVNFVCJcPziJJDPX6vYUmHoy","","0000-00-00 00:00:00","");
INSERT INTO suscripciones VALUES("16","Josber25","josberhernandez3@gmail.com","04120444652","2024-10-06 18:25:39","29554820","$2y$10$lTE9xT1Uip6UiVuNPEaLL.J3BVv8zVHUihDYlxyE1Jzi7xj0OTLh2","","0000-00-00 00:00:00","");
INSERT INTO suscripciones VALUES("17","Josber5","josberhernandez5@gmail.com","04120444652","2024-10-06 18:42:50","29554820","$2y$10$0qysnxiTooPy1KSY/nBW5uUaOFMsNDA9MdDpfPAfpPJkUc9SP9gBK","","0000-00-00 00:00:00","");
INSERT INTO suscripciones VALUES("18","Josber Hernández","Jj12@gmail.com","04120444652","2024-10-09 08:20:46","29554820","$2y$10$VVLW3eARcYDta.34OJ7THuVpOo5uDb.4ziZeWyMCeicRCS0i/r.RK","","0000-00-00 00:00:00","");
INSERT INTO suscripciones VALUES("21","Josber14","josberhernandez20@gmail.com","04140444652","2024-11-16 19:57:17","29554820","$2y$10$MxaUwyIapQ938TA4M76VUO1TMxoW5fyFxjmE9z5cFiDVSNLb1ZXE.","","0000-00-00 00:00:00","");
INSERT INTO suscripciones VALUES("23","Josber233","josbe@gmail.com","04140444652","2024-11-19 05:57:47","29554820","$2y$10$mAQ2B/mbza8zBWf/6H1umujO9oc7S21n/E.DPKM6Q3ZapgnwIai96","","0000-00-00 00:00:00","");
INSERT INTO suscripciones VALUES("24","Josber Hernández","j2@gmail.com","04120444652","2024-11-26 10:28:05","29554820","$2y$10$/Msz94kFcdXD.c50lwRMtutDfW4Hfx/iVEaeq0Exbj3oSzsujvsn2","","0000-00-00 00:00:00","");
INSERT INTO suscripciones VALUES("25","Jos","j22@gmail.com","04120444652","2024-11-26 10:29:30","29554820","$2y$10$cAnriU.W8FsRla02sCkhVuD4.5e24mpVMJtEno6vAGQ8twu0VdCmm","","0000-00-00 00:00:00","");
INSERT INTO suscripciones VALUES("26","Karlis","karlis@12.com","04120444652","2024-12-03 10:37:46","12345678","$2y$10$HccN2Ql/9KzDy2y4eJjU4.yORcRgZrpQpNLaDRq7ASE0NsUK1l5bO","","0000-00-00 00:00:00","");
INSERT INTO suscripciones VALUES("27","Karly","karlis12@gmail.com","04120444652","2024-12-03 10:39:48","29554820","$2y$10$Lao0BGLtwyxznSR5/e0U4.8EIXRFyzIWfYS1MWSDKM3k1rZ9tPXc6","","0000-00-00 00:00:00","");



DROP TABLE IF EXISTS usuario;

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `tipo_documento` varchar(20) NOT NULL,
  `num_documento` varchar(20) NOT NULL,
  `direccion` varchar(70) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `cargo` varchar(20) DEFAULT NULL,
  `login` varchar(20) NOT NULL,
  `clave` varchar(64) NOT NULL,
  `imagen` varchar(50) NOT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT 1,
  `token` varchar(255) DEFAULT NULL,
  `expira_token` datetime DEFAULT NULL,
  PRIMARY KEY (`idusuario`),
  UNIQUE KEY `login_UNIQUE` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO usuario VALUES("1","Admin","DNI","63238","Conocido","27386126","admin@gmail.com","admin","admin","$2y$10$T1FADU/bzUFfMbKolKY39.BLjUxD4XFYW1IgdbzdP/H5.z.MrAcKC","1725731907.png","1","","0000-00-00 00:00:00");
INSERT INTO usuario VALUES("21","Yostyn Medina US","CEDULA","30016813","LA CHAPA","04140709794","yostynmedina550@gmail.com","Empacador","Yostyn","$2y$10$ZHHVC/JBp/l9iMpUqbFvW.vzQvHWzFuPllTiq.lWpjQ29DH61rsfq","1731809257.png","1","","0000-00-00 00:00:00");
INSERT INTO usuario VALUES("22","Yostyn 2","CEDULA","30016813","LA CHAPA","04140709794","yostynmedina550@gmail.com","Admin","Yostyn2","a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3","1728277528.png","1","","0000-00-00 00:00:00");
INSERT INTO usuario VALUES("23","Samuel","CEDULA","30016813","maracay","04125267690","admin@gmail.com","Profe","Samu","$2y$10$sTSe1avPcLHNZNCyDMunauA1F0Z5a3tilxQKeCQELHMXIahsdoLLO","1733746949.jpg","1","","0000-00-00 00:00:00");
INSERT INTO usuario VALUES("24","Josber","CEDULA","30016813","LA CHAPA","04120444652","","Empacador","Josber","$2y$10$0q/n1AEnC2faiAqOqPgKFuZFII20Y.Y259Qnh9EXghmcZJ2vSV4vS","1733218274.jpg","1","","0000-00-00 00:00:00");
INSERT INTO usuario VALUES("25","Josber","DNI","29554821","","dasdasdas","","Admin","Jos","$2y$10$jOoSnFABRB2Uj56OIAHYFeMSunP9aB68DJcQ/IwIdiBb246KKNCBe","","1","","0000-00-00 00:00:00");



DROP TABLE IF EXISTS usuario_permiso;

CREATE TABLE `usuario_permiso` (
  `idusuario_permiso` int(11) NOT NULL AUTO_INCREMENT,
  `idusuario` int(11) NOT NULL,
  `idpermiso` int(11) NOT NULL,
  PRIMARY KEY (`idusuario_permiso`),
  KEY `fk_usuario_permiso_permiso_idx` (`idpermiso`),
  KEY `fk_usuario_permiso_usuario_idx` (`idusuario`),
  CONSTRAINT `fk_usuario_permiso_permiso` FOREIGN KEY (`idpermiso`) REFERENCES `permiso` (`idpermiso`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_permiso_usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=405 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO usuario_permiso VALUES("273","22","1");
INSERT INTO usuario_permiso VALUES("274","22","2");
INSERT INTO usuario_permiso VALUES("275","22","3");
INSERT INTO usuario_permiso VALUES("276","22","4");
INSERT INTO usuario_permiso VALUES("277","22","5");
INSERT INTO usuario_permiso VALUES("300","1","1");
INSERT INTO usuario_permiso VALUES("301","1","2");
INSERT INTO usuario_permiso VALUES("302","1","3");
INSERT INTO usuario_permiso VALUES("303","1","4");
INSERT INTO usuario_permiso VALUES("304","1","5");
INSERT INTO usuario_permiso VALUES("305","1","6");
INSERT INTO usuario_permiso VALUES("306","1","7");
INSERT INTO usuario_permiso VALUES("344","25","2");
INSERT INTO usuario_permiso VALUES("345","25","1");
INSERT INTO usuario_permiso VALUES("383","21","2");
INSERT INTO usuario_permiso VALUES("384","21","3");
INSERT INTO usuario_permiso VALUES("385","21","4");
INSERT INTO usuario_permiso VALUES("386","21","5");
INSERT INTO usuario_permiso VALUES("387","21","1");
INSERT INTO usuario_permiso VALUES("388","23","2");
INSERT INTO usuario_permiso VALUES("389","23","3");
INSERT INTO usuario_permiso VALUES("390","23","5");
INSERT INTO usuario_permiso VALUES("391","23","1");
INSERT INTO usuario_permiso VALUES("399","24","2");
INSERT INTO usuario_permiso VALUES("400","24","3");
INSERT INTO usuario_permiso VALUES("401","24","5");
INSERT INTO usuario_permiso VALUES("402","24","6");
INSERT INTO usuario_permiso VALUES("403","24","7");
INSERT INTO usuario_permiso VALUES("404","24","1");



DROP TABLE IF EXISTS venta;

CREATE TABLE `venta` (
  `idventa` int(11) NOT NULL AUTO_INCREMENT,
  `idcliente` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `tipo_comprobante` varchar(20) NOT NULL,
  `serie_comprobante` varchar(7) DEFAULT NULL,
  `num_comprobante` varchar(10) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `impuesto` decimal(4,2) NOT NULL,
  `total_venta` decimal(11,2) NOT NULL,
  `estado` varchar(20) NOT NULL,
  PRIMARY KEY (`idventa`),
  KEY `fk_venta_persona_idx` (`idcliente`),
  KEY `fk_venta_usuario_idx` (`idusuario`),
  CONSTRAINT `fk_venta_persona` FOREIGN KEY (`idcliente`) REFERENCES `persona` (`idpersona`) ON DELETE CASCADE,
  CONSTRAINT `fk_venta_usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO venta VALUES("4","6","1","Factura","ffff","222","2024-07-03 00:00:00","18.00","130.00","Aceptado");
INSERT INTO venta VALUES("7","6","1","Factura","2","2","2024-09-22 00:00:00","10.00","0.00","Aceptado");
INSERT INTO venta VALUES("9","15","1","Factura","","2","2024-09-22 00:00:00","0.00","0.00","Anulado");
INSERT INTO venta VALUES("10","15","1","Factura","2","2","2016-01-22 00:00:00","0.00","2.00","Anulado");
INSERT INTO venta VALUES("11","15","1","Factura","2","1","2024-09-23 00:00:00","0.00","44.00","Anulado");



SET FOREIGN_KEY_CHECKS=1;
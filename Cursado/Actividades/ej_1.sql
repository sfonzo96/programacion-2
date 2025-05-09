-- CREATE DATABASE IF NOT EXISTS prog2_practica;

-- USE prog2_practica;

-- CREATE TABLE IF NOT EXISTS ciudades (
-- 	id_ciudad INT NOT NULL AUTO_INCREMENT,
-- 	nombre_ciudad VARCHAR(30),
-- 	PRIMARY KEY (id_ciudad)
-- );
-- 
-- CREATE TABLE IF NOT EXISTS clientes (
-- 	id_cliente INT NOT NULL AUTO_INCREMENT,
-- 	nombre VARCHAR(50),
-- 	apellido VARCHAR(50),
-- 	id_ciudad INT NOT NULL,
-- 	PRIMARY KEY(id_cliente),
-- 	FOREIGN KEY (id_ciudad) REFERENCES ciudades(id_ciudad)
-- );

-- INSERT INTO ciudades(nombre_ciudad) VALUES ('Carcarañá'), ('Rosario'), ('Funes'), ('Roldán'), ('San Lorenzo');
-- 
-- INSERT INTO clientes  (nombre, apellido, id_ciudad) VALUES ('Santiago', 'Fonzo', 1), ('Sebastián', 'Bruserlario', 2);
-- 
-- SELECT * FROM ciudades;
-- 
-- SELECT * FROM clientes;


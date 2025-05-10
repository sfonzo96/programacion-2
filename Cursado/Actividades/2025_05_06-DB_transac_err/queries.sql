-- CREATE TABLE IF NOT EXISTS products (
-- 	id INT NOT NULL AUTO_INCREMENT,
-- 	name VARCHAR(50),
-- 	price DECIMAL(10,2),
-- 	PRIMARY KEY(id)
-- );
-- 
-- INSERT INTO products (name, price) 
-- VALUES
--   ('WD SSD 512 GB', 30000.00),
--   ('CRUCIAL RAM DDR4 16GB', 70000.00),
--   ('INTEL CORE i5 12400F', 150000.00),
--   ('AMD RYZEN 5 5600X', 140000.00),
--   ('ASUS B550M MOTHERBOARD', 85000.00),
--   ('KINGSTON NV2 1TB SSD', 45000.00),
--   ('GIGABYTE RTX 3060 12GB', 300000.00),
--   ('LOGITECH G PRO MOUSE', 25000.00),
--   ('CORSAIR 750W PSU 80+ GOLD', 90000.00),
--   ('NZXT H510 CASE', 60000.00),
--   ('DEEPCOOL AK620 CPU COOLER', 40000.00),
--   ('SAMSUNG 24" MONITOR 75HZ', 80000.00);

-- SELECT id, name, price FROM products ORDER BY id DESC;

-- CREATE TABLE IF NOT EXISTS users (
-- 	id INT AUTO_INCREMENT,
-- 	email VARCHAR(100),
-- 	status VARCHAR(20),
-- 	PRIMARY KEY (id)
-- );
-- 
-- INSERT INTO users (email, status) 
-- VALUES
-- 	('santiago.fonzo@institutozonaoeste.edu.ar', 'inactive'),
-- 	('sebastian.bruselario@institutozonaoeste.edu.ar', 'active');

-- SELECT * FROM users;

-- 
-- CREATE TABLE IF NOT EXISTS accounts (
-- 	id INT AUTO_INCREMENT,
-- 	balance DECIMAL(10,2),
-- 	PRIMARY KEY(id)
-- );
-- 
-- INSERT INTO accounts (balance)
-- VALUES
-- 	(500.00),
-- 	(1000.00);

SELECT * FROM accounts;

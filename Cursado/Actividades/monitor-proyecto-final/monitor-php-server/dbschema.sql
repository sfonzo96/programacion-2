-- ROLES
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- INSERT INTO roles (name) VALUES ('admin'),('manager'), ('watcher'),  ('daemon');

-- USERS
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role_id INT NOT NULL DEFAULT 1,
    enabled BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_login_at DATETIME DEFAULT NULL,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

CREATE TABLE IF NOT EXISTS sse_events_queue (
	id INT AUTO_INCREMENT PRIMARY KEY,
	motive VARCHAR(15),
	data VARCHAR(255),
	created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS sse_user_states (
    user_id INT PRIMARY KEY,
    last_event_id INT NOT NULL DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
-- INSERT INTO users (first_name, last_name, username, password, role_id, enabled) VALUES ("Ad", "Min", "admin", "admin123", 1, TRUE)

-- METRICS
CREATE TABLE IF NOT EXISTS metrics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    description VARCHAR(255),
    measure_unit_format VARCHAR(100)
);

-- INSERT INTO metrics (name, description, measure_unit_format)
-- VALUES ("CPU Usage", "Percent of the CPU in usage", "%"), ("RAM Usage", "Percent of the RAM Memory in usage", "%");

-- RECORDS
CREATE TABLE IF NOT EXISTS metrics_records (
    metric_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    value FLOAT NOT NULL,
    PRIMARY KEY (metric_id, created_at),
    FOREIGN KEY (metric_id) REFERENCES metrics(id)
);

select * 
-- NETWORKS
CREATE TABLE IF NOT EXISTS networks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45),
    cidr_mask VARCHAR(45),
    description VARCHAR(50),
    UNIQUE KEY uniq_network (ip_address, cidr_mask)
);

-- HOSTS
CREATE TABLE IF NOT EXISTS hosts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mac_address VARCHAR(255),
    ip_address VARCHAR(45) NOT NULL,
    network_id INT NOT NULL,
    hostname VARCHAR(255),
    first_seen DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    last_seen DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_online BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (network_id) REFERENCES networks(id) ON DELETE CASCADE
);

CREATE DATABASE IF NOT EXISTS inventory_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inventory_db;

CREATE TABLE users (
 id INT AUTO_INCREMENT PRIMARY KEY,
 username VARCHAR(60) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL,
 role ENUM('admin','staff') NOT NULL DEFAULT 'staff',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE categories (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL UNIQUE,
 description VARCHAR(255) NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE suppliers (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(150) NOT NULL,
 phone VARCHAR(40) NULL,
 email VARCHAR(150) NULL,
 address VARCHAR(255) NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE products (
 id INT AUTO_INCREMENT PRIMARY KEY,
 category_id INT NOT NULL,
 sku VARCHAR(50) NOT NULL UNIQUE,
 name VARCHAR(150) NOT NULL,
 price DECIMAL(12,2) NOT NULL DEFAULT 0 CHECK(price >= 0),
 stock INT NOT NULL DEFAULT 0 CHECK(stock >= 0),
 minimum_stock INT NOT NULL DEFAULT 5 CHECK(minimum_stock >= 0),
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 CONSTRAINT fk_products_category FOREIGN KEY(category_id) REFERENCES categories(id) ON UPDATE CASCADE ON DELETE RESTRICT
);
CREATE TABLE product_suppliers (
 product_id INT NOT NULL,
 supplier_id INT NOT NULL,
 supplier_price DECIMAL(12,2) NULL,
 PRIMARY KEY(product_id,supplier_id),
 FOREIGN KEY(product_id) REFERENCES products(id) ON UPDATE CASCADE ON DELETE CASCADE,
 FOREIGN KEY(supplier_id) REFERENCES suppliers(id) ON UPDATE CASCADE ON DELETE RESTRICT
);
CREATE TABLE stock_movements (
 id BIGINT AUTO_INCREMENT PRIMARY KEY,
 product_id INT NOT NULL,
 user_id INT NOT NULL,
 type ENUM('IN','OUT') NOT NULL,
 quantity INT NOT NULL CHECK(quantity > 0),
 note VARCHAR(255) NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(product_id) REFERENCES products(id) ON UPDATE CASCADE ON DELETE RESTRICT,
 FOREIGN KEY(user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE RESTRICT,
 INDEX idx_movements_created(created_at),
 INDEX idx_movements_type(type)
);

-- Demo accounts: password "password". Replace these hashes/passwords after setup.
INSERT INTO users(username,password_hash,role) VALUES
('admin','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.','admin'),
('staff','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.','staff');

INSERT INTO categories(name,description) VALUES
('Elektronik','Perangkat elektronik dan aksesoris'),
('Peralatan Kantor','Perlengkapan kantor'),
('Aksesoris','Aksesoris umum');
INSERT INTO suppliers(name,phone,email,address) VALUES
('Sumber Jaya','081234567890','sales@sumberjaya.test','Medan'),
('Mitra Niaga','081298765432','mitra@example.test','Binjai');
INSERT INTO products(category_id,sku,name,price,stock,minimum_stock) VALUES
(1,'EL-001','Keyboard Mechanical',350000,28,5),
(1,'EL-002','Mouse Wireless',125000,7,10),
(2,'KT-001','Kertas A4',58000,42,10),
(3,'AK-001','Kabel USB-C',45000,4,8);
INSERT INTO product_suppliers(product_id,supplier_id,supplier_price) VALUES
(1,1,290000),(2,1,90000),(3,2,47000),(4,2,32000);

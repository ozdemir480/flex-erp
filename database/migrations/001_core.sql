CREATE TABLE companies (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  tax_no VARCHAR(30) NOT NULL,
  currency_code CHAR(3) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_id INT NOT NULL,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','accountant','sales','viewer') NOT NULL,
  status TINYINT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (company_id) REFERENCES companies(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_id INT NOT NULL,
  code VARCHAR(50) NOT NULL,
  name VARCHAR(100) NOT NULL,
  tax_no VARCHAR(30) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  email VARCHAR(100) NOT NULL,
  billing_address TEXT,
  shipping_address TEXT,
  balance DECIMAL(18,2) DEFAULT 0.00,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_customer_code (company_id, code),
  FOREIGN KEY (company_id) REFERENCES companies(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_id INT NOT NULL,
  sku VARCHAR(50) NOT NULL,
  name VARCHAR(100) NOT NULL,
  unit VARCHAR(20) NOT NULL,
  price DECIMAL(18,2) NOT NULL,
  tax_rate DECIMAL(5,2) NOT NULL,
  is_service TINYINT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_product_sku (company_id, sku),
  FOREIGN KEY (company_id) REFERENCES companies(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

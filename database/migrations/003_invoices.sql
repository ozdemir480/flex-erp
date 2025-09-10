CREATE TABLE invoices (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_id INT NOT NULL,
  customer_id INT NOT NULL,
  number VARCHAR(40) NOT NULL,
  issue_date DATE NOT NULL,
  due_date DATE NOT NULL,
  status ENUM('draft','sent','partial','paid','void','overdue') NOT NULL,
  currency_code CHAR(3) NOT NULL,
  subtotal DECIMAL(18,2) NOT NULL,
  tax_total DECIMAL(18,2) NOT NULL,
  discount_total DECIMAL(18,2) NOT NULL,
  total DECIMAL(18,2) NOT NULL,
  notes TEXT,
  created_by INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_invoice_number (company_id, number),
  FOREIGN KEY (company_id) REFERENCES companies(id),
  FOREIGN KEY (customer_id) REFERENCES customers(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE invoice_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  invoice_id INT NOT NULL,
  product_id INT NULL,
  description VARCHAR(255) NOT NULL,
  qty DECIMAL(18,4) NOT NULL,
  unit_price DECIMAL(18,2) NOT NULL,
  tax_rate DECIMAL(5,2) NOT NULL,
  line_subtotal DECIMAL(18,2) NOT NULL,
  line_tax DECIMAL(18,2) NOT NULL,
  line_total DECIMAL(18,2) NOT NULL,
  FOREIGN KEY (invoice_id) REFERENCES invoices(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

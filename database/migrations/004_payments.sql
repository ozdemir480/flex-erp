CREATE TABLE payments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_id INT NOT NULL,
  customer_id INT NOT NULL,
  invoice_id INT NULL,
  date DATE NOT NULL,
  method ENUM('cash','bank','card','other') NOT NULL,
  amount DECIMAL(18,2) NOT NULL,
  notes TEXT,
  FOREIGN KEY (company_id) REFERENCES companies(id),
  FOREIGN KEY (customer_id) REFERENCES customers(id),
  FOREIGN KEY (invoice_id) REFERENCES invoices(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX idx_payments_customer ON payments(customer_id);
CREATE INDEX idx_payments_invoice ON payments(invoice_id);

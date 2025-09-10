CREATE TABLE account_types (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(10) NOT NULL,
  name VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE accounts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_id INT NOT NULL,
  code VARCHAR(20) NOT NULL,
  name VARCHAR(100) NOT NULL,
  type_id INT NOT NULL,
  is_active TINYINT NOT NULL DEFAULT 1,
  UNIQUE KEY uniq_account (company_id, code),
  FOREIGN KEY (type_id) REFERENCES account_types(id),
  FOREIGN KEY (company_id) REFERENCES companies(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE journal_entries (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_id INT NOT NULL,
  date DATE NOT NULL,
  ref VARCHAR(40) NOT NULL,
  memo TEXT,
  created_by INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (company_id) REFERENCES companies(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE journal_lines (
  id INT AUTO_INCREMENT PRIMARY KEY,
  entry_id INT NOT NULL,
  account_id INT NOT NULL,
  debit DECIMAL(18,2) DEFAULT 0.00,
  credit DECIMAL(18,2) DEFAULT 0.00,
  line_memo VARCHAR(200),
  FOREIGN KEY (entry_id) REFERENCES journal_entries(id),
  FOREIGN KEY (account_id) REFERENCES accounts(id),
  CHECK ((debit = 0 AND credit > 0) OR (credit = 0 AND debit > 0))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

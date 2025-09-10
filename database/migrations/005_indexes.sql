CREATE INDEX idx_users_company ON users(company_id);
CREATE INDEX idx_customers_company ON customers(company_id);
CREATE INDEX idx_products_company ON products(company_id);
CREATE INDEX idx_invoices_company ON invoices(company_id);
CREATE INDEX idx_invoice_items_invoice ON invoice_items(invoice_id);
CREATE INDEX idx_journal_entries_company ON journal_entries(company_id);
CREATE INDEX idx_journal_lines_entry ON journal_lines(entry_id);

<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Core\DB;
use App\Domain\Entities\Invoice;
use App\Domain\Entities\InvoiceItem;
use App\Domain\Repositories\InvoiceRepositoryInterface;
use PDO;

class PdoInvoiceRepository implements InvoiceRepositoryInterface
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = DB::get();
    }

    public function find(int $id): ?Invoice
    {
        $stmt = $this->pdo->prepare('SELECT * FROM invoices WHERE id = ?');
        $stmt->execute([$id]);
        $invoice = $stmt->fetch();
        if (!$invoice) {
            return null;
        }
        $itemStmt = $this->pdo->prepare('SELECT * FROM invoice_items WHERE invoice_id = ?');
        $itemStmt->execute([$id]);
        $items = array_map(fn($r) => $this->mapItem($r), $itemStmt->fetchAll());
        return $this->map($invoice, $items);
    }

    public function all(int $companyId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM invoices WHERE company_id = ?');
        $stmt->execute([$companyId]);
        $rows = $stmt->fetchAll();
        return array_map(fn($r) => $this->map($r, []), $rows);
    }

    public function create(Invoice $invoice): int
    {
        $stmt = $this->pdo->prepare('INSERT INTO invoices (company_id, customer_id, number, issue_date, due_date, status, currency_code, subtotal, tax_total, discount_total, total, notes, created_by) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $stmt->execute([
            $invoice->companyId,
            $invoice->customerId,
            $invoice->number,
            $invoice->issueDate,
            $invoice->dueDate,
            $invoice->status,
            $invoice->currencyCode,
            $invoice->subtotal,
            $invoice->taxTotal,
            $invoice->discountTotal,
            $invoice->total,
            $invoice->notes,
            $invoice->createdBy,
        ]);
        $id = (int)$this->pdo->lastInsertId();
        foreach ($invoice->items as $item) {
            $this->createItem($id, $item);
        }
        return $id;
    }

    public function updateStatus(int $id, string $status): void
    {
        $stmt = $this->pdo->prepare('UPDATE invoices SET status = ? WHERE id = ?');
        $stmt->execute([$status, $id]);
    }

    private function createItem(int $invoiceId, InvoiceItem $item): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO invoice_items (invoice_id, product_id, description, qty, unit_price, tax_rate, line_subtotal, line_tax, line_total) VALUES (?,?,?,?,?,?,?,?,?)');
        $stmt->execute([
            $invoiceId,
            $item->productId,
            $item->description,
            $item->qty,
            $item->unitPrice,
            $item->taxRate,
            $item->lineSubtotal,
            $item->lineTax,
            $item->lineTotal,
        ]);
    }

    private function map(array $row, array $items): Invoice
    {
        return new Invoice(
            (int)$row['id'],
            (int)$row['company_id'],
            (int)$row['customer_id'],
            $row['number'],
            $row['issue_date'],
            $row['due_date'],
            $row['status'],
            $row['currency_code'],
            (float)$row['subtotal'],
            (float)$row['tax_total'],
            (float)$row['discount_total'],
            (float)$row['total'],
            $row['notes'],
            (int)$row['created_by'],
            $items
        );
    }

    private function mapItem(array $row): InvoiceItem
    {
        return new InvoiceItem(
            (int)$row['id'],
            (int)$row['invoice_id'],
            $row['product_id'] ? (int)$row['product_id'] : null,
            $row['description'],
            (float)$row['qty'],
            (float)$row['unit_price'],
            (float)$row['tax_rate'],
            (float)$row['line_subtotal'],
            (float)$row['line_tax'],
            (float)$row['line_total']
        );
    }
}

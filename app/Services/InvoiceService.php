<?php

declare(strict_types=1);

namespace App\Services;

use App\Domain\Entities\Invoice;
use App\Domain\Entities\InvoiceItem;
use App\Domain\Repositories\InvoiceRepositoryInterface;

class InvoiceService
{
    public function __construct(private InvoiceRepositoryInterface $invoices)
    {
    }

    /** @param array<int, array{description:string, qty:float, unit_price:float, tax_rate:float, product_id?:int|null}> $data */
    public function create(int $companyId, int $customerId, array $data, int $userId): Invoice
    {
        $items = [];
        $subtotal = $taxTotal = 0.0;
        foreach ($data as $d) {
            $lineSubtotal = $d['qty'] * $d['unit_price'];
            $lineTax = $lineSubtotal * $d['tax_rate'] / 100;
            $lineTotal = $lineSubtotal + $lineTax;
            $items[] = new InvoiceItem(0, 0, $d['product_id'] ?? null, $d['description'], $d['qty'], $d['unit_price'], $d['tax_rate'], $lineSubtotal, $lineTax, $lineTotal);
            $subtotal += $lineSubtotal;
            $taxTotal += $lineTax;
        }
        $invoice = new Invoice(0, $companyId, $customerId, uniqid('INV'), date('Y-m-d'), date('Y-m-d'), 'draft', 'TRY', $subtotal, $taxTotal, 0.0, $subtotal + $taxTotal, '', $userId, $items);
        $id = $this->invoices->create($invoice);
        $invoice->id = $id;
        return $invoice;
    }
}

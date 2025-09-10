<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\DB;
use App\Domain\Entities\Invoice;
use App\Domain\Entities\JournalEntry;
use App\Domain\Entities\JournalLine;
use App\Domain\Entities\Payment;
use App\Domain\Repositories\AccountRepositoryInterface;
use App\Domain\Repositories\InvoiceRepositoryInterface;
use App\Domain\Repositories\JournalRepositoryInterface;
use PDO;
use RuntimeException;

class AccountingService
{
    public function __construct(
        private JournalRepositoryInterface $journals,
        private AccountRepositoryInterface $accounts,
        private InvoiceRepositoryInterface $invoices
    ) {
    }

    public function postInvoice(Invoice $invoice): int
    {
        $pdo = DB::get();
        $pdo->beginTransaction();
        try {
            $entry = new JournalEntry(0, $invoice->companyId, $invoice->issueDate, $invoice->number, 'Invoice', $invoice->createdBy, [
                new JournalLine(0, 0, $this->accountId($invoice->companyId, '120'), $invoice->total, 0.0, ''),
                new JournalLine(0, 0, $this->accountId($invoice->companyId, '600'), 0.0, $invoice->subtotal - $invoice->discountTotal, ''),
                new JournalLine(0, 0, $this->accountId($invoice->companyId, '391'), 0.0, $invoice->taxTotal, ''),
            ]);
            $debit = $invoice->total;
            $credit = $invoice->subtotal - $invoice->discountTotal + $invoice->taxTotal;
            if (abs($debit - $credit) > 0.01) {
                throw new RuntimeException('Journal not balanced');
            }
            $entryId = $this->journals->create($entry);
            $this->invoices->updateStatus($invoice->id, 'posted');
            $pdo->commit();
            return $entryId;
        } catch (RuntimeException $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public function postPayment(Payment $payment): int
    {
        $pdo = DB::get();
        $pdo->beginTransaction();
        try {
            $cashCode = match ($payment->method) {
                'bank' => '102',
                default => '100',
            };
            $entry = new JournalEntry(0, $payment->companyId, $payment->date, 'PAY-' . $payment->id, 'Payment', 1, [
                new JournalLine(0, 0, $this->accountId($payment->companyId, $cashCode), $payment->amount, 0.0, ''),
                new JournalLine(0, 0, $this->accountId($payment->companyId, '120'), 0.0, $payment->amount, ''),
            ]);
            $entryId = $this->journals->create($entry);
            $pdo->commit();
            return $entryId;
        } catch (RuntimeException $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    private function accountId(int $companyId, string $code): int
    {
        $acc = $this->accounts->findByCode($companyId, $code);
        if (!$acc) {
            throw new RuntimeException('Account not found: ' . $code);
        }
        return $acc->id;
    }
}

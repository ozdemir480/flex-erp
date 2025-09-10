<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Core\DB;
use App\Domain\Entities\JournalEntry;
use App\Domain\Entities\JournalLine;
use App\Domain\Repositories\JournalRepositoryInterface;
use PDO;

class PdoJournalRepository implements JournalRepositoryInterface
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = DB::get();
    }

    public function find(int $id): ?JournalEntry
    {
        $stmt = $this->pdo->prepare('SELECT * FROM journal_entries WHERE id = ?');
        $stmt->execute([$id]);
        $entry = $stmt->fetch();
        if (!$entry) {
            return null;
        }
        $linesStmt = $this->pdo->prepare('SELECT * FROM journal_lines WHERE entry_id = ?');
        $linesStmt->execute([$id]);
        $lines = array_map(fn($r) => $this->mapLine($r), $linesStmt->fetchAll());
        return $this->mapEntry($entry, $lines);
    }

    public function recent(int $companyId, int $limit = 20): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM journal_entries WHERE company_id = ? ORDER BY id DESC LIMIT ?');
        $stmt->execute([$companyId, $limit]);
        $rows = $stmt->fetchAll();
        return array_map(fn($r) => $this->mapEntry($r, []), $rows);
    }

    public function create(JournalEntry $entry): int
    {
        $stmt = $this->pdo->prepare('INSERT INTO journal_entries (company_id, date, ref, memo, created_by) VALUES (?,?,?,?,?)');
        $stmt->execute([
            $entry->companyId,
            $entry->date,
            $entry->ref,
            $entry->memo,
            $entry->createdBy,
        ]);
        $id = (int)$this->pdo->lastInsertId();
        foreach ($entry->lines as $line) {
            $this->createLine($id, $line);
        }
        return $id;
    }

    private function createLine(int $entryId, JournalLine $line): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO journal_lines (entry_id, account_id, debit, credit, line_memo) VALUES (?,?,?,?,?)');
        $stmt->execute([
            $entryId,
            $line->accountId,
            $line->debit,
            $line->credit,
            $line->memo,
        ]);
    }

    private function mapEntry(array $row, array $lines): JournalEntry
    {
        return new JournalEntry(
            (int)$row['id'],
            (int)$row['company_id'],
            $row['date'],
            $row['ref'],
            $row['memo'],
            (int)$row['created_by'],
            $lines
        );
    }

    private function mapLine(array $row): JournalLine
    {
        return new JournalLine(
            (int)$row['id'],
            (int)$row['entry_id'],
            (int)$row['account_id'],
            (float)$row['debit'],
            (float)$row['credit'],
            $row['line_memo']
        );
    }
}

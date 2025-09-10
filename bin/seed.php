<?php

declare(strict_types=1);

use App\Core\DB;
use App\Infrastructure\Repositories\{PdoCustomerRepository,PdoProductRepository,PdoAccountRepository,PdoInvoiceRepository,PdoJournalRepository,PdoPaymentRepository};
use App\Services\{InvoiceService,AccountingService};
use App\Domain\Entities\Customer;
use App\Domain\Entities\Product;
use App\Domain\Entities\Payment;

require __DIR__ . '/../vendor/autoload.php';

$pdo = DB::get();

$pdo->exec("INSERT INTO companies (id,name,tax_no,currency_code) VALUES (1,'Demo Co','123','TRY');");
$pdo->exec("INSERT INTO users (id,company_id,name,email,password_hash,role,status) VALUES (1,1,'Admin','admin@example.com','" . password_hash('secret', PASSWORD_ARGON2ID) . "','admin',1);");

$customers = new PdoCustomerRepository();
$customers->create(new Customer(0,1,'C001','Alice','', '', '', '', '', 0));
$customers->create(new Customer(0,1,'C002','Bob','', '', '', '', '', 0));

$products = new PdoProductRepository();
$products->create(new Product(0,1,'P001','Item A','pcs',100,18,false));
$products->create(new Product(0,1,'P002','Item B','pcs',200,18,false));

$accounts = new PdoAccountRepository();
$pdo->exec("INSERT INTO account_types (id,code,name) VALUES (1,'asset','Assets'),(2,'liability','Liabilities'),(3,'equity','Equity'),(4,'revenue','Revenue'),(5,'expense','Expense');");
$accounts->create(new App\Domain\Entities\Account(0,1,'100','Kasa',1,true));
$accounts->create(new App\Domain\Entities\Account(0,1,'102','Banka',1,true));
$accounts->create(new App\Domain\Entities\Account(0,1,'120','Alıcılar',1,true));
$accounts->create(new App\Domain\Entities\Account(0,1,'600','Satış Gelirleri',4,true));
$accounts->create(new App\Domain\Entities\Account(0,1,'391','Hesaplanan KDV',2,true));

$invoicesRepo = new PdoInvoiceRepository();
$journalsRepo = new PdoJournalRepository();
$paymentsRepo = new PdoPaymentRepository();
$invoiceService = new InvoiceService($invoicesRepo);
$accounting = new AccountingService($journalsRepo,$accounts,$invoicesRepo);

$invoice = $invoiceService->create(1,1,[['description'=>'Sample','qty'=>1,'unit_price'=>100,'tax_rate'=>18]],1);
$accounting->postInvoice($invoice);

$payment = new Payment(0,1,1,$invoice->id,date('Y-m-d'),'cash',$invoice->total,'');
$paymentId = $paymentsRepo->create($payment);
$payment->id = $paymentId;
$accounting->postPayment($payment);

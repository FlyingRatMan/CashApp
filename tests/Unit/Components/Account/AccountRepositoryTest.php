<?php
declare(strict_types=1);

namespace Unit\Components\Account;

use App\Components\Account\Persistence\AccountRepository;
use App\Components\Account\Persistence\Mapper\AccountMapper;
use App\db_script;
use App\Model\DB\ORMEntityManager;
use PHPUnit\Framework\TestCase;

class AccountRepositoryTest extends TestCase
{
    private AccountRepository $accountRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->accountRepository = new AccountRepository(new ORMEntityManager(), new AccountMapper());
    }

    protected function tearDown(): void
    {
        $db = new db_script();
        $db->cleanDatabase();

        parent::tearDown();
    }

    public function testFindAllReturnsEmpty(): void
    {
        $userID = 0;

        $actualData = $this->accountRepository->findAll($userID);

        $this->assertEmpty($actualData);
    }

    public function testFindAllReturnsTransactions(): void
    {
        $actualData = $this->accountRepository->findAll(2);

        foreach ($actualData as $transaction) {
            $arr = (array)$transaction;
            $this->assertIsArray($arr);
            $this->assertArrayHasKey('user_id', $arr);
            $this->assertSame(2, $arr['user_id']);
            $this->assertArrayHasKey('amount', $arr);
            $this->assertSame(100.0, $arr['amount']);
            $this->assertArrayHasKey('date', $arr);
            $this->assertSame('2024-11-06', $arr['date']);
        }
    }
}
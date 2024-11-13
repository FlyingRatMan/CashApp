<?php
declare(strict_types=1);

namespace Unit\Components\Account;

use App\Components\Account\Persistence\AccountEntityManager;
use App\Components\Account\Persistence\AccountRepository;
use App\Components\Account\Persistence\Mapper\AccountMapper;
use App\DataTransferObjects\AccountDTO;
use App\db_script;
use App\DBConnector\ORMEntityManager;
use PHPUnit\Framework\TestCase;

class AccountEntityManagerTest extends TestCase
{
    private AccountRepository $accountRepository;
    private AccountEntityManager $accountEntityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->accountEntityManager = new AccountEntityManager(new ORMEntityManager());
        $this->accountRepository = new AccountRepository(new AccountMapper());
    }

    protected function tearDown(): void
    {
        $db = new db_script();
        $db->cleanDatabase();

        parent::tearDown();
    }

    public function testAdd(): void
    {
        $expectedData = new AccountDTO(1, 3, 100, '2024-01-01 00:00:00');
        $userID = 3;

        $this->accountEntityManager->add($expectedData, $userID);
        $actualData = $this->accountRepository->findAll($userID);

        $this->assertCount(1, $actualData);
        $this->assertSame($expectedData->user_id, $actualData[0]->user_id);
        $this->assertSame($expectedData->amount, $actualData[0]->amount);
        $this->assertSame($expectedData->date, $actualData[0]->date);
    }
}
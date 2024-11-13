<?php
declare(strict_types=1);

namespace Unit\Components\Account\Model;

use App\Components\Account\Business\Model\AccountService;
use App\Components\Account\Persistence\AccountRepository;
use App\DataTransferObjects\AccountDTO;
use PHPUnit\Framework\TestCase;

class AccountServiceTest extends TestCase
{
    private AccountService $accountService;
    private AccountRepository $accountRepository;

    protected function setUp(): void
    {
        $this->accountRepository = $this->createMock(AccountRepository::class);
        $this->accountService = new AccountService($this->accountRepository);
    }

    public function testGetBalanceReturnsCorrectBalance(): void
    {
        $accountDTO = new AccountDTO(1, 1, 50.0, '2024-01-01 00:00:00');

        $this->accountRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$accountDTO]);

        $actualData = $this->accountService->getBalance(1);

        $this->assertSame(50, $actualData);
    }

    public function testGetBalanceReturnsZero(): void
    {
        $this->accountRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $actualData = $this->accountService->getBalance(777);

        $this->assertSame(0, $actualData);
    }
}
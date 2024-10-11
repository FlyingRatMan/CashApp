<?php
declare(strict_types=1);

namespace Unit\Controller;

use App\Controller\ResetPasswordController;
use App\Model\DB\SqlConnector;
use PHPUnit\Framework\TestCase;

class ResetPasswordControllerTest extends TestCase
{
    private $db;
    private $resetPasswordController;

    protected function setUp(): void
    {
        parent::setUp();

        $this->db = new SqlConnector();
        $this->resetPasswordController = new ResetPasswordController($this->db);
    }

    private function testTokenValidationIsExpired(): void
    {

    }
}
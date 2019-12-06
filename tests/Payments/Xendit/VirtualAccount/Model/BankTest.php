<?php declare(strict_types=1);

namespace Tests\Payments\Xendit\Model;

use PHPUnit\Framework\TestCase;
use Ziswapp\Payments\Xendit\VirtualAccount\Model\Bank;

/**
 * @author Nuradiyana <me@nooradiana.com>
 */
final class BankTest extends TestCase
{
    public function testCanCreateFromApi()
    {
        $content = <<<JSON
{
  "name": "Bank Negara Indonesia",
  "code": "BNI"
}
JSON;

        $data = \json_decode($content, true);

        $bank = Bank::fromApi($data);
        $this->assertSame($bank->getCode(), $data['code']);
        $this->assertSame($bank->getName(), $data['name']);
    }
}

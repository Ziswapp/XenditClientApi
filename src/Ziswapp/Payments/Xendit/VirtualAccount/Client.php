<?php declare(strict_types=1);

namespace Ziswapp\Payments\Xendit\VirtualAccount;

use Ziswapp\Payments\Xendit\Model\ModelInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Ziswapp\Payments\Xendit\VirtualAccount\Model\Bank;
use Ziswapp\Payments\Xendit\VirtualAccount\Model\Payment;
use Ziswapp\Payments\Xendit\Exception\ExpectedAmountException;
use Ziswapp\Payments\Xendit\VirtualAccount\Model\VirtualAccount;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;

/**
 * @author Nuradiyana <me@nooradiana.com>
 */
final class Client
{
    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getVirtualAccountBanks(): array
    {
        $response = $this->client->request('GET', '/available_virtual_account_banks');

        $contents = $response->toArray();

        return \array_map(function (array $content): Bank {
            /** @var Bank $bank */
            $bank = Bank::fromApi($content);

            return $bank;
        }, $contents);
    }

    /**
     * @param VirtualAccount $account
     *
     * @return ModelInterface|VirtualAccount
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ExpectedAmountException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function createVirtualAccount(VirtualAccount $account)
    {
        $response = $this->client->request('POST', '/callback_virtual_accounts', [
            'body' => $account->toRequestBody(),
        ]);

        $content = $response->toArray();

        return VirtualAccount::fromApi($content);
    }

    /**
     * @param string $id
     *
     * @return ModelInterface|VirtualAccount
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function findVirtualAccount(string $id)
    {
        $response = $this->client->request('GET', \sprintf('/callback_virtual_accounts/%s', $id));

        $content = $response->toArray();

        return VirtualAccount::fromApi($content);
    }

    /**
     * @param string $id
     * @param string $key
     * @param mixed  $value
     *
     * @return ModelInterface|VirtualAccount
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function update(string $id, string $key, $value)
    {
        $response = $this->client->request('PATCH', \sprintf('/callback_virtual_accounts/%s', $id), [
            'body' => [$key => $value],
        ]);

        $content = $response->toArray();

        return VirtualAccount::fromApi($content);
    }

    /**
     * @param string $id
     *
     * @return ModelInterface|Payment
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getPaymentVirtualAccount(string $id)
    {
        $response = $this->client->request('GET', \sprintf('/callback_virtual_account_payments/payment_id=%s', $id));

        $content = $response->toArray();

        return Payment::fromApi($content);
    }
}

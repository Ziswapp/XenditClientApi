<?php declare(strict_types=1);

namespace Ziswapp\Payments\Xendit\VirtualAccount\Model;

use Carbon\Carbon;
use DateTimeInterface;
use Ziswapp\Payments\Xendit\Model\ModelInterface;
use Ziswapp\Payments\Xendit\Model\ModelRequestInterface;
use Ziswapp\Payments\Xendit\Exception\ExpectedAmountException;

/**
 * @author Nuradiyana <me@nooradiana.com>
 */
final class VirtualAccount implements ModelInterface, ModelRequestInterface
{
    /**
     * @var string|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $externalId;

    /**
     * @var string|null
     */
    private $bankCode;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $accountNumber;

    /**
     * @var float|null
     */
    private $suggestedAmount;

    /**
     * @var bool
     */
    private $isClose;

    /**
     * @var float
     */
    private $expectedAmount;

    /**
     * @var DateTimeInterface|null
     */
    private $expiredDate;

    /**
     * @var bool
     */
    private $isSingleUse;

    /**
     * @var string|null
     */
    private $ownerId;

    /**
     * @var string|null
     */
    private $merchantCode;

    /**
     * @var string|null
     */
    private $status;

    /**
     * @param string                 $externalId
     * @param string                 $bankCode
     * @param string                 $name
     * @param bool              $isClose
     * @param bool              $isSingleUse
     * @param float             $suggestedAmount
     * @param float             $expectedAmount
     * @param DateTimeInterface|null $expiredDate
     */
    public function __construct(string $externalId, string $bankCode, string $name, bool $isClose = false, bool $isSingleUse = false, float $suggestedAmount = 0.0, float $expectedAmount = 0.0, ?DateTimeInterface $expiredDate = null)
    {
        $this->externalId = $externalId;
        $this->bankCode = $bankCode;
        $this->name = $name;
        $this->suggestedAmount = $suggestedAmount;
        $this->isClose = (bool) $isClose;
        $this->expectedAmount = $expectedAmount;
        $this->expiredDate = $expiredDate;
        $this->isSingleUse = (bool) $isSingleUse;
    }

    /**
     * @return string|null
     */
    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    /**
     * @return string|null
     */
    public function getBankCode(): ?string
    {
        return $this->bankCode;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getAccountNumber(): ?string
    {
        return $this->accountNumber;
    }

    /**
     * @return float|null
     */
    public function getSuggestedAmount(): ?float
    {
        return $this->suggestedAmount === null ? 0.0 : (float) $this->suggestedAmount;
    }

    /**
     * @return bool
     */
    public function isClose(): bool
    {
        return (bool) $this->isClose;
    }

    /**
     * @return float|null
     */
    public function getExpectedAmount(): ?float
    {
        return (float) $this->expectedAmount;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getExpiredDate(): ?DateTimeInterface
    {
        return $this->expiredDate;
    }

    /**
     * @return bool
     */
    public function isSingleUse(): bool
    {
        return (bool) $this->isSingleUse;
    }

    /**
     * @param string|null $accountNumber
     */
    public function setAccountNumber(?string $accountNumber): void
    {
        $this->accountNumber = $accountNumber;
    }

    /**
     * @param float|null $suggestedAmount
     */
    public function setSuggestedAmount(?float $suggestedAmount): void
    {
        $this->suggestedAmount = $suggestedAmount;
    }

    /**
     * @param bool $isClose
     */
    public function setIsClose(bool $isClose): void
    {
        $this->isClose = $isClose;
    }

    /**
     * @param float $expectedAmount
     */
    public function setExpectedAmount(float $expectedAmount): void
    {
        $this->expectedAmount = $expectedAmount;
    }

    /**
     * @param DateTimeInterface|null $expiredDate
     */
    public function setExpiredDate(?DateTimeInterface $expiredDate): void
    {
        $this->expiredDate = $expiredDate;
    }

    /**
     * @param bool $isSingleUse
     */
    public function setIsSingleUse(bool $isSingleUse): void
    {
        $this->isSingleUse = $isSingleUse;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getOwnerId(): ?string
    {
        return $this->ownerId;
    }

    /**
     * @param string|null $ownerId
     */
    public function setOwnerId(?string $ownerId): void
    {
        $this->ownerId = $ownerId;
    }

    /**
     * @return string|null
     */
    public function getMerchantCode(): ?string
    {
        return $this->merchantCode;
    }

    /**
     * @param string|null $merchantCode
     */
    public function setMerchantCode(?string $merchantCode): void
    {
        $this->merchantCode = $merchantCode;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     */
    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return array
     * @throws ExpectedAmountException
     */
    public function toRequestBody(): array
    {
        if ($this->getExpectedAmount() > 0 && $this->isClose() === true) {
            throw new ExpectedAmountException();
        }

        $body = ['external_id' => $this->getExternalId(), 'bank_code' => $this->getBankCode(), 'name' => $this->getName()];

        if ($this->getSuggestedAmount() > 0) {
            $body['suggested_amount'] = $this->suggestedAmount;
        }

        if ($this->isClose()) {
            $body['is_closed'] = $this->isClose();
        }

        if ($this->getExpectedAmount() > 0) {
            $body['expected_amount'] = $this->getExpectedAmount();
        }

        if ($this->getExpiredDate()) {
            /** @var DateTimeInterface $date */
            $date = $this->getExpiredDate();

            $body['expiration_date'] = Carbon::instance($date)->toISOString();
        }

        if ($this->isSingleUse()) {
            $body['is_single_use'] = $this->isSingleUse();
        }

        return $body;
    }

    /**
     * @param array $content
     *
     * @return ModelInterface|self
     */
    public static function fromApi(array $content): ModelInterface
    {
        $self = new static(
            $content['external_id'],
            $content['bank_code'],
            $content['name'],
            $content['is_closed'],
            $content['is_single_use']
        );

        $self->setId($content['id']);
        $self->setOwnerId($content['owner_id']);
        $self->setMerchantCode($content['merchant_code']);
        $self->setStatus($content['status']);

        if (\array_key_exists('account_number', $content)) {
            $self->setAccountNumber($content['account_number']);
        }

        if (\array_key_exists('suggested_amount', $content)) {
            $self->setSuggestedAmount($content['suggested_amount']);
        }

        if (\array_key_exists('expected_amount', $content)) {
            $self->setExpectedAmount($content['expected_amount']);
        }

        if (\array_key_exists('expiration_date', $content)) {
            $self->setExpiredDate(Carbon::createFromTimeString($content['expiration_date']));
        }

        return $self;
    }
}

<?php declare(strict_types=1);

namespace Ziswapp\Payments\Xendit\VirtualAccount\Model;

use Ziswapp\Payments\Xendit\Model\ModelInterface;

/**
 * @author Nuradiyana <me@nooradiana.com>
 */
final class Bank implements ModelInterface
{
    /**
     * @var string|null
     */
    private $code;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @param string|null $code
     * @param string|null $name
     */
    public function __construct(?string $code, ?string $name)
    {
        $this->code = $code;
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param array $content
     *
     * @return ModelInterface|self
     */
    public static function fromApi(array $content): ModelInterface
    {
        return new static($content['code'], $content['name']);
    }
}

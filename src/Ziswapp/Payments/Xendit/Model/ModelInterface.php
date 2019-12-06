<?php declare(strict_types=1);

namespace Ziswapp\Payments\Xendit\Model;

/**
 * @author Nuradiyana <me@nooradiana.com>
 */
interface ModelInterface
{
    /**
     * @param array $content
     *
     * @return ModelInterface|self
     */
    public static function fromApi(array $content): self;
}

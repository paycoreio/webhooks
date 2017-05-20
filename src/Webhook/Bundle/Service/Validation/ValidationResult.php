<?php
declare(strict_types=1);


namespace Webhook\Bundle\Service\Validation;

/**
 * Class ValidationResult
 * @package Webhook\Bundle\Service\Validation
 */
final class ValidationResult
{
    /** @var bool */
    public $isValid;

    /** @var string */
    public $errorMessage;
}
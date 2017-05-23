<?php
declare(strict_types=1);


namespace Webhook\Bundle\Service\Validation;

use Webhook\Bundle\Exception\BodyMissingException;
use Webhook\Bundle\Exception\NotURLException;
use Webhook\Bundle\Exception\NullBodyException;
use Webhook\Bundle\Exception\NullURLException;
use Webhook\Bundle\Exception\URLMissingException;

/**
 * Class RequestContentValidator
 * @package Webhook\Bundle\Service
 */
final class RequestContentValidator
{
    /**
     * @param array $data
     * @return ValidationResult
     */
    public function validate(array $data)
    {
        $result = new ValidationResult();
        try {
            $this->validateWithException($data);
            $result->isValid = true;
        } catch (\InvalidArgumentException $exception) {
            $result->isValid = false;
            $result->errorMessage = $exception->getMessage();
        }
        return $result;
    }

    /**
     * @param array $data
     */
    private function validateWithException(array $data)
    {
        if (!array_key_exists('url', $data)) {
            throw new URLMissingException();
        }
        if ($data['url'] === null) {
            throw new NullURLException();
        }
        if (!filter_var($data['url'], FILTER_VALIDATE_URL)) {
            throw new NotURLException($data['url']);
        }
        if (!array_key_exists('body', $data)) {
            throw new BodyMissingException();
        }
        if ($data['body'] === null) {
            throw new NullBodyException();
        }
    }
}
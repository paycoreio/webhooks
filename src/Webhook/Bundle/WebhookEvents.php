<?php
declare(strict_types=1);


namespace Webhook\Bundle;


/**
 * Class WebhookEvents
 *
 * @package Webhook\Bundle
 */
final class WebhookEvents
{
    const WEBHOOK_RETRY = 'webhook.retry';
    const WEBHOOK_DONE = 'webhook.done';
    const WEBHOOK_FAIL = 'webhook.fail';
}
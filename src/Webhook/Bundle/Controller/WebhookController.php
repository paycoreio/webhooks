<?php
declare(strict_types=1);


namespace Webhook\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Validation;
use Webhook\Bundle\Repository\WebhookRepository;
use Webhook\Domain\Infrastructure\Strategy\StrategyFactory;
use Webhook\Domain\Infrastructure\Strategy\StrategyRegistry;
use Webhook\Domain\Model\Webhook;


/**
 * Class WebhookController
 *
 * @package Webhook\Bundle\Controller
 */
class WebhookController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data) || json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Malformed json provided.'], 400);
        }

        if (true !== $response = $this->validate($data)) {
            return $response;
        }

        $message = $this->buildMessage($data);

        $this->get('webhook.repository')->save($message);
        $this->get('amqp.producer')->publish($message);

        return new JsonResponse($message, Response::HTTP_CREATED);
    }

    /**
     * @param array $data
     *
     * @return bool|JsonResponse
     */
    private function validate(array $data)
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($data, $this->getConstraints());

        if (0 !== $violations->count()) {
            $errors = [];
            foreach ($violations as $violation) {
                $field = preg_replace('/\[|\]/', '', $violation->getPropertyPath());
                $error = $violation->getMessage();
                $errors[$field] = $error;
            }

            return new JsonResponse(['errors' => $errors], 400);
        }

        return true;
    }

    /**
     * @return Collection
     */
    private function getConstraints(): Collection
    {
        $strategiesMap = StrategyRegistry::getMap();
        $strategies = array_keys($strategiesMap);

        return new Collection([
            'fields' => [
                'body'            => new Required(),
                'url'             => new Url(),
                'strategy'        => new Optional(
                    new Collection(
                        [
                            'fields' => [
                                'name'    => new Required(new Choice(['choices' => $strategies, 'strict' => true])),
                                'options' => new Optional(new All(new Length(['min' => 1]))),
                            ]
                        ])
                ),
                'raw'             => new Optional(new Choice(['choices' => [true, false], 'strict' => true])),
                'maxAttempts'     => new Optional(new Range(['min' => 1, 'max' => 100])),
                'expectedCode'    => new Optional(new Range(['min' => 200, 'max' => 515])),
                'expectedContent' => new Optional(new Length(['min' => 1, 'max' => 128])),
                'userAgent'       => new Optional(new Length(['min' => 1, 'max' => 128])),
                'metadata'        => new Optional(new All(new Length(['min' => 1, 'max' => 128]))),
                'callbackUrl'     => new Optional(new Url()),
            ]
        ]);
    }

    /**
     * @param $id
     *
     * @return JsonResponse
     */
    public function getAction($id): JsonResponse
    {
        $message = $this->get('webhook.repository')->get($id);

        if (null === $message) {
            return new JsonResponse(['error' => 'Message not found'], 404);
        }

        return new JsonResponse($message);
    }

    /**
     * @return JsonResponse
     */
    public function getLastAction(): JsonResponse
    {
        /** @var WebhookRepository $message */
        $repo = $this->get('webhook.repository');

        return new JsonResponse($repo->getLastWebhooks(50));
    }

    /**
     * @param $data
     *
     * @return Webhook
     */
    private function buildMessage($data): Webhook
    {
        $body = is_array($data['body']) ? json_encode($data['body']) : $data['body'];
        $message = new Webhook($data['url'], $body);

        unset($data['url'], $data['body']);

        if (null !== $data['strategy']) {
            $name = $data['strategy']['name'];  
            $options = $data['strategy']['options'] ?? [];

            $data['strategy'] = StrategyFactory::create($name, $options);
        }

        $accessor = new PropertyAccessor();
        foreach ($data as $k => $v) {
            if (null === $v || '' === $v) {
                continue;
            }

            if ($accessor->isWritable($message, $k)) {
                $accessor->setValue($message, $k, $v);
            }
        }
        return $message;
    }
}
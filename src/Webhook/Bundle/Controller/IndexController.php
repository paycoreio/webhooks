<?php
declare(strict_types=1);


namespace Webhook\Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Validation;
use Webhook\Domain\Infrastructure\Strategy\StrategyFactory;
use Webhook\Domain\Infrastructure\Strategy\StrategyRegistry;
use Webhook\Domain\Model\Message;


/**
 * Class IndexController
 *
 * @package Webhook\Bundle\Controller
 */
class IndexController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     * @Route(path="/message", name="publish_message", methods={"POST"})
     */
    public function indexAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data) || json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Malformed json provided.'], 400);
        }

        $strategiesMap = StrategyRegistry::getMap();
        $strategies = array_keys($strategiesMap);

        $constraints = new Collection([
            'fields' => [
                'body'     => new Required(),
                'url'      => new Url(),
                'strategy' => new Optional(
                    new Collection(
                        [
                            'fields' => [
                                'name'    => new Required(new Choice(['choices' => $strategies, 'strict' => true])),
                                'options' => new Optional(new All(new Length(['min' => 1]))),
                            ]
                        ])
                ),
                'raw'      => new Choice(['choices' => [true, false], 'strict' => true])
            ]
        ]);

        $resolver = new OptionsResolver();

        try {
            $resolver->setDefaults(array(
                'strategy' => null,
                'raw'      => true,
            ))->setDefined(['body', 'url']);


            $data = $resolver->resolve($data);
        } catch (\Throwable $exception) {
            return new JsonResponse(['error' => 'Bad request parameters'], 400);
        }


        $validator = Validation::createValidator();
        $violations = $validator->validate($data, $constraints);

        if (0 !== $violations->count()) {
            $errors = [];
            foreach ($violations as $violation) {
                $field = preg_replace('/\[|\]/', '', $violation->getPropertyPath());
                $error = $violation->getMessage();
                $errors[$field] = $error;
            }

            return new JsonResponse(['errors' => $errors], 400);
        }

        $message = new Message($data['url'], $data['body']);

        if (null !== $data['strategy']) {
            $name = $data['strategy']['name'];
            $options = $data['strategy']['options'] ?? [];

            $strategy = StrategyFactory::create($name, $options);
            $message->setStrategy($strategy);
        }

        $this->get('message.repository')->save($message);

        $this->get('amqp.producer')->publish($message);
        return new JsonResponse(['data' => $message], Response::HTTP_CREATED);
    }

    /**
     * @param $id
     *
     * @return JsonResponse
     * @Route("/message/{id}", methods={"GET"})
     */
    public function getMessage($id)
    {
        $message = $this->get('message.repository')->get($id);

        if (null === $message) {
            return new JsonResponse(['error' => 'Message not found'], 404);
        }

        return new JsonResponse($message);
    }
}
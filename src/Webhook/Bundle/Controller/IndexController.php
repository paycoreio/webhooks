<?php


namespace Webhook\Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Validation;
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

        $strategies = $this->getParameter('strategies.map');
        $constraints = new Collection([
            'fields' => [
                'body'     => new Required(),
                'url'      => new Url(),
                'strategy' => new Choice(['choices' => array_keys($strategies), 'strict' => true]),
                'raw'      => new Choice(['choices' => [true, false], 'strict' => true])
            ]
        ]);

        $validator = Validation::createValidator();
        $result = $validator->validate($data, $constraints);

        if (0 !== $result->count()) {
            dump($result);
            return new JsonResponse(['errors' => ''], 400);
        }

        // build message
        $message = new Message($data['url'], $data['body']);

        if (null !== $data['strategy']) {
            $className = $data['strategy'];
            $strategy = new $className;
            $message->setStrategy($strategy);
        }

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
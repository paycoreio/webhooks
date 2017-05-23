<?php


namespace Webhook\Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Webhook\Bundle\Controller\ParameterBag\StrategyParameterBag;
use Webhook\Bundle\Event\MessageFromApiSentEvent;
use Webhook\Bundle\Events;
use Webhook\Bundle\Service\StrategyFactory;
use Webhook\Domain\Model\Message;
use Webmozart\Assert\Assert;

/**
 * Class IndexController
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

        $validator = $this->get('request.content.validator');
        $result = $validator->validate($data);

        if (!$result->isValid) {
            return new JsonResponse(['error' => $result->errorMessage], 400);
        }

        $bag = new StrategyParameterBag($request->query);
        $event = new MessageFromApiSentEvent($data, $bag);
        $this->get('event_dispatcher')->dispatch(Events::MESSAGE_FROM_API_SENT_EVENT, $event);
        $message = $event->getMessage();

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
            return new JsonResponse('Message not found', 404);
        }

        return new JsonResponse($message);
    }
}
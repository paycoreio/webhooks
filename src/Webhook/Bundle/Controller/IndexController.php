<?php


namespace Webhook\Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Webhook\Domain\Model\Message;


class IndexController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     * @Route("/message", methods={"POST"})
     */
    public function indexAction(Request $request)
    {
        $data = json_encode($request->getContent(), true);

        if (empty($data) || json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Malformed json provided.'], 400);
        }


        // validate
        // save
        $message = new Message('', '');
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
            throw new NotFoundHttpException('Message not found');
        }

        return new JsonResponse($message);
    }
}
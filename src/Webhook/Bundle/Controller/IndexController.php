<?php


namespace Webhook\Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Webhook\Domain\Model\Message;


class IndexController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     * @Route("/", methods={"POST"})
     */
    public function indexAction(Request $request)
    {

        return new Response('Hello');
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
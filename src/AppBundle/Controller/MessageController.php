<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * MessageController
 *
 * @author Kévin Dhénin <dhenin.k@laposte.net>
 */
class MessageController extends Controller
{
    /**
     * @Template("AppBundle:Message:message.html.twig")
     * @Route("/messages", name="messages")
     */
    public function indexAction(){
        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository('AppBundle:Message')->listeMessages();

        return array('messages'=>$messages);
    }

    /**
     * @Route("/message/ajouter", name="message_ajouter", options={"expose"=true}, condition="request.isXmlHttpRequest()")
     */
    public function messageAjouterAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $pseudo = $request->request->get('pseudo');
        $message = $request->request->get('corps');

        $mess = new Message();
        $mess->setPseudo($pseudo);
        $mess->setCorps($message);
        $em->persist($mess);
        $em->flush();
        $messages = $em->getRepository('AppBundle:Message')->listeMessages();
        $template = $this->render('AppBundle:Message:message.detail.html.twig', array('messages' => $messages))->getContent();

        return new JsonResponse($template, JsonResponse::HTTP_OK);

    }

    /**
     * @Route("/message/supprimer/{id}", name="message_supprimer", options={"expose"=true}, condition="request.isXmlHttpRequest()")
     */
    public function supprimerMessageAction(Message $id)
    {
        $em = $this->getDoctrine()->getManager();
        $mess = $em->getRepository('AppBundle:Message')->findOneBy(array('id' => $id));

        if($mess !== null){
            $em->remove($id);
            $em->flush();
            $messages = $em->getRepository('AppBundle:Message')->listeMessages();
            $template = $this->render('AppBundle:Message:message.detail.html.twig', array('messages' => $messages))->getContent();

            return new JsonResponse($template, JsonResponse::HTTP_OK);
        }
    }
}
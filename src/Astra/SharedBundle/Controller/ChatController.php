<?php

namespace Astra\SharedBundle\Controller;

use Astra\SharedBundle\Utils\Values;
use Symfony\Component\HttpFoundation\Request;

class ChatController extends BaseController
{
    function showNewMessagesAction(Request $request)
    {
        $chatService = $this->get('astra.chat.service');
        $id = $request->get('id',0);
        $fromId = $request->get('last',0);
        $reverse = $request->get('reverse',0);
        $messageContainer = $chatService->getMessageContainer($id);

        $result =
            [
                'messages' => '',
                'users' => '',
            ];

        if(!$messageContainer)
        {
            $this->json($result);
        }

        $users = $chatService->getUsersForChat($messageContainer);
        $messages = $chatService->getNewMessages($messageContainer,$fromId,$this->getUser(),50,$reverse);

        $template = $request->get('template',null);
        if($template)
        {
            return $this->render($template,['messages'=>$messages,'currentUser'=>$this->getUser()]);
        }

        $result =
            [
                'messages' => $this->render('AstraSharedBundle:Chat:_messageList.html.twig',['messages'=>$messages,'currentUser'=>$this->getUser()])->getContent(),
                'users' => $this->render('AstraSharedBundle:Chat:_userList.html.twig',['users'=>$users])->getContent(),
            ];


        return $this->json($result);
    }

    function postMessageAction(Request $request)
    {
        $result =
            [
                'success'=>false,
            ];

        $chatService = $this->get('astra.chat.service');
        $id = $request->get('id',0);
        $messageContainer = $chatService->getMessageContainer($id);

        if(!$messageContainer)
        {
            return $this->json($result);
        }

        $message = Values::getString($request->get('message',''));

        if(empty($message))
        {
            return $this->json($result);
        }

        $chatService->createMessage($message, $request->get('files', []), $messageContainer, $this->getUser());

        $result['success'] = true;
        return $this->json($result);
    }
}

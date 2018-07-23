<?php

namespace Astra\SharedBundle\Controller;
use Astra\SharedBundle\Entity\User;
use Astra\SharedBundle\Services\SharedVariableService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ContactController extends BaseController
{
    public function quickAddMessageAction(Request $request)
    {

    }

    public function addQuickContactListAction(Request $request)
    {
        $contactService = $this->get('astra.contact.service');
        $userId = $request->get('userId',0);
        if(!$userId)return $this->redirectToRoute('homepage');
        $user = $this->getEm()->getRepository('AstraSharedBundle:User')->find($userId);
        if(!$user)return $this->redirectToRoute('homepage');

        if($user === $this->getUser())
        {
            return $this->redirectToRoute('astra_myprofile_view');
        }

        $allLists = $contactService->getContactListForUsers([$this->getUser(),$user]);
        if(empty($allLists))
        {
            $list = $contactService->addContactList([$this->getUser(),$user]);
        }
        else
        {
            $list = reset($allLists);
        }

        return $this->redirectToRoute('astra_contact_view_chat',['chatId'=>$list->getId()]);
    }

    public function viewChatAction(Request $request)
    {
        $sharedVariable = $this->get('astra.shared_variable.service');
        $chatId = $request->get('chatId',0);
        if(!$chatId)throw new NotFoundHttpException();

        $contactList = $this->getEm()->getRepository('AstraSharedBundle:ContactList')->find($chatId);
        $activeContact = $contactList->getUserContact($this->getUser());
        if(!$activeContact)throw new NotFoundHttpException();

        $sharedVariable->set(SharedVariableService::NAME_PRIVATE_MESSAGE,$contactList);

        return $this->render('AstraSharedBundle:Contact:chat.html.twig',
            ['contactList'=>$contactList, 'activeContact'=>$activeContact,'messageContainer'=>$contactList->getMessageContainer()]);
    }

    public function contactsListAction(Request $request)
    {
        return $this->render('AstraSharedBundle:Contact:index.html.twig');
    }

    public function contactsListAjaxAction(Request $request)
    {
        $contactService = $this->get('astra.contact.service');
        $contacts = $contactService->getContactListForUser($this->getUser(),$request->get('search'));

        return $this->render('AstraSharedBundle:Contact:_index_contact.html.twig',['contacts'=>$contacts]);
    }

    public function usersListAjaxAction(Request $request)
    {
        $contactService = $this->get('astra.contact.service');
        $serarchService = $this->get('astra.search.service');
        $search = $request->get('search',null);
        $users = [];
        $contactList = [];
        if(!empty($search))
        {
            /** @var User[] $users */
            $users = $serarchService->SearchUsers($search,50,[$this->getUser()]);
            foreach ($users as $user)
            {
                $contactList[$user->getId()] = $contactService->getContactListForUsers([$this->getUser(),$user]);
            }
        }
        return $this->render('AstraSharedBundle:Contact:_index_users.html.twig',['users'=>$users,'contactList'=>$contactList]);
    }

    public function allMessagesListAction(Request $request)
    {
        return $this->render('AstraSharedBundle:Contact:all_messages_list.html.twig', []);
    }

    public function allMessagesListAjaxAction(Request $request)
    {
        $chatService = $this->get('astra.chat.service');
        $fromId = $request->get('last',0);
        $reverse = $request->get('reverse',0);

        $messages = $chatService->getAllMessagesUser($this->getUser(),$fromId,50,$reverse);
        $result =
            [
                'messages' => $this->render('AstraSharedBundle:Contact:_messageList.html.twig',['messages'=>$messages,'currentUser'=>$this->getUser()])->getContent(),
                'users' => '',
            ];
        return $this->json($result);
    }
}

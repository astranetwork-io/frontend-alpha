<?php
namespace Astra\SharedBundle\Services;
use Astra\SharedBundle\Entity\Contact;
use Astra\SharedBundle\Entity\ContactList;
use Astra\SharedBundle\Entity\MessageContainer;
use Astra\SharedBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class ContactsService
{
    private $entityManager;
    private $chatService;

    public function __construct(EntityManager $entityManager, ChatService $chatService)
    {
        $this->entityManager = $entityManager;
        $this->chatService = $chatService;
    }

    /**
     * @param User $users
     * @param string $request
     * @return Contact[]
     */
    public function getContactListForUser(User $users, $request = '')
    {
        return $this->entityManager->getRepository('AstraSharedBundle:Contact')->getForUser($users,$request);
    }

    /**
     * @param array $users
     * @return ContactList[]
     */
    public function getContactListForUsers(array $users)
    {
        return $this->entityManager->getRepository('AstraSharedBundle:ContactList')->getForUserList($users,true);
    }

    /**
     * @param array $users
     * @return ContactList
     */
    public function addContactList(array $users)
    {
        $contacList = new ContactList();
        $messageContainer = new MessageContainer();
        $messageContainer->setType(MessageContainer::TYPE_CONTACT);
        $contacList->setMessageContainer($messageContainer);
        $this->entityManager->persist($contacList);
        $this->entityManager->persist($messageContainer);
        $this->entityManager->flush();

        foreach ($users as $user)
        {
            $this->addUserToContactList($contacList,$user);
        }
        $this->entityManager->flush();
        return $contacList;
    }

    public function addUserToContactList(ContactList $contactList,$user)
    {
        foreach ($contactList->getContacts() as $contact)
        {
            if($contact->getUser() === $user) return;
        }

        $newContact = new Contact();
        $newContact->setUser($user);
        $newContact->setName('');
        $newContact->setContactList($contactList);
        $this->entityManager->persist($newContact);
        $contactList->getMessageContainer()->addUser($user);
    }

}
<?php
namespace App\EventListener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Doctrine\ORM\EntityManagerInterface;

class LoginListener
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if (method_exists($user, 'setLastLogin')) {
            $user->setLastLogin(new \DateTimeImmutable());
            $this->em->persist($user);
            $this->em->flush();
        }
    }
}

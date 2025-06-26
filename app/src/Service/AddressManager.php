<?php

namespace App\Service;

use App\Entity\Adress;
use Doctrine\ORM\EntityManagerInterface;

class AddressManager
{
private EntityManagerInterface $em;

public function __construct(EntityManagerInterface $em)
{
$this->em = $em;
}

public function setFavoriteAddress(Adress $address): void
{
$user = $address->getUsers();

foreach ($user->getAdress() as $addr) {
$addr->setIsFavorite($addr === $address);
}

$this->em->flush();
}
}

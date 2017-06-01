<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MessageRepository
 * @author Kévin Dhénin <dhenin.k@laposte.net>
 */
class MessageRepository extends EntityRepository
{
    public function listeMessages() {

        $qb = $this->_em->createQueryBuilder();
        $qb->select('messages')
            ->from('AppBundle:Message', 'messages');

        return $qb->getQuery()->getArrayResult();
    }
}

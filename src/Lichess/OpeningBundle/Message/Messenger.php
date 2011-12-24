<?php

namespace Lichess\OpeningBundle\Message;

use Lichess\OpeningBundle\Document\MessageRepository;
use Lichess\OpeningBundle\Sync\Memory;
use Lichess\OpeningBundle\Document\Message;
use Application\UserBundle\Document\User;

class Messenger
{
    public function __construct(MessageRepository $repository, Memory $memory)
    {
        $this->repository = $repository;
        $this->memory = $memory;
    }

    public function send($user, $text)
    {
        $message = new Message($user, $text);

        if ($message->isValid() && !$this->isSpam($message)) {
            $this->repository->add($message);
        }

		return $message;
    }

    public function isSpam(Message $message)
    {
        $lastMessage = $this->repository->findLastOne();

        if ($message->isLike($lastMessage)) {
            return true;
        }
    }
}

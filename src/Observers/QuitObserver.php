<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

declare(strict_types=1);
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Core\Observers;

use Evenement\EventEmitterInterface;
use Psr\Log\LoggerInterface;
use WildPHP\Core\Entities\IrcUser;
use WildPHP\Core\Events\IncomingIrcMessageEvent;
use WildPHP\Core\Storage\IrcUserChannelRelationStorageInterface;
use WildPHP\Core\Storage\IrcUserStorageInterface;
use WildPHP\Messages\Quit;

class QuitObserver
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var IrcUserStorageInterface
     */
    private $userStorage;

    /**
     * @var IrcUserChannelRelationStorageInterface
     */
    private $relationStorage;

    /**
     * BaseModule constructor.
     *
     * @param EventEmitterInterface $eventEmitter
     * @param LoggerInterface $logger
     * @param IrcUserStorageInterface $userStorage
     * @param IrcUserChannelRelationStorageInterface $relationStorage
     */
    public function __construct(
        EventEmitterInterface $eventEmitter,
        LoggerInterface $logger,
        IrcUserStorageInterface $userStorage,
        IrcUserChannelRelationStorageInterface $relationStorage
    ) {
        $eventEmitter->on('irc.msg.in.quit', [$this, 'processUserQuit']);

        $this->logger = $logger;
        $this->userStorage = $userStorage;
        $this->relationStorage = $relationStorage;
    }

    /**
     * @param IncomingIrcMessageEvent $ircMessageEvent
     */
    public function processUserQuit(IncomingIrcMessageEvent $ircMessageEvent): void
    {
        /** @var Quit $quitMessage */
        $quitMessage = $ircMessageEvent->getIncomingMessage();

        /** @var IrcUser $user */
        $user = $this->userStorage->getOneByNickname($quitMessage->getNickname());

        foreach ($this->relationStorage->getByUserId($user->getUserId()) as $relation) {
            $this->relationStorage->delete($relation);
        }

        $this->logger->debug('Removed all user-channel relationships', [
            'reason' => 'quit',
            'nickname' => $quitMessage->getNickname()
        ]);
    }
}

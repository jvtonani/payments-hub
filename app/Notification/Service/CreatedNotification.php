<?php

namespace App\Notification\Service;

use App\Notification\Service\NotificationChannel\EmailNotification;
use App\Notification\Model\User as UserModel;
use Psr\Log\LoggerInterface;

class CreatedNotification implements NotificationStrategyInterface
{
    private const MESSAGE = 'Sua transferência foi criada. Você será notificado quando for finalizada';
    public function __construct(
        private UserModel $userModel,
        private EmailNotification $emailNotification,
        private LoggerInterface $logger
    )
    {}
    public function notify(array $data): void
    {
        $this->logger->info('Transação Criada - Inicio da notificação para o payer', $data);
        $payer = $this->userModel->findById($data['payer_id']);
        $this->emailNotification->send($payer['email'], self::MESSAGE);
    }
}
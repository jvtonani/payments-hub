<?php

namespace App\Notification\Service;

use App\Notification\Service\NotificationChannel\EmailNotification;
use App\Notification\Model\User as UserModel;
use Psr\Log\LoggerInterface;
class FinishedNotification implements NotificationStrategyInterface
{
    private const MESSAGE_PAYER = 'Sua transferência não foi finalizada. Tente novamente.';
    private const MESSAGE_PAYEE = 'Você recebeu uma transferência';

    public function __construct(
        private UserModel $userModel,
        private EmailNotification $emailNotification,
        private LoggerInterface $logger
    )
    {}
    public function notify(array $data): void
    {
        $this->logger->info('Transferência Finaliza - Inicio da notificação para o payer e payee', $data);

        $payer = $this->userModel->findById($data['payer_id']);
        $payee = $this->userModel->findById($data['payee_id']);

        $this->emailNotification->send($payer['email'], self::MESSAGE_PAYER);
        $this->emailNotification->send($payee['email'], self::MESSAGE_PAYEE);
    }
}
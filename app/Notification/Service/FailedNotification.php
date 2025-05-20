<?php

namespace App\Notification\Service;

use App\Notification\Service\NotificationChannel\EmailNotification;
use App\Notification\Model\User as UserModel;
use App\Notification\Service\NotificationChannel\SmsNotification;
use Psr\Log\LoggerInterface;

class FailedNotification implements NotificationStrategyInterface
{
    private const MESSAGE = 'Sua transferência não foi finalizada. Tente novamente.';
    public function __construct(
        private UserModel $userModel,
        private SmsNotification $smsNotification,
        private LoggerInterface $logger
    )
    {}
    public function notify(array $data): void
    {
        $this->logger->info('Transferência Falhou - Inicio da notificação para o payer', $data);
        $payer = $this->userModel->findById($data['payer_id']);
        $this->smsNotification->send($payer['cellphone'], self::MESSAGE);
    }
}
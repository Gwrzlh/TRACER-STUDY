<?php

namespace App\Libraries;

use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client;

class BrevoMailer
{
    private $apiInstance;

    public function __construct()
    {
        $config = Configuration::getDefaultConfiguration()
            ->setApiKey('api-key', getenv('BREVO_API_KEY'));

        $this->apiInstance = new TransactionalEmailsApi(
            new Client(),
            $config
        );
    }

    public function sendEmail($toEmail, $toName, $subject, $htmlContent)
    {
        try {
            $sendSmtpEmail = new SendSmtpEmail([
                'subject' => $subject,
                'sender' => ['name' => 'Tracer Study', 'email' => 'tspolban@gmail.com'], // sementara Gmail
                'to' => [['email' => $toEmail, 'name' => $toName]],
                'htmlContent' => $htmlContent,
            ]);

            $result = $this->apiInstance->sendTransacEmail($sendSmtpEmail);

            // Debug: tulis log detail response Brevo
            log_message('debug', 'Brevo send email response: ' . print_r($result, true));

            // Jika ada messageId, anggap berhasil
            if (isset($result['messageId']) || (isset($result->getMessageId) && $result->getMessageId())) {
                return true;
            } else {
                log_message('error', 'Brevo: Response tidak valid: ' . print_r($result, true));
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'Brevo email gagal: ' . $e->getMessage());
            return false;
        }
    }
}

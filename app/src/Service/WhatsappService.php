<?php
// Localização: src/Service/WhatsappService.php
namespace App\Service;

use Twilio\Rest\Client;
use Cake\Log\Log;
use Exception;

class WhatsappService
{
    /**
     * @var \Twilio\Rest\Client|null
     */
    private $client;

    /**
     * @var string
     */
    private $twilioPhone;

    /**
     * @var string|null
     */
    private $sid;

    /**
     * @var string|null
     */
    private $token;

    public function __construct()
    {
        // Lê as credenciais do seu arquivo .env
        $this->sid = env('TWILIO_SID', null);
        $this->token = env('TWILIO_TOKEN', null);

        // Número do WhatsApp Sandbox da Twilio
        $this->twilioPhone = 'whatsapp:+14155238886'; 

        if (!$this->sid || !$this->token) {
            Log::error('As credenciais da Twilio (TWILIO_SID ou TWILIO_TOKEN) não foram definidas no arquivo .env');
            return;
        }

        $this->client = new Client($this->sid, $this->token);
    }

    /**
     * Envia uma Mensagem de Template pré-aprovada.
     *
     * @param string $to Número do destinatário (ex: +5511999998888)
     * @param string $contentSid O ID do seu template (ex: HXb5b6...)
     * @param array $variables As variáveis para preencher o template (ex: ['1' => 'Gustavo', '2' => '15:30'])
     * @return bool
     */
    public function sendTemplate(string $to, string $contentSid, array $variables = []): bool
    {
        if (!$this->client) {
            return false;
        }
        
        try {
            $contentVariables = json_encode($variables);

            $this->client->messages->create(
                "whatsapp:" . $to,
                [
                    "from" => $this->twilioPhone,
                    "contentSid" => $contentSid,
                    "contentVariables" => $contentVariables,
                ]
            );
            return true;
        } catch (Exception $e) {
            Log::error('Erro ao enviar Template do WhatsApp via Twilio: ' . $e->getMessage());
            return false;
        }
    }
}
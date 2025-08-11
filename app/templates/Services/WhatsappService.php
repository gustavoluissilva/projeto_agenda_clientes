<?php
// Localização: src/Service/WhatsappService.php
namespace App\Service;

use Twilio\Rest\Client;
use Cake\Log\Log;

class WhatsappService
{
    private $client;
    private $twilioPhone;

   public function __construct()
{
    // Lê as credenciais do arquivo .env de forma segura
    $this->sid = env('TWILIO_SID', null);
    $this->token = env('TWILIO_TOKEN', null);

    $this->twilioPhone = 'whatsapp:+14155238886'; // Número do WhatsApp Sandbox da Twilio

    if (!$this->sid || !$this->token) {
        // Lança um erro ou loga se as chaves não forem encontradas
        throw new \Exception('As credenciais da Twilio não foram definidas no arquivo .env');
    }

    $this->client = new Client($this->sid, $this->token);
}

    /**
     * Envia uma mensagem de texto simples.
     * Útil para responder a um cliente que já iniciou contato.
     *
     * @param string $to Número do destinatário (ex: +5511999998888)
     * @param string $message A mensagem a ser enviada.
     * @return bool
     */
    public function sendMessage(string $to, string $message): bool
    {
        try {
            // O número do cliente precisa incluir o código do país
            $formattedTo = 'whatsapp:' . $to;

            $this->client->messages->create(
                $formattedTo,
                [
                    'from' => $this->twilioPhone,
                    'body' => $message
                ]
            );
            return true;
        } catch (\Exception $e) {
            // Se der erro, registra a mensagem de erro no log do CakePHP
            // Você pode ver os logs na pasta /logs/error.log
            Log::error('Erro ao enviar WhatsApp via Twilio: ' . $e->getMessage());
            return false;
        }
    }
    /**
     * Envia uma Mensagem de Template pré-aprovada.
     * É o método correto para iniciar uma notificação.
     *
     * @param string $to Número do destinatário (ex: +5511999998888)
     * @param string $contentSid O ID do seu template (ex: HXb5b6...)
     * @param array $variables As variáveis para preencher o template (ex: ['1' => 'Gustavo', '2' => '15:30'])
     * @return bool
     */
    public function sendTemplate(string $to, string $contentSid, array $variables = []): bool
    {
        try {
            // Converte o array de variáveis para o formato JSON que a Twilio espera
            $contentVariables = json_encode($variables);

            $this->client->messages->create(
                "whatsapp:" . $to, // to
                [
                    "from" => $this->twilioPhone,
                    "contentSid" => $contentSid,
                    // Envia as variáveis para preencher os campos {{1}}, {{2}}, etc. do template
                    "contentVariables" => $contentVariables,
                ]
            );

            return true;
        } catch (\Exception $e) {
            Log::error('Erro ao enviar Template do WhatsApp: ' . $e->getMessage());
            return false;
        }
    }
}

<?php
// Localização: src/Service/WhatsappService.php
namespace App\Service;

use Twilio\Rest\Client;
use Cake\Log\Log;
use Exception; // É uma boa prática importar a classe Exception

class WhatsappService
{
    /**
     * O cliente da API da Twilio.
     * @var \Twilio\Rest\Client|null
     */
    private $client;

    /**
     * O número de telefone da Twilio (remetente).
     * @var string
     */
    private $twilioPhone;

    /**
     * A credencial SID da conta Twilio.
     * @var string|null
     */
    private $sid;

    /**
     * A credencial Token da conta Twilio.
     * @var string|null
     */
    private $token;

    /**
     * O construtor é chamado quando a classe é criada.
     * Ele lê as credenciais e prepara o cliente da API.
     */
    public function __construct()
    {
        // Lê as credenciais do seu arquivo .env de forma segura
        $this->sid = env('TWILIO_SID', null);
        $this->token = env('TWILIO_TOKEN', null);

        $this->twilioPhone = 'whatsapp:+14155238886'; // Número do WhatsApp Sandbox da Twilio

        // Se uma das chaves não for encontrada, o serviço não pode funcionar.
        if (!$this->sid || !$this->token) {
            // Loga um erro para o desenvolvedor ver, sem quebrar o site para o usuário.
            Log::error('As credenciais da Twilio (TWILIO_SID ou TWILIO_TOKEN) não foram definidas no arquivo .env');
            return; // Interrompe a construção do objeto para evitar mais erros.
        }

        // Se as chaves existem, cria o cliente da API
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
        // Se o cliente não pôde ser criado no construtor, não tenta enviar.
        if (!$this->client) {
            return false;
        }

        try {
            $this->client->messages->create(
                'whatsapp:' . $to,
                [
                    'from' => $this->twilioPhone,
                    'body' => $message
                ]
            );
            return true;
        } catch (Exception $e) {
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
        // Se o cliente não pôde ser criado no construtor, não tenta enviar.
        if (!$this->client) {
            return false;
        }
        
        try {
            $contentVariables = json_encode($variables);

            $this->client->messages->create(
                "whatsapp:" . $to, // to
                [
                    "from" => $this->twilioPhone,
                    "contentSid" => $contentSid,
                    "contentVariables" => $contentVariables,
                ]
            );
            return true;
        } catch (Exception $e) {
            Log::error('Erro ao enviar Template do WhatsApp: ' . $e->getMessage());
            return false;
        }
    }
}
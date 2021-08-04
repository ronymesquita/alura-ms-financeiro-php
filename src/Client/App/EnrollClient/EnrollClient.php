<?php

namespace Alura\Financeiro\Client\App\EnrollClient;

use Alura\Financeiro\Client\Domain\Card\CardExpirationDate;
use Alura\Financeiro\Client\Domain\Card\CardInformation;
use Alura\Financeiro\Client\Domain\Card\CardNumber;
use Alura\Financeiro\Client\Domain\Card\OwnerFullName;
use Alura\Financeiro\Client\Domain\Card\SecurityCode;
use Alura\Financeiro\Client\Domain\Client;
use Alura\Financeiro\Client\Domain\ClientRepository;
use Alura\Financeiro\Client\Domain\Document;
use Alura\Financeiro\Shared\App\MessagingQueue;

class EnrollClient
{
    public function __construct(private ClientRepository $clientRepository, private MessagingQueue $messagingQueue)
    {
    }

    public function __invoke(EnrollClientInputData $data): void
    {
        $document = new Document($data->clientDocument);
        $cardInfo = new CardInformation(
            new OwnerFullName($data->cardOwnerFullName),
            new CardNumber($data->cardNumber),
            new CardExpirationDate($data->cardExpirationMonth, $data->cardExpirationYear),
            new SecurityCode($data->cardSecurityCode),
        );
        $client = new Client($document, $cardInfo);
        // Pagamento em si

        $this->clientRepository->add($client);

        $this->messagingQueue->send('client_enrolled', 'alura-ms');
    }
}

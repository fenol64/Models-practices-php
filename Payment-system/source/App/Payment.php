<?php


namespace source\App;
use PagarMe\Client;
use Source\Models\Cards;
use Source\Models\Transactions;

class Payment
{
    private $api_key;
    private $data;
    private $view;
    private $pagarme;

    public function __construct()
    {
        $this->api_key = PAGARME_API_KEY;
        $this->pagarme = new Client($this->api_key);
    }

    public function receivedata($data)
    {
        $this->data = [
            "payment_type" => "credit_card",
            "holder_name" => $data["holder_name"],
            "card_number" => $data["card_number"],
            "expiration_date" => $data["expiration_date"],
            "cvv" => intval($data["cvv"]),
            "amount" => $data["amount"]
        ];
        $this->createCard();
    }

    public function registerCard($card)
    {
        $cards = new Cards();
        $cards->hash = $card->id;
        $cards->brand = $card->brand;
        $cards->last_digits = $card->last_digits;
        if ($cards->save()){
            echo "Cartão salvo com sucesso!";
        }
    }

    public function registerTransaction(string $status, string $amount, string $hash)
    {
        $transactions = new Transactions();
        $transactions->name_user = $this->data["holder_name"];
        $transactions->payment_method = $this->data["payment_type"];
        $transactions->amount = $amount;
        $transactions->status = $status;
        $transactions->id_card = $hash;
        if ($transactions->save()){
            echo "Transação salvo com sucesso!";
        }
    }

    private function createCard()
    {
        $getCreditCard = $this->pagarme->cards()->create([
            'holder_name' => $this->data["holder_name"],
            'number' => $this->data["card_number"],
            'expiration_date' => $this->data["expiration_date"],
            'cvv' => $this->data["cvv"]
        ]);
        if ($getCreditCard->valid == true){
            $this->registerCard($getCreditCard);
            $this->withCard($this->data["amount"], $getCreditCard->id);
        }else{
            echo "Cartão inválido";
        }
    }

    private function withCard(string $amount, string $hash)
    {
        $value = explode("R$", $amount);
        $transaction = $this->pagarme->transactions()->create([
            "payment_method"  => $this->data["payment_type"],
            "amount" => (intval($value[1]) * 100),
            "card_id" => $hash
        ]);
        if ($transaction->status == "paid") {
            $this->registerTransaction($transaction->status, $amount, $hash);
        }
    }
}
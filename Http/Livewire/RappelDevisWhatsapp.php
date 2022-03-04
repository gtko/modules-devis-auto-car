<?php

namespace Modules\DevisAutoCar\Http\Livewire;

use Livewire\Component;
use Modules\CoreCRM\Actions\Devis\GenerateLinkDevis;

class RappelDevisWhatsapp extends Component
{
    public $devis;

    public function mount($devis)
    {
        $this->devis = $devis;
    }

    public function render()
    {
        $this->link = (new GenerateLinkDevis())->GenerateLink($this->devis);
        $phone = $this->devis->dossier->client->personne->phones->first()->phone;
        $phone = substr($phone, 1);
        $phone = '33' . $phone;
        $text = "Votre conseillé Centrale Autocar a essayé de vous joindre vous pouvais le rapeller au " . ' ' . $this->devis->commercial->phone;

        $lien = 'https://api.whatsapp.com/send?phone=' . $phone . '&text=' . $text;


        return view('devisautocar::livewire.rappel-devis-whatsapp',
            [
                'lien' => $lien
            ]
        );
    }
}


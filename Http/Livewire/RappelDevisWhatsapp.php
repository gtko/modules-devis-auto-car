<?php

namespace Modules\DevisAutoCar\Http\Livewire;

use Livewire\Component;
use Modules\CoreCRM\Actions\Devis\GenerateLinkDevis;

class RappelDevisWhatsapp extends Component
{
    public $dossier;

    public function mount($dossier)
    {
        $this->dossier = $dossier;
    }

    public function send()
    {
        dd('ss');
        $this->emit('refreshTimeline');
    }

    public function render()
    {
        $phone = $this->dossier->client->personne->phones->first()->phone;
        $phone = substr($phone, 1);
        $phone = '33' . $phone;
        $text = "Votre conseillé Centrale Autocar a essayé de vous joindre vous pouvais le rapeller au " . ' ' . $this->dossier->commercial->phone;

        $lien = 'https://api.whatsapp.com/send?phone=' . $phone . '&text=' . $text;


        return view('devisautocar::livewire.rappel-devis-whatsapp',
            [
                'lien' => $lien
            ]
        );
    }
}


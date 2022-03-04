<?php

namespace Modules\DevisAutoCar\Http\Livewire;

use Livewire\Component;
use Modules\CoreCRM\Actions\Devis\GenerateLinkDevis;

class SendDevisWhatapps extends Component
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

        $text = "Je vous remercie de l'intérêt que vous portez à Centrale Autocar, votre complice pour tous vos transferts. Retrouvez votre devis en ligne en cliquant sur le liens ci-joint";
        $message = $text . ' ' . $this->link;

        $lien = 'https://api.whatsapp.com/send?phone=' . $phone . '&text=' . $message;



        return view('devisautocar::livewire.send-devis-whatapps',
            ['lien' => $lien]
        );
    }
}
<?php

namespace Modules\DevisAutoCar\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\CallCRM\Flow\Attributes\ClientDossierAppelCreate;
use Modules\CoreCRM\Actions\Devis\GenerateLinkDevis;
use Modules\CoreCRM\Contracts\Services\FlowContract;
use Modules\CoreCRM\Flow\Attributes\ClientDossierCreate;
use Modules\CoreCRM\Services\FlowCRM;
use Modules\CrmAutoCar\Flow\Attributes\ClientDossierDemandeFournisseurDelete;
use Modules\CrmAutoCar\Flow\Attributes\ClientDossierRappelWhatsapp;

class RappelDevisWhatsapp extends Component
{
    public $dossier;

    public function mount($dossier)
    {
        $this->dossier = $dossier;
    }

    public function send()
    {
        (new FlowCRM())->add($this->dossier, new ClientDossierRappelWhatsapp($this->dossier, Auth::user()));
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


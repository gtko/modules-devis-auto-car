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
        $phone = $this->dossier->client->personne->phones->first()->phone ?? '';
        $phone = substr($phone, 1);
        $phone = '33' . $phone;

        $signature = $this->dossier->commercial->format_nom ."\n" . $this->dossier->commercial->email."%0D%0A" . $this->dossier->commercial->phone;
        $text = "
Bienvenue chez Centrale Autocar %0D%0A

J'ai bien reçu votre recherche de transfert en autocar, cependant je n'ai pas réussi à vous joindre afin d’affiner votre projet. %0D%0A

A quel moment pouvons nous fixer ensemble un RDV téléphonique? %0D%0A

D'ici là, je vous invite à me faire part de vos critères par whatsapp  afin que je puisse avancer dans mes recherches : %0D%0A

Je me ferai un plaisir de vous renseigner.  %0D%0A"

. $signature;

        $lien = 'https://api.whatsapp.com/send?phone=' . $phone . '&text=' . $text;


        return view('devisautocar::livewire.rappel-devis-whatsapp',
            [
                'lien' => $lien
            ]
        );
    }
}

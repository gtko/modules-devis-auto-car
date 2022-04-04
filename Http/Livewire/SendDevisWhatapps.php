<?php

namespace Modules\DevisAutoCar\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\CoreCRM\Actions\Devis\GenerateLinkDevis;
use Modules\CoreCRM\Contracts\Repositories\DevisRepositoryContract;
use Modules\CoreCRM\Services\FlowCRM;
use Modules\CrmAutoCar\Flow\Attributes\ClientDossierDevisSendWhatsapp;
use Modules\CrmAutoCar\Flow\Attributes\ClientDossierRappelWhatsapp;

class SendDevisWhatapps extends Component
{
    public $devis;

    public function mount($devis)
    {
        $this->devis = $devis;
    }


    public function send($devis)
    {
        $devis = app(DevisRepositoryContract::class)->fetchById($devis['id']);

        (new FlowCRM())->add($devis->dossier, new ClientDossierDevisSendWhatsapp(Auth::user(), $devis));
        $this->emit('refreshTimeline');
    }

    public function render()
    {
        $this->link = (new GenerateLinkDevis())->GenerateLink($this->devis);
        $phone = $this->devis->dossier->client->personne->phones->first()->phone ?? '';
        $phone = substr($phone, 1);
        $phone = '33' . $phone;

        $text = "Je vous remercie de l'intérêt que vous portez à Centrale Autocar, votre complice pour tous vos transferts.\n\nRetrouvez votre devis en ligne en cliquant sur le liens ci-joint";
        $message = $text . "\n" . $this->link;

        $lien = 'https://api.whatsapp.com/send?phone=' . $phone . '&text=' .urlencode($message);

        return view('devisautocar::livewire.send-devis-whatapps',
            ['lien' => $lien]
        );
    }
}

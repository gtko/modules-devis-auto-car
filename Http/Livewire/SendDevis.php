<?php

namespace Modules\DevisAutoCar\Http\Livewire;

use Livewire\Component;
use Modules\CoreCRM\Contracts\Services\FlowContract;
use Modules\CrmAutoCar\Flow\Attributes\DevisSendClient;

class SendDevis extends Component
{

    public $devis;

    public function mount($devis){

        $this->devis = $devis;

    }

    public function send(){
        $flowable = $this->devis->dossier;
        app(FlowContract::class)->add($flowable, (new DevisSendClient($this->devis)));
    }

    public function render()
    {
        return view('devisautocar::livewire.send-devis');
    }
}

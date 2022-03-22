<?php

namespace Modules\DevisAutoCar\Http\Livewire;

use Livewire\Component;
use Modules\CoreCRM\Contracts\Entities\DevisEntities;
use Modules\CoreCRM\Contracts\Services\FlowContract;
use Modules\CoreCRM\Flow\Works\Actions\ActionsSendNotification;
use Modules\CoreCRM\Flow\Works\WorkflowKernel;
use Modules\CrmAutoCar\Flow\Attributes\DevisSendClient;
use Modules\CrmAutoCar\Flow\Attributes\DevisSendManualClient;
use Modules\CrmAutoCar\Models\Dossier;

class SendDevis extends Component
{
    public $devis;



    public function mount($devis){
        $this->devis = $devis;
    }

    public function getListeners()
    {
        return [
            'senddevis:confirm_'.$this->devis->id => 'confirm'
        ];
    }

    public function send(){
        $flowable = $this->devis->dossier;
        $this->emit('send-mail:open', [
            'flowable' => [Dossier::class, $this->devis->dossier->id],
            'observable' => [
                [
                    DevisSendManualClient::class,
                    [
                        'devis_id' => $this->devis->id
                    ]
                ]
            ],
            'callback' => 'senddevis:confirm_'.$this->devis->id
        ]);
    }

    public function confirm($data){
        $flowable = $this->devis->dossier;

        app(FlowContract::class)->add($flowable, (new DevisSendClient($this->devis)));
        session()->flash('success', 'Devis envoyé au client');
        return redirect()->route('dossiers.show', [$flowable->client, $flowable, $this->devis]);
    }

    public function render()
    {
        return view('devisautocar::livewire.send-devis');
    }
}

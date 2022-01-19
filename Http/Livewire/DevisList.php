<?php

namespace Modules\DevisAutoCar\Http\Livewire;

use Livewire\Component;
use Modules\CoreCRM\Contracts\Entities\ClientEntity;
use Modules\CoreCRM\Contracts\Repositories\DevisRepositoryContract;
use Modules\CoreCRM\Models\Dossier;
use Modules\CoreCRM\Repositories\DevisRepository;
use Modules\CrmAutoCar\Models\Brand;

class DevisList extends Component
{

    public $dossier = null;
    public $client = null;

    protected $listeners = ['refreshDevi' => '$refresh'];

    public function mount( ClientEntity $client, Dossier $dossier, $devis){
        $this->client = $client;
        $this->dossier = $dossier;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $devisRep = app(DevisRepositoryContract::class);

        return view('devisautocar::livewire.devis-list', [
            'brands' => Brand::all(),
            'client' => $this->client,
            'devis' => $devisRep->getDevisByDossier( $this->dossier)
        ]);
    }
}

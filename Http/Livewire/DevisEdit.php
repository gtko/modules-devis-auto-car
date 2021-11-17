<?php

namespace Modules\DevisAutoCar\Http\Livewire;

use Illuminate\Support\Str;
use Modules\CoreCRM\Contracts\Entities\DevisEntities;
use Modules\CoreCRM\Contracts\Repositories\DevisRepositoryContract;
use Livewire\Component;
use Modules\CoreCRM\Models\Fournisseur;
use Modules\CrmAutoCar\Contracts\Service\DistanceApiContract;
use Modules\CrmAutoCar\Models\Brand;

class DevisEdit extends Component
{

    public DevisEntities $devis;
    public $data = ['trajets' => []];

    protected array $rules = [
        'data.*' => '',
        'data.trajets' => '',
        'devis.tva_applicable' => ''
    ];

    protected $listeners = [
        'devis:update' => "updateTrajetChild"
    ];

    public function mount(DevisEntities $devis){
        $this->devis = $devis;
        $this->data = $this->devis->data ?? false;

        if(!$this->data || !($this->data['trajets'] ?? false)){
            $this->data = ['trajets' => []];
            $this->addTrajet();
        }

    }

    public function store(DevisRepositoryContract $deviRep){
        if(!$this->devis->invoice()->exists())
        {
            $deviRep->updateData($this->devis, $this->data);

            session()->flash('success', __('basecore::crud.common.saved'));

            return redirect()
                ->route('dossiers.show', [$this->devis->dossier->client, $this->devis->dossier]);
        }else{
            session()->flash('danger', "Une facture a été émise et je devis n'est plus modifiable.");

            return redirect()
                ->route('dossiers.show', [$this->devis->dossier->client, $this->devis->dossier]);
        }
    }

    public function updateTrajetChild($data){
        $this->data['trajets'][$data['id']] = $data['trajet'];
    }

    public function addTrajet()
    {
        $this->data['trajets'][] = [
            'inclus_repas_chauffeur' => false,
            'inclus_hebergement' => false,
            'inclus_parking' => false,
            'inclus_peages' => true,
            'non_inclus_repas_chauffeur' => true,
            'non_inclus_hebergement' => true,
            'non_inclus_parking' => true,
            'non_inclus_peages' => false,
        ];

        $this->emit('devis:trajet-open', ['id' => count($this->data['trajets']) - 1]);
    }

    public function removeTrajet($trajet)
    {
        unset($this->data['trajets'][$trajet]);
    }

    /**
     * Get the views / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {

        $invoice_exists = $this->devis->invoice()->exists();

        return view('devisautocar::livewire.devis-edit', compact( 'invoice_exists'));
    }
}

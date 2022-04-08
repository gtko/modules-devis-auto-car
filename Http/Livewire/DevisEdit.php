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
    public $devis_titre;

    protected array $rules = [
        'data.*' => '',
        'data.trajets' => '',
        'devis.tva_applicable' => '',
        'devis_titre' => '',
    ];

    protected $listeners = [
        'devis:update' => "updateTrajetChild"
    ];

    public function mount(DevisEntities $devis)
    {
        $this->devis = $devis;
        $this->data = ($this->devis->data ?? []);
        $this->devis_titre = ($this->devis->title ?? '');

        if (!$this->data || array_key_exists('trajets', $this->data) === false) {
            $this->data = ['trajets' => []];
        }
    }

    public function store(DevisRepositoryContract $deviRep)
    {
        if (!$this->devis->invoice()->exists()) {

            $this->data['trajets'] = array_values($this->data['trajets'] ?? []);

            $deviRep->updateData($this->devis, $this->data, $this->devis_titre);

            session()->flash('success', __('basecore::crud.common.saved'));

            return redirect()
                ->route('dossiers.show', [$this->devis->dossier->client, $this->devis->dossier]);
        } else {
            session()->flash('danger', "Une facture a été émise et je devis n'est plus modifiable.");

            return redirect()
                ->route('dossiers.show', [$this->devis->dossier->client, $this->devis->dossier]);
        }
    }

    public function updateTrajetChild($data)
    {

        $this->data['trajets'][$data['id']] = $data['trajet'];
    }

    public function addTrajet()
    {
        $this->data['trajets'][] = [
            'repas_chauffeur' => 'non_compris',
            'hebergement' => 'non_compris',
            'parking' => 'non_compris',
            'peages' => 'compris',
        ];

        $this->emit('devis:trajet-open', ['id' => count($this->data['trajets']) - 1]);
    }

    public function removeTrajet($trajet)
    {
        unset($this->data['trajets'][$trajet]);
    }

    public function addLine(){
        $this->data['lines'][] = [
            'line' => '',
            'qte' => 1,
            'tva' => 1.1,
            'pu' => 0,
        ];
    }

    public function removeLine($line){
        unset($this->data['lines'][$line]);
    }

    /**
     * Get the views / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {

        $invoice_exists = $this->devis->invoice()->exists();

        return view('devisautocar::livewire.devis-edit', compact('invoice_exists'));
    }
}

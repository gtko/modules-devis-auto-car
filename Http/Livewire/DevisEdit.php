<?php

namespace Modules\DevisAutoCar\Http\Livewire;

use Modules\CoreCRM\Contracts\Entities\DevisEntities;
use Modules\CoreCRM\Contracts\Repositories\DevisRepositoryContract;
use Livewire\Component;
use Modules\CoreCRM\Models\Fournisseur;
use Modules\CrmAutoCar\Contracts\Service\DistanceApiContract;
use Modules\CrmAutoCar\Models\Brand;

class DevisEdit extends Component
{

    public DevisEntities $devis;
    public array $data;

    protected array $rules = [
        'data.*' => ''
    ];

    public function mount(DevisEntities $devis){
        $this->devis = $devis;
        $this->data = $this->devis->data ?? [];
    }


    public function store(DevisRepositoryContract $deviRep){
        if(!$this->devis->invoice()->exists()) {

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

    public function updatedDataAllerPointDepartGeo($value){
        $this->updateDistanceAller();
    }

    public function updatedDataAllerPointArriverGeo($value){
        $this->updateDistanceAller();
    }

    public function updateDistanceAller(){
       if(($this->data['aller_point_depart_geo'] ?? null) && ($this->data['aller_point_arriver_geo'] ?? null))
       {
           $this->data['aller_distance'] = app(DistanceApiContract::class)
               ->distance(
                   $this->data['aller_point_depart_geo'] ,
                   $this->data['aller_point_arriver_geo']
               )->toArray();
       }
    }

    public function updatedDataRetourPointDepartGeo($value){
        $this->updateDistanceRetour();
    }

    public function updatedDataRetourPointArriverGeo($value){
        $this->updateDistanceRetour();
    }

    public function updateDistanceRetour(){
        if(($this->data['retour_point_depart_geo'] ?? null) && ($this->data['retour_point_arriver_geo'] ?? null))
        {
            $this->data['retour_distance'] = app(DistanceApiContract::class)
                ->distance(
                    $this->data['retour_point_depart_geo'] ,
                    $this->data['retour_point_arriver_geo']
                )->toArray();
        }
    }

    /**
     * Get the views / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $fournisseurs = Fournisseur::all();
        $brands = Brand::all();
        $invoice_exists = $this->devis->invoice()->exists();

        return view('devisautocar::livewire.devis-edit', compact( 'fournisseurs', 'brands', 'invoice_exists'));
    }
}

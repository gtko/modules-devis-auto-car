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
    public array $data;

    protected array $rules = [
        'data.*' => '',
        'data.trajets' => '',
    ];

    public function mount(DevisEntities $devis){
        $this->devis = $devis;
        $this->data = $this->devis->data ?? ['trajets' => []];

        //patch ancienne version
        if(!($this->data['trajets'] ?? false)){
            $this->data = ['trajets' => []];
        }
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
    }

    public function removeTrajet($trajet)
    {
        unset($this->data['trajets'][$trajet]);
    }

    public function updatedData($value, $key){

        /*"bordeaux"
        "trajets.0.aller_point_depart"*/


        if(Str::contains($key, '_geo')){
            $keys = explode('.', $key);
            $this->{'updatedData'.Str::ucfirst(Str::camel(last($keys)))}($value, $keys[1]);
        }

    }

    public function updatedDataAllerPointDepartGeo($value, $trajet){
        $this->updateDistanceAller($trajet);
    }

    public function updatedDataAllerPointArriverGeo($value, $trajet){
        $this->updateDistanceAller($trajet);
    }

    public function updateDistanceAller($trajet){
        if(($this->data['trajets'][$trajet]['aller_point_depart_geo'] ?? null) && ($this->data['trajets'][$trajet]['aller_point_arriver_geo'] ?? null))
        {
            $this->data['trajets'][$trajet]['aller_distance'] = app(DistanceApiContract::class)
                ->distance(
                    $this->data['trajets'][$trajet]['aller_point_depart_geo'] ,
                    $this->data['trajets'][$trajet]['aller_point_arriver_geo']
                )->toArray();



            if(!($this->data['trajets'][$trajet]['retour_point_depart_geo'] ?? null) && !($this->data['trajets'][$trajet]['retour_point_arriver_geo'] ?? null)){
                $this->data['trajets'][$trajet]['retour_point_depart_geo'] = $this->data['trajets'][$trajet]['aller_point_arriver_geo'];
                $this->data['trajets'][$trajet]['retour_point_arriver_geo'] = $this->data['trajets'][$trajet]['aller_point_depart_geo'];

                $this->data['trajets'][$trajet]['retour_point_depart'] = $this->data['trajets'][$trajet]['aller_point_arriver'];
                $this->data['trajets'][$trajet]['retour_point_arriver'] = $this->data['trajets'][$trajet]['aller_point_depart'];

                $this->updateDistanceRetour($trajet);
            }
        }
        if(($this->data['trajets'][$trajet]['aller_point_depart_geo'] ?? null) && !($this->data['trajets'][$trajet]['addresse_ramassage'] ?? null)){
            $this->data['trajets'][$trajet]['addresse_ramassage'] = $this->data['trajets'][$trajet]['aller_point_depart'];
        }

    }

    public function updatedDataRetourPointDepartGeo($value,$trajet){
        $this->updateDistanceRetour($trajet);
    }

    public function updatedDataRetourPointArriverGeo($value,$trajet){
        $this->updateDistanceRetour($trajet);
    }

    public function updateDistanceRetour($trajet){
        if(($this->data['trajets'][$trajet]['retour_point_depart_geo'] ?? null) && ($this->data['trajets'][$trajet]['retour_point_arriver_geo'] ?? null))
        {
            $this->data['trajets'][$trajet]['retour_distance'] = app(DistanceApiContract::class)
                ->distance(
                    $this->data['trajets'][$trajet]['retour_point_depart_geo'] ,
                    $this->data['trajets'][$trajet]['retour_point_arriver_geo']
                )->toArray();

            if(!($this->data['trajets'][$trajet]['aller_point_depart_geo'] ?? null) && !($this->data['trajets'][$trajet]['aller_point_arriver_geo'] ?? null)){
                $this->data['trajets'][$trajet]['aller_point_depart_geo'] = $this->data['trajets'][$trajet]['retour_point_arriver_geo'];
                $this->data['trajets'][$trajet]['aller_point_arriver_geo'] = $this->data['trajets'][$trajet]['retour_point_depart_geo'];

                $this->data['trajets'][$trajet]['aller_point_depart'] = $this->data['trajets'][$trajet]['retour_point_arriver'];
                $this->data['trajets'][$trajet]['aller_point_arriver'] = $this->data['trajets'][$trajet]['retour_point_depart'];

                $this->updateDistanceAller($trajet);
            }
        }

        if(($this->data['trajets'][$trajet]['retour_point_depart_geo'] ?? null) && !($this->data['trajets'][$trajet]['addresse_destination'] ?? null)){
            $this->data['trajets'][$trajet]['addresse_destination'] = $this->data['trajets'][$trajet]['retour_point_depart'];
        }

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

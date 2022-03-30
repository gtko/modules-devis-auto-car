<?php

namespace Modules\DevisAutoCar\Http\Livewire;

use Illuminate\Support\Str;
use Livewire\Component;
use Modules\CoreCRM\Models\Fournisseur;
use Modules\CrmAutoCar\Contracts\Service\DistanceApiContract;
use Modules\CrmAutoCar\Models\Brand;

class DevisEditProduct extends Component
{
    public $trajet;
    public $trajetId;
    public $open = false;


    public function mount($trajetId, $trajet)
    {
        $this->trajetId = $trajetId;
        $this->trajet = $trajet;

        if ($trajetId == 0) {
            $this->open = true;
        }

    }


    protected function getListeners()
    {
        return [
            'devis:update-' . $this->trajetId . '-data' => "updateData",
            'devis:trajet-open' => "openForm"
        ];
    }

    public function openForm($data)
    {
        $this->open = $this->trajetId === $data['id'];
    }


    public function changeOption($option)
    {
        if (($this->trajet[$option] ?? false) == 'compris')
        {
            $this->trajet[$option] = 'non_compris';
        }
        elseif (($this->trajet[$option] ?? false) == 'non_compris')
        {
            $this->trajet[$option] = 'non_affiche';
        }
        elseif (($this->trajet[$option] ?? false) == 'non_affiche')
        {
            $this->trajet[$option] = 'compris';
        }


//        dd($option, $this->trajet);
    }

    public function updated()
    {
//        $this->trajet['non_inclus_repas_chauffeur'] = !$this->trajet['inclus_repas_chauffeur'];
//        $this->trajet['non_inclus_hebergement'] = !$this->trajet['inclus_hebergement'];
//        $this->trajet['non_inclus_parking'] = !$this->trajet['inclus_parking'];
//        $this->trajet['non_inclus_peages'] = !$this->trajet['inclus_peages'];

        if (empty($this->trajet['retour_pax'] ?? null) && !empty($this->trajet['aller_pax'] ?? null)) {
            $this->trajet['retour_pax'] = $this->trajet['aller_pax'];
        }

        if (empty($this->trajet['aller_pax'] ?? null) && !empty($this->trajet['retour_pax'] ?? null)) {
            $this->trajet['aller_pax'] = $this->trajet['retour_pax'];
        }
        $this->emitUp('devis:update', ['trajet' => $this->trajet, 'id' => $this->trajetId]);
    }


    public function updateData($data)
    {

        if (Str::contains($data['name'], 'aller')) {
            if (Str::contains($data['name'], 'depart')) {
                $this->trajet['aller_point_depart'] = $data['format'];
                $this->trajet['aller_point_depart_geo'] = $data['geo'];
            } else {
                $this->trajet['aller_point_arriver'] = $data['format'];
                $this->trajet['aller_point_arriver_geo'] = $data['geo'];
            }
            $this->updateDistanceAller();
        } else {
            if (Str::contains($data['name'], 'depart')) {
                $this->trajet['retour_point_depart'] = $data['format'];
                $this->trajet['retour_point_depart_geo'] = $data['geo'];
            } else {
                $this->trajet['retour_point_arriver'] = $data['format'];
                $this->trajet['retour_point_arriver_geo'] = $data['geo'];
            }
            $this->updateDistanceRetour();
        }
    }

    public function updateDistanceAller()
    {
        if (($this->trajet['aller_point_depart_geo'] ?? null) && ($this->trajet['aller_point_arriver_geo'] ?? null)) {
            $this->trajet['aller_distance'] = app(DistanceApiContract::class)
                ->distance(
                    $this->trajet['aller_point_depart_geo'],
                    $this->trajet['aller_point_arriver_geo'],
                    $this->trajet['aller_point_depart'],
                    $this->trajet['aller_point_arriver']
                )->toArray();

            if (!($this->trajet['retour_point_depart_geo'] ?? null) && !($this->trajet['retour_point_arriver_geo'] ?? null)) {
                $this->trajet['retour_point_depart_geo'] = $this->trajet['aller_point_arriver_geo'];
                $this->trajet['retour_point_arriver_geo'] = $this->trajet['aller_point_depart_geo'];

                $this->trajet['retour_point_depart'] = $this->trajet['aller_point_arriver'];
                $this->trajet['retour_point_arriver'] = $this->trajet['aller_point_depart'];

                $this->updateDistanceRetour();
            }
        }
        if (($this->trajet['aller_point_depart_geo'] ?? null) && !($this->trajet['addresse_ramassage'] ?? null)) {
            $this->trajet['addresse_ramassage'] = $this->trajet['aller_point_depart'];
        }

        $this->updated();
    }

    public function updateDistanceRetour()
    {
        if (($this->trajet['retour_point_depart_geo'] ?? null) && ($this->trajet['retour_point_arriver_geo'] ?? null)) {
            $this->trajet['retour_distance'] = app(DistanceApiContract::class)
                ->distance(
                    $this->trajet['retour_point_depart_geo'],
                    $this->trajet['retour_point_arriver_geo'],
                    $this->trajet['retour_point_depart'],
                    $this->trajet['retour_point_arriver']
                )->toArray();

            if (!($this->trajet['aller_point_depart_geo'] ?? null) && !($this->trajet['aller_point_arriver_geo'] ?? null)) {
                $this->trajet['aller_point_depart_geo'] = $this->trajet['retour_point_arriver_geo'];
                $this->trajet['aller_point_arriver_geo'] = $this->trajet['retour_point_depart_geo'];

                $this->trajet['aller_point_depart'] = $this->trajet['retour_point_arriver'];
                $this->trajet['aller_point_arriver'] = $this->trajet['retour_point_depart'];

                $this->updateDistanceAller();
            }
        }

        if (($this->trajet['retour_point_depart_geo'] ?? null) && !($this->trajet['addresse_destination'] ?? null)) {
            $this->trajet['addresse_destination'] = $this->trajet['retour_point_depart'];
        }

        $this->updated();
    }

    public function render()
    {
        $fournisseurs = Fournisseur::all();
        $brands = Brand::all();
        return view('devisautocar::livewire.devis-edit-product', compact('brands', 'fournisseurs'));
    }
}

<?php

namespace Modules\DevisAutoCar\Entities;

use Illuminate\Support\Collection;
use Modules\CrmAutoCar\Models\Brand;
use Modules\DevisAutoCar\Models\Devi;

class DevisPrice
{

    protected float $total_ttc = 0;
    protected bool $tva = false;

    protected Brand $brand;

    protected Collection $trajets;

    /**
     * @param \Modules\DevisAutoCar\Models\Devi $devis
     * @param \Modules\CrmAutoCar\Models\Brand $brand
     */

    public function __construct(Devi $devis, Brand $brand){
        $this->trajets = collect();
        $this->brand = $brand;
        $this->getTrajetsTotal($devis, $brand);

        $this->total_ttc = $this->trajets->sum(function($item){
            return $item->getPriceTTC();
        });
        $this->tva = (bool) ($devis->tva_applicable ?? true);
    }

    protected function getTrajetsTotal(Devi $devis , Brand $brand){
        foreach(($devis->data['trajets'] ?? [])as $id => $trajet){
            $this->trajets->push(new DevisTrajetPrice($devis, $id, $brand));
        }
    }

    public function getTrajets(){
        return $this->trajets;
    }

    public function getTrajet($id){
        return $this->trajets[$id] ?? null;
    }

    public function getPriceTTC(){
        return $this->total_ttc;
    }

    public function getTauxTVA(){
        if($this->tva) {
            return config('crmautocar.tva');
        }

        return 0;
    }

    public function getPriceHT(){
        return $this->getPriceTTC() / (1 + ($this->getTauxTVA() / 100));
    }

    public function getPriceTVA(){
        return $this->getPriceTTC() - $this->getPriceHT();
    }
}

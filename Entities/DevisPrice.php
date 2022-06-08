<?php

namespace Modules\DevisAutoCar\Entities;

use Illuminate\Support\Collection;
use Modules\CoreCRM\Contracts\Entities\DevisEntities;
use Modules\CrmAutoCar\Contracts\Repositories\BrandsRepositoryContract;
use Modules\CrmAutoCar\Models\Brand;
use Modules\DevisAutoCar\Models\Devi;

class DevisPrice
{

    protected float $total_ttc = 0;
    protected float $total_ht = 0;
    protected bool $tva = false;

    protected Brand $brand;
    protected DevisEntities $devis;

    protected Collection $trajets;
    protected Collection $lines;

    /**
     * @param \Modules\DevisAutoCar\Models\Devi $devis
     * @param \Modules\CrmAutoCar\Models\Brand $brand
     */

    public function __construct(Devi $devis, Brand $brand){
        $this->trajets = collect();
        $this->lines = collect();
        $this->devis = $devis;
        $this->brand = $brand;
        $this->getTrajetsTotal($devis, $brand);
        $this->getLinesTotal($devis);

        $this->total_ttc = $this->trajets->sum(function($item){
            return $item->getPriceTTC();
        }) + $this->lines->sum(function($item){
            return $item->getPriceTTC();
        });

        $this->total_ht = $this->trajets->sum(function($item){
                return $item->getPriceHT();
            }) + $this->lines->sum(function($item){
                return $item->getPriceHT();
            });


        $this->tva = (bool) ($devis->tva_applicable ?? true);
    }

    protected function getTrajetsTotal(Devi $devis , Brand $brand){
        foreach(($devis->data['trajets'] ?? [])as $id => $trajet){
            $this->trajets->push(new DevisTrajetPrice($devis, $id, $brand));
        }
    }

    public function getTrajetsPrices(){
        $trajetsPrice = collect([]);
        $brand = app(BrandsRepositoryContract::class)->getDefault();
        foreach(($this->devis->data['trajets'] ?? [])as $id => $trajet){
            $trajetsPrice->push(new DevisTrajetPrice($this->devis, $id, $brand));
        }

        return $trajetsPrice;
    }

    protected function getLinesTotal(Devi $devis){
        foreach(($devis->data['lines'] ?? [])as $id => $line){
            $this->lines->push(new DevisLinePrice($devis, $id));
        }
    }

    public function getTrajets(){
        return $this->trajets;
    }

    public function getLines(){
        return $this->lines;
    }

    public function getTrajet($id){
        return $this->trajets[$id] ?? null;
    }

    public function getLine($id){
        return $this->lines[$id] ?? null;
    }

    public function getPriceTTC(){
        return $this->total_ttc;
    }

    public function getAcompteTTC(){
        if($this->getPriceTTC() > 0) {
            return $this->getPriceTTC() * 0.3;
        }

        return 0;
    }

    public function getPriceHT(){
        return $this->total_ht;
    }

    public function getPriceTVA(){
        return $this->getPriceTTC() - $this->getPriceHT();
    }


    public function getTauxTVA(){
        if($this->tva) {
            return config('crmautocar.tva');
        }

        return 0;
    }
}

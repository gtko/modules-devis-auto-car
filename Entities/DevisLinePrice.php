<?php

namespace Modules\DevisAutoCar\Entities;

use Modules\CrmAutoCar\Models\Brand;
use Modules\DevisAutoCar\Models\Devi;

class DevisLinePrice
{

    protected float $total_ttc = 0;
    protected bool $tva = false;
    protected array $data = [];

    protected float $qte = 0;
    protected float $pu = 0;
    protected float $taux_tva = 1;

    /**
     * @param \Modules\DevisAutoCar\Models\Devi $devis
     * @param \Modules\CrmAutoCar\Models\Brand $brand
     */

    public function __construct(Devi $devis, int $idLine){

        $this->data = $devis->data['lines'][$idLine];
        $this->taux_tva = (float) ($devis->data['lines'][$idLine]['tva'] ?? 1.00);
        $this->qte = (float) ($devis->data['lines'][$idLine]['qte'] ?? 0);
        $this->pu = (float) ($devis->data['lines'][$idLine]['pu'] ?? 0);
        $this->total_ttc = (float) ($this->qte * $this->pu) * $this->taux_tva;
        $this->tva = (bool) $this->taux_tva > 1.00;
    }

    public function getLine(){
        return $this->data['line'] ?? '';
    }

    public function getPriceTTC(){
        return $this->total_ttc;
    }

    public function getTauxTVA(){
        if($this->tva) {
            return (($this->taux_tva - 1)*100);
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

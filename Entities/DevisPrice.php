<?php

namespace Modules\DevisAutoCar\Entities;

use Modules\CrmAutoCar\Models\Brand;
use Modules\DevisAutoCar\Models\Devi;

class DevisPrice
{

    protected float $total_ttc = 0;
    protected bool $tva = false;

    /**
     * @param \Modules\DevisAutoCar\Models\Devi $devis
     * @param \Modules\CrmAutoCar\Models\Brand $brand
     */

    public function __construct(Devi $devis, Brand $brand){

        /**
         * {
         * "aller_point_depart":"33620 Cubnezais, France",
         * "aller_date_depart":"2021-10-11T19:23",
         * "aller_point_depart_geo":"45.074191,-0.408943",
         * "aller_point_arriver":"Toulouse, France",
         * "aller_point_arriver_geo":"43.604652,1.444209",
         * "aller_distance":{
         *    "origin_formatted":"Pedroslb33@hotmail.fr, 33620 Cubnezais, France",
         *    "destination_formatted":"8 Av. L\u00e9on Jouhaux, 31140 Saint-Alban, France","distance_formatted":"2 hours 40 mins",
         *    "duration_formatted":"275 km",
         *    "distance_meter":9608,
         *    "duration_second":274623
         * },
         * "aller_pax":"15",
         * "retour_date_depart":"2021-10-20T19:23",
         * "retour_point_depart":"Toulouse, France",
         * "retour_point_depart_geo":"43.604652,1.444209",
         * "retour_point_arriver":"33620 Cubnezais, France",
         * "retour_point_arriver_geo":"45.074191,-0.408943",
         * "retour_distance":{
         *    "origin_formatted":"8 Av. L\u00e9on Jouhaux, 31140 Saint-Alban, France",
         *    "destination_formatted":"Pedroslb33@hotmail.fr, 33620 Cubnezais, France",
         *    "distance_formatted":"2 hours 41 mins",
         *    "duration_formatted":"274 km",
         *    "distance_meter":9685,
         *    "duration_second":274438
         * },
         * "retour_pax":"15",
         * "inclus_repas_chauffeur":"1",
         * "inclus_peages":"1",
         * "inclus_parking":"1",
         * "non_inclus_hebergement":"1",
         * "brands":{"1":"1500","2":"2360","3":"3500"}
         * }
         */

        $this->total_ttc = (float) ($devis->data['brands'][$brand->id] ?? 0);
        $this->tva = (bool) ($devis->data['tva'] ?? true);
    }

    public function getPriceTTC(){
        return $this->total_ttc;
    }

    public function getTVA(){
        if($this->tva) {
            return config('crmautocar.tva');
        }

        return 0;
    }

    public function getPriceHT(){
        return $this->getPriceTTC() / (1 + ($this->getTVA() / 100));
    }

    public function getPriceTVA(){
        return $this->getPriceTTC() - $this->getPriceHT();
    }
}

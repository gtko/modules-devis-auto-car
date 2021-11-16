<?php

namespace Modules\DevisAutoCar\Entities;

use Illuminate\Support\Collection;
use Modules\CrmAutoCar\Contracts\Repositories\BrandsRepositoryContract;
use Modules\CrmAutoCar\Models\Brand;
use Modules\CrmAutoCar\Repositories\BrandsRepository;
use Modules\DevisAutoCar\Models\Devi;

class DevisPriceAllBrand
{

    protected Collection $brands;

    /**
     * @param \Modules\DevisAutoCar\Models\Devi $devis
     */

    public function __construct(Devi $devis){

        $this->brands = collect();
        $brands = app(BrandsRepositoryContract::class)->all();
        foreach($brands as $brand){
            $this->brands->push(new DevisPrice($devis, $brand));
        }
    }

    public function getBrand(Brand $brand){
        return $this->brands->where('id', $brand->id)->first();
    }
}

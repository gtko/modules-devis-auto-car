<?php

namespace Modules\DevisAutoCar\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Modules\BaseCore\Contracts\Entities\UserEntity;
use Modules\BaseCore\Models\User;
use Modules\CoreCRM\Models\Commercial;
use Modules\CoreCRM\Models\Dossier;
use Modules\CoreCRM\Models\Fournisseur;
use Modules\CoreCRM\Models\Scopes\HasRef;
use Modules\CrmAutoCar\Models\Brand;
use Modules\CrmAutoCar\Models\Invoice;
use Modules\CrmAutoCar\Models\Proformat;
use Modules\CrmAutoCar\Repositories\BrandsRepository;
use Modules\DevisAutoCar\Entities\DevisPrice;

/**
 * Class Devi
 *
 * @package App\Models
 * @property array $data
 * @property Dossier $dossier
 * @property Commercial $commercial
 * @property Fournisseur $fournisseur
 * @property \Modules\CrmAutoCar\Models\Brand $brand
 * @mixin Builder
 * @mixin \Illuminate\Database\Query\Builder
 */
class Devi extends \Modules\CoreCRM\Models\Devi
{
    use HasFactory;
    use HasRef;

    protected $with = ['dossier.client', 'fournisseur'];

    protected $fillable = [
        'dossier_id',
        'commercial_id',
        'data',
        'tva_applicable',
        'fournisseur_id',
    ];

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(app(UserEntity::class)::class, 'fournisseur_id');
    }

    public function fournisseurs(): BelongsToMany
    {
        return $this->belongsToMany(app(UserEntity::class)::class, 'devi_fournisseurs')->withPivot('prix', 'validate', 'mail_sended');
    }

    public function fournisseursValidated(): BelongsToMany
    {
        return $this->belongsToMany(app(UserEntity::class)::class, 'devi_fournisseurs')
            ->withPivot('prix', 'validate', 'mail_sended')
            ->wherePivot('validate', true);
    }

    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class);
    }

    public function invoice():HasOne
    {
        return $this->hasOne(Invoice::class, 'devis_id', 'id');
    }

    public function proformat():HasOne
    {
        return $this->hasOne(Proformat::class, 'devis_id', 'id');
    }

    public function getTotal(): float
    {
        $brand = app(BrandsRepository::class)->fetchById(config('crmautocar.brand_default'));
        return (new DevisPrice($this, $brand))->getPriceTTC();
    }

    public function getDateDepartAttribute(){
        $date = $this->data['aller_date_depart'] ?? '';
        if(!empty($date)) {
            return Carbon::parse($date);
        }

        return '';
    }

    public function getDateRetourAttribute(){
        $date = $this->data['retour_date_depart'] ?? '';
        if(!empty($date)) {
            return Carbon::parse($date);
        }

        return '';
    }

    public function getState():String
    {
        if($this->invoice()->exists()) return 'invoice';
        if($this->proformat()->exists()) return 'proformat';

        return 'devis';
    }
}

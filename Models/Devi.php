<?php

namespace Modules\DevisAutoCar\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Modules\CoreCRM\Models\Commercial;
use Modules\CoreCRM\Models\Dossier;
use Modules\CoreCRM\Models\Fournisseur;
use Modules\CoreCRM\Models\Scopes\HasRef;
use Modules\CrmAutoCar\Contracts\Repositories\BrandsRepositoryContract;
use Modules\CrmAutoCar\Models\Brand;
use Modules\CrmAutoCar\Models\Invoice;
use Modules\CrmAutoCar\Models\Proformat;
use Modules\DevisAutoCar\Entities\DevisPrice;
use Modules\SearchCRM\Entities\SearchResult;

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

    public function getIsMultipleAttribute()
    {
        return count($this->data['trajets'] ?? []) > 1;
    }

    public static function getPrefixRef(){
        return 'DEV';
    }

    public function dossier(): BelongsTo
    {
        return $this->belongsTo(\Modules\CrmAutoCar\Models\Dossier::class);
    }

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(\Modules\CrmAutoCar\Models\Fournisseur::class, 'fournisseur_id');
    }

    public function fournisseurs(): BelongsToMany
    {
        return $this->belongsToMany(\Modules\CrmAutoCar\Models\Fournisseur::class, 'devi_fournisseurs', 'devi_id', 'user_id')
            ->withPivot('prix', 'validate', 'mail_sended', 'bpa');
    }

    public function fournisseursValidated(): BelongsToMany
    {
        return $this->belongsToMany(\Modules\CrmAutoCar\Models\Fournisseur::class, 'devi_fournisseurs', 'devi_id', 'user_id')
            ->withPivot('prix', 'validate', 'mail_sended', 'bpa')
            ->wherePivot('validate', true);
    }

    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'devis_id', 'id');
    }

    public function proformat(): HasOne
    {
        return $this->hasOne(Proformat::class, 'devis_id', 'id');
    }

    public function getTotal(): float
    {
        return (new DevisPrice($this, app(BrandsRepositoryContract::class)->getDefault()))->getPriceTTC();
    }

    public function getDateDepartAttribute()
    {
        $date = $this->data['trajets'][0]['aller_date_depart'] ?? '';
        if (!empty($date)) {
            return Carbon::parse($date);
        }

        return '';
    }

    public function getDateRetourAttribute()
    {
        if ($this->isMultiple) {
            $date = last($this->data['trajets'])['retour_date_depart'] ?? '';
        } else {
            $date = $this->data['trajets'][0]['retour_date_depart'] ?? '';
        }

        if (!empty($date)) {
            return Carbon::parse($date);
        }

        return '';
    }

    public function getAddressValidationAttribute()
    {
        return $this->data['address_validation'] ?? '';
    }

    public function getNameValidationAttribute()
    {
        return $this->data['name_validation'] ?? '';
    }

    public function getSocieteValidationAttribute()
    {
        return $this->data['societe_validation'] ?? '';
    }

    public function getPaiementTypeValidationAttribute()
    {
        return $this->data['paiement_type_validation'] ?? '';
    }

    public function getValidateAttribute()
    {
        if (array_key_exists('paiement_type_validation', $this->data) || array_key_exists('societe_validation', $this->data) || array_key_exists('name_validation', $this->data) || array_key_exists('address_validation', $this->data)) {
            return true;
        } else {
            return false;
        }
    }

    public function getSendableAttribute(){
        $sendable = true;

        foreach(($this->data['trajets'] ?? []) as $trajet) {
            if (!($trajet['aller_date_depart'] ?? false)) {
                $sendable = false;
            }

            if($trajet['retour_point_depart'] ?? false){
                if (!($trajet['retour_date_depart'] ?? false)) {
                    $sendable = false;
                }
            }
        }

        return $sendable;
    }


    public function getState(): string
    {
        if ($this->invoice()->exists()) return 'invoice';
        if ($this->proformat()->exists()) return 'proformat';

        return 'devis';
    }

    public function getSearchResult(): SearchResult
    {
        if($this->dossier) {
            $result = new SearchResult(
                $this,
                "#{$this->ref} - " . ($this->title ?? 'N/A').' '. $this->dossier->client->format_name,
                route('devis.edit', [$this->dossier->client, $this->dossier, $this]),
                'devis',
                html:"<small>{$this->created_at->format('d/m/Y')}</small> - <small>{$this->commercial->format_name}</small>"
            );

            $result->setImg($this->dossier->client->avatar_url);

        }else{
            $result = new SearchResult(
                $this,
                "#{$this->ref} - n'a plus de dossier",
                '',
                'devis',
                html: ""
            );
        }
        return $result;

    }
}

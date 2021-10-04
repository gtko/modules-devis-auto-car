<?php

namespace Modules\DevisAutoCar\Models;


use App\Models\Brand;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\BaseCore\Contracts\Entities\UserEntity;
use Modules\BaseCore\Models\User;
use Modules\CoreCRM\Models\Commercial;
use Modules\CoreCRM\Models\Dossier;
use Modules\CoreCRM\Models\Fournisseur;
use Modules\CoreCRM\Models\Scopes\HasRef;

/**
 * Class Devi
 *
 * @package App\Models
 * @property array $data
 * @property Dossier $dossier
 * @property Commercial $commercial
 * @property Fournisseur $fournisseur
 * @property Brand $brand
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

    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class);
    }

    public function getTotal(): float
    {

    }
}

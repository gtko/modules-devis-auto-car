<?php

namespace Modules\DevisAutoCar\Entities;

use Modules\CoreCRM\Contracts\Views\DevisEditViewContract;
use Modules\BaseCore\Entities\TypeView;

class DevisEditView implements DevisEditViewContract
{

    public function getTypeView(): TypeView
    {
        return (new TypeView(TypeView::TYPE_LIVEWIRE,'devisautocar::devis-edit'));
    }
}

<?php

namespace Modules\DevisAutoCar\Providers;

use Config;
use Illuminate\Support\ServiceProvider;
use Modules\BaseCore\Contracts\Services\CompositeurThemeContract;
use Modules\BaseCore\Entities\TypeView;
use Modules\CoreCRM\Contracts\Entities\DevisEntities;
use Modules\CoreCRM\Contracts\Views\Devis\DevisListContrat;
use Modules\CoreCRM\Contracts\Views\DevisEditViewContract;
use Modules\DevisAutoCar\Entities\DevisEditView;
use Modules\DevisAutoCar\Models\Devi;

class DevisAutoCarServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected string $moduleName = 'DevisAutoCar';

    /**
     * @var string $moduleNameLower
     */
    protected string $moduleNameLower = 'devisautocar';


    /**
     * Register services.
     */
    public function register()
    {
        $this->app->bind(DevisEntities::class, Devi::class);
        $this->app->bind(DevisEditViewContract::class, DevisEditView::class);


        /** Overide des views CoreCRM */
        app(CompositeurThemeContract::class)
            ->setViews(DevisEditViewContract::class,[
                'devis-view' => new TypeView(TypeView::TYPE_LIVEWIRE,'devisautocar::devis-edit')
            ])->setViews(DevisListContrat::class, [
                'devis-list' => new TypeView(TypeView::TYPE_LIVEWIRE, 'devisautocar::devis-list')
            ]);

    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
       $this->registerViews();
    }


    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }


    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}

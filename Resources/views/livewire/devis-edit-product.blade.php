<div x-data="{open:false}" class="mt-3">
    <div class="flex w-full box p-3 justify-between items-center cursor-pointer" x-on:click="open=!open">
        <span>Trajet #{{$keyTrajet+1}}</span>

        <span x-show="!open">Ouvrir</span>
        <span x-show="open">Fermer</span>
    </div>
    <div class="border p-3" x-bind:class="{'flex flex-col' : open, 'hidden' : !open}" x-cloak>
        <div class="flex flex-wrap flex-col">
            <div class="mb-5">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Détails de l'aller
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Indiquez les détails de l'aller, la date de départ, l'adresse de départ et de d'arriver.
                </p>
            </div>
            <x-basecore::partials.card>
                <div class="w-full grid grid-cols-4">
                    <x-basecore::inputs.group class="w-full">
                        <x-basecore::inputs.datetime label="Date de départ" name="aller_date_depart" wire:model.defer="data.trajets.{{$keyTrajet}}.aller_date_depart" placeholder="Date de départ"/>
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group class="w-full">
                        <x-basecore::inputs.basic label="Point de départ" class='addressmap' data-trajet="{{$keyTrajet}}" name="aller_point_depart"  wire:model.defer="data.trajets.{{$keyTrajet}}.aller_point_depart" placeholder="Ville, adresse , ..."/>
                        <input type="hidden" name='aller_point_depart_geo_{{$keyTrajet}}' wire:model.defer="data.trajets.{{$keyTrajet}}.aller_point_depart_geo"/>
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group class="w-full">
                        <x-basecore::inputs.basic label="Point d'arriver" class='addressmap' data-trajet="{{$keyTrajet}}" name="aller_point_arriver" wire:model.defer="data.trajets.{{$keyTrajet}}.aller_point_arriver" placeholder="Ville, adresse , ..."/>
                        <input type="hidden" name='aller_point_arriver_geo_{{$keyTrajet}}' wire:model.defer="data.trajets.{{$keyTrajet}}.aller_point_arriver_geo"/>
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group class="w-full">
                        <x-basecore::inputs.basic label="Pax" name="aller_pax" wire:model.defer="data.trajets.{{$keyTrajet}}.aller_pax" placeholder="Nombre de passagers"/>
                    </x-basecore::inputs.group>
                </div>
            </x-basecore::partials.card>
            @if(($data['trajets'][$keyTrajet]['aller_distance'] ?? null))
                <livewire:devisautocar::devis-distance :distance="$data['trajets'][$keyTrajet]['aller_distance']" />
            @endif

            <div class="my-5">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-5">
                    Détails du retour
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Indiquez les détails du retour, la date de départ, l'adresse de départ et de d'arriver.
                </p>
            </div>
            <x-basecore::partials.card>
                <div class="w-full grid grid-cols-4">
                    <x-basecore::inputs.group class="w-full">
                        <x-basecore::inputs.datetime label="Date de départ"  name="retour_date_depart" wire:model.lazy="data.trajets.{{$keyTrajet}}.retour_date_depart" placeholder="Date de départ"/>
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group class="w-full">
                        <x-basecore::inputs.basic label="Point de départ" class='addressmap' data-trajet="{{$keyTrajet}}" name="retour_point_depart" wire:model.lazy="data.trajets.{{$keyTrajet}}.retour_point_depart" placeholder="Ville, adresse , ..."/>
                        <input type="hidden" name='retour_point_depart_geo_{{$keyTrajet}}' wire:model.defer="data.trajets.{{$keyTrajet}}.retour_point_depart_geo"/>
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group class="w-full">
                        <x-basecore::inputs.basic label="Point d'arriver" class='addressmap' data-trajet="{{$keyTrajet}}" name="retour_point_arriver" wire:model.lazy="data.trajets.{{$keyTrajet}}.retour_point_arriver" placeholder="Ville, adresse , ..."/>
                        <input type="hidden" name='retour_point_arriver_geo_{{$keyTrajet}}' wire:model.defer="data.trajets.{{$keyTrajet}}.retour_point_arriver_geo"/>
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group class="w-full">
                        <x-basecore::inputs.basic label="Pax" name="retour_pax" wire:model.defer="data.trajets.{{$keyTrajet}}.retour_pax" placeholder="Nombre de passagers"/>
                    </x-basecore::inputs.group>
                </div>
            </x-basecore::partials.card>
            @if(($data['trajets'][$keyTrajet]['retour_distance'] ?? null))
                <livewire:devisautocar::devis-distance :distance="$data['trajets'][$keyTrajet]['retour_distance']"/>
            @endif

            <div class="my-5">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Tarifs
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Indiquez les tarifs par marques.
                </p>
            </div>
            <x-basecore::partials.card>
                <div class="grid grid-cols-3">
                    @foreach($brands as $brand)
                        <x-basecore::inputs.group>
                            <x-basecore::inputs.number label="{{$brand->label}}" name="" wire:model.lazy="data.trajets.{{$keyTrajet}}.brands.{{$brand->id}}" placeholder="Tarif en €"/>
                        </x-basecore::inputs.group>
                    @endforeach
                </div>
            </x-basecore::partials.card>

            <div class="my-5">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Le prix comprend
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Options inclus pour le tarif indiqué
                </p>
            </div>
            <x-basecore::partials.card>
                <div class="grid grid-cols-2">
                    <x-basecore::inputs.group>
                        <x-basecore::inputs.checkbox label="Repas chauffeur" name="" wire:model.lazy="data.trajets.{{$keyTrajet}}.inclus_repas_chauffeur"/>
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group>
                        <x-basecore::inputs.checkbox label="Hébergement" name="" wire:model.lazy="data.trajets.{{$keyTrajet}}.inclus_hebergement"/>
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group>
                        <x-basecore::inputs.checkbox label="Parking"  name="" wire:model.lazy="data.trajets.{{$keyTrajet}}.inclus_parking"/>
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group>
                        <x-basecore::inputs.checkbox label="Péages"  name="" wire:model.lazy="data.trajets.{{$keyTrajet}}.inclus_peages"/>
                    </x-basecore::inputs.group>
                </div>
            </x-basecore::partials.card>

            <div class="my-5">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Le prix ne comprend pas
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Options qui ne sont pas inclus pour le tarif indiqué
                </p>
            </div>
            <x-basecore::partials.card>
                <div class="grid grid-cols-2">
                    <x-basecore::inputs.group>
                        <x-basecore::inputs.checkbox label="Repas chauffeur" name="" wire:model.lazy="data.trajets.{{$keyTrajet}}.non_inclus_repas_chauffeur"/>
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group>
                        <x-basecore::inputs.checkbox label="Hébergement" name="" wire:model.lazy="data.trajets.{{$keyTrajet}}.non_inclus_hebergement"/>
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group>
                        <x-basecore::inputs.checkbox label="Parking"  name="" wire:model.lazy="data.trajets.{{$keyTrajet}}.non_inclus_parking"/>
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group>
                        <x-basecore::inputs.checkbox label="Péages"  name="" wire:model.lazy="data.trajets.{{$keyTrajet}}.non_inclus_peages"/>
                    </x-basecore::inputs.group>
                </div>
            </x-basecore::partials.card>

            <div class="my-5">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Informations de ramassage
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Indiquez les adresses de ramassage pour l'aller et le retour
                </p>
            </div>
            <x-basecore::partials.card>
                <div class="grid grid-cols-2">
                    <x-basecore::inputs.group>
                        <x-basecore::inputs.basic label="Adresse de ramassage" name=""  wire:model.lazy="data.trajets.{{$keyTrajet}}.addresse_ramassage"/>
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group>
                        <x-basecore::inputs.basic label="Adresse de destination" name=""  wire:model.lazy="data.trajets.{{$keyTrajet}}.addresse_destination"/>
                    </x-basecore::inputs.group>
                </div>
            </x-basecore::partials.card>
        </div>
    </div>
    <script>
        initInput('.addressmap')
    </script>
</div>

<div x-data="{open:@entangle('open')}" class="mt-3 border "
     x-bind:class="{'border-transparent' : !open}"
>
    <div class="group flex w-full bg-white p-3 justify-between items-center cursor-pointer"
         x-bind:class="{'rounded' : !open, 'rounded-t border-b bg-gray-200' : open}"
         x-on:click="open=!open">
        <h3 class="text-lg leading-6 font-medium text-gray-900 group-hover:text-gray-600">
            Trajet #{{$trajetId+1}}
        </h3>

        <span class='transform transition group-hover:text-gray-600' x-bind:class="{'-rotate-180' : !open}">@icon('chevronDown', null)</span>
    </div>
    <div class="p-3 bg-gray-200" x-bind:class="{'flex flex-col' : open, 'hidden' : !open}" x-cloak>
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
                        <x-basecore::inputs.datetime label="Date de départ" name="aller_date_depart" wire:model="trajet.aller_date_depart" placeholder="Date de départ"/>
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group class="w-full">
                        <x-basecore::inputs.basic label="Point de départ" class='addressmap' data-trajet="{{$trajetId}}" name="aller_point_depart"  wire:model="trajet.aller_point_depart" placeholder="Ville, adresse , ..."/>
                        <input type="hidden" name='aller_point_depart_geo_{{$trajetId}}' wire:model="trajet.aller_point_depart_geo"/>
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group class="w-full">
                        <x-basecore::inputs.basic label="Point d'arriver" class='addressmap' data-trajet="{{$trajetId}}" name="aller_point_arriver" wire:model="trajet.aller_point_arriver" placeholder="Ville, adresse , ..."/>
                        <input type="hidden" name='aller_point_arriver_geo_{{$trajetId}}' wire:model="trajet.aller_point_arriver_geo"/>
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group class="w-full">
                        <x-basecore::inputs.basic label="Pax" name="aller_pax" wire:model.lazy="trajet.aller_pax" placeholder="Nombre de passagers"/>
                    </x-basecore::inputs.group>
                </div>
            </x-basecore::partials.card>

            @if(($trajet['aller_distance'] ?? null))
                <livewire:devisautocar::devis-distance :distance="$trajet['aller_distance']" />
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
                        <x-basecore::inputs.datetime label="Date de départ"  name="retour_date_depart" wire:model.lazy="trajet.retour_date_depart" placeholder="Date de départ"/>
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group class="w-full">
                        <x-basecore::inputs.basic label="Point de départ" class='addressmap' data-trajet="{{$trajetId}}" name="retour_point_depart" wire:model.lazy="trajet.retour_point_depart" placeholder="Ville, adresse , ..."/>
                        <input type="hidden" name='retour_point_depart_geo_{{$trajetId}}' wire:model="trajet.retour_point_depart_geo"/>
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group class="w-full">
                        <x-basecore::inputs.basic label="Point d'arriver" class='addressmap' data-trajet="{{$trajetId}}" name="retour_point_arriver" wire:model.lazy="trajet.retour_point_arriver" placeholder="Ville, adresse , ..."/>
                        <input type="hidden" name='retour_point_arriver_geo_{{$trajetId}}' wire:model="trajet.retour_point_arriver_geo"/>
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group class="w-full">
                        <x-basecore::inputs.basic label="Pax" name="retour_pax" wire:model.lazy="trajet.retour_pax" placeholder="Nombre de passagers"/>
                    </x-basecore::inputs.group>
                </div>
            </x-basecore::partials.card>
            @if(($trajet['retour_distance'] ?? null))
                <livewire:devisautocar::devis-distance :distance="$trajet['retour_distance']"/>
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
                            <x-basecore::inputs.number label="{{$brand->label}}" name="" wire:model.lazy="trajet.brands.{{$brand->id}}" placeholder="Tarif en €"/>
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
                        <x-basecore::inputs.checkbox label="Repas chauffeur" name="" wire:model.lazy="trajet.inclus_repas_chauffeur"/>
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group>
                        <x-basecore::inputs.checkbox label="Hébergement" name="" wire:model.lazy="trajet.inclus_hebergement"/>
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group>
                        <x-basecore::inputs.checkbox label="Parking"  name="" wire:model.lazy="trajet.inclus_parking"/>
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group>
                        <x-basecore::inputs.checkbox label="Péages"  name="" wire:model.lazy="trajet.inclus_peages"/>
                    </x-basecore::inputs.group>
                </div>
                    <input type="hidden" name="non_inclus_repas_chauffeur" wire:model.lazy="trajet.non_inclus_repas_chauffeur"/>
                    <input type="hidden" name="non_inclus_hebergement" wire:model.lazy="trajet.non_inclus_hebergement"/>
                    <input type="hidden" name="non_inclus_parking" wire:model.lazy="trajet.non_inclus_parking"/>
                    <input type="hidden" name="non_inclus_peages" wire:model.lazy="trajet.non_inclus_peages"/>
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
                        <x-basecore::inputs.basic label="Adresse de ramassage" name=""  wire:model.lazy="trajet.addresse_ramassage"/>
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group>
                        <x-basecore::inputs.basic label="Adresse de destination" name=""  wire:model.lazy="trajet.addresse_destination"/>
                    </x-basecore::inputs.group>
                </div>
            </x-basecore::partials.card>
            <div class="my-5">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Commentaire
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Commentaire accroché au devis
                </p>
            </div>
            <x-basecore::inputs.textarea
                wire:model="trajet.commentaire"
                name="commentaire"
                class="h-36"
            />
        </div>

    </div>
    <script>
        if (typeof initInput !== 'undefined') {
            initInput('.addressmap')
        }
    </script>
</div>

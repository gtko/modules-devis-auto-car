<div>

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
                    <x-basecore::inputs.datetime label="Date de départ" name="aller_date_depart" wire:model.defer="data.aller_date_depart" placeholder="Date de départ"/>
                </x-basecore::inputs.group>
                <x-basecore::inputs.group class="w-full">
                    <x-basecore::inputs.basic label="Point de départ" name="aller_point_depart"  wire:model.defer="data.aller_point_depart" placeholder="Ville, adresse , ..."/>
                    <input type="hidden" id='aller_point_depart_geo' wire:model.defer="data.aller_point_depart_geo"/>
                </x-basecore::inputs.group>
                <x-basecore::inputs.group class="w-full">
                    <x-basecore::inputs.basic label="Point d'arriver" name="aller_point_arriver" wire:model.defer="data.aller_point_arriver" placeholder="Ville, adresse , ..."/>
                    <input type="hidden" id='aller_point_arriver_geo' wire:model.defer="data.aller_point_arriver_geo"/>
                </x-basecore::inputs.group>
                <x-basecore::inputs.group class="w-full">
                    <x-basecore::inputs.basic label="Pax" name="aller_pax" wire:model.defer="data.aller_pax" placeholder="Nombre de passagers"/>
                </x-basecore::inputs.group>
            </div>
        </x-basecore::partials.card>
        @if(($data['aller_distance'] ?? null))
            <livewire:devisautocar::devis-distance :distance="$data['aller_distance']" />
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
                    <x-basecore::inputs.datetime label="Date de départ" name="aller_date_depart" wire:model.lazy="data.retour_date_depart" placeholder="Date de départ"/>
                </x-basecore::inputs.group>
                <x-basecore::inputs.group class="w-full">
                    <x-basecore::inputs.basic label="Point de départ" name="retour_point_depart" wire:model.lazy="data.retour_point_depart" placeholder="Ville, adresse , ..."/>
                    <input type="hidden" id='retour_point_depart_geo' wire:model.defer="data.retour_point_depart_geo"/>
                </x-basecore::inputs.group>
                <x-basecore::inputs.group class="w-full">
                    <x-basecore::inputs.basic label="Point d'arriver" name="retour_point_arriver" wire:model.lazy="data.retour_point_arriver" placeholder="Ville, adresse , ..."/>
                    <input type="hidden" id='retour_point_arriver_geo' wire:model.defer="data.retour_point_arriver_geo"/>
                </x-basecore::inputs.group>
                <x-basecore::inputs.group class="w-full">
                    <x-basecore::inputs.basic label="Pax" name="retour_pax" wire:model.defer="data.retour_pax" placeholder="Nombre de passagers"/>
                </x-basecore::inputs.group>
            </div>
        </x-basecore::partials.card>
        @if(($data['retour_distance'] ?? null))
            <livewire:devisautocar::devis-distance :distance="$data['retour_distance']"/>
        @endif

        <script>
            // src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
            function initInput(selecteur) {
                let addressField;
                let autocomplete;
                addressField = document.querySelector(selecteur);
                autocomplete = new google.maps.places.Autocomplete(addressField, {
                    componentRestrictions: {},
                    fields: ["formatted_address", "geometry"],
                });
                autocomplete.addListener("place_changed", () => {
                    const place = autocomplete.getPlace()
                    let latlng = place.geometry.location.lat() + "," +  place.geometry.location.lng()
                    let geoField = document.querySelector(selecteur + "_geo")
                    geoField.value = latlng
                    addressField.value = place.formatted_address
                    @this.set("data."+selecteur.replace('#', '') + "_geo", latlng)
                    @this.set("data."+selecteur.replace('#', ''), place.formatted_address)
                });
            }

            function initAutocomplete() {
                initInput('#aller_point_depart')
                initInput('#aller_point_arriver')
                initInput('#retour_point_depart')
                initInput('#retour_point_arriver')
            }
        </script>
        <script
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAksepNYHf1gKZvOPd5Zu3hmMoXYUG2LRw&callback=initAutocomplete&libraries=places&v=weekly"
            async
        ></script>


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
                    <x-basecore::inputs.number label="{{$brand->label}}" name="" wire:model.lazy="data.brands.{{$brand->id}}" placeholder="Tarif en €"/>
                </x-basecore::inputs.group>
                @endforeach
                <x-basecore::inputs.group class="w-full">
                    <x-basecore::inputs.checkbox
                        name="tva_applicable"
                        label="Tva Applicable"
                        :checked="$devis->tva_applicable"
                    ></x-basecore::inputs.checkbox>
                </x-basecore::inputs.group>
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
                    <x-basecore::inputs.checkbox label="Repas chauffeur" name="" wire:model.lazy="data.inclus_repas_chauffeur"/>
                </x-basecore::inputs.group>
                <x-basecore::inputs.group>
                    <x-basecore::inputs.checkbox label="Hébergement" name="" wire:model.lazy="data.inclus_hebergement"/>
                </x-basecore::inputs.group>
                <x-basecore::inputs.group>
                    <x-basecore::inputs.checkbox label="Parking"  name="" wire:model.lazy="data.inclus_parking"/>
                </x-basecore::inputs.group>
                <x-basecore::inputs.group>
                    <x-basecore::inputs.checkbox label="Péages"  name="" wire:model.lazy="data.inclus_peages"/>
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
                    <x-basecore::inputs.checkbox label="Repas chauffeur" name="" wire:model.lazy="data.non_inclus_repas_chauffeur"/>
                </x-basecore::inputs.group>
                <x-basecore::inputs.group>
                    <x-basecore::inputs.checkbox label="Hébergement" name="" wire:model.lazy="data.non_inclus_hebergement"/>
                </x-basecore::inputs.group>
                <x-basecore::inputs.group>
                    <x-basecore::inputs.checkbox label="Parking"  name="" wire:model.lazy="data.non_inclus_parking"/>
                </x-basecore::inputs.group>
                <x-basecore::inputs.group>
                    <x-basecore::inputs.checkbox label="Péages"  name="" wire:model.lazy="data.non_inclus_peages"/>
                </x-basecore::inputs.group>
            </div>
        </x-basecore::partials.card>

        <div class="my-5">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Informations
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                informations divers sur les trajets en bus
            </p>
        </div>
        <x-basecore::partials.card>
            <div class="grid grid-cols-2">
                <x-basecore::inputs.group>
                    <x-basecore::inputs.basic label="Adresse de ramassage" name=""  wire:model.lazy="data.addresse_ramassage"/>
                </x-basecore::inputs.group>
                <x-basecore::inputs.group>
                    <x-basecore::inputs.basic label="Adresse de destination" name=""  wire:model.lazy="data.addresse_destination"/>
                </x-basecore::inputs.group>
                <x-basecore::inputs.group>
                    <x-basecore::inputs.basic label="N° de tel chauffeur Aller"  name="" wire:model.lazy="data.aller_tel_chauffeur"/>
                </x-basecore::inputs.group>
                <x-basecore::inputs.group>
                    <x-basecore::inputs.basic label="N° de tel chauffeur Retour" name=""  wire:model.lazy="data.retour_tel_chauffeur"/>
                </x-basecore::inputs.group>
                <x-basecore::inputs.group>
                    <x-basecore::inputs.number label="Nombres de cars" name=""  wire:model.lazy="data.nombre_bus"/>
                </x-basecore::inputs.group>
                <x-basecore::inputs.group>
                    <x-basecore::inputs.number label="Nombre de chauffeurs" name=""  wire:model.lazy="data.nombre_chauffeur"/>
                </x-basecore::inputs.group>
            </div>
        </x-basecore::partials.card>
    </div>

    <div class="flex justify-between items-center mt-5">
        <div></div>
        @if(!$invoice_exists)
        <x-basecore::button type="submit" wire:click="store">
            <i class="mr-1 icon ion-md-save"></i>
            @lang('basecore::crud.common.update')
        </x-basecore::button>
        @else
            <div class="alert alert-danger-soft show flex items-center mb-2" role="alert">
                @icon('noIcon', null, 'mr-2') Ce devis a été converti en facture.
            </div>
        @endif
    </div>
</div>

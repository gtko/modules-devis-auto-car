<div>
    <div class="my-4 flex flex-col" >
        <span>Titre de devis :</span>
        <x-basecore::inputs.text name="devis_titre" wire:model="devis_titre" class="form-control-sm"/>
    </div>
    @if(count($data['trajets'] ?? []) > 0)
        <button wire:click="addTrajet" class="btn btn-primary">@icon('trajet', null, 'mr-2 text-white') Ajouter un
            trajet
        </button>
    @endif

    <div>
        @forelse(($data['trajets'] ?? []) as $keyTrajet => $trajet)
            <livewire:devisautocar::devis-edit-product :key="$keyTrajet" :trajet="$trajet" :trajet-id="$keyTrajet"/>
        @empty
            <div
                class="flex flex-col justify-center items-center h-48 w-full border-warning border-4 border-dashed border-gray-400">
                <span class="text-gray-500 font-bold text-2xl mb-3">Ajouter un trajet à ce devis</span>
                <button wire:click="addTrajet" class="btn btn-primary">@icon('trajet', null, 'mr-2 text-white') Ajouter
                    un trajet
                </button>
            </div>
        @endforelse
        <div class="my-5">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Informations
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                informations divers sur les trajets en autocar
            </p>
        </div>
        <x-basecore::partials.card>
            <div class="grid grid-cols-2">
                <x-basecore::inputs.group>
                    <x-basecore::inputs.basic label="N° de tel chauffeur Aller" name=""
                                              wire:model.lazy="data.aller_tel_chauffeur"/>
                </x-basecore::inputs.group>
                <x-basecore::inputs.group>
                    <x-basecore::inputs.basic label="N° de tel chauffeur Retour" name=""
                                              wire:model.lazy="data.retour_tel_chauffeur"/>
                </x-basecore::inputs.group>
                <x-basecore::inputs.group>
                    <x-basecore::inputs.number label="Nombres de cars" name="" wire:model.lazy="data.nombre_bus"/>
                </x-basecore::inputs.group>
                <x-basecore::inputs.group>
                    <x-basecore::inputs.number label="Nombre de chauffeurs" name=""
                                               wire:model.lazy="data.nombre_chauffeur"/>
                </x-basecore::inputs.group>

            </div>
        </x-basecore::partials.card>

        <div class="my-5">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                TVA
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                Ne pas établir un devis ou facture avec la TVA FR.
            </p>
        </div>
        <x-basecore::partials.card>
            <x-basecore::inputs.group class="w-full">
                <x-basecore::inputs.checkbox
                    wire:model="devis.tva_applicable"
                    name="tva_applicable"
                    label="Tva Applicable"
                    :checked="$devis->tva_applicable"
                />
            </x-basecore::inputs.group>
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
            wire:model="data.commentaire"
            name="commentaire"
            class="h-36"
        />

        <div class="flex justify-between items-center mt-5">
            @if(!$invoice_exists)
                <x-basecore::loading-replace label="Enregistrement en cours">
                    <x-slot name="loader">
                        <div class="btn btn-primary">
                            @icon('spinner', 20, 'animate-spin mr-2')
                            @lang('basecore::crud.common.update')
                        </div>
                    </x-slot>
                    <x-basecore::button type="submit" wire:click="store">
                        <i class="mr-1 icon ion-md-save"></i>
                        @lang('basecore::crud.common.update')
                    </x-basecore::button>
                </x-basecore::loading-replace>
            @else
                <div class="alert alert-danger-soft show flex items-center mb-2" role="alert">
                    @icon('noIcon', null, 'mr-2') Ce devis a été converti en facture.
                </div>
            @endif
        </div>
    </div>

    <script>
        function initInput(selecteur) {
            let addressFields;
            addressFields = document.querySelectorAll(selecteur);
            for (let addressField of addressFields) {


                let autocomplete;
                autocomplete = new google.maps.places.Autocomplete(addressField, {
                    componentRestrictions: {},
                    fields: ["formatted_address", "geometry"],

                });

                autocomplete.addListener("place_changed", () => {

                    const place = autocomplete.getPlace()
                    let latlng = place.geometry.location.lat() + "," + place.geometry.location.lng()
                    let geoField = document.querySelector('[name=' + addressField.getAttribute('name') + "_geo_" + addressField.getAttribute('data-trajet') + ']')
                    geoField.value = latlng
                    addressField.value = place.formatted_address
                @this.emit('devis:update-' + addressField.getAttribute('data-trajet') + '-data', {
                    'name': addressField.getAttribute('name'),
                    'format': place.formatted_address,
                    'geo': latlng
                })
                });
            }
        }

        function initAutocomplete() {
            initInput('.addressmap')
        }
    </script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}=places&v=weekly"
    ></script>

</div>

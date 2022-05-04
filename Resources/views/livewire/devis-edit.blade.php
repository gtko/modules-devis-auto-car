<div>
        @foreach ($errors->all() as $error)
            <div class="w-full h-8 bg-red-600 text-white flex items-center justify-center rounded ">{{ $error }}</div>
        @endforeach
    <div class="my-4 flex flex-col">
        <span>Titre de devis :</span>

        @if(count($data['trajets']) == 0)
            <x-basecore::inputs.text name="devis_titre" wire:model="devis_titre" class="form-control-sm"
                                     wire:keydown.enter="addTrajet"/>
        @else
            <x-basecore::inputs.text name="devis_titre" wire:model="devis_titre" class="form-control-sm"/>
        @endif
    </div>
    @if(count($data['trajets'] ?? []) > 0 || count($data['lines'] ?? []) > 0)
        <button wire:click="addTrajet" class="btn btn-primary">@icon('trajet', null, 'mr-2 text-white') Ajouter un
            trajet
        </button>

        <button wire:click="addLine" class="btn btn-primary">@icon('trajet', null, 'mr-2 text-white') Ajouter une
            ligne
        </button>
    @endif

    <div>
        @foreach(($data['trajets'] ?? []) as $keyTrajet => $trajet)
            <livewire:devisautocar::devis-edit-product :key="$keyTrajet" :trajet="$trajet" :trajet-id="$keyTrajet"/>
            <span class="btn btn-danger my-2 form-control-sm" wire:click="removeTrajet({{$keyTrajet}})">
                @icon('delete', null, 'mr-2')
                Supprimer le  trajet</span>
        @endforeach

        @if(!empty($data['lines'] ?? []))
            <div class="my-5">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Lignes
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Ajouter des options au devis
                </p>
            </div>
            <div>
                <div class="grid grid-cols-12 gap-x-3 items-center mb-2">
                    <div class='col-span-6 font-bold'>Nom</div>
                    <div class='col-span-1 font-bold'>Qté</div>
                    <div class='col-span-2 font-bold'>PU</div>
                </div>
            </div>
            @foreach(($data['lines'] ?? []) as $keyLine => $line)
                <div class="group flex w-full bg-white p-3 justify-between items-center cursor-pointer">
                    <div class="grid grid-cols-12 gap-x-3 items-center">
                        <x-basecore::inputs.text class='col-span-6' name="line" placeholder="ligne"
                                                 wire:model="data.lines.{{$keyLine}}.line"/>
                        <x-basecore::inputs.text class='col-span-1' name="qte" placeholder="Qte"
                                                 wire:model="data.lines.{{$keyLine}}.qte"/>
                        <x-basecore::inputs.text class='col-span-2' name="pu" placeholder="Prix unitaire"
                                                 wire:model="data.lines.{{$keyLine}}.pu"/>
                        <div class="whitespace-nowrap font-bold">
                            @marge(($line['qte'] * $line['pu']) * $line['tva'])€
                        </div>
                    </div>
                    <div wire:click="removeLine({{$keyLine}})" class="hover:text-red-700 cursor-pointer">
                        @icon('delete', null, 'mr-2')
                    </div>
                </div>
            @endforeach
        @endif

        @if(empty($data['trajets'] ?? []) && empty($data['lines'] ?? []))
            <div
                class="flex flex-col justify-center items-center h-48 w-full border-warning border-4 border-dashed border-gray-400">
                <span class="text-gray-500 font-bold text-2xl mb-3">Ajouter un trajet ou une ligne à ce devis</span>
                <div class="flex justify-center items-center space-x-2">
                    <button wire:click="addTrajet" class="btn btn-primary">
                        @icon('trajet', null, 'mr-2 text-white') Ajouter un trajet
                    </button>

                    <button wire:click="addLine" class="btn btn-primary">
                        @icon('note', null, 'mr-2 text-white') Ajouter une ligne
                    </button>
                </div>
            </div>
        @endif
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
                    <x-basecore::inputs.number label="Nombres de cars" name="" wire:model.debounce="data.nombre_bus"/>
                </x-basecore::inputs.group>
                <x-basecore::inputs.group>
                    <x-basecore::inputs.number label="Nombre de chauffeurs" name=""
                                               wire:model.debounce="data.nombre_chauffeur"/>
                </x-basecore::inputs.group>

            </div>
        </x-basecore::partials.card>

        <div class="my-5">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Entête
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                Entête du devis
            </p>
        </div>
        <x-basecore::partials.card>
            <x-basecore::inputs.textarea
                wire:model.debounce="data.entete"
                name="entete"
                class="h-36"
            />
        </x-basecore::partials.card>

        <div class="flex justify-end items-center mt-5 space-x-4 fixed bg-white shadow bg-opacity-50 backdrop-filter backdrop-blur bottom-0 left-0 right-0 p-3">
                <x-basecore::inputs.checkbox
                    wire:model="devis.tva_applicable"
                    name="tva_applicable"
                    label="Tva Applicable"
                    :checked="$devis->tva_applicable"
                />
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
                    region:'EU',
                    fields: ["formatted_address", "geometry"],

                });

                autocomplete.addListener("place_changed", () => {

                    const place = autocomplete.getPlace()
                    let latlng = place.geometry.location.lat() + "," + place.geometry.location.lng()
                    let geoField = document.querySelector('[name=' + addressField.getAttribute('name') + "_geo_" + addressField.getAttribute('data-trajet') + ']')
                    geoField.value = latlng
                    console.log(autocomplete);
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

<div x-data="{open:@entangle('open')}" class="mt-3 border "
     x-bind:class="{'border-transparent' : !open}"
>
    <div class="group flex w-full bg-white p-3 justify-between items-center cursor-pointer"
         x-bind:class="{'rounded' : !open, 'rounded-t border-b bg-gray-200' : open}"
         x-on:click="open=!open">
        <h3 class="text-lg leading-6 font-medium text-gray-900 group-hover:text-gray-600">
            Trajet #{{$trajetId+1}}
        </h3>

        <span class='transform transition group-hover:text-gray-600'
              x-bind:class="{'-rotate-180' : !open}">@icon('chevronDown', null)</span>
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
                        <x-basecore::inputs.datetime label="Date de départ" name="aller_date_depart"
                                                     wire:model="trajet.aller_date_depart"
                                                     placeholder="Date de départ"
                                                     required
                        />
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
                <livewire:devisautocar::devis-distance :key="md5($trajetId.'_aller_'.json_encode($trajet['aller_distance']))" :distance="$trajet['aller_distance']" />
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
                        <x-basecore::inputs.datetime label="Date de départ"
                                                     name="retour_date_depart"
                                                     wire:model="trajet.retour_date_depart"
                                                     placeholder="Date de départ"
                                                     required
                        />
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group class="w-full">
                        <x-basecore::inputs.basic label="Point de départ" class='addressmap' data-trajet="{{$trajetId}}" name="retour_point_depart" wire:model="trajet.retour_point_depart" placeholder="Ville, adresse , ..."/>
                        <input type="hidden" name='retour_point_depart_geo_{{$trajetId}}' wire:model="trajet.retour_point_depart_geo"/>
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group class="w-full">
                        <x-basecore::inputs.basic label="Point d'arriver" class='addressmap' data-trajet="{{$trajetId}}" name="retour_point_arriver" wire:model="trajet.retour_point_arriver" placeholder="Ville, adresse , ..."/>
                        <input type="hidden" name='retour_point_arriver_geo_{{$trajetId}}' wire:model="trajet.retour_point_arriver_geo"/>
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group class="w-full">
                        <x-basecore::inputs.basic label="Pax" name="retour_pax" wire:model.lazy="trajet.retour_pax" placeholder="Nombre de passagers"/>
                    </x-basecore::inputs.group>
                </div>
            </x-basecore::partials.card>
            @if(($trajet['retour_distance'] ?? null))
                <livewire:devisautocar::devis-distance :key="md5($trajetId.'_retour_'.json_encode($trajet['retour_distance']))" :distance="$trajet['retour_distance']"/>
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
                            <x-basecore::inputs.number label="{{$brand->label}}" name="" wire:model.debounce="trajet.brands.{{$brand->id}}" placeholder="Tarif en €"/>
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
                    <span
                        class="flex space-x-1 mt-2 @if(($this->trajet['repas_chauffeur'] ?? false) == 'compris') text-green-500 @elseif(($this->trajet['repas_chauffeur'] ?? false) === 'non_compris') text-red-500 @else text-blue-500 @endif cursor-pointer "
                        wire:click="changeOption('repas_chauffeur')"
                    >
                         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor" class="w-4">
                            <path
                                d="M464 16c-17.67 0-32 14.31-32 32v74.09C392.1 66.52 327.4 32 256 32C161.5 32 78.59 92.34 49.58 182.2c-5.438 16.81 3.797 34.88 20.61 40.28c16.89 5.5 34.88-3.812 40.3-20.59C130.9 138.5 189.4 96 256 96c50.5 0 96.26 24.55 124.4 64H336c-17.67 0-32 14.31-32 32s14.33 32 32 32h128c17.67 0 32-14.31 32-32V48C496 30.31 481.7 16 464 16zM441.8 289.6c-16.92-5.438-34.88 3.812-40.3 20.59C381.1 373.5 322.6 416 256 416c-50.5 0-96.25-24.55-124.4-64H176c17.67 0 32-14.31 32-32s-14.33-32-32-32h-128c-17.67 0-32 14.31-32 32v144c0 17.69 14.33 32 32 32s32-14.31 32-32v-74.09C119.9 445.5 184.6 480 255.1 480c94.45 0 177.4-60.34 206.4-150.2C467.9 313 458.6 294.1 441.8 289.6z"/></svg>
                       <span> Repas Chauffeur ( @if(($this->trajet['repas_chauffeur'] ?? false) == 'compris')
                               compris @elseif(($this->trajet['repas_chauffeur'] ?? false) === 'non_compris') non compris @else non
                               affiché @endif )
                           </span>
                    </span>

                    <span
                        class="flex space-x-1 mt-2 @if(($this->trajet['hebergement'] ?? false) == 'compris') text-green-500 @elseif(($this->trajet['hebergement'] ?? false) === 'non_compris') text-red-500 @else text-blue-500 @endif cursor-pointer "
                        wire:click="changeOption('hebergement')"
                    >
                         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor" class="w-4">
                            <path
                                d="M464 16c-17.67 0-32 14.31-32 32v74.09C392.1 66.52 327.4 32 256 32C161.5 32 78.59 92.34 49.58 182.2c-5.438 16.81 3.797 34.88 20.61 40.28c16.89 5.5 34.88-3.812 40.3-20.59C130.9 138.5 189.4 96 256 96c50.5 0 96.26 24.55 124.4 64H336c-17.67 0-32 14.31-32 32s14.33 32 32 32h128c17.67 0 32-14.31 32-32V48C496 30.31 481.7 16 464 16zM441.8 289.6c-16.92-5.438-34.88 3.812-40.3 20.59C381.1 373.5 322.6 416 256 416c-50.5 0-96.25-24.55-124.4-64H176c17.67 0 32-14.31 32-32s-14.33-32-32-32h-128c-17.67 0-32 14.31-32 32v144c0 17.69 14.33 32 32 32s32-14.31 32-32v-74.09C119.9 445.5 184.6 480 255.1 480c94.45 0 177.4-60.34 206.4-150.2C467.9 313 458.6 294.1 441.8 289.6z"/></svg>
                      <span>  Hébergement ( @if(($this->trajet['hebergement'] ?? false) == 'compris')
                              compris @elseif(($this->trajet['hebergement'] ?? false) === 'non_compris') non compris @else non
                              affiché @endif )</span>
                    </span>

                    <span
                        class="flex space-x-1 mt-2 @if(($this->trajet['parking'] ?? false) == 'compris') text-green-500 @elseif(($this->trajet['parking'] ?? false) === 'non_compris') text-red-500 @else text-blue-500 @endif cursor-pointer "
                        wire:click="changeOption('parking')"
                    >
                         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor" class="w-4">
                            <path
                                d="M464 16c-17.67 0-32 14.31-32 32v74.09C392.1 66.52 327.4 32 256 32C161.5 32 78.59 92.34 49.58 182.2c-5.438 16.81 3.797 34.88 20.61 40.28c16.89 5.5 34.88-3.812 40.3-20.59C130.9 138.5 189.4 96 256 96c50.5 0 96.26 24.55 124.4 64H336c-17.67 0-32 14.31-32 32s14.33 32 32 32h128c17.67 0 32-14.31 32-32V48C496 30.31 481.7 16 464 16zM441.8 289.6c-16.92-5.438-34.88 3.812-40.3 20.59C381.1 373.5 322.6 416 256 416c-50.5 0-96.25-24.55-124.4-64H176c17.67 0 32-14.31 32-32s-14.33-32-32-32h-128c-17.67 0-32 14.31-32 32v144c0 17.69 14.33 32 32 32s32-14.31 32-32v-74.09C119.9 445.5 184.6 480 255.1 480c94.45 0 177.4-60.34 206.4-150.2C467.9 313 458.6 294.1 441.8 289.6z"/></svg>
                     <span>   Parking ( @if(($this->trajet['parking'] ?? false) == 'compris')
                             compris @elseif(($this->trajet['parking'] ?? false) === 'non_compris') non compris @else non
                             affiché @endif )
                         </span>
                    </span>

                    <span
                        class="flex space-x-1 mt-2 @if(($this->trajet['peages'] ?? false) == 'compris') text-green-500 @elseif(($this->trajet['peages'] ?? false)  === 'non_compris') text-red-500 @else text-blue-500 @endif cursor-pointer "
                        wire:click="changeOption('peages')"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor" class="w-4">
                            <path
                                d="M464 16c-17.67 0-32 14.31-32 32v74.09C392.1 66.52 327.4 32 256 32C161.5 32 78.59 92.34 49.58 182.2c-5.438 16.81 3.797 34.88 20.61 40.28c16.89 5.5 34.88-3.812 40.3-20.59C130.9 138.5 189.4 96 256 96c50.5 0 96.26 24.55 124.4 64H336c-17.67 0-32 14.31-32 32s14.33 32 32 32h128c17.67 0 32-14.31 32-32V48C496 30.31 481.7 16 464 16zM441.8 289.6c-16.92-5.438-34.88 3.812-40.3 20.59C381.1 373.5 322.6 416 256 416c-50.5 0-96.25-24.55-124.4-64H176c17.67 0 32-14.31 32-32s-14.33-32-32-32h-128c-17.67 0-32 14.31-32 32v144c0 17.69 14.33 32 32 32s32-14.31 32-32v-74.09C119.9 445.5 184.6 480 255.1 480c94.45 0 177.4-60.34 206.4-150.2C467.9 313 458.6 294.1 441.8 289.6z"/></svg>
                       <span> Péages ( @if(($this->trajet['peages'] ?? false) == 'compris')
                               compris @elseif(($this->trajet['peages'] ?? false) === 'non_compris') non compris @else non
                               affiché @endif )
                        </span>
                    </span>


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
                        <x-basecore::inputs.basic label="Adresse de ramassage du trajet aller" name=""
                                                  wire:model.debounce="trajet.addresse_ramassage"/>
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group>
                        <x-basecore::inputs.basic label="Adresse de destination du trajet aller" name=""
                                                  wire:model.debounce="trajet.addresse_destination"/>
                    </x-basecore::inputs.group>

                    <x-basecore::inputs.group>
                        <x-basecore::inputs.basic label="Adresse de ramassage du trajet retour" name=""
                                                  wire:model.debounce="trajet.addresse_ramassage_retour"/>
                    </x-basecore::inputs.group>
                    <x-basecore::inputs.group>
                        <x-basecore::inputs.basic label="Adresse de destination du trajet retour" name=""
                                                  wire:model.debounce="trajet.addresse_destination_retour"/>
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
                wire:model.debounce="trajet.commentaire"
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

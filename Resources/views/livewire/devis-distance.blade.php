<div class="flex flex-col mt-5 w-full">
    <x-corecrm::timeline-item>
        <x-slot name="image">
            @icon('busStop')
        </x-slot>
        <div class="flex items-center">
            <div class="font-medium">
                {{$this->distance['origin_formatted'] ?? 'Aucun point de départ renseigné'}}
            </div>
        </div>
    </x-corecrm::timeline-item>
    <x-corecrm::timeline-item>
        <x-slot name="image">
            @icon('trajet')
        </x-slot>
        <div class="flex items-center">
            <div class="font-normal">
                Temps de trajet de
                <span class="font-medium text-blue-500">{{$this->distance['duration_formatted']}}</span>
                pour une distance de
                <span class="font-medium text-blue-500">{{$this->distance['distance_formatted']}}</span>
            </div>
        </div>
    </x-corecrm::timeline-item>
    <x-corecrm::timeline-item>
        <x-slot name="image">
            @icon('busStop')
        </x-slot>
        <div class="flex items-center">
            <div class="font-medium">
                {{$this->distance['destination_formatted'] ??  "Aucun point de d'arrivé renseigné"}}
            </div>
        </div>
    </x-corecrm::timeline-item>
</div>

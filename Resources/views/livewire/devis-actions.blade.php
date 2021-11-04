<div class="flex items-center justify-between">



    @can('update', $devis)
        <a class="flex items-center"
           href="{{route('devis.edit', [$client, $dossier, $devis])}}">
            @icon("edit", null,"w-4 h-4 mr-1")
        </a>
    @endcan


    <livewire:devisautocar::send-devis :devis="$devis"/>

    <x-corecrm::link-devis :devis="$devis" class="flex items-center mr-3">
        @icon("show", null,"w-4 h-4 mr-1")
    </x-corecrm::link-devis>

    <a class='ignore-link' href="{{route('pdf-devis-download', $devis)}}" target="_blank">
        @icon('pdf', null, 'w-4 h-4 mr-1')
    </a>

    @if($state === 'devis')
        <livewire:crmautocar::create-proformat :devis="$devis"/>
    @endif

</div>

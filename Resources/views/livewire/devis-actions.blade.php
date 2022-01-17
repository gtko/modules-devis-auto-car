<div class="flex items-center justify-between">


    @can('update', $devis)
        <a class="flex items-center"
           title="Editer le devis"
           href="{{route('devis.edit', [$client, $dossier, $devis])}}">
            @icon("edit", null,"w-4 h-4 mr-1")
        </a>
    @endcan


    <livewire:devisautocar::send-devis :devis="$devis"/>

    <x-corecrm::link-devis :devis="$devis" class="flex items-center" title="voir le devis">
        @icon("show", null,"w-4 h-4 mr-1")
    </x-corecrm::link-devis>

    <a class='ignore-link' href="{{route('pdf-devis-download', $devis)}}" target="_blank">
        @icon('pdf', null, 'w-4 h-4 mr-1')
    </a>

    @if($state === 'devis')
        <livewire:crmautocar::create-proformat :devis="$devis"/>
    @endif
{{--    @if (Auth::user()->hasRole('super-admin'))--}}

{{--        <livewire:devisautocar::delete-devis :devis="$devis"/>--}}

{{--    @endif--}}

</div>

<div class="flex items-center justify-between">
    <x-corecrm::link-devis :devis="$devis" class="flex items-center" title="voir le devis">
        @icon("show", null,"w-4 h-4 mr-1")
    </x-corecrm::link-devis>
    @if(!$devis->validate && !$devis->hasCancel())
        @can('update', $devis)
            <a class="flex items-center"
               title="Editer le devis"
               target="_blank"
               href="{{route('devis.edit', [$client, $dossier, $devis])}}">
                @icon("edit", null,"w-4 h-4 mr-1")
            </a>
        @endcan
    @endif

    @if($devis->sendable)
        <livewire:devisautocar::send-devis :devis="$devis"/>
        <livewire:devisautocar::send-devis-whatapps :devis="$devis"/>


        <a class='ignore-link' href="{{route('pdf-devis-download', $devis)}}" target="_blank">
            @icon('pdf', null, 'w-4 h-4 mr-1')
        </a>

        @if($state === 'devis')
            <livewire:crmautocar::create-proformat :devis="$devis"/>
        @endif
    @else
        <a class="flex"  href="{{route('devis.edit', [$client, $dossier, $devis])}}">
            <svg class='w-5 h-5 mr-1 text-red-600' fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                <path d="M320 128C333.3 128 344 138.7 344 152V264C344 277.3 333.3 288 320 288C306.7 288 296 277.3 296 264V152C296 138.7 306.7 128 320 128zM352 352C352 369.7 337.7 384 320 384C302.3 384 288 369.7 288 352C288 334.3 302.3 320 320 320C337.7 320 352 334.3 352 352zM96 256C96 132.3 196.3 32 320 32C443.7 32 544 132.3 544 256C544 379.7 443.7 480 320 480C196.3 480 96 379.7 96 256zM320 432C417.2 432 496 353.2 496 256C496 158.8 417.2 80 320 80C222.8 80 144 158.8 144 256C144 353.2 222.8 432 320 432zM83.92 75.82C95.06 82.99 98.28 97.85 91.11 108.1C63.83 151.4 48 201.8 48 255.1C48 310.2 63.83 360.6 91.11 403C98.28 414.2 95.06 429 83.92 436.2C72.77 443.4 57.92 440.1 50.75 428.1C18.63 379.1 0 319.7 0 255.1C0 192.3 18.63 132.9 50.75 83.01C57.92 71.86 72.77 68.65 83.92 75.82V75.82zM556.1 75.82C567.2 68.65 582.1 71.86 589.3 83.01C621.4 132.9 640 192.3 640 255.1C640 319.7 621.4 379.1 589.3 428.1C582.1 440.1 567.2 443.4 556.1 436.2C544.9 429 541.7 414.2 548.9 403C576.2 360.6 592 310.2 592 255.1C592 201.8 576.2 151.4 548.9 108.1C541.7 97.85 544.9 82.99 556.1 75.82V75.82z"/></svg>
        </a>
    @endif

    @if(!$devis->hasCanceller())
        <livewire:devisautocar::duplicate-devis :devis="$devis"/>
    @endif

    @if(!$devis->validate)
        <livewire:devisautocar::delete-devis :devis="$devis"/>
    @endif

</div>

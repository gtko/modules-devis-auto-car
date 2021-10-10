<div>
    <div class="overflow-x-auto">
        <table class="table mt-5">
            <thead>
            <tr class="text-gray-700">
                <th class="whitespace-nowrap" colspan="3">
                    {{$devis->count()}} devis
                </th>
                @foreach($brands as $brand)
                    <th colspan="2">{{$brand->label}}</th>
                @endforeach
                <th></th>
            </tr>
            <tr class="bg-gray-100 text-gray-700">
                <th class="whitespace-nowrap">#</th>
                <th class="whitespace-nowrap">Commercial</th>
                <th class="whitespace-nowrap">Date du devis</th>
                @foreach($brands as $brand)
                    <th class="whitespace-nowrap">Tarif</th>
                    <th class="whitespace-nowrap"></th>
                @endforeach
                <th class="whitespace-nowrap">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($devis as $devi)
                <tr>
                    <td class="border-b dark:border-dark-5">
                        <a href="{{route('devis.edit', [$client, $dossier, $devi])}}">#{{$devi->ref}}</a>
                    </td>
                    <td class="border-b dark:border-dark-5">
                        {{$devi->commercial->format_name}}
                    </td>
                    <td class="border-b dark:border-dark-5">
                        {{$devi->created_at->format('d/m/Y H:i')}}
                    </td>
                    @foreach($brands as $brand)
                        <td class="border-b dark:border-dark-5">{{$devi->data['brands'][$brand->id] ?? 0}}€</td>
                        <td class="border-b dark:border-dark-5"></td>
                    @endforeach
                    <td class="border-b dark:border-dark-5">
                        <div class="flex">
                            @can('update', $devi)
                                <a class="flex items-center mr-3"
                                   href="{{route('devis.edit', [$client, $dossier, $devi])}}">
                                    @icon("edit", null,"w-4 h-4 mr-1")
                                </a>
                            @endcan

                            <x-corecrm::link-devis :devis="$devi" class="flex items-center mr-3">
                                @icon("show", null,"w-4 h-4 mr-1")
                            </x-corecrm::link-devis>

                            <a class='ignore-link' href="{{route('pdf-devis-download', $devi)}}">
                                @icon('pdf', null, 'w-4 h-4 mr-1')
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{$devis->links()}}
    </div>
</div>

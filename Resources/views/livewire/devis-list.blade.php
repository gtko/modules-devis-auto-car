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
                <th class="whitespace-nowrap">Titre</th>
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

                <livewire:devisautocar::devis-list-detail :devi="$devi" :client="$client" :dossier="$dossier"
                                                          :brands="$brands" :key="$devi->id"/>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{$devis->links()}}
    </div>
</div>

<tr>
    <td class="border-b dark:border-dark-5">
        <a href="{{route('devis.edit', [$client, $dossier, $devi])}}">#{{$devi->ref}}</a>
    </td>
    <td class="border-b dark:border-dark-5 whitespace-nowrap">
        {{ $devi->title ?? 'N/A' }}
    </td>
    <td class="border-b dark:border-dark-5">
        {{$devi->commercial->format_name}}
    </td>
    <td class="border-b dark:border-dark-5">
        {{$devi->created_at->format('d/m/Y H:i')}}
    </td>
    @foreach($brands as $brand)
        <td class="border-b dark:border-dark-5">
            {{(new Modules\DevisAutoCar\Entities\DevisPrice($devi, $brand))->getPriceTTC()}}€
        </td>
        <td class="border-b dark:border-dark-5"></td>
    @endforeach
    <td class="border-b dark:border-dark-5">
        <livewire:devisautocar::devis-actions :devis="$devi"/>
    </td>
</tr>

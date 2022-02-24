<x-basecore::loading-replace wire:target="delete()">
    <span title="supprimer le devis" wire:click="delete()" class="cursor-pointer">
            @icon('delete', null, 'w-4 h-4 mr-1')
    </span>
</x-basecore::loading-replace>

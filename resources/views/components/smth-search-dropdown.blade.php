<div>
    <div class="flex py-2 border-b border-gray-500 ">
        <div class="flex-shrink-0 w-1/5 py-1">{{ $label }}:</div>
        <div 
            x-data="{ isOpen: true, found: {{ $found->toJson() }} }" 
            @@click.away="isOpen = false"
            class="relative w-2/5"
        >
            <input 
                class="w-full px-3 py-1" 
                type="text" 
                @@focus="isOpen = true"
                @@click="isOpen = true"
                @@keydown.escape.window="isOpen = false"
                @@keydown.prevent.arrow-down="$refs.list.querySelector('li>a')?.focus()" 
                @@keydown.prevent.arrow-up="$refs.list.lastElementChild.firstElementChild?.focus()"
                wire:keydown.enter.prevent="add{{ ucfirst($smth) }}($event.target.value || $event.target.innerText)" 
                wire:model="{{ $smth }}"
                x-ref="searchLine"
                title="Введите что-нибудь и нажмите Enter"
            >
            <ul 
                x-ref="list" 
                x-show.transition.opacity="isOpen && found.length"
                @@mouseover="$event.target.focus()"
                wire:click.prevent="add{{ ucfirst($smth) }}($event.target.innerText)"
                class="absolute z-50 w-full text-blue-300 bg-gray-900"
            >
                <template x-for="(item, index) in found">
                    <li>
                        <a 
                            :data-index = "index"
                            href="#"                            
                            class="block px-3 focus:bg-gray-700"
                            x-text = "item"
                            @@keydown.arrow-up.prevent="(($event.target.dataset.index == 0 && $refs.searchLine) || $event.target.parentElement.previousElementSibling.firstElementChild).focus()"
                            @@keydown.arrow-down.prevent="(($event.target.dataset.index == found.length - 1 && $refs.searchLine) || $event.target.parentElement.nextElementSibling.firstElementChild).focus()"
                        ></a>
                    </li>
                </template>
            </ul>
        </div>
        <div class="pl-3" wire:click="remove{{ ucfirst($smth) }}($event.target.dataset.index)">
            @foreach($added as $idx => $item)
                <p class="cursor-pointer hover:line-through" data-index={{ $idx }}>{{ $item }}</p>
            @endforeach
        </div>
    </div>
</div>
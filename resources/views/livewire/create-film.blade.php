<form class="flex mt-5">
    <input type="hidden" wire:model="submitted">
    <div class="w-2/5">
        @if(!$poster)
            <img src="{{ $posterUrl }}" alt="Постер">
        @else
            <img src="{{ $poster->temporaryUrl() }}" alt="Постер">
        @endif
        <input style="max-width: 300px;" type="file" title="Загрузить постер" wire:model="poster">
    </div>
    <div class="w-3/5 ml-10">
        <div class="flex justify-end">
            <a href="#" title="Загрузить" wire:click.prevent="store">
                <svg xmlns="http://www.w3.org/2000/svg" class="rounded-full" width="24px" height="24px">
                    <path fill="#1e429f" d="M12,2A10,10,0,1,0,22,12,10,10,0,0,0,12,2Zm2.29,11.71L13,12.41V18H11V12.41L9.71,13.71,8.29,12.29,12,8.59l3.71,3.71ZM16,8H8V6h8Z"/>
                </svg>
            </a>
        </div>
        <div class="flex py-2 border-b border-gray-500">
            <div class="w-1/5 py-1">Видеофайл:</div>
            <input class="w-4/5 flex-grow" type="file" title="Загрузить видеофайл" wire:model="video">
        </div>
        <div class="flex py-2 border-b border-gray-500">
            <div class="w-1/5 py-1">Название:</div>
            <input class="flex-grow px-3 py-1" type="text" wire:model="title">
        </div>
        <div class="flex py-2 border-b border-gray-500">
            <div class="w-1/5 py-1">Name:</div>
            <input class="flex-grow px-3 py-1" type="text" wire:model="titleAlt">
        </div>
        <div class="flex py-2 border-b border-gray-500">
            <div class="w-1/5 py-1">Год:</div>
            <input class="flex-grow px-3 py-1" type="number" wire:model="year">
        </div>
        <div class="flex py-2 border-b border-gray-500">
            <div class="w-1/5 py-1 flex-shrink-0">Страна:</div>
            <div 
                x-data="{ isOpen: false }" 
                @@click.away="isOpen = false"
                class="relative w-2/5"
            >
                <input 
                    class="px-3 py-1 w-full" 
                    type="text" 
                    @@focus="isOpen = true"
                    @@click="isOpen = true"
                    @@keydown.escape.window="isOpen = false"
                    @@keydown.prevent.arrow-down="$refs.list.firstElementChild.firstElementChild.focus()"
                    @@keydown.prevent.arrow-up="$refs.list.lastElementChild.firstElementChild.focus()"
                    wire:keydown.enter.prevent="addCountry($event.target.value || $event.target.innerText)" 
                    wire:model="country"
                    x-ref="country"
                    title="Введите страну и нажмите Enter"
                >
                @if (count($foundCountries) > 0)
                    <ul 
                        x-ref="list" 
                        x-show.transition.opacity="isOpen"
                        @@mouseover="$event.target.focus()"
                        wire:click.prevent="addCountry($event.target.innerText)"
                        class="absolute bg-gray-900 text-blue-300 w-full z-50"
                    >
                        @foreach($foundCountries as $cntr)
                            <li>
                                <a 
                                    href="#"                            
                                    class="block px-3 focus:bg-gray-700"
                                    @if($loop->first)
                                        @@keydown.prevent.arrow-up="$refs.country.focus()"
                                    @else
                                        @@keydown.prevent.arrow-up="$event.target.parentElement.previousElementSibling.firstElementChild.focus()"
                                    @endif
                                    @if($loop->last)
                                        @@keydown.prevent.arrow-down="$refs.country.focus()"
                                    @else
                                        @@keydown.prevent.arrow-down="$event.target.parentElement.nextElementSibling.firstElementChild.focus()"
                                    @endif
                                >
                                    {{ $cntr }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="pl-3" wire:click="removeCountry($event.target.dataset.index)">
                @foreach($countries as $idx => $cntr)
                    <p class="cursor-pointer hover:line-through" data-index={{ $idx }}>{{ $cntr }}</p>
                @endforeach
            </div>
        </div>

        <div class="flex py-2 border-b border-gray-500">
            <div class="w-1/5 py-1">Жанры:</div>
            <select 
                class="w-2/5 flex-grow px-3" 
                wire:click="setGenre($event.target.value, $event.target.selected, $event.ctrlKey)" 
                wire:model="genres"
                multiple
            >
                @foreach($allgenres as $genre)
                    <option>{{ $genre }}</option>
                @endforeach
            </select>
            <div class="w-2/5 pl-3">{{ implode(', ', $genres) }}</div>
        </div>

        <div class="flex py-2 border-b border-gray-500 ">
            <div class="w-1/5 py-1 flex-shrink-0">Режиссеры:</div>
            <div 
                x-data="{ isOpen: false }" 
                @@click.away="isOpen = false"
                class="relative w-2/5"
            >
                <input 
                    class="px-3 py-1 w-full" 
                    type="text" 
                    @@focus="isOpen = true"
                    @@click="isOpen = true"
                    @@keydown.escape.window="isOpen = false"
                    @@keydown.prevent.arrow-down="$refs.list.firstElementChild.firstElementChild.focus()"
                    @@keydown.prevent.arrow-up="$refs.list.lastElementChild.firstElementChild.focus()"
                    wire:keydown.enter.prevent="addDirector($event.target.value || $event.target.innerText)" 
                    wire:model="director"
                    x-ref="director"
                    title="Введите режиссера и нажмите Enter"
                >
                @if (count($foundDirectors) > 0)
                    <ul 
                        x-ref="list" 
                        x-show.transition.opacity="isOpen"
                        @@mouseover="$event.target.focus()"
                        wire:click.prevent="addDirector($event.target.innerText)"
                        class="absolute bg-gray-900 text-blue-300 w-full z-50"
                    >
                        @foreach($foundDirectors as $dir)
                            <li>
                                <a 
                                    href="#"                            
                                    class="block px-3 focus:bg-gray-700"
                                    @if($loop->first)
                                        @@keydown.prevent.arrow-up="$refs.director.focus()"
                                    @else
                                        @@keydown.prevent.arrow-up="$event.target.parentElement.previousElementSibling.firstElementChild.focus()"
                                    @endif
                                    @if($loop->last)
                                        @@keydown.prevent.arrow-down="$refs.director.focus()"
                                    @else
                                        @@keydown.prevent.arrow-down="$event.target.parentElement.nextElementSibling.firstElementChild.focus()"
                                    @endif
                                >
                                    {{ $dir }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="pl-3" wire:click="removeDirector($event.target.dataset.index)">
                @foreach($directors as $idx => $dir)
                    <p class="cursor-pointer hover:line-through" data-index={{ $idx }}>{{ $dir }}</p>
                @endforeach
            </div>
        </div>

        <div class="flex py-2 border-b border-gray-500">
            <div class="w-1/5 py-1 flex-shrink-0">Сценаристы:</div>
            <div 
                x-data="{ isOpen: false }" 
                @@click.away="isOpen = false"
                class="relative w-2/5"
            >
                <input 
                    class="px-3 py-1 w-full" 
                    type="text" 
                    @@focus="isOpen = true"
                    @@click="isOpen = true"
                    @@keydown.escape.window="isOpen = false"
                    @@keydown.prevent.arrow-down="$refs.list.firstElementChild.firstElementChild.focus()"
                    @@keydown.prevent.arrow-up="$refs.list.lastElementChild.firstElementChild.focus()"
                    wire:keydown.enter.prevent="addScreenwriter($event.target.value || $event.target.innerText)" 
                    wire:model="screenwriter"
                    x-ref="screenwriter"
                    title="Введите сценариста и нажмите Enter"
                >
                @if (count($foundScreenwriters) > 0)
                    <ul 
                        x-ref="list" 
                        x-show.transition.opacity="isOpen"
                        @@mouseover="$event.target.focus()"
                        wire:click.prevent="addScreenwriter($event.target.innerText)"
                        class="absolute bg-gray-900 text-blue-300 w-full z-50"
                    >
                        @foreach($foundScreenwriters as $scr)
                            <li>
                                <a 
                                    href="#"                            
                                    class="block px-3 focus:bg-gray-700"
                                    @if($loop->first)
                                        @@keydown.prevent.arrow-up="$refs.screenwriter.focus()"
                                    @else
                                        @@keydown.prevent.arrow-up="$event.target.parentElement.previousElementSibling.firstElementChild.focus()"
                                    @endif
                                    @if($loop->last)
                                        @@keydown.prevent.arrow-down="$refs.screenwriter.focus()"
                                    @else
                                        @@keydown.prevent.arrow-down="$event.target.parentElement.nextElementSibling.firstElementChild.focus()"
                                    @endif
                                >
                                    {{ $scr }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="pl-3" wire:click="removeScreenwriter($event.target.dataset.index)">
                @foreach($screenwriters as $idx => $scr)
                    <p class="cursor-pointer hover:line-through" data-index={{ $idx }}>{{ $scr }}</p>
                @endforeach
            </div>
        </div>

        <div class="flex py-2">
            <div class="w-1/5 py-1">Описание:</div>
            <textarea class="flex-grow px-3 py-1" rows="5" wire:model="description">{{ $description }}</textarea>
        </div> 
    </div>
</form>
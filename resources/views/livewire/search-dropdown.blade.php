<div class="flex items-center relative w-2/5 mr-10" x-data="{ isOpen: true }" @click.away="isOpen = false">
    <input 
        wire:model.debounce.300ms="search" 
        type="text" 
        class="border border-gray-700 border-solid w-full text-gray-500 bg-gray-800 focus:text-black focus:bg-gray-400 focus:placeholder-black py-2 pl-3 pr-10" 
        placeholder="Поиск и добавление"
        {{-- @if (count($searchResults) > 0) - не работает --}}
        {{-- по-видимому, нельзя указывать условие в модели wire:model, которое зависит от этой модели --}}
        {{-- возможное решение - переместить рендер результатов поиска на клиентскую часть --}}
            @@focus="isOpen = true"
            @@click="isOpen = true"
            @@keydown.prevent.arrow-down="$refs.links.firstElementChild.nextElementSibling.firstElementChild.focus()"
            @@keydown.prevent.arrow-up="$refs.links.lastElementChild.firstElementChild.focus()"
            @@keydown.prevent.tab="$refs.links.firstElementChild.nextElementSibling.firstElementChild.focus()"
            @@keydown.prevent.shift.tab="$refs.links.lastElementChild.firstElementChild.focus()"
            @@keydown.escape.window="isOpen = false"
            x-ref="search"
        {{-- @endif --}}
    >
    <span class="absolute right-0 flex items-center justify-center text-gray-600 h-10 w-10 pointer-events-none">
        <svg class="h-4" aria-hidden="true" data-prefix="fas" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
            <path fill="currentColor" d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path>
        </svg>
    </span>
    <div 
        class="fixed mt-16 top-0 bg-gray-900 w-2/5" 
        x-show.transition.opacity="isOpen"
    >
        @if (count($searchResults['kinopoisk']) > 0 || count($searchResults['hasInDb']) > 0)
            <ul x-ref="links" @@mouseover="$event.target.focus()">
                @if (count($searchResults['hasInDb']) > 0)
                    <li class="text-center bg-blue-900">Просмотр</li>
                    @foreach($searchResults['hasInDb'] as $result)
                        <li class="border-b border-gray-700">
                            <a 
                                href="{{ route('movies.show', ['video' => $result['id']]) }}" 
                                class="px-3 py-3 flex items-center justify-between focus:bg-gray-700"
                                @if ($loop->first)
                                    @@keydown.prevent.arrow-up="$refs.search.focus()"
                                @else
                                    @@keydown.prevent.arrow-up="$event.target.parentElement.previousElementSibling.firstElementChild.focus()"
                                @endif
                                @if ($loop->last) 
                                    @if (count($searchResults['kinopoisk']) > 0)
                                        @@keydown.prevent.arrow-down="$event.target.parentElement.nextElementSibling.nextElementSibling.firstElementChild.focus()"
                                    @else
                                        @@keydown.prevent.arrow-down="$refs.search.focus()"
                                        @@keydown.tab.prevent="$refs.search.focus()"
                                        @@keydown.prevent.shift.tab="$event.target.parentElement.previousElementSibling.firstElementChild.focus()"
                                    @endif
                                @else
                                    @@keydown.prevent.arrow-down="$event.target.parentElement.nextElementSibling.firstElementChild.focus()"
                                @endif
                            >
                                <img src="https:{{ $result['poster'] }}" alt="poster" class="w-16 flex-shrink-0">
                                <div class="mx-4 text-lg flex-grow">
                                    <p>{{ $result['russian'] ?? $result['original'] }}</p>
                                    <span class="text-xs text-gray-400">
                                        @if ($result['russian'] && $result['original'])
                                            {{ $result['original'] }},
                                        @endif
                                        {{ $result['productionYear'] }}
                                    </span>
                                </div>
                                <span x-data="{ rating: {{ $result['rating'] }} }" x-text="rating ? rating.toFixed(1) : '—'" class="text-xl" 
                                    :class="{'text-gray-400': rating === 0 || rating >= 5 && rating < 7, 'text-green-500': rating >= 7, 'text-red-500': rating < 5 && rating > 0}"
                                ></span>
                            </a>
                        </li>
                    @endforeach
                @endif

                @if (count($searchResults['kinopoisk']) > 0)
                    <li class="text-center bg-blue-900">Добавление</li>
                    @foreach ($searchResults['kinopoisk'] as $result)
                        <li class="border-b border-gray-700">
                            <a 
                                href="{{ route('movies.create', ['kinopoiskId' => $result['kinopoiskId']]) }}" 
                                class="px-3 py-3 flex items-center justify-between focus:bg-gray-700"
                                @if ($loop->first)
                                    @if (count($searchResults['hasInDb']) > 0)
                                        @@keydown.prevent.arrow-up="$event.target.parentElement.previousElementSibling.previousElementSibling.firstElementChild.focus()"
                                    @else
                                        @@keydown.prevent.arrow-up="$refs.search.focus()"
                                    @endif
                                @else
                                    @@keydown.prevent.arrow-up="$event.target.parentElement.previousElementSibling.firstElementChild.focus()"
                                @endif
                                @if ($loop->last) 
                                    @@keydown.tab.prevent="$refs.search.focus()" 
                                    @@keydown.prevent.shift.tab="$event.target.parentElement.previousElementSibling.firstElementChild.focus()"
                                    @@keydown.prevent.arrow-down="$refs.search.focus()"
                                @else
                                    @@keydown.prevent.arrow-down="$event.target.parentElement.nextElementSibling.firstElementChild.focus()"
                                @endif
                            >
                                <img src="https:{{ $result['poster'] }}" alt="poster" class="w-16 flex-shrink-0">
                                <div class="mx-4 text-lg flex-grow">
                                    <p>{{ $result['russian'] ?? $result['original'] }}</p>
                                    <span class="text-xs text-gray-400">
                                        @if ($result['russian'] && $result['original'])
                                            {{ $result['original'] }},
                                        @endif
                                        {{ $result['productionYear'] }}
                                    </span>
                                </div>
                                <span x-data="{ rating: {{ $result['rating'] }} }" x-text="rating ? rating.toFixed(1) : '—'" class="text-xl" 
                                    :class="{'text-gray-400': rating === 0 || rating >= 5 && rating < 7, 'text-green-500': rating >= 7, 'text-red-500': rating < 5 && rating > 0}"
                                ></span>
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
        @endif
    </div>
</div>
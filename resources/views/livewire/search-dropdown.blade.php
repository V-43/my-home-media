<div class="relative flex items-center w-2/5 mr-10" 
    x-data="{ isOpen: true, kinopoisk: {{ $searchResults['kinopoisk']->toJson() }}, hasInDb: {{ $searchResults['hasInDb']->toJson() }} }" 
    @@click.away="isOpen = false"
>
    <input 
        wire:model.debounce.300ms="search" 
        type="text" 
        class="w-full py-2 pl-3 pr-10 text-gray-500 bg-gray-800 border border-gray-700 border-solid focus:text-black focus:bg-gray-400 focus:placeholder-black" 
        placeholder="Поиск и добавление"
        @@focus="isOpen = true"
        @@click="isOpen = true"
        @@keydown.arrow-down.prevent="($refs.showLinks || $refs.createLinks)?.querySelector('li>a').focus()"
        @@keydown.arrow-up.prevent="($refs.createLinks || $refs.showLinks)?.lastElementChild.firstElementChild.focus()"
        @@keydown.escape.window="isOpen = false"
        x-ref="search"
    >
    <span class="absolute right-0 flex items-center justify-center w-10 h-10 text-gray-600 pointer-events-none">
        <svg class="h-4" aria-hidden="true" data-prefix="fas" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
            <path fill="currentColor" d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path>
        </svg>
    </span>
    <div 
        class="fixed top-0 w-2/5 mt-16 bg-gray-900" 
        x-show.transition.opacity="isOpen && (kinopoisk.length || hasInDb.length)"
    >
        <ul @@mouseover="$event.target.focus()">
            <template x-if="hasInDb.length">
                <div x-ref="showLinks">
                    <li class="text-center bg-blue-900">Просмотр</li>
                    <template x-for="(item, index) in hasInDb">
                        <li class="border-b border-gray-700">
                            <a 
                                :href="item.href" 
                                :data-index="index"
                                class="flex items-center justify-between px-3 py-3 focus:bg-gray-700"
                                @@keydown.arrow-up.prevent="(($event.target.dataset.index == 0 && $refs.search) || $event.target.parentElement.previousElementSibling.firstElementChild).focus()"
                                @@keydown.arrow-down.prevent="(($event.target.dataset.index == hasInDb.length - 1 && ($refs.createLinks?.querySelector('li>a') || $refs.search)) || $event.target.parentElement.nextElementSibling.firstElementChild).focus()"
                            >
                                <img :src="'https:' + item.poster" alt="poster" class="flex-shrink-0 w-16">
                                <div class="flex-grow mx-4 text-lg">
                                    <p x-text="item.russian || item.original"></p>
                                    <span class="text-xs text-gray-400">
                                        <template x-if="item.russian && item.original">
                                            <span x-text="item.original+','"></span>
                                        </template>
                                        <span x-text="item.productionYear"></span>
                                    </span>
                                </div>
                                <span x-text="item.rating ? item.rating.toFixed(1) : '—'" class="text-xl" 
                                    :class="{'text-gray-400': item.rating === 0 || item.rating >= 5 && item.rating < 7, 'text-green-500': item.rating >= 7, 'text-red-500': item.rating < 5 && item.rating > 0}"
                                ></span>
                            </a>
                        </li>
                    </template>
                </div>
            </template>
            
            <template x-if="kinopoisk.length">
                <div x-ref="createLinks">
                    <li class="text-center bg-blue-900">Добавление</li>
                    <template x-for="(item, index) in kinopoisk">
                        <li class="border-b border-gray-700">
                            <a 
                                :href="item.href"
                                :data-index="index"
                                class="flex items-center justify-between px-3 py-3 focus:bg-gray-700"
                                @@keydown.arrow-up.prevent="(($event.target.dataset.index == 0 && ($refs.showLinks?.querySelector('li>a') || $refs.search)) || $event.target.parentElement.previousElementSibling.firstElementChild).focus()"
                                @@keydown.arrow-down.prevent="(($event.target.dataset.index == kinopoisk.length - 1 && $refs.search) || $event.target.parentElement.nextElementSibling.firstElementChild).focus()"
                            >
                                <img :src="'https:' + item.poster" alt="poster" class="flex-shrink-0 w-16">
                                <div class="flex-grow mx-4 text-lg">
                                    <p x-text="item.russian || item.original"></p>
                                    <span class="text-xs text-gray-400">
                                        <template x-if="item.russian && item.original">
                                            <span x-text="item.original+','"></span>
                                        </template>
                                        <span x-text="item.productionYear"></span>
                                    </span>
                                </div>
                                <span x-text="item.rating ? item.rating.toFixed(1) : '—'" class="text-xl" 
                                    :class="{'text-gray-400': item.rating === 0 || item.rating >= 5 && item.rating < 7, 'text-green-500': item.rating >= 7, 'text-red-500': item.rating < 5 && item.rating > 0}"
                                ></span>
                            </a>
                        </li>
                    </template>
                </div>
            </template>
        </ul>
    </div>
</div>
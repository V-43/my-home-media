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
            <input class="flex-grow w-4/5" type="file" title="Загрузить видеофайл" wire:model="video">
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
        
        <x-smth-search-dropdown smth="country" label="Страна" :added="$countries" :found="$foundCountries"/>

        <div class="flex py-2 border-b border-gray-500">
            <div class="w-1/5 py-1">Жанры:</div>
            <select 
                class="flex-grow w-2/5 px-3" 
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

        <x-smth-search-dropdown smth="director" label="Режиссеры" :added="$directors" :found="$foundDirectors"/>
        <x-smth-search-dropdown smth="screenwriter" label="Сценаристы" :added="$screenwriters" :found="$foundScreenwriters"/>

        <div class="flex py-2">
            <div class="w-1/5 py-1">Описание:</div>
            <textarea class="flex-grow px-3 py-1" rows="5" wire:model="description">{{ $description }}</textarea>
        </div> 
    </div>
</form>
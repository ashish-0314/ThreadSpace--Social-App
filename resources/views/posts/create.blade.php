<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Create a Post in c/{{ $community->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('communities.posts.store', $community) }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Title -->
                        <div>
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Intent -->
                        <div class="mt-4">
                            <x-input-label for="intent" :value="__('Post Intent')" />
                            <select id="intent" name="intent" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" required>
                                <option value="Discussion" {{ old('intent') == 'Discussion' ? 'selected' : '' }}>Discussion</option>
                                <option value="Question" {{ old('intent') == 'Question' ? 'selected' : '' }}>Question</option>
                                <option value="Tutorial" {{ old('intent') == 'Tutorial' ? 'selected' : '' }}>Tutorial</option>
                                <option value="Opinion" {{ old('intent') == 'Opinion' ? 'selected' : '' }}>Opinion</option>
                            </select>
                            <x-input-error :messages="$errors->get('intent')" class="mt-2" />
                        </div>

                        <!-- Type (Tabs simulation) -->
                        <div class="mt-6 border-b border-gray-200 dark:border-gray-700 mb-4" x-data="{ tab: 'text' }">
                            <input type="hidden" name="type" x-model="tab">
                            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
                                <li class="mr-2" role="presentation">
                                    <button class="inline-block p-4 border-b-2 rounded-t-lg" :class="tab === 'text' ? 'border-indigo-600 text-indigo-600' : 'border-transparent hover:text-gray-600 hover:border-gray-300'" @click.prevent="tab = 'text'" type="button" role="tab">Text</button>
                                </li>
                                <li class="mr-2" role="presentation">
                                    <button class="inline-block p-4 border-b-2 rounded-t-lg" :class="tab === 'image' ? 'border-indigo-600 text-indigo-600' : 'border-transparent hover:text-gray-600 hover:border-gray-300'" @click.prevent="tab = 'image'" type="button" role="tab">Image</button>
                                </li>
                                <li class="mr-2" role="presentation">
                                    <button class="inline-block p-4 border-b-2 rounded-t-lg" :class="tab === 'link' ? 'border-indigo-600 text-indigo-600' : 'border-transparent hover:text-gray-600 hover:border-gray-300'" @click.prevent="tab = 'link'" type="button" role="tab">Link</button>
                                </li>
                            </ul>
                            
                            <div class="py-4">
                                <!-- Text Content -->
                                <div x-show="tab === 'text'">
                                    <textarea name="content" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" rows="6" placeholder="What are your thoughts?">{{ old('content') }}</textarea>
                                </div>
                                
                                <!-- Image Upload -->
                                <div x-show="tab === 'image'" style="display: none;">
                                    <div class="flex items-center justify-center w-full">
                                        <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                                </svg>
                                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG or GIF (MAX. 5MB)</p>
                                            </div>
                                            <input id="dropzone-file" type="file" name="image" class="hidden" accept="image/*" />
                                        </label>
                                    </div> 
                                    <x-input-error :messages="$errors->get('image')" class="mt-2" />
                                </div>

                                <!-- Link -->
                                <div x-show="tab === 'link'" style="display: none;">
                                    <x-text-input class="block mt-1 w-full" type="url" name="content" :value="old('content')" placeholder="Url" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Post') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

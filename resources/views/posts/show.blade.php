<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Post Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <div>
                            <h3 class="font-semibold">Title:</h3>
                        </div>
                        <div>
                            <p>{{ $post->title }}</p>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div>
                            <h3 class="font-semibold">Text:</h3>
                        </div>
                        <div>
                            <p>{{ $post->text }}</p>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div>
                            <h3 class="font-semibold">Category:</h3>
                        </div>
                        <div>
                            <p>{{ $post->category->name }}</p>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div>
                            <h3 class="font-semibold">Created:</h3>
                        </div>
                        <div>
                            <p>{{ \Illuminate\Support\Carbon::parse($post->created_at)->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div>
                            <h3 class="font-semibold">Updated:</h3>
                        </div>
                        <div>
                            <p>{{ \Illuminate\Support\Carbon::parse($post->updated_at)->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

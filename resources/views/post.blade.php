<x-layout>
    <x-slot:content>
        <article>
            <h1>
                {{ $post->title }}
            </h1>
            <div>
                {!! $post->body !!}
            </div>
        </article>
        
        <a href="/">Go back</a>
    </x-slot:content>
</x-layout>
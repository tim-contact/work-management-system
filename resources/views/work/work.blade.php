@props(['works'])
<x-layout>
    <x-slot:title>
        Work Management System
    </x-slot:title>

    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mt-8">Latest Works</h1>

        <!-- Work Form -->
        <div class="card bg-base-100 shadow mt-8">
            <div class="card-body">
                <form method="POST" action="{{ route('works.store') }}">
                    @csrf

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">Title</span>
                        </label>
                        <input
                            type="text"
                            name="title"
                            placeholder="e.g., Call client, Prepare report..."
                            class="input input-bordered w-full @error('title') input-error @enderror"
                            value="{{ old('title') }}"
                            maxlength="120"
                            required
                        />
                        @error('title')
                            <div class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="form-control w-full mt-4">
                        <label class="label">
                            <span class="label-text">Description (optional)</span>
                        </label>
                        <textarea
                            name="description"
                            placeholder="Add details (optional)"
                            class="textarea textarea-bordered w-full resize-none @error('description') textarea-error @enderror"
                            rows="3"
                            maxlength="500"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <div class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="mt-4 flex items-center justify-end">
                        <button type="submit" class="btn btn-primary btn-sm">
                            Add Work
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Works List -->
        <div class="space-y-4 mt-8">
            @forelse ($works as $work)
                <div class="card bg-base-100 shadow">
                    <div class="card-body">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="font-semibold">
                                    {{ $work->user?->name ?? 'Anonymous' }}
                                </div>

                                <div class="mt-1 font-medium">{{ $work->title }}</div>

                                @if($work->description)
                                    <div class="mt-1 text-base-content/80">{{ $work->description }}</div>
                                @endif

                                <div class="mt-2 text-sm">
                                    <span class="badge badge-outline">{{ $work->status }}</span>
                                </div>

                                <div class="text-sm text-base-content/60 mt-2">
                                    {{ $work->created_at->diffForHumans() }}
                                </div>
                            </div>

                            <div class="flex flex-col gap-2">
                                @if($work->status === 'pending')
                                    <form method="POST" action="{{ route('works.start', $work) }}">
                                        @csrf
                                        <button class="btn btn-success btn-xs">Start</button>
                                    </form>
                                @elseif($work->status === 'running')
                                    <form method="POST" action="{{ route('works.stop', $work) }}">
                                        @csrf
                                        <button class="btn btn-warning btn-xs">Stop</button>
                                    </form>
                                @endif

                                @if ($work->status !== 'completed')
                                    <form method="POST" action="{{ route('works.completed', $work) }}">
                                    @csrf   
                                        <button class="btn btn-primary btn-xs">Done</button>
                                </form>
                                @endif

                                <div class="flex gap-2">

                                <a href="{{ route('works.edit', $work)}}" class="btn btn-ghost btn-xs">Edit</a>

                                <form method="POST" action="{{ route('works.destroy', $work) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-error btn-xs"
                                        onclick="return confirm('Delete this work?')">
                                        Delete
                                    </button>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="hero py-12">
                    <div class="hero-content text-center">
                        <div>
                            <svg class="mx-auto h-12 w-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <p class="mt-4 text-base-content/60">No works yet. Add your first work above.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-layout>

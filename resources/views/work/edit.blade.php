<x-layout>
    <x-slot:title>
        Edit Work
    </x-slot:title>

            <div class="card bg-base-100 shadow mt-8">
            <div class="card-body">
                <form method="POST" action="{{ route('works.update', $work)}}">
                    @csrf
                    @method('PUT')
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">Title</span>
                        </label>
                        <input
                            type="text"
                            name="title"
                            placeholder="e.g., Call client, Prepare report..."
                            class="input input-bordered w-full @error('title') input-error @enderror"
                            value="{{ old('title', $work->title)}}"
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
                        >{{ old('description', $work->description) }}</textarea>
                        @error('description')
                            <div class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                    <div class="card-actions justify-between mt-4">
                        <a href="/works" class="btn btn-ghost btn-sm">
                            Cancel
                        </a>

                    <div class="mt-4 flex items-center justify-end">
                        <button type="submit" class="btn btn-primary btn-sm">
                            Update Work
                        </button>
                    </div>
                </form>
            </div>
        </div>

</x-layout>
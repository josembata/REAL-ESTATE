<!-- <pre>
    {{ dd($unit) }}
</pre> -->


<x-app-layout>
    <div class="max-w-4xl mx-auto mt-8">
        <h2 class="text-2xl font-bold mb-4">{{ $unit->unit_name }}</h2>

  <div class="grid grid-cols-3 gap-4">
    @forelse($unit->unitImages as $image)
        <div class="rounded shadow-sm overflow-hidden">
            <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-48 object-cover" alt="Unit Image">
        </div>
    @empty
        <p>No images available for this unit.</p>
    @endforelse
</div>




        {{-- Unit Details --}}
        <div class="mt-6 space-y-2">
            <p><strong>Description:</strong> {{ $unit->description }}</p>
            <p><strong>Price:</strong> {{ $unit->price }} {{ $unit->currency }}</p>

            <p><strong>Type:</strong> {{ $unit->unit_type }}</p>
            <p><strong>Size:</strong> {{ $unit->size_sqft }} sqft</p>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            let current = 0;
            const items = document.querySelectorAll('[data-carousel-item]');
            const total = items.length;

            document.getElementById('next').addEventListener('click', () => {
                items[current].classList.add('hidden');
                current = (current + 1) % total;
                items[current].classList.remove('hidden');
            });

            document.getElementById('prev').addEventListener('click', () => {
                items[current].classList.add('hidden');
                current = (current - 1 + total) % total;
                items[current].classList.remove('hidden');
            });
        });
    </script>
</x-app-layout>

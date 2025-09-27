<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Add Price Plan for {{ $unit->unit_name }}</h2>
    </x-slot>

    <div class="p-6 bg-white shadow rounded">
        <form action="{{ route('price-plans.store', $unit->id) }}" method="POST">
            @csrf

            <!-- Category dropdown -->
            <div class="mb-4">
                <label>Plan Category</label>
                <div class="flex space-x-2">
                    <select name="category_id" id="categorySelect" class="w-full border rounded px-3 py-2" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>

                    <!-- Button to show input for new category -->
                    <button type="button" id="addCategoryBtn" class="bg-green-500 text-white px-3 py-1 rounded">+ Add</button>
                </div>
            </div>

            <!-- Hidden input for new category -->
            <div class="mb-4 hidden" id="newCategoryDiv">
                <label>New Category Name</label>
                <input type="text" id="newCategoryInput" class="w-full border px-3 py-2 rounded">
                <button type="button" id="saveCategoryBtn" class="mt-2 bg-blue-600 text-white px-3 py-1 rounded">Save Category</button>
            </div>

            <div class="mb-4">
                <label>Plan Name</label>
                <input type="text" name="name" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div class="mb-4">
                <label>Price</label>
                <input type="number" step="0.01" name="price" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div class="mb-4">
                <label>Currency</label>
                <input type="text" name="currency" class="w-full border px-3 py-2 rounded" maxlength="3" required>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save Plan</button>
        </form>
    </div>

    <!-- Inline JS to handle new category -->
    <script>
        const addBtn = document.getElementById('addCategoryBtn');
        const newDiv = document.getElementById('newCategoryDiv');
        const saveBtn = document.getElementById('saveCategoryBtn');
        const categorySelect = document.getElementById('categorySelect');
        const newInput = document.getElementById('newCategoryInput');

        addBtn.addEventListener('click', () => {
            newDiv.classList.remove('hidden');
        });

        saveBtn.addEventListener('click', async () => {
            const name = newInput.value.trim();
            if (!name) return alert('Category name required');

            try {
                const res = await fetch('{{ route("price_plan_categories.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ name })
                });

                if (res.ok) {
                    const option = document.createElement('option');
                    option.value = 'new'; // placeholder, will fix after backend
                    option.textContent = name;
                    option.selected = true;
                    categorySelect.appendChild(option);
                    newDiv.classList.add('hidden');
                    newInput.value = '';
                    location.reload(); // refresh to get real ID
                } else {
                    const data = await res.json();
                    alert(data.message || 'Error creating category');
                }
            } catch (err) {
                alert('Error creating category');
            }
        });
    </script>
</x-app-layout>

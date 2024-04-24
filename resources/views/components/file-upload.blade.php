<div x-data="imageViewer()">
    <div class="mb-2">
        <!-- Show the image -->
        <template x-if="imageUrl">
            <img :src="imageUrl"
                 class="object-cover rounded border border-gray-200"
                 style="width: 100px; height: 100px;"
            >
        </template>

        <!-- Show the gray box when image is not available -->
        <template x-if="!imageUrl">
            <div
                class="border rounded border-gray-200 bg-gray-100"
                style="width: 100px; height: 100px;"
            ></div>
        </template>

        <!-- Image file selector -->
        <input class="mt-2" type="file" accept="image/*">

    </div>
</div>

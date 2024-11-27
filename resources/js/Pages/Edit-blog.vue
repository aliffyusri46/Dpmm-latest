<template>
    <section class="container mx-auto py-8">
        <form @submit.prevent="updateBlog">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h1 class="text-2xl font-semibold text-gray-800 mb-4">Edit Blog</h1>

                <!-- Title -->
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 font-medium mb-2">Title</label>
                    <input
                        id="title"
                        type="text"
                        v-model="form.title"
                        class="w-full p-2 border border-gray-300 rounded-lg"
                    />
                </div>

                <!-- Content (WYSIWYG Editor) -->
                <div class="mb-4">
                    <label for="content" class="block text-gray-700 font-medium mb-2">Content</label>
                    <!-- NEW: WYSIWYG editor -->
                    <div id="editor" class="border border-gray-300 rounded-b-lg min-h-[12rem] max-h-[12rem] overflow-y-auto"></div>
                </div>

                <!-- Display Existing Images -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Existing Images</label>
                    <div v-if="form.images.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <div
                            v-for="(image, index) in form.images"
                            :key="index"
                            class="relative border border-gray-300 p-2 rounded-lg"
                        >
                            <img
                                :src="`/storage/${image}`" 
                                alt="Blog Image"
                                class="w-full h-32 object-cover rounded-md"
                            />
                            <button
                                type="button"
                                @click="deleteImage(index)"
                                class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1"
                            >
                                ✕
                            </button>
                        </div>
                    </div>
                    <p v-else class="text-gray-600">No images available.</p>
                </div>
                
                

                <!-- Upload New Images -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Upload New Images</label>
                    <input
                        type="file"
                        multiple
                        @change="handleNewFiles"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                    />
                </div>

                <!-- Submit -->
                <button
                    type="submit"
                    class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600"
                >
                    Update Blog
                </button>
            </div>
        </form>
    </section>
</template>

<script setup>
// Imports
import { ref, onMounted } from "vue";
import { router } from "@inertiajs/vue3";
import Quill from "quill"; // NEW: Import Quill
import "quill/dist/quill.snow.css"; // NEW: Quill CSS

// Props for initial blog data
const props = defineProps({
    blog: {
        type: Object,
        required: true,
    },
});

// Form state
const form = ref({
    id: props.blog.id,
    title: props.blog.title,
    content: props.blog.content,
    images: Array.isArray(props.blog.images) ? props.blog.images : [], // Validate images array
    newImages: [],
});

// Quill editor instance (NEW)
let quill = null;

onMounted(() => {
    // Initialize Quill editor
    quill = new Quill("#editor", {
        theme: "snow",
        placeholder: "Write your blog content here...",
        modules: {
            toolbar: [
                ["bold", "italic", "underline", "strike"], // Formatting
                [{ header: [1, 2, 3, 4, 5, false] }], // Headers
                [{ list: "ordered" }, { list: "bullet" }], // Lists
                ["link", "image"], // Links and images
                ["clean"], // Remove formatting
            ],
        },
    });

    // Load existing blog content into the editor
    quill.root.innerHTML = form.value.content;

    // Update form.content when the editor changes
    quill.on("text-change", () => {
        form.value.content = quill.root.innerHTML;
    });
});

// Handle new file uploads
const handleNewFiles = (event) => {
    const files = Array.from(event.target.files);
    form.value.newImages.push(...files);
};

// Remove an existing image
const deleteImage = (index) => {
    form.value.images.splice(index, 1);
};

// Update blog
const updateBlog = () => {
    const data = new FormData();
    data.append("title", form.value.title);
    data.append("content", form.value.content); // Updated content from Quill editor

    // Include existing images (remaining ones)
    form.value.images.forEach((image) => {
        data.append("existingImages[]", image);
    });

    // Include new images
    form.value.newImages.forEach((file) => {
        data.append("newImages[]", file);
    });

    router.post(`/blogs/${form.value.id}`, data, {
        headers: { "X-HTTP-Method-Override": "PUT" }, // Override method for PUT
        onSuccess: () => {
            alert("Blog updated successfully!");
        },
        onError: (errors) => {
        if (errors.response?.data?.errors) {
            const validationErrors = errors.response.data.errors;

            if (validationErrors.images) {
                alert(validationErrors.images[0]); // Show image error
            } else {
                alert("Validation failed. Please check the form.");
                console.error(validationErrors);
            }
        } else {
            alert("An unknown error occurred. Please try again.");
            console.error(errors);
        }
    },
    });
};
</script>

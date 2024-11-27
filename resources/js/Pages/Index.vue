<template>
    <section id="news" class="news section">
        <!-- Section Title -->
        <div class="container mx-auto mt-10 pb-16 relative" data-aos="fade-up">
            <div>
                <!-- Section Title (h2) -->
                <h2 class="text-sm font-medium pb-0 m-0 leading-tight tracking-wider uppercase text-gradient relative">
                    DPMM / Dewan Muda / Dewanita / MPIH-DPMM
                    <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-28 h-[3px] ml-[-345px]   bg-accent-color mb-2"></span>
                </h2>
                <!-- Subtitle (p) -->
                <p class="text-heading-color text-3xl font-extrabold uppercase">
                    Press Statement / News / Events
                </p>
                <form @submit.prevent="logout">
                    <Button class="bg-red-500 rounded px-4 py-2">
                        Log Out
                    </Button>                   
                </form>
                
            </div>
            <hr class="mt-4">
        </div>

        <!-- Blog Items (Including Create Blog Button) -->
        <div class="container mx-auto">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <!-- Create Blog Button (on the first row) -->
                <div class="news-item relative bg-white shadow-custom rounded-lg overflow-hidden transition-all duration-300 hover:translate-y-[-10px] p-4">
                    <Link :href="`/blogs/create`" class="hover:cursor-pointer">
                        
                        <div class="news-content p-4 bg-white flex flex-col items-center justify-center text-center h-full">
                            <Icon icon="akar-icons:plus" width="96" class="text-4xl text-gray-800" />
                            <h1 class="font-semibold text-lg text-gray-800 mt-5">
                                Create
                            </h1>
                            <p class="text-sm text-gray-600">
                                New Blog
                            </p>
                        </div>
                    </Link>
                </div>

                <!-- Blog Items -->
                <div v-for="(blog, index) in blogs" :key="index" class="news-item relative bg-white shadow-custom rounded-lg overflow-hidden transition-all duration-300 hover:translate-y-[-10px] hover:cursor-pointer p-4">
                    <Link :href="`/blogs/${blog.id}`">
                        <div class="news-content p-4 bg-white">
                            <!-- Display the first image -->
                            <img 
                                v-if="blog.images && blog.images.length > 0"
                                :src="`/storage/${blog.images[0]}`" 
                                alt="Blog Image" 
                                class="w-full h-48 object-cover rounded-lg mb-2" 
                            />
                            <h3 class="font-semibold text-lg text-gray-800">
                                {{ blog.title }}
                            </h3>
                        </div>
                    </Link>
                    <!-- Edit and Delete Buttons -->
                    <div class="flex space-x-2 mt-2">
                        <Link
                            :href="`/blogs/${blog.id}/edit`"
                            class="bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-600 block w-full text-center"
                        >
                            Edit
                        </Link>
                        <button
                            @click="deleteBlog(blog.id)"
                            class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600 block w-full text-center"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import { Icon } from '@iconify/vue';

// Define the props with default empty array if blogs are not provided
const props = defineProps({
    blogs: {
        type: Array,
        default: () => [], // Default to an empty array
    },
});

function deleteBlog(blogId) {
    if (confirm("Are you sure you want to delete this blog?")) {
        Inertia.delete(`/blogs/${blogId}`, {
            onSuccess: () => {
                alert("Blog deleted successfully!");
            },
            onError: () => {
                alert("Failed to delete the blog.");
            },
        });
    }
}

const logout = () => {
    router.post(route('logout'));
};
</script>
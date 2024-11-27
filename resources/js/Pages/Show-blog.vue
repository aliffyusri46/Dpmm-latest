<template>
    <section class="container mx-auto py-8">
        <div class="bg-white p-6 rounded-lg shadow-custom">

            <h1 class="text-3xl font-semibold text-gray-800 mb-4">{{ blog.title }}</h1>
            
            <div v-if="blog.images && blog.images.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                <img 
                    v-for="(image, index) in blog.images" 
                    :key="index" 
                    :src="`/storage/${image}`" 
                    alt="Blog Image" 
                    class="w-full h-64 object-cover rounded-lg  border" />
            </div>

            <div class="text-lg text-gray-800" v-html="blog.content"></div>
        </div>
    </section>

      <!-- Comments Section -->
    <div class="container mx-auto mt-8">
        <h4 class="text-xl font-bold mb-4">Comments</h4>
        <div v-for="comment in comments" :key="comment.id" class="flex mb-6">
            <div class="mr-3">
                <img class="w-15" src="assets/img/blog/comments-1.jpg" alt="Commenter" />
            </div>
            <div>
                <div class="flex">
                    <h5 class="text-lg font-bold mb-1">
                        <a href="#" class="text-gray-800 hover:text-blue-500 transition">{{ comment.name }}</a>
                    </h5>
                    <button @click="deleteComment(comment.id)" class="ml-4 bg-red-500 pl-2 pr-2 mb-1 rounded ">
                        Delete
                    </button>
                </div>
                <time class="text-sm text-gray-400 block mb-2">{{ new Date(comment.created_at).toLocaleDateString() }}</time>
                <p>{{ comment.comment }}</p>
            </div>

            
        </div>
    </div>

    <!-- Comment Form Section -->
    <section class="container mx-auto">
        <form @submit.prevent="submitComment" class="bg-white mt-8 p-8 shadow-custom mb-10 rounded-lg">
            <h4 class="font-bold text-xl">Post Comment</h4>
            <p class="text-sm mt-2">Your email address will not be published. Required fields are marked *</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                <div>
                    <input
                        v-model="newComment.name"
                        name="name"
                        type="text"
                        class="w-full bg-gray-50 text-gray-800 border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Your Name*"
                        required
                    />
                </div>
                <div>
                    <input
                        v-model="newComment.email"
                        name="email"
                        type="email"
                        class="w-full bg-gray-50 text-gray-800 border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Your Email*"
                        required
                    />
                </div>
            </div>

            <div class="mt-4">
                <textarea
                    v-model="newComment.comment"
                    name="comment"
                    class="w-full bg-gray-50 text-gray-800 border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 min-h-[12rem] max-h-[12rem] overflow-y-auto"
                    placeholder="Your Comment*"
                    required
                ></textarea>
            </div>

            <div class="text-center mt-6">
                <button
                    type="submit"
                    class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition"
                >
                    Post Comment
                </button>
            </div>
        </form>
    </section>

</template>


<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { Icon } from '@iconify/vue';

const props = defineProps({
    blog: {
        type: Object,
        required: true, // The blog prop is required
    },
});

const comments = ref([]);
const newComment = ref({
    name: '',
    email: '',
    comment: '',
});

const fetchComments = async () => {
    const { data } = await axios.get(`/blogs/${props.blog.id}/comments`);
    comments.value = data;
};

// Delete a comment
const deleteComment = async (commentId) => {
  try {
    await axios.delete(`/blogs/${props.blog.id}/comments/${commentId}`);
    await fetchComments(); // Refresh comments
    alert('Comment deleted successfully!');
  } catch (error) {
    console.error(error);
    alert('Failed to delete comment. Please try again.');
  }
};

const submitComment = async () => {
    try {
        await axios.post(`/blogs/${props.blog.id}/comments`, newComment.value);
        newComment.value = { name: '', email: '', comment: '' };
        await fetchComments(); // Refresh comments
        alert('Comment added successfully!');
    } catch (error) {
        console.error(error);
        alert('Failed to add comment. Please try again.');
    }
};

onMounted(fetchComments);

</script>

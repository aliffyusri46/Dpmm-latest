<template>
  <section class="container mx-auto py-8">
    <div class="bg-white p-6 rounded-lg shadow-lg">
      <h1 class="text-2xl font-bold mb-4">Create Blog</h1>
      <form @submit.prevent="submit">
        <div>
          <label for="title">Title</label>
          <input
            v-model="form.title"
            id="title"
            type="text"
            class="border p-2 rounded w-full"
          />
        </div>
        <div class="mt-10">
          <label for="content">Content</label>
          <!-- New WYSIWYG Editor Container -->
          <div id="editor" class="border p-2 rounded-b-lg w-full min-h-[12rem] max-h-[12rem] overflow-y-auto"></div>
        </div>
        <div class="mt-10">
          <label for="images" >Images (Max: 5)</label>
          <input
            type="file"
            id="images"
            multiple
            accept="image/*"
            @change="previewImages"
          />
        </div>
        <div class="grid grid-cols-5 gap-2 mt-4">
          <div
            v-for="(image, index) in previews"
            :key="index"
            class="relative"
          >
            <img :src="image" alt="Preview" class="w-full h-20 object-cover rounded" />
            <button
              type="button"
              @click="removeImage(index)"
              class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center"
            >
              x
            </button>
          </div>
        </div>
        <button
          type="submit"
          class="mt-4 bg-blue-500 text-white px-4 py-2 rounded"
        >
          Create Blog
        </button>
      </form>
    </div>
  </section>
  </template>
  
  <script>
  import { Inertia } from '@inertiajs/inertia';
  import Quill from "quill"; /* NEW CODE */
  import "quill/dist/quill.snow.css"; /* NEW CODE */
  
  export default {
    data() {
      return {
        form: {
          title: '',
          content: '',
          images: [],
        },
        previews: [],
        quill: null, /* NEW CODE */
      };
    },
    mounted() { // NEW CODE for wysiwyg text editor
    // Initialize Quill editor
    this.quill = new Quill("#editor", {
      theme: "snow",
      placeholder: "Write your blog content here...", /* NEW CODE */
      modules: {
        toolbar: [
          ["bold", "italic", "underline", 'strike'],
          [{ list: "ordered" }, { list: "bullet" }],
          ["link"], /* NEW CODE */
          [{ 'color': [] }, { 'background': [] }],
          [{ 'font': [] }],
          [{ 'align': [] }],
          [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
          [{ 'direction': 'rtl' }], 
        ],
      },
    });

    // Listen for content changes and update the form content
    this.quill.on("text-change", () => {
      this.form.content = this.quill.root.innerHTML; /* NEW CODE */
    });
  },
    methods: {
      previewImages(event) {
        const files = Array.from(event.target.files);
        if (files.length > 5) {
          alert('You can only upload a maximum of 5 images.');
          return;
        }
        this.previews = files.map((file) => URL.createObjectURL(file));
        this.form.images = files;
      },
      removeImage(index) {
        this.previews.splice(index, 1);
        this.form.images.splice(index, 1);
      },
      submit() {
        const formData = new FormData();
        formData.append('title', this.form.title);
        formData.append('content', this.form.content);
        this.form.images.forEach((image, index) => {
          formData.append(`images[${index}]`, image);
        });
  
        Inertia.post('/blogs', formData);
      },
    },
  };
  </script>
  
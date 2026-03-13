<template>
  <main class="max-w-7xl mx-auto px-6 py-12">
    <!-- HERO SECTION -->
    <div class="bg-[#dfe8ea] p-10">
      <div class="grid grid-cols-12 gap-10 items-center">
        <!-- LEFT CONTENT -->
        <div class="col-span-12 lg:col-span-6 space-y-6">
          <h1 class="text-4xl lg:text-4xl font-extrabold leading-tight">
            6-Million-Year-Old Meteorite Strike Created a Massive Field of
            Natural Glass in Brazil
          </h1>

          <div class="text-sm text-gray-500 space-y-1 uppercase">
            <p>Hisotech Group • Ngày đăng: 30-4-2026</p>
          </div>
        </div>

        <!-- RIGHT IMAGE -->
        <div class="col-span-12 lg:col-span-6">
          <img
            src="https://images.unsplash.com/photo-1446776811953-b23d57bd21aa"
            class="w-full h-[400px] object-cover shadow-lg"
          />
        </div>
      </div>
    </div>

    <!-- CONTENT SECTION -->
    <div class="grid grid-cols-12 gap-12 mt-16">
      <!-- ARTICLE BODY -->
      <article
        class="col-span-12 lg:col-span-8 space-y-6 text-lg leading-relaxed pe-5"
      >
        <p>
          Scientists uncovered a vast field of tektites in Brazil—a rare type of
          natural glass forged in the aftermath of meteorite impacts.
        </p>

        <p>
          The field stretches across 500 miles (800 kilometers) and dates back
          to a massive impact that took place around 6.3 million years ago. An
          international team of researchers collected around 500 of the newly
          discovered specimens.
        </p>

        <p>
          The findings are detailed in a study published in Geology and help
          fill in the gaps of South America's ancient impact history. The
          researchers, however, are still searching for the crater.
        </p>

        <h2 class="text-2xl font-bold text-green-700 pt-6">
          Extraterrestrial debris
        </h2>

        <p>
          There are nearly 200 known impact craters on Earth, and yet only five
          tektite fields had been discovered prior to the one in Brazil. That's
          because a more complex process is required for melted glass to form.
        </p>
      </article>

      <!-- SIDEBAR -->
      <aside class="col-span-12 lg:col-span-4 p-5">
        <CommonsBlogItem
          class="mb-2"
          v-for="item in blogs_featured"
          :key="item.id"
          :title="item.title"
          :image="item.image"
          :slug="item.slug"
        />
      </aside>
    </div>
  </main>
</template>
<script setup lang="ts">
import { useRoute } from "vue-router";

const blogs_featured = [
  {
    id: 1,
    title:
      "Google’s Chatbot Told Man to Give It an Android Body Before Encouraging Suicide, Lawsuit Alleges",
    image: "https://images.unsplash.com/photo-1446776811953-b23d57bd21aa",
    slug: "12312312312321",
  },
  {
    id: 2,
    title: "The Plague That Changed the Course of Game of Thrones’ History",
    image: "https://images.unsplash.com/photo-1520975916090-3105956dac38",
    slug: "12312312312321",
  },
];

const route = useRoute();
const slugArray = route.params.slug;
const slug = Array.isArray(slugArray) ? slugArray.join('/') : (slugArray || '');
</script>

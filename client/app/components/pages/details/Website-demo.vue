<template>
  <div>
    <main
      class="relative overflow-hidden from-sky-50 via-white to-emerald-50 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 py-24"
    >
      <!-- Background Blur Shapes -->
      <div
        class="absolute -top-40 -left-40 w-96 h-96 bg-sky-300/30 rounded-full blur-3xl"
      ></div>
      <div
        class="absolute -bottom-40 -right-40 w-96 h-96 bg-emerald-300/30 rounded-full blur-3xl"
      ></div>

      <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
        <!-- HERO SECTION -->
        <div class="grid lg:grid-cols-2 gap-16 items-center mb-10">
          <!-- LEFT CONTENT -->
          <div>
            <h1
              class="text-4xl md:text-6xl font-extrabold leading-tight tracking-tight text-slate-900 dark:text-white mb-6"
            >
              Grocery <br />
              <span
                class="bg-gradient-to-r from-sky-500 to-emerald-500 bg-clip-text text-transparent"
              >
                Nền tảng mua sắm thực phẩm trực tuyến
              </span>
            </h1>

            <p
              class="text-lg md:text-xl text-slate-600 dark:text-slate-400 leading-relaxed mb-8"
            >
              Giải pháp toàn diện giúp doanh nghiệp xây dựng cửa hàng thực phẩm
              online chuyên nghiệp, tối ưu trải nghiệm người dùng và quản lý vận
              hành hiệu quả.
            </p>

            <!-- Buttons -->
            <div class="flex flex-wrap gap-4 mb-10">
              <button
                class="px-8 py-3.5 rounded-2xl bg-gradient-to-r from-sky-500 to-emerald-500 text-white font-semibold shadow-lg hover:shadow-xl hover:-translate-y-1 transition"
              >
                WEB DEMO
              </button>

              <button
                class="px-8 py-3.5 rounded-2xl bg-white/70 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white font-semibold hover:-translate-y-1 transition"
              >
                ADMIN DEMO
              </button>

              <button
                class="px-8 py-3.5 rounded-2xl bg-slate-900 dark:bg-white dark:text-slate-900 text-white font-semibold hover:-translate-y-1 transition"
              >
                LIÊN HỆ NGAY
              </button>
            </div>

            <!-- Feature Checks -->
            <div class="space-y-4 text-slate-700 dark:text-slate-300">
              <div class="flex items-center gap-3">
                <div
                  class="w-6 h-6 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-600 text-sm"
                >
                  ✓
                </div>
                Trải nghiệm mua sắm dễ dàng
              </div>
              <div class="flex items-center gap-3">
                <div
                  class="w-6 h-6 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-600 text-sm"
                >
                  ✓
                </div>
                Quản lý cửa hàng hiệu quả
              </div>
              <div class="flex items-center gap-3">
                <div
                  class="w-6 h-6 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-600 text-sm"
                >
                  ✓
                </div>
                Thông tin kỹ thuật: <span>Laravel</span> <span>VueJS</span>
                <span>MySQL</span>
              </div>
            </div>
          </div>

          <!-- RIGHT IMAGE -->
          <div class="relative">
            <div
              class="absolute inset-0 bg-gradient-to-tr from-sky-400/20 to-emerald-400/20 rounded-3xl blur-2xl"
            ></div>
            <img
              src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?q=80&w=1200&auto=format&fit=crop"
              class="relative rounded-3xl shadow-2xl border border-white/40 dark:border-slate-700"
              alt="Dashboard preview"
            />
          </div>
        </div>

        <!-- FEATURES SECTION -->
        <div class="space-y-16">
          <!-- User UI -->
          <div
            class="w-full lg:w-[90%] bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl p-8 rounded-3xl border border-white/40 dark:border-slate-800 shadow-lg"
          >
            <div class="flex flex-col lg:flex-row items-stretch gap-8">
              <!-- TEXT -->
              <div class="flex-1 flex flex-col justify-between">
                <!-- TOP -->
                <div class="ps-10">
                  <h3
                    class="text-xl font-bold text-slate-900 dark:text-white mb-6"
                  >
                    Giao diện Người Dùng
                  </h3>
                  <ul class="space-y-3 text-slate-600 dark:text-slate-400">
                    <li>• Tìm kiếm nhanh chóng</li>
                    <li>• Danh mục sản phẩm đa dạng</li>
                    <li>• Giỏ hàng tiện lợi</li>
                    <li>• Responsive mọi thiết bị</li>
                  </ul>
                </div>

                <!-- BOTTOM (button nằm trong TEXT) -->
                <div class="flex justify-end mt-6 pe-5">
                  <a
                    href="#"
                    class="px-7 py-4 rounded-xl bg-gradient-to-r from-sky-500 to-emerald-500 text-white font-semibold shadow-lg hover:shadow-xl hover:-translate-y-1 transition"
                  >
                    WEB DEMO
                  </a>
                </div>
              </div>

              <!-- IMAGE -->
              <div class="flex-1">
                <img
                  src="https://images.unsplash.com/photo-1559028012-481c04fa702d?q=80&w=1000&auto=format&fit=crop"
                  class="w-full h-[260px] rounded-2xl object-cover"
                />
              </div>
            </div>
          </div>

          <!-- Admin -->
          <div
            class="w-full lg:w-[90%] ml-auto bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl p-8 rounded-3xl border border-white/40 dark:border-slate-800 shadow-lg"
          >
            <div class="flex flex-col lg:flex-row-reverse items-stretch gap-8">
              <!-- TEXT -->
              <div class="flex-1 flex flex-col justify-between min-h-[260px]">
                <!-- TOP -->
                <div class="ps-10">
                  <h3
                    class="text-xl font-bold text-slate-900 dark:text-white mb-6"
                  >
                    Admin Quản Trị
                  </h3>
                  <ul class="space-y-3 text-slate-600 dark:text-slate-400">
                    <li>• Quản lý sản phẩm & đơn hàng</li>
                    <li>• Theo dõi doanh thu</li>
                    <li>• Quản lý khách hàng</li>
                    <li>• Báo cáo & thống kê</li>
                  </ul>
                </div>

                <!-- BOTTOM -->
                <div class="flex justify-end mt-6 pe-5">
                  <a
                    href="#"
                    class="px-7 py-4 rounded-xl bg-gradient-to-r from-sky-500 to-emerald-500 text-white font-semibold shadow-lg hover:shadow-xl hover:-translate-y-1 transition"
                  >
                    ADMIN DEMO
                  </a>
                </div>
              </div>

              <!-- IMAGE -->
              <div class="flex-1">
                <img
                  src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=1000&auto=format&fit=crop"
                  class="w-full h-[260px] rounded-2xl object-cover"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
  <!-- Tech Info -->
  <!-- <div
            class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl p-8 rounded-3xl border border-white/40 dark:border-slate-800 shadow-lg"
          >
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">
              Thông tin kỹ thuật
            </h3>

            <div class="space-y-5 text-sm">
              <div>
                <div class="text-slate-500 uppercase text-xs mb-2">
                  Công nghệ
                </div>
                <div class="flex flex-wrap gap-2">
                  <span
                    class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-slate-800"
                    >Laravel</span
                  >
                  <span
                    class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-slate-800"
                    >VueJS</span
                  >
                  <span
                    class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-slate-800"
                    >MySQL</span
                  >
                </div>
              </div>

              <div>
                <div class="text-slate-500 uppercase text-xs mb-2">
                  Cập nhật
                </div>
                <div class="font-medium text-slate-800 dark:text-slate-200">
                  17/05/2024
                </div>
              </div>

              <div>
                <div class="text-slate-500 uppercase text-xs mb-2">
                  Phát hành bởi
                </div>
                <div class="font-medium text-slate-800 dark:text-slate-200">
                  HISOTECH Group
                </div>
              </div>
            </div>
          </div> -->
</template>


// <script setup>
// import { useMappedShortcodes } from "~/composables/useMappedShortcodes";

// const { Shortcodes, mapSectionsToShortcodes } = useMappedShortcodes();
// const { data: pageData, pending } = (await usePageDetail) < any > props.slug;
// console.log(pageData.value);

// if (pageData.value?.sections) {
//   mapSectionsToShortcodes(pageData.value.sections);
// }
//
</script>

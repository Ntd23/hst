<style>
.bg-main {
  background: #d1ebf8f6;
}
</style>
<template>
  <main
    class="relative overflow-hidden bg-main from-sky-50 via-white to-emerald-50 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 py-24"
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
      <div class="grid lg:grid-cols-2 gap-16 items-center mb-16">
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
                class="w-6 h-6 rounded-full bg-white flex items-center justify-center"
              >
                <svg
                  class="w-3.5 h-3.5 text-emerald-600"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="3"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M5 13l4 4L19 7"
                  />
                </svg>
              </div>
              Trải nghiệm mua sắm dễ dàng
            </div>
            <div class="flex items-center gap-3">
              <div
                class="w-6 h-6 rounded-full bg-white flex items-center justify-center"
              >
                <svg
                  class="w-3.5 h-3.5 text-emerald-600"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="3"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M5 13l4 4L19 7"
                  />
                </svg>
              </div>
              Quản lý cửa hàng hiệu quả
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
      <div class="grid lg:grid-cols-2 gap-10">
        <!-- User UI -->
        <div
          class="bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl p-8 rounded-3xl border border-white/40 dark:border-slate-800 shadow-lg"
        >
          <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">
            Giao diện Người Dùng
          </h3>
          <ul class="space-y-3 text-slate-600 dark:text-slate-400">
            <li>• Tìm kiếm nhanh chóng</li>
            <li>• Danh mục sản phẩm đa dạng</li>
            <li>• Giỏ hàng tiện lợi</li>
            <li>• Responsive mọi thiết bị</li>
          </ul>
        </div>

        <!-- Admin -->
        <div
          class="bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl p-8 rounded-3xl border border-white/40 dark:border-slate-800 shadow-lg"
        >
          <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">
            Admin Quản Trị
          </h3>
          <ul class="space-y-3 text-slate-600 dark:text-slate-400">
            <li>• Quản lý sản phẩm & đơn hàng</li>
            <li>• Theo dõi doanh thu</li>
            <li>• Quản lý khách hàng</li>
          </ul>

          <!-- <button
            class="mt-6 w-full py-3 rounded-xl bg-gradient-to-r from-sky-500 to-emerald-500 text-white font-semibold hover:opacity-90 transition"
          >
            Yêu cầu báo giá
          </button> -->
        </div>
      </div>
      <div class="grid lg:grid-cols-1 gap-10 mt-5">
        <div
          class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl p-8 rounded-3xl border border-white/40 dark:border-slate-800 shadow-lg"
        >
          <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">
            Thông tin kỹ thuật
          </h3>

          <div class="space-y-5 text-sm">
            <div class="flex">
              <div class="text-slate-500 uppercase text-xs mb-2">
                Công nghệ:
                <span
                  class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 mx-2"
                  >Laravel</span
                >
                <span
                  class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 mx-2"
                  >VueJS</span
                >
                <span
                  class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 mx-2"
                  >MySQL</span
                >
              </div>
            </div>

            <div class="flex">
              <div class="text-slate-500 uppercase text-xs mb-2">
                Cập nhật:
                <span class="font-medium text-slate-800 dark:text-slate-200"
                  >17/05/2024</span
                >
              </div>
            </div>

            <div class="flex">
              <div class="text-slate-500 uppercase text-xs mb-2">
                Phát hành bởi:
                <span class="font-medium text-slate-800 dark:text-slate-200"
                  >HISOTECH Group</span
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>



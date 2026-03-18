<template>
  <main class="py-12 relative overflow-hidden">
    <div
      class="absolute top-0 right-0 -mr-40 -mt-40 w-96 h-96 rounded-full bg-primary/10 blur-3xl pointer-events-none"
    ></div>
    <div
      class="absolute bottom-0 left-0 -ml-40 -mb-40 w-96 h-96 rounded-full bg-blue-500/10 blur-3xl pointer-events-none"
    ></div>
    <div class="container mx-auto px-4 relative z-10">
      <div class="mb-12 text-center md:text-left">
        <h1
          class="text-3xl md:text-5xl font-extrabold text-slate-900 dark:text-white mb-4 leading-tight"
        >
          Grocery | Nền tảng mua sắm <br class="hidden md:block" />
          <span class="gradient-text">thực phẩm trực tuyến</span>
        </h1>
        <p class="text-lg text-slate-600 dark:text-slate-400 max-w-2xl">
          Giải pháp toàn diện giúp doanh nghiệp xây dựng cửa hàng thực phẩm
          online chuyên nghiệp, tối ưu trải nghiệm người dùng và quản lý vận
          hành hiệu quả.
        </p>
      </div>
      <div
        class="mb-12 rounded-3xl overflow-hidden shadow-2xl shadow-primary/20 border border-white/50 dark:border-slate-700/50 relative group"
      >
        <div
          class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent z-10 opacity-60"
        ></div>
        <img
          alt="Grocery Platform Dashboard Preview"
          class="w-full h-[400px] md:h-[600px] object-cover object-center transform transition duration-700 group-hover:scale-105"
          src="https://lh3.googleusercontent.com/aida-public/AB6AXuAslChm5qi9-NVemHqP6ip1wGp-LXVLlPGrobMLZDkvT_X0lR3CPsZ6jO88dw7dcohc89facqWTNSRTjdzbVBgFb_5oefTqg-5UD6Ht1kCUasYfobkc5vGlp1S5pws00aRjXmZAIBmpDrLlZePEOtVQP9eHx3W1ef0i3qKSIUXDmDEeF5J7zSvY5IvboFkc6nyb5ORXSRfn5tOmvUaINCU0sULGV0DZOWr7ugnFxyvgV9hMPbOVvizGuIl-_AY2xWYIVaS2iBHBEFI"
        />
        <div
          class="absolute bottom-0 left-0 right-0 p-8 z-20 flex flex-col md:flex-row items-end justify-between gap-6"
        >
          <div
            class="glass-panel bg-glass-bg dark:bg-glass-bg-dark border border-glass-border dark:border-glass-border-dark p-6 rounded-2xl max-w-xl"
          >
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">
              Trải nghiệm người dùng mượt mà
            </h3>
            <p class="text-slate-600 dark:text-slate-300 text-sm">
              Giao diện được thiết kế tối ưu cho việc tìm kiếm và đặt hàng thực
              phẩm tươi sống, đảm bảo sự tiện lợi tối đa.
            </p>
          </div>
        </div>
      </div>
      <div class="flex flex-wrap gap-4 mb-16 justify-center md:justify-start">
        <a
          class="flex items-center space-x-2 bg-primary hover:bg-sky-600 text-white px-8 py-3 rounded-xl font-bold transition shadow-lg shadow-primary/30 transform hover:-translate-y-1"
          href="#"
        >
          <span>WEB DEMO</span>
          <span class="material-icons text-sm">arrow_forward</span>
        </a>
        <a
          class="flex items-center space-x-2 bg-white dark:bg-slate-800 text-primary border border-primary/20 hover:border-primary px-8 py-3 rounded-xl font-bold transition shadow-sm hover:shadow-md transform hover:-translate-y-1"
          href="#"
        >
          <span>ADMIN DEMO</span>
          <span class="material-icons text-sm">arrow_forward</span>
        </a>
        <a
          class="flex items-center space-x-2 bg-slate-900 dark:bg-slate-700 text-white hover:bg-slate-800 dark:hover:bg-slate-600 px-8 py-3 rounded-xl font-bold transition shadow-lg transform hover:-translate-y-1"
          href="#"
        >
          <span>LIÊN HỆ NGAY</span>
          <span class="material-icons text-sm">arrow_forward</span>
        </a>
      </div>
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-8 space-y-8">
          <div
            class="glass-panel bg-white/60 dark:bg-slate-800/60 border border-white/50 dark:border-slate-700/50 p-8 rounded-3xl shadow-glass"
          >
            <div class="flex items-start space-x-4 mb-6">
              <div class="p-3 bg-primary/10 rounded-2xl text-primary">
                <span class="material-icons text-3xl">language</span>
              </div>
              <div>
                <h2
                  class="text-2xl font-bold text-slate-900 dark:text-white mb-2"
                >
                  1. Giao Diện Người Dùng – Trải Nghiệm Mua Sắm Thực Phẩm Hiện
                  Đại
                </h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
                  Hisotech Grocery cung cấp một nền tảng mua sắm trực tuyến với
                  giao diện thân thiện và dễ sử dụng, giúp khách hàng dễ dàng
                  tìm kiếm và đặt mua các sản phẩm thực phẩm và nhu yếu phẩm.
                </p>
              </div>
            </div>
            <div class="space-y-4 pl-0 md:pl-16">
              <h3
                class="flex items-center font-bold text-lg text-slate-800 dark:text-slate-100"
              >
                <span class="material-icons text-primary mr-2">search</span>
                Tính năng nổi bật:
              </h3>
              <ul class="space-y-3 text-slate-600 dark:text-slate-300">
                <li class="flex items-start">
                  <span
                    class="w-1.5 h-1.5 bg-primary rounded-full mt-2 mr-3 flex-shrink-0"
                  ></span>
                  <span
                    ><strong class="text-slate-800 dark:text-slate-200"
                      >Danh mục sản phẩm đa dạng:</strong
                    >
                    Bao gồm rau củ, trái cây, thịt cá, sữa, đồ khô, đồ uống và
                    các sản phẩm hữu cơ.</span
                  >
                </li>
                <li class="flex items-start">
                  <span
                    class="w-1.5 h-1.5 bg-primary rounded-full mt-2 mr-3 flex-shrink-0"
                  ></span>
                  <span
                    ><strong class="text-slate-800 dark:text-slate-200"
                      >Tìm kiếm thông minh:</strong
                    >
                    Hỗ trợ tìm kiếm sản phẩm theo từ khóa, danh mục, thương hiệu
                    hoặc giá cả.</span
                  >
                </li>
                <li class="flex items-start">
                  <span
                    class="w-1.5 h-1.5 bg-primary rounded-full mt-2 mr-3 flex-shrink-0"
                  ></span>
                  <span
                    ><strong class="text-slate-800 dark:text-slate-200"
                      >Giỏ hàng và thanh toán linh hoạt:</strong
                    >
                    Cho phép khách hàng dễ dàng thêm sản phẩm vào giỏ hàng và
                    thanh toán qua nhiều hình thức khác nhau.</span
                  >
                </li>
                <li class="flex items-start">
                  <span
                    class="w-1.5 h-1.5 bg-primary rounded-full mt-2 mr-3 flex-shrink-0"
                  ></span>
                  <span
                    ><strong class="text-slate-800 dark:text-slate-200"
                      >Lịch sử mua hàng:</strong
                    >
                    Khách hàng có thể xem lại các đơn hàng đã đặt và theo dõi
                    trạng thái giao hàng.</span
                  >
                </li>
                <li class="flex items-start">
                  <span
                    class="w-1.5 h-1.5 bg-primary rounded-full mt-2 mr-3 flex-shrink-0"
                  ></span>
                  <span
                    ><strong class="text-slate-800 dark:text-slate-200"
                      >Giao diện tương thích:</strong
                    >
                    Thiết kế responsive, phù hợp với cả máy tính và thiết bị di
                    động.</span
                  >
                </li>
              </ul>
            </div>
          </div>
          <div
            class="glass-panel bg-white/60 dark:bg-slate-800/60 border border-white/50 dark:border-slate-700/50 p-8 rounded-3xl shadow-glass"
          >
            <div class="flex items-start space-x-4 mb-6">
              <div class="p-3 bg-primary/10 rounded-2xl text-primary">
                <span class="material-icons text-3xl"
                  >admin_panel_settings</span
                >
              </div>
              <div>
                <h2
                  class="text-2xl font-bold text-slate-900 dark:text-white mb-2"
                >
                  2. Trang Quản Trị – Quản Lý Cửa Hàng Hiệu Quả
                </h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
                  Hệ thống quản trị mạnh mẽ giúp chủ cửa hàng dễ dàng kiểm soát
                  mọi hoạt động kinh doanh từ xa.
                </p>
              </div>
            </div>
            <div class="space-y-4 pl-0 md:pl-16">
              <h3
                class="flex items-center font-bold text-lg text-slate-800 dark:text-slate-100"
              >
                <span class="material-icons text-primary mr-2"
                  >settings_suggest</span
                >
                Các tính năng chính:
              </h3>
              <ul class="space-y-3 text-slate-600 dark:text-slate-300">
                <li class="flex items-start">
                  <span
                    class="w-1.5 h-1.5 bg-primary rounded-full mt-2 mr-3 flex-shrink-0"
                  ></span>
                  <span
                    ><strong class="text-slate-800 dark:text-slate-200"
                      >Quản lý sản phẩm:</strong
                    >
                    Thêm, chỉnh sửa và xóa sản phẩm, bao gồm thông tin chi tiết,
                    hình ảnh và giá cả.</span
                  >
                </li>
                <li class="flex items-start">
                  <span
                    class="w-1.5 h-1.5 bg-primary rounded-full mt-2 mr-3 flex-shrink-0"
                  ></span>
                  <span
                    ><strong class="text-slate-800 dark:text-slate-200"
                      >Quản lý đơn hàng:</strong
                    >
                    Theo dõi và xử lý các đơn hàng, bao gồm việc xác nhận, hủy
                    và cập nhật trạng thái giao hàng.</span
                  >
                </li>
                <li class="flex items-start">
                  <span
                    class="w-1.5 h-1.5 bg-primary rounded-full mt-2 mr-3 flex-shrink-0"
                  ></span>
                  <span
                    ><strong class="text-slate-800 dark:text-slate-200"
                      >Quản lý khách hàng:</strong
                    >
                    Xem và quản lý thông tin khách hàng, hỗ trợ trong việc cung
                    cấp dịch vụ cá nhân hóa.</span
                  >
                </li>
                <li class="flex items-start">
                  <span
                    class="w-1.5 h-1.5 bg-primary rounded-full mt-2 mr-3 flex-shrink-0"
                  ></span>
                  <span
                    ><strong class="text-slate-800 dark:text-slate-200"
                      >Báo cáo và thống kê:</strong
                    >
                    Cung cấp dữ liệu về doanh thu, số lượng đơn hàng, sản phẩm
                    bán chạy và các chỉ số quan trọng khác.</span
                  >
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="lg:col-span-4 space-y-6">
          <div
            class="glass-panel bg-white/80 dark:bg-slate-800/80 border border-white/50 dark:border-slate-700/50 p-6 rounded-3xl shadow-glass sticky top-24"
          >
            <h3
              class="font-bold text-lg text-slate-900 dark:text-white mb-4 border-b border-gray-100 dark:border-gray-700 pb-2"
            >
              Thông tin kỹ thuật
            </h3>
            <div class="space-y-4">
              <div>
                <div
                  class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1"
                >
                  Công nghệ
                </div>
                <div class="flex flex-wrap gap-2">
                  <span
                    class="px-3 py-1 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-sm font-medium"
                    >Laravel</span
                  >
                  <span
                    class="px-3 py-1 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-sm font-medium"
                    >VueJS</span
                  >
                  <span
                    class="px-3 py-1 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-sm font-medium"
                    >MySQL</span
                  >
                </div>
              </div>
              <div>
                <div
                  class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1"
                >
                  Cập nhật lần cuối
                </div>
                <div
                  class="text-slate-800 dark:text-slate-200 font-medium flex items-center"
                >
                  <span class="material-icons text-sm mr-1 text-primary"
                    >calendar_today</span
                  >
                  12/05/2024
                </div>
              </div>
              <div>
                <div
                  class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1"
                >
                  Phát hành bởi
                </div>
                <div class="flex items-center gap-2">
                  <span
                    class="w-6 h-6 bg-primary rounded-full flex items-center justify-center text-white text-xs font-bold"
                    >H</span
                  >
                  <span class="text-slate-800 dark:text-slate-200 font-medium"
                    >HISOTECH Group</span
                  >
                </div>
              </div>
              <div
                class="pt-4 mt-4 border-t border-gray-100 dark:border-gray-700"
              >
                <button
                  class="w-full bg-slate-900 dark:bg-white dark:text-slate-900 text-white py-3 rounded-xl font-bold hover:opacity-90 transition"
                >
                  Yêu cầu báo giá
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { useMappedShortcodes } from "~/composables/useMappedShortcodes";

const props = defineProps<{
  slug: string;
}>();

const { Shortcodes, mapSectionsToShortcodes } = useMappedShortcodes();
const { data: pageData, pending } = await usePageDetail<any>(props.slug);
console.log(pageData.value);

if (pageData.value?.sections) {
  mapSectionsToShortcodes(pageData.value.sections);
}
</script>
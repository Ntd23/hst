<style>
body {
  font-family: "Inter", sans-serif;
}
.glass-card {
  background: rgba(255, 255, 255, 0.949);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.5);
  box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
}
.dark .glass-card {
  background: rgba(30, 41, 59, 0.7);
  border: 1px solid rgba(255, 255, 255, 0.05);
  box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
}
.glass-nav {
  background: rgba(255, 255, 255, 0.85);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border-bottom: 1px solid rgba(255, 255, 255, 0.3);
}
.dark .glass-nav {
  background: rgba(15, 23, 42, 0.85);
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}
.bg-avata {
  background: #1e3b8a;
}
</style>
<template>
  <main class="flex-grow container mx-auto px-4 lg:px-8 py-12 relative z-10">
    <div class="mb-12">
      <h1
        class="text-4xl lg:text-5xl font-bold text-hisotech-blue dark:text-blue-400 mb-4 tracking-tight"
      >
        Phần mềm demo
      </h1>
      <div
        class="flex items-center space-x-2 text-sm text-slate-500 dark:text-slate-400"
      >
        <a class="hover:text-primary" href="#">Trang chủ</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-primary font-medium">Phần mềm demo</span>
      </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <CommonsProductItem
        v-for="item in products"
        :key="item.id"
        :title="item.title"
        :image="item.image"
      />
    </div>
    <div class="flex justify-center mt-16 space-x-2">
      <a
        class="w-10 h-10 flex items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-500 hover:border-primary hover:text-primary transition-colors"
        href="#"
      >
        <span class="material-symbols-outlined text-sm"
          ><svg
            xmlns="http://www.w3.org/2000/svg"
            width="18"
            height="18"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <path d="M19 12H5" />
            <path d="M11 19l-7-7 7-7" />
          </svg>
      </span>
      </a>
      <a
        class="w-10 h-10 flex items-center justify-center rounded-lg bg-primary text-white font-medium shadow-lg shadow-blue-500/30"
        href="#"
        >1</a
      >
      <a
        class="w-10 h-10 flex items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:border-primary hover:text-primary transition-colors"
        href="#"
        >2</a
      >
      <a
        class="w-10 h-10 flex items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:border-primary hover:text-primary transition-colors"
        href="#"
        >3</a
      >
      <a
        class="w-10 h-10 flex items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-500 hover:border-primary hover:text-primary transition-colors"
        href="#"
      >
        <span class="material-symbols-outlined text-sm"
          ><svg
            xmlns="http://www.w3.org/2000/svg"
            width="18"
            height="18"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <path d="M5 12h14" />
            <path d="M13 5l7 7-7 7" /></svg
        ></span>
      </a>
    </div>
  </main>
</template>


<script setup lang="ts">
const products = [
  {
    id: 1,
    title: "Property | Nền Tảng Mua Bán Bất Động Sản",
    image:
      "https://lh3.googleusercontent.com/aida-public/AB6AXuC4Hm81Nfqgfzi28eVspDnoxqzM5WoqBzesU9E0tQElv0IOH-ITCiNnLHvWR8_Ay-xfepPaShX6WqUpJI1n510_JvlVH3UVjukrYF-fX9BrPJpF3zl_PYQEIVI8FkjG_wUJBwB_8YMuRKYeWJvGrCxeSak5P3d2s5qYd-LDeA7U2HgkQnjhBUH0ddYkg3DfReBAcy0oKrg11pVe3K5qAAk4NTTT5bhQAJCBBneMtP5JUMyZhQWONOsfb-sHK3J6Qgl834kxG2gwdr0",
  },
  {
    id: 2,
    title: "PropBusiness | Nền Tảng Quản Lý Doanh Nghiệp Toàn Diện",
    image:
      "https://lh3.googleusercontent.com/aida-public/AB6AXuDFAzEAExTtjttrz7-87igWcOiCoDtD71hobC1uvDmPzIUVI0hMLOf6tG97nmo76tyrXZNHLhhp11ovPU6EvNru1nRTwbQyCAYSJ4YSKaK5t2Elp1M8LAjM6jbtwIRyIqSfNXv1HtxJgIneYFPws3KhXZnYWmEt21tGHU7n1cUxfDjciEd1bsmVDSwq1cCrpMPPzHdGvCfOxXIDr3yo1rPEBjrqYVLSz-ErcELl1KiaItJPdKmPJT-FMD2-RTuLRDIUMCx1iseXkcI",
  },
  {
    id: 3,
    title: "Carrental | Nền Tảng Cho Thuê Xe Trực Tuyến",
    image:
      "https://lh3.googleusercontent.com/aida-public/AB6AXuAJQOuzwInLf1PWQt8_eQFg6SOksReN70V2ZmwCsmFTOPWSc5A-__F565uUopsOxM2N92N2IJACRx5YjvMrxd2JqCt0_YrQu_um6ON4HSWla4C-hUz78YlKuYdN9zd3qmRFvstFRLXSmo-vM_h4zB21tisfPbPOMt4B3MXD4Dy8Nmf-2-tdE7Hn9uvS8AVNQ1fcxp3iQsFnwmOUyXjYHeyajHcqhZk7hznXt9wedXf_2irQf3g_-xiMMs1AxZ5kOQ4SjS7Pj_s0bBw",
  },
  {
    id: 4,
    title: "Chat | Nền Tảng Nhắn Tin Và Giao Tiếp Trực Tuyến",
    image:
      "https://lh3.googleusercontent.com/aida-public/AB6AXuBBGYQPIJLll075DDKhO4-FoKzR4RtUHOLwe5S-BX9OXwlC8PbJZDARhVnvfEkjlWAhVpESlyFOG39WwYnW61XLxdRRiYGnl7HV0Uc055wceRMQWTwgrpGV5OIoSglV2-vCp8sMJii-v_4m6ylNhh9uo3impQegkvL2o-SH_yveyV0duHEIdvH6jxGcRoWvCvph-itC8vBTEqbXzUQeBLw_BmZ79jajWt_HTldYncCyASq-w_r3eWUmUL0AfDurz0Sq8ywCW8IPQlM",
  },
  {
    id: 5,
    title: "DRIVE | Nền Tảng Lưu Trữ Và Chia Sẻ Tập Tin",
    image:
      "https://lh3.googleusercontent.com/aida-public/AB6AXuAehBwBpic7lMalhXAwbsZLea-DLyPWcQFWhMSXf0zyRGmSMqhe-hDNVNJBU6X42Ll99igowo1Dh_uOcnhNM0YRoozo8IJ5NAoRlllKduBiIv1wz39U4WqAhIfFgstKmpxYL688mQRZJ7miqChscp-HjTKAEzTd56FLBIVnecsXoD1VlSkoFoAcgNIcet7TfKkXCj4qTd7-L9x1ijM4g1sNlF5_fqaEWmUhaNANsLC9ZT8vGGKWk9INf8Vllt33Y6rfui02PytdWnU",
  },
  {
    id: 6,
    title: "Coffee | Website Coffee Hisotech",
    image:
      "https://lh3.googleusercontent.com/aida-public/AB6AXuD6JHzxnTHF7M98qGVVShaWF8IhAHKbLC0NFnavpszihiRdFC8dNognHND7iPTdduyklz9Tc7SIKkGHOe7-7fuhI2E9ZFO3fkESwvDyNBPsooFEnuF7ohIQmZ7kExiyVNDbGls0QMWqrvshlPpu96-xCJXBq9re2qOGQu_KLVomK0PjlCaacz0UF_XVSlFNAoLVaRHzCugD0CDfg89cWA32s9PIM7GxfUW5_PSsoa9DslrYgjvCY4jlLttvmMubTbkoCuIa1eUHYkY",
  },
  {
    id: 7,
    title: "Grocery | Nền Tảng Mua Sắm Thực Phẩm Trực Tuyến",
    image:
      "https://lh3.googleusercontent.com/aida-public/AB6AXuDDEslczHT55Z9B-9xLZfYiWWlXbZduDgl_w7fJp-bWOK110yko8cG98_YYWOzioeafV94Cw0gmHEBqvbdVvxIwrk5sPnWSl8cMWoMD_A34qNxRn90bghKzBgFo39x7ZcCCPLEzWoFFjaKWU381JfuVRRjA9uwYm_mo5RxJ4HHOJ4XJYdP4UZOYcJPSidrTwiZzb0HEQ_pXmfAlh-Jj_Iicva_WGDNYGS9m_6YxIwwjv0VECp8C8ADxfJKFIOmY3uCiCv7_lRIJQy8",
  },
  {
    id: 8,
    title: "Hotel | Nền Tảng Quản Lý Khách Sạn",
    image:
      "https://lh3.googleusercontent.com/aida-public/AB6AXuBTfSu0KfhNbTkoQiSU5wQsOByl5PEJuw3DTvE9B7F_3hF8xhOQ1gPtvB8Dh1sGaD3zi3AJlRHsz1MMaYdCcN9YokvDQXXse2ndNRz9Gm5smrzr9m8qalJV4UEqR5mXclo4-gYMfdlSIZwZzMxTuOlA49NsgN8jd89P-v0Ix8pTTa15tVuO_UbY27VTVJzvVYlBl33oEGkm-KEFe-P08iwMXTsLEL9r6jJcflNJlcDQMN_W4daDKWvE0Td3HgWz-9mIyFX3-cTAuRA",
  },
  {
    id: 9,
    title: "Blog | Website Tin Tức",
    image:
      "https://lh3.googleusercontent.com/aida-public/AB6AXuB28fg_wwKQ08w1ZtrYcOaY74PyF7FDqO0Z8mdH1TCP2HFDaAnrK2lWyH-28hQiYp_WAeUfeA0Jf0rqP5Z1r8hua06dYqVRhBThXNDl7qJQCXJyozOM-zxY2VcZZQZgTslcQ4wzRdacn-a1-ODiAtvNIS9uumc3zR65kj1To1lIxonOMBLqtEnD5Eit_VL3977urxaeKojg-qlNSHWtk5I0j_OCsnq5C5FD_HVVCyFsQnbacjJsD3DYp89Sij0z5usEdeCpCp6vk0Y",
  },
];
</script>
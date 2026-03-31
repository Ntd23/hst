export const useDetailRoutePage = () => {
  const route = useRoute();

  const page = computed(() => String(route.params.page || ""));
  const detail = computed(() => String(route.params.detail || ""));
  const { detailComponent } = useMappedDetailPage(page);

  return {
    page,
    detail,
    detailComponent,
  };
};

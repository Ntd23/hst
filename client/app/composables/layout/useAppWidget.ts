import { useMappedWidgets } from "~/composables/layout/useMappedWidgets";
import { useLayoutWidgets } from "~/composables/layout/useLayoutWidgets";

type AppWidgetItem = {
  component: any;
  data: any;
  meta?: {
    widget?: string;
    position?: number | null;
  };
};

export const useAppWidget = () => {
  const { layoutWidgetData } = useLayoutWidgets();
  const { mapWidgets } = useMappedWidgets();
  const resolveLayoutItems = (section: any) => section?.items ?? section ?? [];

  const footerSettings = computed(() => layoutWidgetData.value?.settings ?? {});

  const topWidgets = computed(() =>
    mapWidgets(resolveLayoutItems(layoutWidgetData.value?.top_footer))
  );
  const mainWidgets = computed(() =>
    mapWidgets(resolveLayoutItems(layoutWidgetData.value?.footer))
  );
  const bottomWidgets = computed(() =>
    mapWidgets(resolveLayoutItems(layoutWidgetData.value?.bottom_footer))
  );

  const allFooterWidgets = computed(() => [...topWidgets.value, ...mainWidgets.value]);

  const newsletterWidget = computed<AppWidgetItem | undefined>(() =>
    allFooterWidgets.value.find((widget) => widget.meta?.widget === "newsletter")
  );

  const contentWidgets = computed(() =>
    mainWidgets.value.filter((widget) => widget.meta?.widget !== "newsletter")
  );

  const orderedContentWidgets = computed(() => contentWidgets.value);

  const copyrightWidget = computed<AppWidgetItem | undefined>(() =>
    bottomWidgets.value.find((widget) => widget.meta?.widget === "site-copyright")
  );

  const socialWidget = computed<AppWidgetItem | undefined>(() =>
    bottomWidgets.value.find((widget) => widget.meta?.widget === "social-links")
  );

  const copyrightText = computed(
    () =>
      copyrightWidget.value?.data?.content ||
      footerSettings.value?.copyright ||
      ""
  );

  const socials = computed(() => socialWidget.value?.data?.socials || []);

  return {
    footerSettings,
    newsletterWidget,
    orderedContentWidgets,
    copyrightText,
    socials,
  };
};

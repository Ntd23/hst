export const useBlogPostFeaturedShortcode = (
  sourceData: MaybeRefOrGetter<any>
) => {
  const sectionData = computed(() => toValue(sourceData) || {});
  const posts = computed(() => sectionData.value?.items || {});
  const postsRight = computed(() =>
    [posts.value?.post_2, posts.value?.post_3].filter(Boolean)
  );
  const preview = computed(() => {
    const match = posts.value?.post_1?.content?.match(/<p>(.*?)<\/p>/);

    return match ? match[1] : "";
  });

  const ready = ref(false);

  onMounted(() => {
    ready.value = true;
  });

  const leftMotion = {
    initial: { opacity: 0, x: -40 },
    visibleOnce: {
      opacity: 1,
      x: 0,
      transition: { duration: 900 },
    },
  };

  const cardMotion = (index: number) => ({
    initial: { opacity: 0, y: 40 },
    visibleOnce: {
      opacity: 1,
      y: 0,
      transition: { duration: 900, delay: index * 150 },
    },
  });

  return {
    posts,
    postsRight,
    preview,
    ready,
    leftMotion,
    cardMotion,
  };
};

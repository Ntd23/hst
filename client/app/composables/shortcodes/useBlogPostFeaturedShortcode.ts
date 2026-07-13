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

  return {
    posts,
    postsRight,
    preview,
  };
};

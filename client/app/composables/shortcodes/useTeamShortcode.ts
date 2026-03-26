type TeamSocials = Record<string, string>;

export const useTeamShortcode = (sourceData: MaybeRefOrGetter<any>) => {
  const rootData = computed(() => {
    const data = toValue(sourceData);

    return data?.content || data || {};
  });

  const sectionData = computed(() => rootData.value?.data || rootData.value || {});
  const team = computed(() => rootData.value?.items || []);

  const socialEntries = (socials?: TeamSocials) =>
    Object.entries(socials || {})
      .filter(([, value]) => Boolean(value))
      .map(([name, url]) => ({ name, url }));

  const socialIcon = (name: string) => {
    const normalized = String(name).toLowerCase();

    if (normalized === "facebook") return "i-simple-icons-facebook";
    if (normalized === "instagram") return "i-simple-icons-instagram";
    if (normalized === "twitter") return "i-simple-icons-x";
    if (normalized === "linkedin") return "i-simple-icons-linkedin";
    if (normalized === "youtube") return "i-simple-icons-youtube";

    return "i-lucide-link";
  };

  return {
    sectionData,
    team,
    socialEntries,
    socialIcon,
  };
};

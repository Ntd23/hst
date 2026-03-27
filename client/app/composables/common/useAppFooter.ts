export const useAppFooter = () => {
  const { translate } = useI18nText();
  const { footerData } = useFooter();

  const test = footerData.value;

  const footerLeftTitle = footerData.value.footer[0].data.title;
  console.log(footerData.value.footer[0].data.title);

  const socials = [
    { label: "f", to: "#" },
    { label: "in", to: "#" },
    { label: "yt", to: "#" },
  ];

  const serviceLinks = computed(() => [
    translate("footer.serviceLinks.software", "Software"),
    translate("footer.serviceLinks.consulting", "Consulting"),
    translate("footer.serviceLinks.design", "Design"),
    translate("footer.serviceLinks.security", "Security"),
  ]);

  const quickLinks = computed(() => [
    translate("footer.quickLinks.aboutUs", "About us"),
    translate("footer.quickLinks.careers", "Careers"),
    translate("footer.quickLinks.blog", "Blog"),
    translate("footer.quickLinks.privacyPolicy", "Privacy policy"),
  ]);

  const handleNewsletterSubmit = (event: Event) => {
    event.preventDefault();
  };

  return {
    socials,
    serviceLinks,
    quickLinks,
    handleNewsletterSubmit,
    test,
    footerLeftTitle,
  };
};

export const useDecodeHtml = () => {
  const decodeHtml = (value?: string) =>
    String(value || "")
      .replace(/&amp;/gi, "&")
      .replace(/&quot;/gi, '"')
      .replace(/&#39;/gi, "'")
      .replace(/&lt;/gi, "<")
      .replace(/&gt;/gi, ">");

  return {
    decodeHtml,
  };
};

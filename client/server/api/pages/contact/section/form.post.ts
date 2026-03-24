import { apiFetch } from "~~/server/utils/apiFetch";
import { getLocale } from "~~/server/utils/getLocale";
import type { ContactFormPayload } from "~~/shared/types/contact";

export default defineEventHandler(async (event) => {
  const locale = getLocale(event);
  const body =
    (event.context.validatedContactFormBody as
      | ContactFormPayload
      | undefined) || (await readBody(event));

  try {
    return await apiFetch<any>(event, "/pages/contact/section/form", {
      method: "POST",
      body,
      query: { locale },
      headers: { "X-Locale": locale },
    });
  } catch (error: any) {
    const responseData = error?.data || error?.response?._data;

    throw createError({
      statusCode: error?.statusCode || error?.response?.status || 500,
      statusMessage:
        responseData?.message ||
        error?.statusMessage ||
        error?.message ||
        "Failed to submit contact form",
      data: responseData,
    });
  }
});

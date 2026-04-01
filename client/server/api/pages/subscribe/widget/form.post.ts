import { proxyApi } from "~~/server/utils/http/apiProxy";
import type { NewsletterPayload } from "~/stores/newsletter";

export default defineEventHandler(async (event) => {
  const body =
    (event.context.validatedNewsletterFormBody as
      | NewsletterPayload
      | undefined) || (await readBody(event));

  try {
    return await proxyApi<any>(event, {
      path: "/pages/subscribe/widget/form",
      method: "POST",
      body,
    });
  } catch (error: any) {
    const responseData = error?.data || error?.response?._data;

    throw createError({
      statusCode: error?.statusCode || error?.response?.status || 500,
      statusMessage:
        error?.statusMessage || error?.message || "Failed to submit newsletter form",
      data: responseData,
    });
  }
});

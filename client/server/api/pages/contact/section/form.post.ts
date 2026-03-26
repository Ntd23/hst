import { proxyApi } from "~~/server/utils/http/apiProxy";
import type { ContactFormPayload } from "~~/shared/validation/types";

export default defineEventHandler(async (event) => {
  const body =
    (event.context.validatedContactFormBody as
      | ContactFormPayload
      | undefined) || (await readBody(event));

  try {
    return await proxyApi<any>(event, {
      path: "/pages/contact/section/form",
      method: "POST",
      body,
    });
  } catch (error: any) {
    const responseData = error?.data || error?.response?._data;

    throw createError({
      statusCode: error?.statusCode || error?.response?.status || 500,
      statusMessage:
        error?.statusMessage || error?.message || "Failed to submit contact form",
      data: responseData,
    });
  }
});



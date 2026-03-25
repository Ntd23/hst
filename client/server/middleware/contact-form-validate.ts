import { validateContactFormBody } from "~~/shared/utils/contactFormValidation";
import { getContactValidationMessages } from "~~/shared/utils/contactFormMessages";
import { getLocale } from "~~/server/utils/getLocale";

export default defineEventHandler(async (event) => {
  if (
    event.node.req.method !== "POST" ||
    event.path !== "/api/pages/contact/section/form"
  ) {
    return;
  }

  const locale = getLocale(event);
  const body = await readBody(event);
  const { data, errors } = validateContactFormBody(body || {}, locale);
  const messages = getContactValidationMessages(locale);

  if (errors) {
    throw createError({
      statusCode: 422,
      statusMessage: "Validation failed",
      data: {
        message: messages.invalid,
        errors,
      },
    });
  }

  event.context.validatedContactFormBody = data;
});

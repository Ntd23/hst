import { proxyApi } from "~~/server/utils/http/apiProxy";

export default defineEventHandler(async (event): Promise<any> => {
  setResponseHeaders(event, {
    "Cache-Control": "no-store, no-cache, must-revalidate",
    Pragma: "no-cache",
  });

  return proxyApi<any>(event, {
    path: "/widgets/layout",
  });
});

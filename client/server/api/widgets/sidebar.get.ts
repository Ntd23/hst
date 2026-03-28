import { getQuery } from "h3";
import { proxyApi } from "~~/server/utils/http/apiProxy";

export default defineEventHandler(async (event): Promise<any> => {
  const query = getQuery(event);

  setResponseHeaders(event, {
    "Cache-Control": "no-store, no-cache, must-revalidate",
    Pragma: "no-cache",
  });

  return proxyApi<any>(event, {
    path: "/widgets/sidebar",
    query: {
      type: query.type || "primary",
    },
  });
});

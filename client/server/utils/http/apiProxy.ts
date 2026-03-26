import type { H3Event } from "h3";
import { apiFetch } from "~~/server/utils/http/apiFetch";
import { getLocale } from "~~/server/utils/locale/getLocale";

type ProxyApiOptions = {
  path: string;
  query?: Record<string, any>;
  headers?: Record<string, string>;
  method?: Parameters<typeof $fetch>[1]["method"];
  body?: any;
};

type ProxyApiErrorOptions<T> = {
  fallback?: T;
  onError?: (error: any) => T;
};

export const proxyApi = async <T = any>(
  event: H3Event,
  options: ProxyApiOptions
): Promise<T> => {
  const locale = getLocale(event);

  return apiFetch<T>(options.path, {
    method: options.method,
    body: options.body,
    query: {
      ...(options.query || {}),
      locale,
    },
    headers: {
      ...(options.headers || {}),
      "X-Locale": locale,
    },
  });
};

export const proxyApiWithFallback = async <T = any>(
  event: H3Event,
  options: ProxyApiOptions,
  errorOptions: ProxyApiErrorOptions<T> = {}
): Promise<T> => {
  try {
    return await proxyApi<T>(event, options);
  } catch (error: any) {
    if (errorOptions.onError) {
      return errorOptions.onError(error);
    }

    if ("fallback" in errorOptions) {
      return errorOptions.fallback as T;
    }

    throw error;
  }
};


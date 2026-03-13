import { ref, markRaw } from 'vue';
import { getShortcodeComponents } from '~/utils/getShortcodeComponents';

export function useMappedShortcodes() {
  const components = getShortcodeComponents();
  const Shortcodes = ref<any[]>([]);

  const mapSectionsToShortcodes = (sections: any[]) => {
    if (!sections || !Array.isArray(sections)) {
      Shortcodes.value = [];
      return;
    }

    Shortcodes.value = sections.map((section: any) => {
      const shortcode = section.shortcode;
      const formattedName = shortcode
        .split('-')
        .map((i: string) => i.charAt(0).toUpperCase() + i.slice(1))
        .join('');
      const comp = components[`Shortcode${formattedName}`] || 'div';
      
      return {
        component: typeof comp === 'string' ? comp : markRaw(comp),
        data: section.content,
      };
    });
  };

  return {
    Shortcodes,
    mapSectionsToShortcodes,
  };
}

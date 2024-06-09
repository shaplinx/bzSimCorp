import { useRoute } from "vue-router";
import { computed } from "vue"

export function useMenuItem(item: Base.Component.Menu.MenuItem) {
  const route = useRoute();
  function nestedSome(
    array: any[] | undefined,
    callback: (item: any) => boolean
  ): boolean {
    if (!array?.length) return false;
    return array.some( (item: any) => {
      if (item.child) {
        return nestedSome(item.child, callback);
      }
      return callback(item.to);
    });
  }

  function checkMenuActive(to: Base.Component.Menu.MenuItem["to"]) {
    if (!to) return false
    if (typeof to !== 'string') {
      return to.name === route.name
    }
      return route.fullPath === to

  }

  const isChildActive = computed(() => {
    return nestedSome(item?.child, (child) => checkMenuActive(child));
  });

  const routeActive = computed(() => {
    return checkMenuActive(item.to)
  });


  const isActive = computed(() => {
    return isChildActive.value || routeActive.value;
  });
  return {
    isChildActive,
    routeActive,
    isActive
  }
}


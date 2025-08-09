export function deepMerge<T extends object>(target: any, ...sources: any[]): T {
  for (const source of sources) {
    if (source && typeof source === 'object') {
      for (const key in source) {
        if (
          source[key] &&
          typeof source[key] === 'object' &&
          !Array.isArray(source[key])
        ) {
          if (!target[key] || typeof target[key] !== 'object') {
            (target as any)[key] = {};
          }
          deepMerge(target[key] as any, source[key] as any);
        } else {
          (target as any)[key] = source[key];
        }
      }
    }
  }
  return target;
}
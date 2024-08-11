import { useCrud } from "./@hooks/api/useCrud";

export const useUserResources = () => useCrud<App.Models.User>({baseUrl:"user"})


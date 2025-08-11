import { AxiosRequestConfig, AxiosResponse } from "axios";
import { SuccessResponse, useCrud } from "./@hooks/api/useCrud";
import axios from "@/@hooks/api/useAxios"


export const useUserResources = () => useCrud<App.Models.User>({ baseUrl: "user", actions: ["export", "index", "create", "delete", "show", "update"] });

export const useInstitutionResources = () => useCrud<App.Models.DocumentsInstitution>({ baseUrl: "documents/institutions", actions: ["export", "index", "create", "delete", "show", "update"] });
export const useClassificationResources = () => useCrud<App.Models.DocumentsClassification>({ baseUrl: "documents/classifications", actions: ["export", "index", "create", "delete", "show", "update"] });
export const useLetterResources = () => useCrud<App.Models.DocumentsLetter>({
  baseUrl: "documents/letters",
  actions: ["export", "index", "create", "delete", "show", "update"],
  overrides: {
    create: (config) => {
      return new Promise<AxiosResponse<SuccessResponse<App.Models.DocumentsLetter>>>((reslove, reject) => {
        axios.postForm('documents/letters', config?.data)
          .then((res) => reslove(res))
          .catch((err) => reject(err))
      })
    },
    update: (identifier, config) => {
      return new Promise<AxiosResponse<SuccessResponse<App.Models.DocumentsLetter>>>((reslove, reject) => {
        axios.postForm(`documents/letters/${identifier}`, config?.data, { params: { _method: "PATCH" } })
          .then((res) => reslove(res))
          .catch((err) => reject(err))
      })
    },
    download: (identifier: string, config: AxiosRequestConfig) => {
      return new Promise<string>((reslove, reject) => {
        axios(`documents/letters/${identifier}/download`, { responseType: 'blob', ...config })
          .then((res) => {
            const file = new Blob([res.data], { type: 'application/pdf' });
            reslove(URL.createObjectURL(file))
          })
          .catch((err) => reject(err))
      })
    }
  }
});

export const useMessagingResources = () => useCrud<App.Models.MessagingMessage>({
  baseUrl: "messages",
  actions: ["index", "create", "show"],
});

export const useShortUrlResources = () => useCrud({
  baseUrl: "shorturl",
  overrides: {
    export: (id, config?: AxiosRequestConfig) => {
      return new Promise<string | undefined>((reslove, reject) => {
        axios({
          method: "GET",
          url: `shorturl/${id}/export`,
          responseType: 'blob',
          ...config
        })
          .then((res) => {
            const file = new Blob([res.data], {
              type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            });
            const url = URL.createObjectURL(file);
            reslove(url);
          })
          .catch((err) => reject(err))
      })
    }
  }
})



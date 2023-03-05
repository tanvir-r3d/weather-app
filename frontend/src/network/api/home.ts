import api from "@/network/api";

export const usersApi = () => {
  return api.get("/users");
};

export const weathersApi = (email: any) => {
  return api.get(`/weathers/${email}`);
};

export const weatherDetailsApi = (email: any) => {
  return api.get(`/weathers/${email}/details`);
};

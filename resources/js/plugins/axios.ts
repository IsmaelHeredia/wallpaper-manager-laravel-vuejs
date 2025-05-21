import axios from 'axios';
import config from '@/config';

const instance = axios.create({
  baseURL: '',
  headers: {
    'Accept': 'application/json',
  },
});

instance.interceptors.request.use(configAxios => {
  const token = sessionStorage.getItem(config.SESSION_NAME);
  if (token) {
    configAxios.headers.Authorization = `Bearer ${token}`;
  }
  return configAxios;
}, error => {
  return Promise.reject(error);
});

export default instance;
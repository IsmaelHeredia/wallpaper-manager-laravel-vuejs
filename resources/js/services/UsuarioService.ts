import api from '@/plugins/axios';

import config from '@/config';

export default {
  login(datos: any) {
    return api.post(`${config.API_URL}/ingreso`, datos);
  },
  validar() {
    return api.get(`${config.API_URL}/validar`);
  },
  actualizarCuenta(datos: any) {
    return api.post(`${config.API_URL}/cuenta/actualizar?_method=PUT`, datos);
  },
  salir() {
    return api.post(`${config.API_URL}/salir`);
  },
};
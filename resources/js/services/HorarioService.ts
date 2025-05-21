import api from '@/plugins/axios';

import config from '@/config';

export default {
  async listar() {
    const response = await api.get(`${config.API_URL}/horarios`);
    return response.data;
  },
};
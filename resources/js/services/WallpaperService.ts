import api from '@/plugins/axios';

import config from '@/config';

export default {
  async buscar(filtros: {
    nombre?: string
    estaciones_ids?: number[]
    horarios_ids?: number[]
    page?: number
  }) {

    const finalParams = {
      ...filtros,
      per_page: 12,
      page: filtros.page ?? 1
    };
  
    const response = await api.get(`${config.API_URL}/wallpapers/buscar`, { params: finalParams });
    
    return response.data;
  },
  async obtener(id: number) {
    const response = await api.get(`${config.API_URL}/wallpapers/${id}`);
    return response.data;
  },
  async crear(data: FormData) {
    const response = await api.post(`${config.API_URL}/wallpapers`, data, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
    return response;
  },

  async actualizar(id: number | string, data: FormData) {
    const response = await api.post(`${config.API_URL}/wallpapers/${id}?_method=PUT`, data, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
    return response;
  },
  async eliminar(id: number) {
    const response = await api.delete(`${config.API_URL}/wallpapers/${id}`);
    return response.data;
  },
  async generarReporte() {
    const response = await api.get(`${config.API_URL}/estadisticas`);
    return response.data;
  },
};
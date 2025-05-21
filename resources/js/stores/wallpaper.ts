import { defineStore } from 'pinia';

interface FiltrosWallpaper {
  nombre: string;
  estaciones: number[];
  horarios: number[];
}

interface PaginacionWallpaper {
  pagina?: number;
}

interface WallpaperState {
  filtros: FiltrosWallpaper;
  paginacion: PaginacionWallpaper;
}

export const useWallpaperStore = defineStore('wallpaper', {
  state: (): WallpaperState => ({
    filtros: {
      nombre: '',
      estaciones: [],
      horarios: [],
    },
    paginacion: {
      pagina: 1
    },
  }),

  actions: {

    setFiltros(filtros: Partial<FiltrosWallpaper>) {
      this.filtros = { ...this.filtros, ...filtros };
    },

    resetFiltros() {
      this.filtros = {
        nombre: '',
        estaciones: [],
        horarios: [],
      };
    },

    setPagina(paginacion: Partial<PaginacionWallpaper>) {
      this.paginacion.pagina = paginacion.pagina;
    }
  },
});

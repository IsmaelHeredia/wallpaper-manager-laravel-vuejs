declare module '@/plugins/axios' {
  import axios from 'axios';
  const instance: typeof axios;
  export default instance;
}
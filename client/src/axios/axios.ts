import axios from 'axios';

const END_POINT = import.meta.env.VITE_API_URL ?? 'http://localhost:8000/';

//Axios instance to overide default configuration
const instance = axios.create({
  baseURL: END_POINT,
  withCredentials:true,
  withXSRFToken:true,
});

//Configure Request Interceptors to pass the token to the requests
instance.interceptors.request.use(
  (config) => {
    console.log('Axios Request interceptor run');

    ;
    return config;
  },
  (err) => {
    console.log('Axios Request interceptor fails');

    return Promise.reject(err);
  },
);

// Configure Respone Interceptor to modify the response payload and hanle refresh token
instance.interceptors.response.use(
  function (response) {
    console.log('Axios Response interceptor run');

    return response;
  },
  async function (error) {

    console.log(error);

    return Promise.reject(error);
  },
);

export default instance;

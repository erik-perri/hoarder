import axios, { AxiosError } from 'axios';
import { isRequestValidationError, isUnhandledExceptionError } from './types';

// Setup the default API options.
const api = axios.create({
  timeout: 5000,
  headers: { 'X-Requested-With': 'XMLHttpRequest' },
});

// Add an interceptor to axios to convert any error responses into ApiResponse types with an 'error'
// status.
api.interceptors.response.use(
  (response) => {
    return response;
  },
  (error: AxiosError) => {
    if (!error.response) {
      // Errors that didn't even make it to a response (timeout, refused, etc)
      throw {
        status: 'error',
        message: error.message
          ? `Unexpected error: ${error.message.replace(/\.$/, '')}.`
          : 'Unknown error.',
      };
    } else if (isUnhandledExceptionError(error.response?.data)) {
      throw {
        status: 'error',
        message:
          process.env.NODE_ENV !== 'production'
            ? error?.response?.data.message
            : 'Unhandled error.',
      };
    } else if (isRequestValidationError(error.response?.data)) {
      throw {
        status: 'error',
        message: error.response?.data.message,
        errors: error.response?.data.errors,
      };
    } else {
      throw {
        status: 'error',
        message: error.message
          ? `Unexpected error: ${error.message.replace(/\.$/, '')}.`
          : 'Unknown error.',
      };
    }
  }
);

export default api;

// This is what Laravel returns when an unhandled exception occurs
export interface UnhandledExceptionError {
  message: string;
  exception: string;
  file: string;
  line: number;
  trace: Array<{
    file: string;
    line: number;
    function: string;
    class: string;
    type: string;
  }>;
}

export function isUnhandledExceptionError(
  response: any
): response is UnhandledExceptionError {
  const testResponse = response as UnhandledExceptionError;
  return !!testResponse.exception && !!testResponse.message;
}

// This is what Laravel returns when a Request validation fails
export interface RequestValidationError {
  message: string;
  errors: Record<string, string[]>;
}

export function isRequestValidationError(
  response: any
): response is RequestValidationError {
  const testResponse = response as RequestValidationError;
  return !!testResponse.message && !!testResponse.errors;
}

// This is what our API requests should return
// TODO Build a class on the server side to return a consistent API response and update this to
//      match.
// TODO Figure out a better way to split errors so we don't need to use conditional checks
//      everywhere.
export interface ApiResponse<DataType = never> {
  status: 'success' | 'fail' | 'error';
  data?: DataType;
  message?: string;
  errors?: Record<string, string[]>;
}

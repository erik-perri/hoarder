// TODO Build a class on the server side to return a consistent API response

export interface ApiResponse<DataType = never> {
  status: 'success' | 'fail' | 'error';
  message?: string;
  errors?: Record<string, string[]>;
  data?: DataType;
}

export interface FormErrors {
  message: string;
  errors: Record<string, string[]>;
}

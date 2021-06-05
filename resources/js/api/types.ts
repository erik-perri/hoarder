// This is what Laravel returns when a Request validation fails
export interface FormErrors {
  message: string;
  errors: Record<string, string[]>;
}

// This is what our API requests should return
// TODO Build a class on the server side to return a consistent API response and update this to
//      match.
export interface ApiResponse<DataType = never> extends Partial<FormErrors> {
  status: 'success' | 'fail' | 'error';
  data?: DataType;
}

export * from './types';
export { default as FieldEditor } from './FieldEditor.vue';

// TODO Should we have this component set a JSON field like CriteriaBuilder? JSON is used there due
//      to the recursive structure which is not needed here, but it feels weird to have one set a
//      JSON field and one that just renders an HTML form for use with a regular request.

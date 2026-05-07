alter table "public"."attendance_records" drop constraint "attendance_records_status_check";

alter table "public"."system_logs" drop constraint "system_logs_action_check";

alter table "public"."users" drop constraint "users_role_check";

alter table "public"."attendance_records" add constraint "attendance_records_status_check" CHECK (((status)::text = ANY (ARRAY['present'::text, 'late'::text, 'absent'::text, 'excused'::text]))) not valid;

alter table "public"."attendance_records" validate constraint "attendance_records_status_check";

alter table "public"."system_logs" add constraint "system_logs_action_check" CHECK (((action)::text = ANY ((ARRAY['login'::character varying, 'logout'::character varying, 'scan_qr'::character varying, 'attendance_record'::character varying, 'create_class'::character varying, 'update_class'::character varying, 'delete_class'::character varying, 'add_student'::character varying, 'remove_student'::character varying, 'generate_qr'::character varying, 'update_user'::character varying, 'delete_user'::character varying, 'other'::character varying])::text[]))) not valid;

alter table "public"."system_logs" validate constraint "system_logs_action_check";

alter table "public"."users" add constraint "users_role_check" CHECK (((role)::text = ANY ((ARRAY['admin'::character varying, 'professor'::character varying, 'student'::character varying])::text[]))) not valid;

alter table "public"."users" validate constraint "users_role_check";



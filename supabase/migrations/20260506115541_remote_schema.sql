


SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;


COMMENT ON SCHEMA "public" IS 'standard public schema';



CREATE EXTENSION IF NOT EXISTS "pg_stat_statements" WITH SCHEMA "extensions";






CREATE EXTENSION IF NOT EXISTS "pgcrypto" WITH SCHEMA "extensions";






CREATE EXTENSION IF NOT EXISTS "supabase_vault" WITH SCHEMA "vault";






CREATE EXTENSION IF NOT EXISTS "uuid-ossp" WITH SCHEMA "extensions";





SET default_tablespace = '';

SET default_table_access_method = "heap";


CREATE TABLE IF NOT EXISTS "public"."attendance_records" (
    "id" bigint NOT NULL,
    "class_id" bigint NOT NULL,
    "student_id" bigint NOT NULL,
    "qr_code_id" bigint,
    "status" character varying(255) DEFAULT 'present'::character varying NOT NULL,
    "minutes_late" integer DEFAULT 0 NOT NULL,
    "recorded_at" timestamp(0) without time zone NOT NULL,
    "created_at" timestamp(0) without time zone,
    "updated_at" timestamp(0) without time zone,
    CONSTRAINT "attendance_records_status_check" CHECK ((("status")::"text" = ANY ((ARRAY['present'::character varying, 'late'::character varying, 'absent'::character varying])::"text"[])))
);


ALTER TABLE "public"."attendance_records" OWNER TO "postgres";


CREATE SEQUENCE IF NOT EXISTS "public"."attendance_records_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE "public"."attendance_records_id_seq" OWNER TO "postgres";


ALTER SEQUENCE "public"."attendance_records_id_seq" OWNED BY "public"."attendance_records"."id";



CREATE TABLE IF NOT EXISTS "public"."cache" (
    "key" character varying(255) NOT NULL,
    "value" "text" NOT NULL,
    "expiration" integer NOT NULL
);


ALTER TABLE "public"."cache" OWNER TO "postgres";


CREATE TABLE IF NOT EXISTS "public"."cache_locks" (
    "key" character varying(255) NOT NULL,
    "owner" character varying(255) NOT NULL,
    "expiration" integer NOT NULL
);


ALTER TABLE "public"."cache_locks" OWNER TO "postgres";


CREATE TABLE IF NOT EXISTS "public"."class_professor" (
    "id" bigint NOT NULL,
    "class_id" bigint NOT NULL,
    "professor_id" bigint NOT NULL,
    "created_at" timestamp(0) without time zone,
    "updated_at" timestamp(0) without time zone
);


ALTER TABLE "public"."class_professor" OWNER TO "postgres";


CREATE SEQUENCE IF NOT EXISTS "public"."class_professor_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE "public"."class_professor_id_seq" OWNER TO "postgres";


ALTER SEQUENCE "public"."class_professor_id_seq" OWNED BY "public"."class_professor"."id";



CREATE TABLE IF NOT EXISTS "public"."class_student" (
    "id" bigint NOT NULL,
    "class_id" bigint NOT NULL,
    "student_id" bigint NOT NULL,
    "enrolled_at" timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    "created_at" timestamp(0) without time zone,
    "updated_at" timestamp(0) without time zone
);


ALTER TABLE "public"."class_student" OWNER TO "postgres";


CREATE SEQUENCE IF NOT EXISTS "public"."class_student_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE "public"."class_student_id_seq" OWNER TO "postgres";


ALTER SEQUENCE "public"."class_student_id_seq" OWNED BY "public"."class_student"."id";



CREATE TABLE IF NOT EXISTS "public"."classes" (
    "id" bigint NOT NULL,
    "code" character varying(20) NOT NULL,
    "name" character varying(255) NOT NULL,
    "description" "text",
    "professor_id" bigint NOT NULL,
    "student_count" integer DEFAULT 0 NOT NULL,
    "is_active" boolean DEFAULT true NOT NULL,
    "created_at" timestamp(0) without time zone,
    "updated_at" timestamp(0) without time zone
);


ALTER TABLE "public"."classes" OWNER TO "postgres";


CREATE SEQUENCE IF NOT EXISTS "public"."classes_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE "public"."classes_id_seq" OWNER TO "postgres";


ALTER SEQUENCE "public"."classes_id_seq" OWNED BY "public"."classes"."id";



CREATE TABLE IF NOT EXISTS "public"."drop_requests" (
    "id" bigint NOT NULL,
    "professor_id" bigint NOT NULL,
    "student_id" bigint NOT NULL,
    "class_id" bigint NOT NULL,
    "reason" "text",
    "status" character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    "admin_id" bigint,
    "reviewed_at" timestamp(0) without time zone,
    "created_at" timestamp(0) without time zone,
    "updated_at" timestamp(0) without time zone
);


ALTER TABLE "public"."drop_requests" OWNER TO "postgres";


CREATE SEQUENCE IF NOT EXISTS "public"."drop_requests_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE "public"."drop_requests_id_seq" OWNER TO "postgres";


ALTER SEQUENCE "public"."drop_requests_id_seq" OWNED BY "public"."drop_requests"."id";



CREATE TABLE IF NOT EXISTS "public"."failed_jobs" (
    "id" bigint NOT NULL,
    "uuid" character varying(255) NOT NULL,
    "connection" "text" NOT NULL,
    "queue" "text" NOT NULL,
    "payload" "text" NOT NULL,
    "exception" "text" NOT NULL,
    "failed_at" timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE "public"."failed_jobs" OWNER TO "postgres";


CREATE SEQUENCE IF NOT EXISTS "public"."failed_jobs_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE "public"."failed_jobs_id_seq" OWNER TO "postgres";


ALTER SEQUENCE "public"."failed_jobs_id_seq" OWNED BY "public"."failed_jobs"."id";



CREATE TABLE IF NOT EXISTS "public"."job_batches" (
    "id" character varying(255) NOT NULL,
    "name" character varying(255) NOT NULL,
    "total_jobs" integer NOT NULL,
    "pending_jobs" integer NOT NULL,
    "failed_jobs" integer NOT NULL,
    "failed_job_ids" "text" NOT NULL,
    "options" "text",
    "cancelled_at" integer,
    "created_at" integer NOT NULL,
    "finished_at" integer
);


ALTER TABLE "public"."job_batches" OWNER TO "postgres";


CREATE TABLE IF NOT EXISTS "public"."jobs" (
    "id" bigint NOT NULL,
    "queue" character varying(255) NOT NULL,
    "payload" "text" NOT NULL,
    "attempts" smallint NOT NULL,
    "reserved_at" integer,
    "available_at" integer NOT NULL,
    "created_at" integer NOT NULL
);


ALTER TABLE "public"."jobs" OWNER TO "postgres";


CREATE SEQUENCE IF NOT EXISTS "public"."jobs_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE "public"."jobs_id_seq" OWNER TO "postgres";


ALTER SEQUENCE "public"."jobs_id_seq" OWNED BY "public"."jobs"."id";



CREATE TABLE IF NOT EXISTS "public"."migrations" (
    "id" integer NOT NULL,
    "migration" character varying(255) NOT NULL,
    "batch" integer NOT NULL
);


ALTER TABLE "public"."migrations" OWNER TO "postgres";


CREATE SEQUENCE IF NOT EXISTS "public"."migrations_id_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE "public"."migrations_id_seq" OWNER TO "postgres";


ALTER SEQUENCE "public"."migrations_id_seq" OWNED BY "public"."migrations"."id";



CREATE TABLE IF NOT EXISTS "public"."password_reset_tokens" (
    "email" character varying(255) NOT NULL,
    "token" character varying(255) NOT NULL,
    "created_at" timestamp(0) without time zone
);


ALTER TABLE "public"."password_reset_tokens" OWNER TO "postgres";


CREATE TABLE IF NOT EXISTS "public"."qr_codes" (
    "id" bigint NOT NULL,
    "uuid" "uuid" NOT NULL,
    "class_id" bigint,
    "professor_id" bigint,
    "is_used" boolean DEFAULT false NOT NULL,
    "used_at" timestamp(0) without time zone,
    "expires_at" timestamp(0) without time zone,
    "created_at" timestamp(0) without time zone,
    "updated_at" timestamp(0) without time zone,
    "student_id" bigint,
    "code" "text"
);


ALTER TABLE "public"."qr_codes" OWNER TO "postgres";


CREATE SEQUENCE IF NOT EXISTS "public"."qr_codes_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE "public"."qr_codes_id_seq" OWNER TO "postgres";


ALTER SEQUENCE "public"."qr_codes_id_seq" OWNED BY "public"."qr_codes"."id";



CREATE TABLE IF NOT EXISTS "public"."schedules" (
    "id" bigint NOT NULL,
    "subject_code" character varying(20) NOT NULL,
    "subject_name" character varying(255) NOT NULL,
    "professor_id" bigint NOT NULL,
    "professor" character varying(255) NOT NULL,
    "days" character varying(20) NOT NULL,
    "time" character varying(50) NOT NULL,
    "room" character varying(20) NOT NULL,
    "created_at" timestamp(0) without time zone,
    "updated_at" timestamp(0) without time zone,
    "class_id" bigint,
    "start_time" time(0) without time zone,
    "end_time" time(0) without time zone
);


ALTER TABLE "public"."schedules" OWNER TO "postgres";


CREATE SEQUENCE IF NOT EXISTS "public"."schedules_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE "public"."schedules_id_seq" OWNER TO "postgres";


ALTER SEQUENCE "public"."schedules_id_seq" OWNED BY "public"."schedules"."id";



CREATE TABLE IF NOT EXISTS "public"."sessions" (
    "id" character varying(255) NOT NULL,
    "user_id" bigint,
    "ip_address" character varying(45),
    "user_agent" "text",
    "payload" "text" NOT NULL,
    "last_activity" integer NOT NULL
);


ALTER TABLE "public"."sessions" OWNER TO "postgres";


CREATE TABLE IF NOT EXISTS "public"."system_logs" (
    "id" bigint NOT NULL,
    "user_id" bigint NOT NULL,
    "action" character varying(255) NOT NULL,
    "description" "text",
    "ip_address" character varying(45),
    "user_agent" character varying(255),
    "created_at" timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT "system_logs_action_check" CHECK ((("action")::"text" = ANY ((ARRAY['login'::character varying, 'logout'::character varying, 'scan_qr'::character varying, 'attendance_record'::character varying, 'create_class'::character varying, 'update_class'::character varying, 'delete_class'::character varying, 'add_student'::character varying, 'remove_student'::character varying, 'generate_qr'::character varying, 'update_user'::character varying, 'delete_user'::character varying, 'other'::character varying])::"text"[])))
);


ALTER TABLE "public"."system_logs" OWNER TO "postgres";


CREATE SEQUENCE IF NOT EXISTS "public"."system_logs_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE "public"."system_logs_id_seq" OWNER TO "postgres";


ALTER SEQUENCE "public"."system_logs_id_seq" OWNED BY "public"."system_logs"."id";



CREATE TABLE IF NOT EXISTS "public"."users" (
    "id" bigint NOT NULL,
    "name" character varying(255) NOT NULL,
    "email" character varying(255) NOT NULL,
    "email_verified_at" timestamp(0) without time zone,
    "password" character varying(255) NOT NULL,
    "remember_token" character varying(100),
    "created_at" timestamp(0) without time zone,
    "updated_at" timestamp(0) without time zone,
    "role" character varying(255) DEFAULT 'student'::character varying NOT NULL,
    "username" character varying(255),
    "student_id" character varying(255),
    "is_active" boolean DEFAULT true NOT NULL,
    CONSTRAINT "users_role_check" CHECK ((("role")::"text" = ANY ((ARRAY['admin'::character varying, 'professor'::character varying, 'student'::character varying])::"text"[])))
);


ALTER TABLE "public"."users" OWNER TO "postgres";


CREATE SEQUENCE IF NOT EXISTS "public"."users_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE "public"."users_id_seq" OWNER TO "postgres";


ALTER SEQUENCE "public"."users_id_seq" OWNED BY "public"."users"."id";



ALTER TABLE ONLY "public"."attendance_records" ALTER COLUMN "id" SET DEFAULT "nextval"('"public"."attendance_records_id_seq"'::"regclass");



ALTER TABLE ONLY "public"."class_professor" ALTER COLUMN "id" SET DEFAULT "nextval"('"public"."class_professor_id_seq"'::"regclass");



ALTER TABLE ONLY "public"."class_student" ALTER COLUMN "id" SET DEFAULT "nextval"('"public"."class_student_id_seq"'::"regclass");



ALTER TABLE ONLY "public"."classes" ALTER COLUMN "id" SET DEFAULT "nextval"('"public"."classes_id_seq"'::"regclass");



ALTER TABLE ONLY "public"."drop_requests" ALTER COLUMN "id" SET DEFAULT "nextval"('"public"."drop_requests_id_seq"'::"regclass");



ALTER TABLE ONLY "public"."failed_jobs" ALTER COLUMN "id" SET DEFAULT "nextval"('"public"."failed_jobs_id_seq"'::"regclass");



ALTER TABLE ONLY "public"."jobs" ALTER COLUMN "id" SET DEFAULT "nextval"('"public"."jobs_id_seq"'::"regclass");



ALTER TABLE ONLY "public"."migrations" ALTER COLUMN "id" SET DEFAULT "nextval"('"public"."migrations_id_seq"'::"regclass");



ALTER TABLE ONLY "public"."qr_codes" ALTER COLUMN "id" SET DEFAULT "nextval"('"public"."qr_codes_id_seq"'::"regclass");



ALTER TABLE ONLY "public"."schedules" ALTER COLUMN "id" SET DEFAULT "nextval"('"public"."schedules_id_seq"'::"regclass");



ALTER TABLE ONLY "public"."system_logs" ALTER COLUMN "id" SET DEFAULT "nextval"('"public"."system_logs_id_seq"'::"regclass");



ALTER TABLE ONLY "public"."users" ALTER COLUMN "id" SET DEFAULT "nextval"('"public"."users_id_seq"'::"regclass");



ALTER TABLE ONLY "public"."attendance_records"
    ADD CONSTRAINT "attendance_records_pkey" PRIMARY KEY ("id");



ALTER TABLE ONLY "public"."cache_locks"
    ADD CONSTRAINT "cache_locks_pkey" PRIMARY KEY ("key");



ALTER TABLE ONLY "public"."cache"
    ADD CONSTRAINT "cache_pkey" PRIMARY KEY ("key");



ALTER TABLE ONLY "public"."class_professor"
    ADD CONSTRAINT "class_professor_class_id_professor_id_unique" UNIQUE ("class_id", "professor_id");



ALTER TABLE ONLY "public"."class_professor"
    ADD CONSTRAINT "class_professor_pkey" PRIMARY KEY ("id");



ALTER TABLE ONLY "public"."class_student"
    ADD CONSTRAINT "class_student_class_id_student_id_unique" UNIQUE ("class_id", "student_id");



ALTER TABLE ONLY "public"."class_student"
    ADD CONSTRAINT "class_student_pkey" PRIMARY KEY ("id");



ALTER TABLE ONLY "public"."classes"
    ADD CONSTRAINT "classes_code_unique" UNIQUE ("code");



ALTER TABLE ONLY "public"."classes"
    ADD CONSTRAINT "classes_pkey" PRIMARY KEY ("id");



ALTER TABLE ONLY "public"."drop_requests"
    ADD CONSTRAINT "drop_request_unique_pending" UNIQUE ("professor_id", "student_id", "class_id", "status");



ALTER TABLE ONLY "public"."drop_requests"
    ADD CONSTRAINT "drop_requests_pkey" PRIMARY KEY ("id");



ALTER TABLE ONLY "public"."failed_jobs"
    ADD CONSTRAINT "failed_jobs_pkey" PRIMARY KEY ("id");



ALTER TABLE ONLY "public"."failed_jobs"
    ADD CONSTRAINT "failed_jobs_uuid_unique" UNIQUE ("uuid");



ALTER TABLE ONLY "public"."job_batches"
    ADD CONSTRAINT "job_batches_pkey" PRIMARY KEY ("id");



ALTER TABLE ONLY "public"."jobs"
    ADD CONSTRAINT "jobs_pkey" PRIMARY KEY ("id");



ALTER TABLE ONLY "public"."migrations"
    ADD CONSTRAINT "migrations_pkey" PRIMARY KEY ("id");



ALTER TABLE ONLY "public"."password_reset_tokens"
    ADD CONSTRAINT "password_reset_tokens_pkey" PRIMARY KEY ("email");



ALTER TABLE ONLY "public"."qr_codes"
    ADD CONSTRAINT "qr_codes_pkey" PRIMARY KEY ("id");



ALTER TABLE ONLY "public"."qr_codes"
    ADD CONSTRAINT "qr_codes_uuid_unique" UNIQUE ("uuid");



ALTER TABLE ONLY "public"."schedules"
    ADD CONSTRAINT "schedules_pkey" PRIMARY KEY ("id");



ALTER TABLE ONLY "public"."sessions"
    ADD CONSTRAINT "sessions_pkey" PRIMARY KEY ("id");



ALTER TABLE ONLY "public"."system_logs"
    ADD CONSTRAINT "system_logs_pkey" PRIMARY KEY ("id");



ALTER TABLE ONLY "public"."users"
    ADD CONSTRAINT "users_email_unique" UNIQUE ("email");



ALTER TABLE ONLY "public"."users"
    ADD CONSTRAINT "users_pkey" PRIMARY KEY ("id");



ALTER TABLE ONLY "public"."users"
    ADD CONSTRAINT "users_student_id_unique" UNIQUE ("student_id");



ALTER TABLE ONLY "public"."users"
    ADD CONSTRAINT "users_username_unique" UNIQUE ("username");



CREATE INDEX "attendance_records_class_id_student_id_index" ON "public"."attendance_records" USING "btree" ("class_id", "student_id");



CREATE INDEX "attendance_records_recorded_at_index" ON "public"."attendance_records" USING "btree" ("recorded_at");



CREATE INDEX "attendance_records_status_index" ON "public"."attendance_records" USING "btree" ("status");



CREATE INDEX "cache_expiration_index" ON "public"."cache" USING "btree" ("expiration");



CREATE INDEX "cache_locks_expiration_index" ON "public"."cache_locks" USING "btree" ("expiration");



CREATE INDEX "class_professor_professor_id_index" ON "public"."class_professor" USING "btree" ("professor_id");



CREATE INDEX "classes_is_active_index" ON "public"."classes" USING "btree" ("is_active");



CREATE INDEX "classes_professor_id_index" ON "public"."classes" USING "btree" ("professor_id");



CREATE INDEX "jobs_queue_index" ON "public"."jobs" USING "btree" ("queue");



CREATE INDEX "qr_codes_class_id_index" ON "public"."qr_codes" USING "btree" ("class_id");



CREATE INDEX "qr_codes_expires_at_index" ON "public"."qr_codes" USING "btree" ("expires_at");



CREATE INDEX "qr_codes_is_used_index" ON "public"."qr_codes" USING "btree" ("is_used");



CREATE INDEX "qr_codes_professor_id_index" ON "public"."qr_codes" USING "btree" ("professor_id");



CREATE INDEX "qr_codes_student_id_index" ON "public"."qr_codes" USING "btree" ("student_id");



CREATE INDEX "sessions_last_activity_index" ON "public"."sessions" USING "btree" ("last_activity");



CREATE INDEX "sessions_user_id_index" ON "public"."sessions" USING "btree" ("user_id");



CREATE INDEX "system_logs_action_index" ON "public"."system_logs" USING "btree" ("action");



CREATE INDEX "system_logs_created_at_index" ON "public"."system_logs" USING "btree" ("created_at");



CREATE INDEX "system_logs_user_id_index" ON "public"."system_logs" USING "btree" ("user_id");



CREATE INDEX "users_email_index" ON "public"."users" USING "btree" ("email");



CREATE INDEX "users_role_index" ON "public"."users" USING "btree" ("role");



ALTER TABLE ONLY "public"."attendance_records"
    ADD CONSTRAINT "attendance_records_class_id_foreign" FOREIGN KEY ("class_id") REFERENCES "public"."classes"("id") ON DELETE CASCADE;



ALTER TABLE ONLY "public"."attendance_records"
    ADD CONSTRAINT "attendance_records_qr_code_id_foreign" FOREIGN KEY ("qr_code_id") REFERENCES "public"."qr_codes"("id") ON DELETE SET NULL;



ALTER TABLE ONLY "public"."attendance_records"
    ADD CONSTRAINT "attendance_records_student_id_foreign" FOREIGN KEY ("student_id") REFERENCES "public"."users"("id") ON DELETE CASCADE;



ALTER TABLE ONLY "public"."class_professor"
    ADD CONSTRAINT "class_professor_class_id_foreign" FOREIGN KEY ("class_id") REFERENCES "public"."classes"("id") ON DELETE CASCADE;



ALTER TABLE ONLY "public"."class_professor"
    ADD CONSTRAINT "class_professor_professor_id_foreign" FOREIGN KEY ("professor_id") REFERENCES "public"."users"("id") ON DELETE CASCADE;



ALTER TABLE ONLY "public"."class_student"
    ADD CONSTRAINT "class_student_class_id_foreign" FOREIGN KEY ("class_id") REFERENCES "public"."classes"("id") ON DELETE CASCADE;



ALTER TABLE ONLY "public"."class_student"
    ADD CONSTRAINT "class_student_student_id_foreign" FOREIGN KEY ("student_id") REFERENCES "public"."users"("id") ON DELETE CASCADE;



ALTER TABLE ONLY "public"."classes"
    ADD CONSTRAINT "classes_professor_id_foreign" FOREIGN KEY ("professor_id") REFERENCES "public"."users"("id") ON DELETE CASCADE;



ALTER TABLE ONLY "public"."drop_requests"
    ADD CONSTRAINT "drop_requests_admin_id_foreign" FOREIGN KEY ("admin_id") REFERENCES "public"."users"("id") ON DELETE SET NULL;



ALTER TABLE ONLY "public"."drop_requests"
    ADD CONSTRAINT "drop_requests_class_id_foreign" FOREIGN KEY ("class_id") REFERENCES "public"."classes"("id") ON DELETE CASCADE;



ALTER TABLE ONLY "public"."drop_requests"
    ADD CONSTRAINT "drop_requests_professor_id_foreign" FOREIGN KEY ("professor_id") REFERENCES "public"."users"("id") ON DELETE CASCADE;



ALTER TABLE ONLY "public"."drop_requests"
    ADD CONSTRAINT "drop_requests_student_id_foreign" FOREIGN KEY ("student_id") REFERENCES "public"."users"("id") ON DELETE CASCADE;



ALTER TABLE ONLY "public"."qr_codes"
    ADD CONSTRAINT "qr_codes_class_id_foreign" FOREIGN KEY ("class_id") REFERENCES "public"."classes"("id") ON DELETE CASCADE;



ALTER TABLE ONLY "public"."qr_codes"
    ADD CONSTRAINT "qr_codes_professor_id_foreign" FOREIGN KEY ("professor_id") REFERENCES "public"."users"("id") ON DELETE CASCADE;



ALTER TABLE ONLY "public"."qr_codes"
    ADD CONSTRAINT "qr_codes_student_id_foreign" FOREIGN KEY ("student_id") REFERENCES "public"."users"("id") ON DELETE CASCADE;



ALTER TABLE ONLY "public"."schedules"
    ADD CONSTRAINT "schedules_class_id_foreign" FOREIGN KEY ("class_id") REFERENCES "public"."classes"("id") ON DELETE CASCADE;



ALTER TABLE ONLY "public"."schedules"
    ADD CONSTRAINT "schedules_professor_id_foreign" FOREIGN KEY ("professor_id") REFERENCES "public"."users"("id") ON DELETE CASCADE;



ALTER TABLE ONLY "public"."system_logs"
    ADD CONSTRAINT "system_logs_user_id_foreign" FOREIGN KEY ("user_id") REFERENCES "public"."users"("id") ON DELETE CASCADE;





ALTER PUBLICATION "supabase_realtime" OWNER TO "postgres";


GRANT USAGE ON SCHEMA "public" TO "postgres";
GRANT USAGE ON SCHEMA "public" TO "anon";
GRANT USAGE ON SCHEMA "public" TO "authenticated";
GRANT USAGE ON SCHEMA "public" TO "service_role";





































































































































































GRANT ALL ON TABLE "public"."attendance_records" TO "anon";
GRANT ALL ON TABLE "public"."attendance_records" TO "authenticated";
GRANT ALL ON TABLE "public"."attendance_records" TO "service_role";



GRANT ALL ON SEQUENCE "public"."attendance_records_id_seq" TO "anon";
GRANT ALL ON SEQUENCE "public"."attendance_records_id_seq" TO "authenticated";
GRANT ALL ON SEQUENCE "public"."attendance_records_id_seq" TO "service_role";



GRANT ALL ON TABLE "public"."cache" TO "anon";
GRANT ALL ON TABLE "public"."cache" TO "authenticated";
GRANT ALL ON TABLE "public"."cache" TO "service_role";



GRANT ALL ON TABLE "public"."cache_locks" TO "anon";
GRANT ALL ON TABLE "public"."cache_locks" TO "authenticated";
GRANT ALL ON TABLE "public"."cache_locks" TO "service_role";



GRANT ALL ON TABLE "public"."class_professor" TO "anon";
GRANT ALL ON TABLE "public"."class_professor" TO "authenticated";
GRANT ALL ON TABLE "public"."class_professor" TO "service_role";



GRANT ALL ON SEQUENCE "public"."class_professor_id_seq" TO "anon";
GRANT ALL ON SEQUENCE "public"."class_professor_id_seq" TO "authenticated";
GRANT ALL ON SEQUENCE "public"."class_professor_id_seq" TO "service_role";



GRANT ALL ON TABLE "public"."class_student" TO "anon";
GRANT ALL ON TABLE "public"."class_student" TO "authenticated";
GRANT ALL ON TABLE "public"."class_student" TO "service_role";



GRANT ALL ON SEQUENCE "public"."class_student_id_seq" TO "anon";
GRANT ALL ON SEQUENCE "public"."class_student_id_seq" TO "authenticated";
GRANT ALL ON SEQUENCE "public"."class_student_id_seq" TO "service_role";



GRANT ALL ON TABLE "public"."classes" TO "anon";
GRANT ALL ON TABLE "public"."classes" TO "authenticated";
GRANT ALL ON TABLE "public"."classes" TO "service_role";



GRANT ALL ON SEQUENCE "public"."classes_id_seq" TO "anon";
GRANT ALL ON SEQUENCE "public"."classes_id_seq" TO "authenticated";
GRANT ALL ON SEQUENCE "public"."classes_id_seq" TO "service_role";



GRANT ALL ON TABLE "public"."drop_requests" TO "anon";
GRANT ALL ON TABLE "public"."drop_requests" TO "authenticated";
GRANT ALL ON TABLE "public"."drop_requests" TO "service_role";



GRANT ALL ON SEQUENCE "public"."drop_requests_id_seq" TO "anon";
GRANT ALL ON SEQUENCE "public"."drop_requests_id_seq" TO "authenticated";
GRANT ALL ON SEQUENCE "public"."drop_requests_id_seq" TO "service_role";



GRANT ALL ON TABLE "public"."failed_jobs" TO "anon";
GRANT ALL ON TABLE "public"."failed_jobs" TO "authenticated";
GRANT ALL ON TABLE "public"."failed_jobs" TO "service_role";



GRANT ALL ON SEQUENCE "public"."failed_jobs_id_seq" TO "anon";
GRANT ALL ON SEQUENCE "public"."failed_jobs_id_seq" TO "authenticated";
GRANT ALL ON SEQUENCE "public"."failed_jobs_id_seq" TO "service_role";



GRANT ALL ON TABLE "public"."job_batches" TO "anon";
GRANT ALL ON TABLE "public"."job_batches" TO "authenticated";
GRANT ALL ON TABLE "public"."job_batches" TO "service_role";



GRANT ALL ON TABLE "public"."jobs" TO "anon";
GRANT ALL ON TABLE "public"."jobs" TO "authenticated";
GRANT ALL ON TABLE "public"."jobs" TO "service_role";



GRANT ALL ON SEQUENCE "public"."jobs_id_seq" TO "anon";
GRANT ALL ON SEQUENCE "public"."jobs_id_seq" TO "authenticated";
GRANT ALL ON SEQUENCE "public"."jobs_id_seq" TO "service_role";



GRANT ALL ON TABLE "public"."migrations" TO "anon";
GRANT ALL ON TABLE "public"."migrations" TO "authenticated";
GRANT ALL ON TABLE "public"."migrations" TO "service_role";



GRANT ALL ON SEQUENCE "public"."migrations_id_seq" TO "anon";
GRANT ALL ON SEQUENCE "public"."migrations_id_seq" TO "authenticated";
GRANT ALL ON SEQUENCE "public"."migrations_id_seq" TO "service_role";



GRANT ALL ON TABLE "public"."password_reset_tokens" TO "anon";
GRANT ALL ON TABLE "public"."password_reset_tokens" TO "authenticated";
GRANT ALL ON TABLE "public"."password_reset_tokens" TO "service_role";



GRANT ALL ON TABLE "public"."qr_codes" TO "anon";
GRANT ALL ON TABLE "public"."qr_codes" TO "authenticated";
GRANT ALL ON TABLE "public"."qr_codes" TO "service_role";



GRANT ALL ON SEQUENCE "public"."qr_codes_id_seq" TO "anon";
GRANT ALL ON SEQUENCE "public"."qr_codes_id_seq" TO "authenticated";
GRANT ALL ON SEQUENCE "public"."qr_codes_id_seq" TO "service_role";



GRANT ALL ON TABLE "public"."schedules" TO "anon";
GRANT ALL ON TABLE "public"."schedules" TO "authenticated";
GRANT ALL ON TABLE "public"."schedules" TO "service_role";



GRANT ALL ON SEQUENCE "public"."schedules_id_seq" TO "anon";
GRANT ALL ON SEQUENCE "public"."schedules_id_seq" TO "authenticated";
GRANT ALL ON SEQUENCE "public"."schedules_id_seq" TO "service_role";



GRANT ALL ON TABLE "public"."sessions" TO "anon";
GRANT ALL ON TABLE "public"."sessions" TO "authenticated";
GRANT ALL ON TABLE "public"."sessions" TO "service_role";



GRANT ALL ON TABLE "public"."system_logs" TO "anon";
GRANT ALL ON TABLE "public"."system_logs" TO "authenticated";
GRANT ALL ON TABLE "public"."system_logs" TO "service_role";



GRANT ALL ON SEQUENCE "public"."system_logs_id_seq" TO "anon";
GRANT ALL ON SEQUENCE "public"."system_logs_id_seq" TO "authenticated";
GRANT ALL ON SEQUENCE "public"."system_logs_id_seq" TO "service_role";



GRANT ALL ON TABLE "public"."users" TO "anon";
GRANT ALL ON TABLE "public"."users" TO "authenticated";
GRANT ALL ON TABLE "public"."users" TO "service_role";



GRANT ALL ON SEQUENCE "public"."users_id_seq" TO "anon";
GRANT ALL ON SEQUENCE "public"."users_id_seq" TO "authenticated";
GRANT ALL ON SEQUENCE "public"."users_id_seq" TO "service_role";









ALTER DEFAULT PRIVILEGES FOR ROLE "postgres" IN SCHEMA "public" GRANT ALL ON SEQUENCES TO "postgres";
ALTER DEFAULT PRIVILEGES FOR ROLE "postgres" IN SCHEMA "public" GRANT ALL ON SEQUENCES TO "anon";
ALTER DEFAULT PRIVILEGES FOR ROLE "postgres" IN SCHEMA "public" GRANT ALL ON SEQUENCES TO "authenticated";
ALTER DEFAULT PRIVILEGES FOR ROLE "postgres" IN SCHEMA "public" GRANT ALL ON SEQUENCES TO "service_role";






ALTER DEFAULT PRIVILEGES FOR ROLE "postgres" IN SCHEMA "public" GRANT ALL ON FUNCTIONS TO "postgres";
ALTER DEFAULT PRIVILEGES FOR ROLE "postgres" IN SCHEMA "public" GRANT ALL ON FUNCTIONS TO "anon";
ALTER DEFAULT PRIVILEGES FOR ROLE "postgres" IN SCHEMA "public" GRANT ALL ON FUNCTIONS TO "authenticated";
ALTER DEFAULT PRIVILEGES FOR ROLE "postgres" IN SCHEMA "public" GRANT ALL ON FUNCTIONS TO "service_role";






ALTER DEFAULT PRIVILEGES FOR ROLE "postgres" IN SCHEMA "public" GRANT ALL ON TABLES TO "postgres";
ALTER DEFAULT PRIVILEGES FOR ROLE "postgres" IN SCHEMA "public" GRANT ALL ON TABLES TO "anon";
ALTER DEFAULT PRIVILEGES FOR ROLE "postgres" IN SCHEMA "public" GRANT ALL ON TABLES TO "authenticated";
ALTER DEFAULT PRIVILEGES FOR ROLE "postgres" IN SCHEMA "public" GRANT ALL ON TABLES TO "service_role";































drop extension if exists "pg_net";

alter table "public"."attendance_records" drop constraint "attendance_records_status_check";

alter table "public"."system_logs" drop constraint "system_logs_action_check";

alter table "public"."users" drop constraint "users_role_check";

alter table "public"."attendance_records" add constraint "attendance_records_status_check" CHECK (((status)::text = ANY ((ARRAY['present'::character varying, 'late'::character varying, 'absent'::character varying])::text[]))) not valid;

alter table "public"."attendance_records" validate constraint "attendance_records_status_check";

alter table "public"."system_logs" add constraint "system_logs_action_check" CHECK (((action)::text = ANY ((ARRAY['login'::character varying, 'logout'::character varying, 'scan_qr'::character varying, 'attendance_record'::character varying, 'create_class'::character varying, 'update_class'::character varying, 'delete_class'::character varying, 'add_student'::character varying, 'remove_student'::character varying, 'generate_qr'::character varying, 'update_user'::character varying, 'delete_user'::character varying, 'other'::character varying])::text[]))) not valid;

alter table "public"."system_logs" validate constraint "system_logs_action_check";

alter table "public"."users" add constraint "users_role_check" CHECK (((role)::text = ANY ((ARRAY['admin'::character varying, 'professor'::character varying, 'student'::character varying])::text[]))) not valid;

alter table "public"."users" validate constraint "users_role_check";



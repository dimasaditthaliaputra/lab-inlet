--
-- PostgreSQL database dump
--

\restrict RzhcQbRHBAFESDgJ0hdiPfi8aBGX7vftMNtEBkXRTuCJO4jTp4hISTZKZnx2aeK

-- Dumped from database version 17.6
-- Dumped by pg_dump version 17.6

-- Started on 2025-12-26 19:36:42

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 20 (class 2615 OID 2200)
-- Name: public; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA public;


--
-- TOC entry 3998 (class 0 OID 0)
-- Dependencies: 20
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON SCHEMA public IS 'standard public schema';


--
-- TOC entry 453 (class 1255 OID 28692)
-- Name: get_attendance_history(integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.get_attendance_history(p_mahasiswa_id integer DEFAULT NULL::integer) RETURNS TABLE(log_id integer, log_time timestamp without time zone, log_type character varying, status character varying, photo_path character varying, latitude character varying, longitude character varying, mahasiswa_id integer, nim character varying, full_name character varying)
    LANGUAGE plpgsql
    AS $$
BEGIN
    RETURN QUERY
    SELECT 
        al.id AS log_id,
        al.log_time,
        al.log_type,
        al.status,
        al.photo_path,
        al.latitude,
        al.longitude,
        al.mahasiswa_id,
        m.nim,
        m.full_name
    FROM 
        public.attendance_logs al
    JOIN 
        public.mahasiswa m ON al.mahasiswa_id = m.id
    WHERE 
        -- Logika Filter:
        -- Jika p_mahasiswa_id NULL, kondisi pertama TRUE (ambil semua).
        -- Jika p_mahasiswa_id terisi, kondisi kedua dicek.
        (p_mahasiswa_id IS NULL OR al.mahasiswa_id = p_mahasiswa_id)
    ORDER BY 
        al.log_time DESC; -- Urutkan dari yang terbaru
END;
$$;


--
-- TOC entry 454 (class 1255 OID 29029)
-- Name: get_daily_attendance_summary(integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.get_daily_attendance_summary(p_mahasiswa_id integer DEFAULT NULL::integer) RETURNS TABLE(mahasiswa_id integer, nim character varying, full_name character varying, attendance_date date, check_in_time time without time zone, check_in_status character varying, check_out_time time without time zone, check_out_status character varying, check_in_photo_path character varying, check_out_photo_path character varying)
    LANGUAGE plpgsql
    AS $$
BEGIN
    RETURN QUERY
    WITH DailyLogs AS (
        SELECT
            al.mahasiswa_id,
            m.nim,
            m.full_name,
            al.log_time::date AS attendance_date,
            al.log_time::time WITHOUT TIME ZONE AS log_time_only,
            al.log_type,
            al.status,
            al.photo_path
        FROM
            public.attendance_logs al
        JOIN
            public.mahasiswa m ON al.mahasiswa_id = m.id
        WHERE
            (p_mahasiswa_id IS NULL OR al.mahasiswa_id = p_mahasiswa_id)
    )
    SELECT
        dl.mahasiswa_id,
        dl.nim,
        dl.full_name,
        dl.attendance_date,
        -- Check-In
        MAX(CASE WHEN dl.log_type = 'check_in' THEN dl.log_time_only END) AS check_in_time,
        -- Dilakukan CASTING eksplisit untuk status
        MAX(CASE WHEN dl.log_type = 'check_in' THEN dl.status END)::character varying AS check_in_status,
        -- Check-Out
        MIN(CASE WHEN dl.log_type = 'check_out' THEN dl.log_time_only END) AS check_out_time,
        -- Dilakukan CASTING eksplisit untuk status
        MIN(CASE WHEN dl.log_type = 'check_out' THEN dl.status END)::character varying AS check_out_status,
        -- Dilakukan CASTING eksplisit untuk photo_path
        MAX(CASE WHEN dl.log_type = 'check_in' THEN dl.photo_path END)::character varying AS check_in_photo_path,
        MIN(CASE WHEN dl.log_type = 'check_out' THEN dl.photo_path END)::character varying AS check_out_photo_path
    FROM
        DailyLogs dl
    GROUP BY
        dl.mahasiswa_id, dl.nim, dl.full_name, dl.attendance_date
    ORDER BY
        dl.attendance_date DESC, dl.full_name ASC;
END;
$$;


--
-- TOC entry 455 (class 1255 OID 29030)
-- Name: get_daily_attendance_summary(integer, integer, integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.get_daily_attendance_summary(p_mahasiswa_id integer DEFAULT NULL::integer, p_year integer DEFAULT NULL::integer, p_month integer DEFAULT NULL::integer) RETURNS TABLE(mahasiswa_id integer, nim character varying, full_name character varying, attendance_date date, check_in_time time without time zone, check_in_status character varying, check_out_time time without time zone, check_out_status character varying, check_in_photo_path character varying, check_out_photo_path character varying)
    LANGUAGE plpgsql
    AS $$
BEGIN
    RETURN QUERY
    WITH DailyLogs AS (
        SELECT
            al.mahasiswa_id,
            m.nim,
            m.full_name,
            al.log_time::date AS attendance_date,
            al.log_time::time WITHOUT TIME ZONE AS log_time_only,
            al.log_type,
            al.status,
            al.photo_path
        FROM
            public.attendance_logs al
        JOIN
            public.mahasiswa m ON al.mahasiswa_id = m.id
    )
    SELECT
        dl.mahasiswa_id,
        dl.nim,
        dl.full_name,
        dl.attendance_date,
        MAX(CASE WHEN dl.log_type = 'check_in' THEN dl.log_time_only END) AS check_in_time,
        MAX(CASE WHEN dl.log_type = 'check_in' THEN dl.status END)::character varying AS check_in_status,
        MIN(CASE WHEN dl.log_type = 'check_out' THEN dl.log_time_only END) AS check_out_time,
        MIN(CASE WHEN dl.log_type = 'check_out' THEN dl.status END)::character varying AS check_out_status,
        MAX(CASE WHEN dl.log_type = 'check_in' THEN dl.photo_path END)::character varying AS check_in_photo_path,
        MIN(CASE WHEN dl.log_type = 'check_out' THEN dl.photo_path END)::character varying AS check_out_photo_path
    FROM
        DailyLogs dl
    WHERE
        (p_mahasiswa_id IS NULL OR dl.mahasiswa_id = p_mahasiswa_id)
        -- Tambahkan filter BULAN & TAHUN di sini
        AND (p_year IS NULL OR EXTRACT(YEAR FROM dl.attendance_date) = p_year)
        AND (p_month IS NULL OR EXTRACT(MONTH FROM dl.attendance_date) = p_month)
    GROUP BY
        dl.mahasiswa_id, dl.nim, dl.full_name, dl.attendance_date
    ORDER BY
        dl.attendance_date DESC, dl.full_name ASC;
END;
$$;


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 299 (class 1259 OID 17651)
-- Name: aboutus; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.aboutus (
    id integer NOT NULL,
    title character varying(100) NOT NULL,
    description text NOT NULL,
    vision text,
    mission text
);


--
-- TOC entry 298 (class 1259 OID 17650)
-- Name: aboutus_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.aboutus_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3999 (class 0 OID 0)
-- Dependencies: 298
-- Name: aboutus_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.aboutus_id_seq OWNED BY public.aboutus.id;


--
-- TOC entry 301 (class 1259 OID 17660)
-- Name: aboutusimages; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.aboutusimages (
    id integer NOT NULL,
    aboutus_id integer NOT NULL,
    image_name character varying(225) NOT NULL
);


--
-- TOC entry 300 (class 1259 OID 17659)
-- Name: aboutusimages_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.aboutusimages_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4000 (class 0 OID 0)
-- Dependencies: 300
-- Name: aboutusimages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.aboutusimages_id_seq OWNED BY public.aboutusimages.id;


--
-- TOC entry 292 (class 1259 OID 17588)
-- Name: activity_log; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.activity_log (
    id integer NOT NULL,
    id_user integer NOT NULL,
    action_type character varying(50) NOT NULL,
    table_name character varying(100),
    record_id integer,
    description text,
    old_data jsonb,
    new_data jsonb,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--
-- TOC entry 291 (class 1259 OID 17587)
-- Name: activity_log_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.activity_log_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4001 (class 0 OID 0)
-- Dependencies: 291
-- Name: activity_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.activity_log_id_seq OWNED BY public.activity_log.id;


--
-- TOC entry 296 (class 1259 OID 17618)
-- Name: app_menus; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.app_menus (
    id integer NOT NULL,
    menu_name character varying(100) NOT NULL,
    route character varying(100),
    icon character varying(50),
    parent_id integer,
    sort_order integer DEFAULT 0,
    is_active boolean DEFAULT true,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    permissions jsonb,
    is_for_mahasiswa boolean DEFAULT false
);


--
-- TOC entry 295 (class 1259 OID 17617)
-- Name: app_menus_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.app_menus_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4002 (class 0 OID 0)
-- Dependencies: 295
-- Name: app_menus_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.app_menus_id_seq OWNED BY public.app_menus.id;


--
-- TOC entry 311 (class 1259 OID 17715)
-- Name: attendance_logs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.attendance_logs (
    id integer NOT NULL,
    log_time timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    log_type character varying(20) NOT NULL,
    status character varying(50),
    photo_path character varying(255),
    latitude character varying(50),
    longitude character varying(50),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    mahasiswa_id integer NOT NULL
);


--
-- TOC entry 310 (class 1259 OID 17714)
-- Name: attendance_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.attendance_logs_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4003 (class 0 OID 0)
-- Dependencies: 310
-- Name: attendance_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.attendance_logs_id_seq OWNED BY public.attendance_logs.id;


--
-- TOC entry 313 (class 1259 OID 17729)
-- Name: attendance_permissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.attendance_permissions (
    id integer NOT NULL,
    mahasiswa_id integer NOT NULL,
    permission_type character varying(50) NOT NULL,
    start_date date NOT NULL,
    end_date date NOT NULL,
    reason text NOT NULL,
    attachment character varying(255),
    status character varying(50) DEFAULT 'pending'::character varying,
    approved_by integer,
    rejection_note text,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone
);


--
-- TOC entry 312 (class 1259 OID 17728)
-- Name: attendance_permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.attendance_permissions_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4004 (class 0 OID 0)
-- Dependencies: 312
-- Name: attendance_permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.attendance_permissions_id_seq OWNED BY public.attendance_permissions.id;


--
-- TOC entry 309 (class 1259 OID 17708)
-- Name: attendance_settings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.attendance_settings (
    id integer NOT NULL,
    clock_in_start_time time without time zone NOT NULL,
    clock_in_end_time time without time zone NOT NULL,
    clock_out_start_time time without time zone NOT NULL,
    updated_at timestamp without time zone
);


--
-- TOC entry 308 (class 1259 OID 17707)
-- Name: attendance_settings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.attendance_settings_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4005 (class 0 OID 0)
-- Dependencies: 308
-- Name: attendance_settings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.attendance_settings_id_seq OWNED BY public.attendance_settings.id;


--
-- TOC entry 303 (class 1259 OID 17672)
-- Name: category_project; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.category_project (
    id integer NOT NULL,
    name character varying(100) NOT NULL
);


--
-- TOC entry 280 (class 1259 OID 17522)
-- Name: facilities; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.facilities (
    id integer NOT NULL,
    name character varying(100) NOT NULL,
    description text,
    image_name character varying(225),
    qty integer DEFAULT 1,
    condition character varying(50) DEFAULT 'Good'::character varying
);


--
-- TOC entry 279 (class 1259 OID 17521)
-- Name: facilities_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.facilities_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4006 (class 0 OID 0)
-- Dependencies: 279
-- Name: facilities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.facilities_id_seq OWNED BY public.facilities.id;


--
-- TOC entry 286 (class 1259 OID 17551)
-- Name: gallery; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gallery (
    id integer NOT NULL,
    title character varying(100) NOT NULL,
    description text,
    image_name character varying(225),
    upload_date timestamp without time zone NOT NULL,
    type character varying(10),
    url character varying(225),
    CONSTRAINT gallery_type_check CHECK (((type)::text = ANY (ARRAY[('Video'::character varying)::text, ('Photo'::character varying)::text])))
);


--
-- TOC entry 285 (class 1259 OID 17550)
-- Name: gallery_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gallery_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4007 (class 0 OID 0)
-- Dependencies: 285
-- Name: gallery_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gallery_id_seq OWNED BY public.gallery.id;


--
-- TOC entry 276 (class 1259 OID 17500)
-- Name: hero_slider; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.hero_slider (
    id integer NOT NULL,
    title character varying(255),
    subtitle text,
    image_name character varying(225) NOT NULL,
    button_text character varying(50),
    button_url character varying(255),
    sort_order integer DEFAULT 0,
    is_active boolean DEFAULT true,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--
-- TOC entry 275 (class 1259 OID 17499)
-- Name: hero_slider_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.hero_slider_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4008 (class 0 OID 0)
-- Dependencies: 275
-- Name: hero_slider_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.hero_slider_id_seq OWNED BY public.hero_slider.id;


--
-- TOC entry 302 (class 1259 OID 17671)
-- Name: kategori_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.kategori_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4009 (class 0 OID 0)
-- Dependencies: 302
-- Name: kategori_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.kategori_id_seq OWNED BY public.category_project.id;


--
-- TOC entry 315 (class 1259 OID 17750)
-- Name: mahasiswa; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.mahasiswa (
    id integer NOT NULL,
    nim character varying(20) NOT NULL,
    full_name character varying(150) NOT NULL,
    university character varying(100) DEFAULT 'Politeknik Negeri Malang'::character varying,
    study_program character varying(100),
    entry_year integer,
    current_semester integer,
    phone_number character varying(20),
    address text,
    status character varying(50) DEFAULT 'active'::character varying,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone
);


--
-- TOC entry 314 (class 1259 OID 17749)
-- Name: mahasiswa_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.mahasiswa_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4010 (class 0 OID 0)
-- Dependencies: 314
-- Name: mahasiswa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.mahasiswa_id_seq OWNED BY public.mahasiswa.id;


--
-- TOC entry 322 (class 1259 OID 19158)
-- Name: mv_dashboard_activity_trend; Type: MATERIALIZED VIEW; Schema: public; Owner: -
--

CREATE MATERIALIZED VIEW public.mv_dashboard_activity_trend AS
 SELECT date(created_at) AS log_date,
    to_char(created_at, 'Dy'::text) AS day_name,
    to_char(created_at, 'DD Mon'::text) AS date_label,
    count(id) AS total_activity
   FROM public.activity_log
  GROUP BY (date(created_at)), (to_char(created_at, 'Dy'::text)), (to_char(created_at, 'DD Mon'::text))
  ORDER BY (date(created_at))
  WITH NO DATA;


--
-- TOC entry 320 (class 1259 OID 19052)
-- Name: project_category_pivot; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.project_category_pivot (
    project_id integer NOT NULL,
    category_id integer NOT NULL
);


--
-- TOC entry 305 (class 1259 OID 17681)
-- Name: project_lab; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.project_lab (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    video_url text,
    image_url text[],
    status character varying(50) DEFAULT 'draft'::character varying,
    created_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT project_lab_status_check CHECK (((status)::text = ANY ((ARRAY['draft'::character varying, 'in_progress'::character varying, 'completed'::character varying, 'archived'::character varying])::text[])))
);


--
-- TOC entry 321 (class 1259 OID 19072)
-- Name: mv_dashboard_project_stats; Type: MATERIALIZED VIEW; Schema: public; Owner: -
--

CREATE MATERIALIZED VIEW public.mv_dashboard_project_stats AS
 SELECT c.id AS category_id,
    c.name AS category_name,
    count(pcp.project_id) AS total_projects
   FROM ((public.category_project c
     LEFT JOIN public.project_category_pivot pcp ON ((c.id = pcp.category_id)))
     LEFT JOIN public.project_lab p ON ((pcp.project_id = p.id)))
  GROUP BY c.id, c.name
  ORDER BY (count(pcp.project_id)) DESC
  WITH NO DATA;


--
-- TOC entry 327 (class 1259 OID 26261)
-- Name: mv_dashboard_student_year; Type: MATERIALIZED VIEW; Schema: public; Owner: -
--

CREATE MATERIALIZED VIEW public.mv_dashboard_student_year AS
 SELECT EXTRACT(year FROM created_at) AS creation_year,
    count(id) AS total_student
   FROM public.mahasiswa
  WHERE (created_at IS NOT NULL)
  GROUP BY (EXTRACT(year FROM created_at))
  ORDER BY (EXTRACT(year FROM created_at))
  WITH NO DATA;


--
-- TOC entry 288 (class 1259 OID 17561)
-- Name: roles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.roles (
    id integer NOT NULL,
    role_name character varying(100) NOT NULL
);


--
-- TOC entry 290 (class 1259 OID 17570)
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id integer NOT NULL,
    username character varying(100) NOT NULL,
    password character varying(100) NOT NULL,
    email character varying(100),
    full_name character varying(100),
    id_roles integer NOT NULL,
    remember_token character varying(255),
    remember_token_expires_at timestamp without time zone,
    mahasiswa_id integer
);


--
-- TOC entry 319 (class 1259 OID 18952)
-- Name: mv_dashboard_user_distribution; Type: MATERIALIZED VIEW; Schema: public; Owner: -
--

CREATE MATERIALIZED VIEW public.mv_dashboard_user_distribution AS
 SELECT r.role_name,
    count(u.id) AS total_user
   FROM (public.users u
     JOIN public.roles r ON ((u.id_roles = r.id)))
  GROUP BY r.role_name
  ORDER BY (count(u.id)) DESC
  WITH NO DATA;


--
-- TOC entry 294 (class 1259 OID 17603)
-- Name: news; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.news (
    id integer NOT NULL,
    title character varying(100) NOT NULL,
    content text NOT NULL,
    image_name character varying(100),
    publish_date timestamp without time zone,
    is_publish boolean DEFAULT false,
    created_by integer
);


--
-- TOC entry 293 (class 1259 OID 17602)
-- Name: news_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.news_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4011 (class 0 OID 0)
-- Dependencies: 293
-- Name: news_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.news_id_seq OWNED BY public.news.id;


--
-- TOC entry 282 (class 1259 OID 17533)
-- Name: partner; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.partner (
    id integer NOT NULL,
    partner_name character varying(150) NOT NULL,
    partner_logo character varying(225),
    url character varying(225)
);


--
-- TOC entry 281 (class 1259 OID 17532)
-- Name: partner_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.partner_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4012 (class 0 OID 0)
-- Dependencies: 281
-- Name: partner_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.partner_id_seq OWNED BY public.partner.id;


--
-- TOC entry 284 (class 1259 OID 17542)
-- Name: product; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.product (
    id integer NOT NULL,
    product_name character varying(100) NOT NULL,
    description text,
    image_name character varying(225),
    release_date timestamp without time zone NOT NULL,
    feature jsonb,
    specification jsonb,
    product_link character varying(255)
);


--
-- TOC entry 283 (class 1259 OID 17541)
-- Name: product_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.product_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4013 (class 0 OID 0)
-- Dependencies: 283
-- Name: product_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.product_id_seq OWNED BY public.product.id;


--
-- TOC entry 304 (class 1259 OID 17680)
-- Name: project_lab_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.project_lab_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4014 (class 0 OID 0)
-- Dependencies: 304
-- Name: project_lab_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.project_lab_id_seq OWNED BY public.project_lab.id;


--
-- TOC entry 278 (class 1259 OID 17512)
-- Name: research_focus; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.research_focus (
    id integer NOT NULL,
    title character varying(100) NOT NULL,
    description text,
    icon_name character varying(100),
    image_cover character varying(255),
    sort_order integer DEFAULT 0
);


--
-- TOC entry 277 (class 1259 OID 17511)
-- Name: research_focus_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.research_focus_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4015 (class 0 OID 0)
-- Dependencies: 277
-- Name: research_focus_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.research_focus_id_seq OWNED BY public.research_focus.id;


--
-- TOC entry 297 (class 1259 OID 17632)
-- Name: role_menus; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.role_menus (
    role_id integer NOT NULL,
    menu_id integer NOT NULL,
    permissions jsonb DEFAULT '{"read": true}'::jsonb
);


--
-- TOC entry 287 (class 1259 OID 17560)
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.roles_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4016 (class 0 OID 0)
-- Dependencies: 287
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- TOC entry 274 (class 1259 OID 17490)
-- Name: site_settings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.site_settings (
    id integer NOT NULL,
    site_name character varying(100) NOT NULL,
    email character varying(100),
    phone character varying(20),
    address text,
    map_embed_url text,
    logo_path character varying(255),
    favicon_path character varying(255),
    social_links jsonb,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone
);


--
-- TOC entry 273 (class 1259 OID 17489)
-- Name: site_settings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.site_settings_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4017 (class 0 OID 0)
-- Dependencies: 273
-- Name: site_settings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.site_settings_id_seq OWNED BY public.site_settings.id;


--
-- TOC entry 324 (class 1259 OID 19965)
-- Name: social_links; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.social_links (
    id integer NOT NULL,
    name character varying(100),
    icon_name character varying(100),
    image_cover character varying(225)
);


--
-- TOC entry 323 (class 1259 OID 19964)
-- Name: social_links_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.social_links_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4018 (class 0 OID 0)
-- Dependencies: 323
-- Name: social_links_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.social_links_id_seq OWNED BY public.social_links.id;


--
-- TOC entry 325 (class 1259 OID 19971)
-- Name: social_team; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.social_team (
    id_social_media integer NOT NULL,
    id_team integer NOT NULL,
    link_sosmed character varying(225)
);


--
-- TOC entry 307 (class 1259 OID 17698)
-- Name: team_member; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.team_member (
    id integer NOT NULL,
    full_name character varying(150) NOT NULL,
    nip character varying(30),
    nidn character varying(30),
    lab_position character varying(100),
    academic_position character varying(100),
    study_program character varying(100),
    email character varying(100),
    office_address text,
    image_name character varying(225),
    expertise jsonb,
    education jsonb,
    certifications jsonb,
    courses_taught jsonb,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone
);


--
-- TOC entry 306 (class 1259 OID 17697)
-- Name: team_member_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.team_member_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4019 (class 0 OID 0)
-- Dependencies: 306
-- Name: team_member_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.team_member_id_seq OWNED BY public.team_member.id;


--
-- TOC entry 289 (class 1259 OID 17569)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4020 (class 0 OID 0)
-- Dependencies: 289
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 317 (class 1259 OID 18939)
-- Name: v_dashboard_attendance_today; Type: VIEW; Schema: public; Owner: -
--

CREATE VIEW public.v_dashboard_attendance_today AS
 WITH settings AS (
         SELECT attendance_settings.clock_in_end_time
           FROM public.attendance_settings
         LIMIT 1
        ), today_attendance AS (
         SELECT DISTINCT ON (al.mahasiswa_id) al.mahasiswa_id,
            al.log_time,
            ((al.log_time)::time without time zone > ( SELECT settings.clock_in_end_time
                   FROM settings)) AS is_late
           FROM public.attendance_logs al
          WHERE (((al.log_time)::date = CURRENT_DATE) AND ((al.log_type)::text = 'check_in'::text))
          ORDER BY al.mahasiswa_id, al.log_time
        ), permit_count AS (
         SELECT count(ap.id) AS total_permit
           FROM public.attendance_permissions ap
          WHERE (((ap.status)::text = 'approved'::text) AND (CURRENT_DATE >= ap.start_date) AND (CURRENT_DATE <= ap.end_date))
        )
 SELECT ( SELECT count(ta.mahasiswa_id) AS count
           FROM today_attendance ta) AS total_present,
    ( SELECT count(ta.mahasiswa_id) AS count
           FROM today_attendance ta
          WHERE (ta.is_late = true)) AS total_late,
    ( SELECT pc.total_permit
           FROM permit_count pc) AS total_permit;


--
-- TOC entry 318 (class 1259 OID 18947)
-- Name: v_dashboard_latest_logs; Type: VIEW; Schema: public; Owner: -
--

CREATE VIEW public.v_dashboard_latest_logs AS
 SELECT al.id,
    COALESCE(u.full_name, u.username, 'Unknown User'::character varying) AS user_name,
    al.action_type,
    al.table_name,
    al.description,
    al.created_at
   FROM (public.activity_log al
     LEFT JOIN public.users u ON ((al.id_user = u.id)))
  ORDER BY al.created_at DESC
 LIMIT 10;


--
-- TOC entry 316 (class 1259 OID 18934)
-- Name: v_dashboard_summary_cards; Type: VIEW; Schema: public; Owner: -
--

CREATE VIEW public.v_dashboard_summary_cards AS
 SELECT ( SELECT count(*) AS count
           FROM public.users) AS total_users,
    ( SELECT count(*) AS count
           FROM public.mahasiswa
          WHERE ((mahasiswa.status)::text = 'Aktif'::text)) AS active_students,
    ( SELECT count(*) AS count
           FROM public.project_lab) AS total_projects,
    ( SELECT count(*) AS count
           FROM public.partner) AS total_partners,
    ( SELECT count(*) AS count
           FROM public.news
          WHERE (news.is_publish = true)) AS published_news;


--
-- TOC entry 3689 (class 2604 OID 17654)
-- Name: aboutus id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.aboutus ALTER COLUMN id SET DEFAULT nextval('public.aboutus_id_seq'::regclass);


--
-- TOC entry 3690 (class 2604 OID 17663)
-- Name: aboutusimages id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.aboutusimages ALTER COLUMN id SET DEFAULT nextval('public.aboutusimages_id_seq'::regclass);


--
-- TOC entry 3679 (class 2604 OID 17591)
-- Name: activity_log id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.activity_log ALTER COLUMN id SET DEFAULT nextval('public.activity_log_id_seq'::regclass);


--
-- TOC entry 3683 (class 2604 OID 17621)
-- Name: app_menus id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_menus ALTER COLUMN id SET DEFAULT nextval('public.app_menus_id_seq'::regclass);


--
-- TOC entry 3698 (class 2604 OID 17718)
-- Name: attendance_logs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.attendance_logs ALTER COLUMN id SET DEFAULT nextval('public.attendance_logs_id_seq'::regclass);


--
-- TOC entry 3701 (class 2604 OID 17732)
-- Name: attendance_permissions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.attendance_permissions ALTER COLUMN id SET DEFAULT nextval('public.attendance_permissions_id_seq'::regclass);


--
-- TOC entry 3697 (class 2604 OID 17711)
-- Name: attendance_settings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.attendance_settings ALTER COLUMN id SET DEFAULT nextval('public.attendance_settings_id_seq'::regclass);


--
-- TOC entry 3691 (class 2604 OID 17675)
-- Name: category_project id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.category_project ALTER COLUMN id SET DEFAULT nextval('public.kategori_id_seq'::regclass);


--
-- TOC entry 3671 (class 2604 OID 17525)
-- Name: facilities id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.facilities ALTER COLUMN id SET DEFAULT nextval('public.facilities_id_seq'::regclass);


--
-- TOC entry 3676 (class 2604 OID 17554)
-- Name: gallery id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gallery ALTER COLUMN id SET DEFAULT nextval('public.gallery_id_seq'::regclass);


--
-- TOC entry 3665 (class 2604 OID 17503)
-- Name: hero_slider id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hero_slider ALTER COLUMN id SET DEFAULT nextval('public.hero_slider_id_seq'::regclass);


--
-- TOC entry 3704 (class 2604 OID 17753)
-- Name: mahasiswa id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.mahasiswa ALTER COLUMN id SET DEFAULT nextval('public.mahasiswa_id_seq'::regclass);


--
-- TOC entry 3681 (class 2604 OID 17606)
-- Name: news id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.news ALTER COLUMN id SET DEFAULT nextval('public.news_id_seq'::regclass);


--
-- TOC entry 3674 (class 2604 OID 17536)
-- Name: partner id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.partner ALTER COLUMN id SET DEFAULT nextval('public.partner_id_seq'::regclass);


--
-- TOC entry 3675 (class 2604 OID 17545)
-- Name: product id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product ALTER COLUMN id SET DEFAULT nextval('public.product_id_seq'::regclass);


--
-- TOC entry 3692 (class 2604 OID 17684)
-- Name: project_lab id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.project_lab ALTER COLUMN id SET DEFAULT nextval('public.project_lab_id_seq'::regclass);


--
-- TOC entry 3669 (class 2604 OID 17515)
-- Name: research_focus id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.research_focus ALTER COLUMN id SET DEFAULT nextval('public.research_focus_id_seq'::regclass);


--
-- TOC entry 3677 (class 2604 OID 17564)
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- TOC entry 3663 (class 2604 OID 17493)
-- Name: site_settings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.site_settings ALTER COLUMN id SET DEFAULT nextval('public.site_settings_id_seq'::regclass);


--
-- TOC entry 3708 (class 2604 OID 19968)
-- Name: social_links id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.social_links ALTER COLUMN id SET DEFAULT nextval('public.social_links_id_seq'::regclass);


--
-- TOC entry 3695 (class 2604 OID 17701)
-- Name: team_member id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.team_member ALTER COLUMN id SET DEFAULT nextval('public.team_member_id_seq'::regclass);


--
-- TOC entry 3678 (class 2604 OID 17573)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 3968 (class 0 OID 17651)
-- Dependencies: 299
-- Data for Name: aboutus; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.aboutus VALUES (1, 'Information and Learning Engineering Technology', 'Laboratorium Sistem Informasi merupakan salah satu laboratorium yang berperan penting dalam mendukung kegiatan praktikum, penelitian, dan pengembangan sistem informasi terapan.', 'Menjadi laboratorium unggulan yang menghasilkan solusi Sistem Informasi terapan untuk kebutuhan pendidikan, bisnis, dan industri.', '1. Mendukung praktikum & pengembangan aplikasi SI (web, mobile, enterprise).
2. Melakukan riset terapan di basis data, proses bisnis, analitik data, dan integrasi SI.
3. Berkolaborasi dengan industri/lembaga untuk proyek SI dan layanan konsultasi.	
4. Selaras dengan mandat pendidikan terapan Polinema & kurikulum prodi TI.');


--
-- TOC entry 3970 (class 0 OID 17660)
-- Dependencies: 301
-- Data for Name: aboutusimages; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.aboutusimages VALUES (1, 1, 'fccfdd7e09a44aee505fd2efcb65335b.jpg');
INSERT INTO public.aboutusimages VALUES (2, 1, 'fdc6c160580879c3a01d2b48bde77c2c.jpg');
INSERT INTO public.aboutusimages VALUES (3, 1, 'c84015d755c6ccef4f4386b3883026dc.jpg');


--
-- TOC entry 3961 (class 0 OID 17588)
-- Dependencies: 292
-- Data for Name: activity_log; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.activity_log VALUES (3, 1, 'Login', NULL, NULL, 'User superadmin successfully logged in', NULL, NULL, '2025-12-17 13:48:20.249077');
INSERT INTO public.activity_log VALUES (8, 1, 'Create', 'users', 6, 'User Sabbaha successfully created', NULL, '{"email": "sabbaha@gmail.com", "id_roles": "3", "password": "$2y$10$ZnwssNynOtlUPr1BjkwJCOhaEr.P1TP6fog.Xdu2wXxCOYKJd3k.K", "username": "Sabbaha", "full_name": "Sabbaha Naufal Erwanda", "mahasiswa_id": "1"}', '2025-12-17 13:52:37.13548');
INSERT INTO public.activity_log VALUES (9, 1, 'Update', 'users', 6, 'User sabbaha successfully updated', '{"id": 6, "email": "sabbaha@gmail.com", "id_roles": 3, "password": "$2y$10$ZnwssNynOtlUPr1BjkwJCOhaEr.P1TP6fog.Xdu2wXxCOYKJd3k.K", "username": "Sabbaha", "full_name": "Sabbaha Naufal Erwanda", "mahasiswa_id": 1, "remember_token": null, "remember_token_expires_at": null}', 'true', '2025-12-17 13:52:57.085942');
INSERT INTO public.activity_log VALUES (10, 13, 'Login', NULL, NULL, 'User sultan successfully logged in', NULL, NULL, '2025-12-17 13:55:44.5092');
INSERT INTO public.activity_log VALUES (12, 4, 'Login', NULL, NULL, 'User Nita successfully logged in', NULL, NULL, '2025-12-17 13:57:49.982951');
INSERT INTO public.activity_log VALUES (13, 4, 'Login', NULL, NULL, 'User Nita successfully logged in', NULL, NULL, '2025-12-17 13:57:53.58664');
INSERT INTO public.activity_log VALUES (14, 8, 'Log Out', NULL, NULL, 'User Diyah logout dari sistem', NULL, NULL, '2025-12-17 14:01:38.31541');
INSERT INTO public.activity_log VALUES (15, 3, 'Login', NULL, NULL, 'User Diyah successfully logged in', NULL, NULL, '2025-12-17 14:01:46.202354');
INSERT INTO public.activity_log VALUES (16, 14, 'Log Out', NULL, NULL, 'User faras41 logout dari sistem', NULL, NULL, '2025-12-17 14:08:05.67819');
INSERT INTO public.activity_log VALUES (17, 13, 'Login', NULL, NULL, 'User sultan successfully logged in', NULL, NULL, '2025-12-17 14:08:14.772925');
INSERT INTO public.activity_log VALUES (18, 13, 'Log Out', NULL, NULL, 'User sultan logout dari sistem', NULL, NULL, '2025-12-17 14:09:15.0948');
INSERT INTO public.activity_log VALUES (19, 16, 'Login', NULL, NULL, 'User alif successfully logged in', NULL, NULL, '2025-12-17 14:09:40.269443');
INSERT INTO public.activity_log VALUES (20, 16, 'Log Out', NULL, NULL, 'User alif logout dari sistem', NULL, NULL, '2025-12-17 14:11:49.186862');
INSERT INTO public.activity_log VALUES (21, 8, 'Login', NULL, NULL, 'User amalia successfully logged in', NULL, NULL, '2025-12-17 14:12:05.978616');
INSERT INTO public.activity_log VALUES (22, 1, 'Log Out', NULL, NULL, 'User superadmin logout dari sistem', NULL, NULL, '2025-12-17 14:13:16.582179');
INSERT INTO public.activity_log VALUES (23, 1, 'Login', NULL, NULL, 'User superadmin successfully logged in', NULL, NULL, '2025-12-17 14:17:09.509512');
INSERT INTO public.activity_log VALUES (24, 1, 'Create', 'users', 28, 'User operator successfully created', NULL, '{"email": "operator@inlet.ac.id", "id_roles": "2", "password": "$2y$10$gCKzwT2SPLtTG/NwEz9iyebpgT0lK4SeJAJgHAGShlh5EvrlEzvEe", "username": "operator", "full_name": "Operator Lab", "mahasiswa_id": null}', '2025-12-17 14:17:50.291738');
INSERT INTO public.activity_log VALUES (25, 13, 'Log Out', NULL, NULL, 'User sultan logout dari sistem', NULL, NULL, '2025-12-17 14:19:50.99531');
INSERT INTO public.activity_log VALUES (26, 8, 'Login', NULL, NULL, 'User amalia successfully logged in', NULL, NULL, '2025-12-17 14:20:05.190303');
INSERT INTO public.activity_log VALUES (27, 1, 'Log Out', NULL, NULL, 'User superadmin logout dari sistem', NULL, NULL, '2025-12-17 14:20:41.372781');
INSERT INTO public.activity_log VALUES (28, 8, 'Login', NULL, NULL, 'User amalia successfully logged in', NULL, NULL, '2025-12-17 14:20:49.712031');
INSERT INTO public.activity_log VALUES (29, 8, 'Log Out', NULL, NULL, 'User amalia logout dari sistem', NULL, NULL, '2025-12-17 14:21:06.342182');
INSERT INTO public.activity_log VALUES (30, 2, 'Login', NULL, NULL, 'User Dimas successfully logged in', NULL, NULL, '2025-12-17 14:21:20.645661');
INSERT INTO public.activity_log VALUES (31, 8, 'Log Out', NULL, NULL, 'User amalia logout dari sistem', NULL, NULL, '2025-12-17 14:25:01.985436');
INSERT INTO public.activity_log VALUES (32, 5, 'Login', NULL, NULL, 'User Farras successfully logged in', NULL, NULL, '2025-12-17 14:25:19.525101');
INSERT INTO public.activity_log VALUES (33, 2, 'Create', 'news', 1, 'News ''Tes'' successfully created as DRAFT', NULL, '{"title": "Tes", "content": "<p><b>Tes</b></p>", "created_by": 2, "image_name": "9a7376e8210a3ce0d9c5b1473e7922fd.png", "is_publish": "true", "publish_date": "2025-12-17 14:36:05"}', '2025-12-17 14:36:11.415036');
INSERT INTO public.activity_log VALUES (34, 2, 'Update', 'partner', 10, 'Partner ''DFKI'' successfully updated', '{"id": 10, "url": null, "partner_logo": "3c2480e2ca49f44f532909aa3294bdec.png", "partner_name": "DFKI"}', '{"url": "www.google.com", "partner_name": "DFKI"}', '2025-12-17 14:54:57.967929');
INSERT INTO public.activity_log VALUES (35, 2, 'Update', 'partner', 8, 'Partner ''Learning Engineering Lab, Hiroshima'' successfully updated', '{"id": 8, "url": null, "partner_logo": "cd83cee188183f66610ce350fea16ff9.jpg", "partner_name": "Learning Engineering Lab, Hiroshima University lead by Prof. Tsukasa Hirashima"}', '{"url": null, "partner_name": "Learning Engineering Lab, Hiroshima"}', '2025-12-17 14:55:33.319808');
INSERT INTO public.activity_log VALUES (36, 4, 'Login', NULL, NULL, 'User Nita successfully logged in', NULL, NULL, '2025-12-17 15:24:28.464914');
INSERT INTO public.activity_log VALUES (37, 2, 'Login', NULL, NULL, 'User Dimas successfully logged in', NULL, NULL, '2025-12-19 09:29:12.052717');
INSERT INTO public.activity_log VALUES (38, 2, 'Log Out', NULL, NULL, 'User Dimas logout dari sistem', NULL, NULL, '2025-12-19 09:29:53.173898');
INSERT INTO public.activity_log VALUES (39, 8, 'Login', NULL, NULL, 'User amalia successfully logged in', NULL, NULL, '2025-12-19 09:30:08.023293');
INSERT INTO public.activity_log VALUES (40, 2, 'Login', NULL, NULL, 'User Dimas successfully logged in', NULL, NULL, '2025-12-19 09:31:17.922927');
INSERT INTO public.activity_log VALUES (41, 3, 'Login', NULL, NULL, 'User Diyah successfully logged in', NULL, NULL, '2025-12-19 20:25:21.695292');
INSERT INTO public.activity_log VALUES (42, 3, 'Create', 'users', 33, 'User Diy successfully created', NULL, '{"email": "diyahramadhani706@gmail.com", "id_roles": "3", "password": "$2y$10$eupyeEXB3lLWNREZZeZf..R8sJZ2fTJaq0gsemzBC6ZTNvNtgKNIy", "username": "Diy", "full_name": "Diy", "mahasiswa_id": null}', '2025-12-19 20:46:22.964395');
INSERT INTO public.activity_log VALUES (43, 3, 'Delete', 'users', 33, 'User Diy successfully deleted', '{"id": 33, "email": "diyahramadhani706@gmail.com", "id_roles": 3, "password": "$2y$10$eupyeEXB3lLWNREZZeZf..R8sJZ2fTJaq0gsemzBC6ZTNvNtgKNIy", "username": "Diy", "full_name": "Diy", "mahasiswa_id": null, "remember_token": null, "remember_token_expires_at": null}', NULL, '2025-12-19 20:46:37.705739');
INSERT INTO public.activity_log VALUES (44, 3, 'Log Out', NULL, NULL, 'User Diyah logout dari sistem', NULL, NULL, '2025-12-19 21:12:25.966181');
INSERT INTO public.activity_log VALUES (45, 3, 'Login', NULL, NULL, 'User Diyah successfully logged in', NULL, NULL, '2025-12-19 21:12:57.626796');
INSERT INTO public.activity_log VALUES (46, 3, 'Create', 'users', 34, 'User diyah successfully created', NULL, '{"email": "244107060152@polinema.ac.id", "id_roles": "3", "password": "$2y$10$pm2DkfdVt/f0GPAoxPByUedbDcnvfsivQlG95JRDVLEkLVvTTZCnS", "username": "diyah", "full_name": "diyah", "mahasiswa_id": null}', '2025-12-19 21:14:16.863977');
INSERT INTO public.activity_log VALUES (47, 3, 'Log Out', NULL, NULL, 'User Diyah logout dari sistem', NULL, NULL, '2025-12-19 21:14:28.239095');
INSERT INTO public.activity_log VALUES (48, 34, 'Login', NULL, NULL, 'User diyah successfully logged in', NULL, NULL, '2025-12-19 21:14:35.808428');
INSERT INTO public.activity_log VALUES (49, 34, 'Log Out', NULL, NULL, 'User diyah logout dari sistem', NULL, NULL, '2025-12-19 21:15:33.690227');
INSERT INTO public.activity_log VALUES (50, 3, 'Login', NULL, NULL, 'User Diyah successfully logged in', NULL, NULL, '2025-12-19 21:15:45.144049');
INSERT INTO public.activity_log VALUES (51, 3, 'Login', NULL, NULL, 'User Diyah successfully logged in', NULL, NULL, '2025-12-23 07:55:18.246342');
INSERT INTO public.activity_log VALUES (52, 4, 'Login', NULL, NULL, 'User Nita successfully logged in', NULL, NULL, '2025-12-23 13:36:06.9762');
INSERT INTO public.activity_log VALUES (53, 4, 'Create', 'mahasiswa', 21, 'Data Mahasiswa (nita - 244134456789) berhasil ditambahkan', NULL, '{"nim": "244134456789", "status": "Aktif", "address": "malang", "full_name": "nita", "entry_year": "2024", "university": "polinema", "phone_number": "081234567890", "study_program": "sib", "current_semester": "3"}', '2025-12-23 13:58:18.586443');
INSERT INTO public.activity_log VALUES (54, 4, 'Delete', 'mahasiswa', 21, 'Data Mahasiswa (nita - 244134456789) berhasil dihapus', '{"id": 21, "nim": "244134456789", "status": "Aktif", "address": "malang", "full_name": "nita", "created_at": "2025-12-23 13:58:18.163418", "entry_year": 2024, "university": "polinema", "updated_at": null, "phone_number": "081234567890", "study_program": "sib", "current_semester": 3}', NULL, '2025-12-23 13:59:25.345351');
INSERT INTO public.activity_log VALUES (55, 4, 'Create', 'mahasiswa', 22, 'Data Mahasiswa (primayunita - 244134456789) berhasil ditambahkan', NULL, '{"nim": "244134456789", "status": "Aktif", "address": "malang", "full_name": "primayunita", "entry_year": "2024", "university": "polinema", "phone_number": "081234567890", "study_program": "sib", "current_semester": "3"}', '2025-12-23 13:59:41.218906');
INSERT INTO public.activity_log VALUES (56, 4, 'Create', 'users', 39, 'User primayunita successfully created', NULL, '{"email": "primayunita@inlet.ac.id", "id_roles": "3", "password": "$2y$10$F5zq9NN5n5Oq7KKw/qLi6.E1pgVW9R6iqoJvXcXk.0cT3Dja7wKfy", "username": "primayunita", "full_name": "primayunita", "mahasiswa_id": "22"}', '2025-12-23 14:00:11.68113');
INSERT INTO public.activity_log VALUES (57, 4, 'Log Out', NULL, NULL, 'User Nita logout dari sistem', NULL, NULL, '2025-12-23 14:00:23.901249');
INSERT INTO public.activity_log VALUES (60, 4, 'Login', NULL, NULL, 'User Nita successfully logged in', NULL, NULL, '2025-12-23 14:06:06.930414');
INSERT INTO public.activity_log VALUES (61, 4, 'Delete', 'mahasiswa', 22, 'Data Mahasiswa (primayunita - 244134456789) berhasil dihapus', '{"id": 22, "nim": "244134456789", "status": "Aktif", "address": "malang", "full_name": "primayunita", "created_at": "2025-12-23 13:59:40.811926", "entry_year": 2024, "university": "polinema", "updated_at": null, "phone_number": "081234567890", "study_program": "sib", "current_semester": 3}', NULL, '2025-12-23 14:07:09.398912');
INSERT INTO public.activity_log VALUES (62, 4, 'Delete', 'users', 39, 'User primayunita successfully deleted', '{"id": 39, "email": "primayunita@inlet.ac.id", "id_roles": 3, "password": "$2y$10$F5zq9NN5n5Oq7KKw/qLi6.E1pgVW9R6iqoJvXcXk.0cT3Dja7wKfy", "username": "primayunita", "full_name": "primayunita", "mahasiswa_id": null, "remember_token": null, "remember_token_expires_at": null}', NULL, '2025-12-23 14:07:19.670871');
INSERT INTO public.activity_log VALUES (63, 4, 'Create', 'mahasiswa', 23, 'Data Mahasiswa (primayunita - 244134456789) berhasil ditambahkan', NULL, '{"nim": "244134456789", "status": "Aktif", "address": "Jl Soekarno Hatta No 9, Kota  Malang", "full_name": "primayunita", "entry_year": "2024", "university": "polinema", "phone_number": "081234567890", "study_program": "sib", "current_semester": "3"}', '2025-12-23 14:08:34.08578');
INSERT INTO public.activity_log VALUES (64, 4, 'Create', 'users', 40, 'User primayunita successfully created', NULL, '{"email": "primayunita@inlet.ac.id", "id_roles": "3", "password": "$2y$10$eTEAZye27ehifb9xqRVJqekd87aggdiAgVwpC5dXyrUaeN2VI8K4S", "username": "primayunita", "full_name": "primayunita", "mahasiswa_id": "23"}', '2025-12-23 14:08:53.197853');
INSERT INTO public.activity_log VALUES (65, 4, 'Log Out', NULL, NULL, 'User Nita logout dari sistem', NULL, NULL, '2025-12-23 14:09:04.318947');
INSERT INTO public.activity_log VALUES (66, 40, 'Login', NULL, NULL, 'User primayunita successfully logged in', NULL, NULL, '2025-12-23 14:09:14.146359');
INSERT INTO public.activity_log VALUES (67, 40, 'Log Out', NULL, NULL, 'User primayunita logout dari sistem', NULL, NULL, '2025-12-23 15:24:07.914995');
INSERT INTO public.activity_log VALUES (68, 2, 'Login', NULL, NULL, 'User Dimas successfully logged in', NULL, NULL, '2025-12-23 16:30:19.023437');
INSERT INTO public.activity_log VALUES (69, 2, 'Delete', 'news', 1, 'News Tes successfully deleted', '{"id": 1, "title": "Tes", "content": "<p><b>Tes</b></p>", "created_by": 2, "image_name": "9a7376e8210a3ce0d9c5b1473e7922fd.png", "is_publish": true, "publish_date": "2025-12-17 14:36:05"}', NULL, '2025-12-23 16:30:34.069275');
INSERT INTO public.activity_log VALUES (70, 3, 'Login', NULL, NULL, 'User Diyah successfully logged in', NULL, NULL, '2025-12-23 17:30:19.529058');
INSERT INTO public.activity_log VALUES (71, 3, 'Login', NULL, NULL, 'User Diyah successfully logged in', NULL, NULL, '2025-12-23 21:24:16.015515');
INSERT INTO public.activity_log VALUES (72, 3, 'Login', NULL, NULL, 'User Diyah successfully logged in', NULL, NULL, '2025-12-24 22:12:34.881972');
INSERT INTO public.activity_log VALUES (73, 3, 'Log Out', NULL, NULL, 'User Diyah logout dari sistem', NULL, NULL, '2025-12-24 22:40:10.957982');
INSERT INTO public.activity_log VALUES (74, 34, 'Login', NULL, NULL, 'User diyah successfully logged in', NULL, NULL, '2025-12-24 22:40:19.120688');
INSERT INTO public.activity_log VALUES (75, 5, 'Login', NULL, NULL, 'User Farras successfully logged in', NULL, NULL, '2025-12-25 18:17:08.228723');
INSERT INTO public.activity_log VALUES (76, 5, 'Log Out', NULL, NULL, 'User Farras logout dari sistem', NULL, NULL, '2025-12-25 18:18:52.890164');
INSERT INTO public.activity_log VALUES (77, 28, 'Login', NULL, NULL, 'User operator successfully logged in', NULL, NULL, '2025-12-25 18:19:03.879535');
INSERT INTO public.activity_log VALUES (78, 28, 'Log Out', NULL, NULL, 'User operator logout dari sistem', NULL, NULL, '2025-12-25 18:23:08.382405');
INSERT INTO public.activity_log VALUES (79, 5, 'Login', NULL, NULL, 'User Farras successfully logged in', NULL, NULL, '2025-12-25 18:23:14.3755');
INSERT INTO public.activity_log VALUES (80, 5, 'Create', 'mahasiswa', 24, 'Data Mahasiswa (Muhammad Farras Awaludin Alwi - 244107060032) berhasil ditambahkan', NULL, '{"nim": "244107060032", "status": "Aktif", "address": "Dsn. morangan ds. minggiran jl. raya minggiran no.93 rt 001/ rw oo2", "full_name": "Muhammad Farras Awaludin Alwi", "entry_year": "2024", "university": "Politeknik Negeri Malang", "phone_number": "085330636086", "study_program": "D4 Sistem Informasi Bisnis", "current_semester": "3"}', '2025-12-25 18:23:57.678976');
INSERT INTO public.activity_log VALUES (81, 5, 'Create', 'users', 41, 'User mfarras successfully created', NULL, '{"email": "244107060032@polinema.ac.id", "id_roles": "3", "password": "$2y$10$2aG49Q0fAkfT/HYh4FkFc.OFCi7Yiwa7Z79XJRmb/bWUQSvMWnOaG", "username": "mfarras", "full_name": "Muhammad Farras Awaludin Alwi", "mahasiswa_id": "24"}', '2025-12-25 18:25:19.029547');
INSERT INTO public.activity_log VALUES (82, 5, 'Log Out', NULL, NULL, 'User Farras logout dari sistem', NULL, NULL, '2025-12-25 18:26:37.249482');
INSERT INTO public.activity_log VALUES (83, 41, 'Login', NULL, NULL, 'User mfarras successfully logged in', NULL, NULL, '2025-12-25 18:27:04.893471');
INSERT INTO public.activity_log VALUES (84, 41, 'Login', NULL, NULL, 'User mfarras successfully logged in', NULL, NULL, '2025-12-25 18:32:30.754618');
INSERT INTO public.activity_log VALUES (85, 41, 'Login', NULL, NULL, 'User mfarras successfully logged in', NULL, NULL, '2025-12-25 18:37:49.934016');
INSERT INTO public.activity_log VALUES (86, 3, 'Login', NULL, NULL, 'User Diyah successfully logged in', NULL, NULL, '2025-12-25 19:48:44.15385');
INSERT INTO public.activity_log VALUES (87, 3, 'Log Out', NULL, NULL, 'User Diyah logout dari sistem', NULL, NULL, '2025-12-25 21:26:59.530398');
INSERT INTO public.activity_log VALUES (88, 34, 'Login', NULL, NULL, 'User diyah successfully logged in', NULL, NULL, '2025-12-25 21:27:08.567458');
INSERT INTO public.activity_log VALUES (89, 41, 'Login', NULL, NULL, 'User mfarras successfully logged in', NULL, NULL, '2025-12-25 22:40:09.31305');
INSERT INTO public.activity_log VALUES (90, 41, 'Log Out', NULL, NULL, 'User mfarras logout dari sistem', NULL, NULL, '2025-12-25 22:44:03.618868');
INSERT INTO public.activity_log VALUES (91, 5, 'Login', NULL, NULL, 'User Farras successfully logged in', NULL, NULL, '2025-12-25 22:44:14.514529');
INSERT INTO public.activity_log VALUES (92, 41, 'Login', NULL, NULL, 'User mfarras successfully logged in', NULL, NULL, '2025-12-25 22:46:09.028766');
INSERT INTO public.activity_log VALUES (93, 5, 'Log Out', NULL, NULL, 'User Farras logout dari sistem', NULL, NULL, '2025-12-25 22:55:52.734374');
INSERT INTO public.activity_log VALUES (94, 28, 'Login', NULL, NULL, 'User operator successfully logged in', NULL, NULL, '2025-12-25 22:58:30.079197');
INSERT INTO public.activity_log VALUES (95, 28, 'Log Out', NULL, NULL, 'User operator logout dari sistem', NULL, NULL, '2025-12-25 23:01:19.578333');
INSERT INTO public.activity_log VALUES (96, 28, 'Login', NULL, NULL, 'User operator successfully logged in', NULL, NULL, '2025-12-25 23:05:39.553252');
INSERT INTO public.activity_log VALUES (97, 34, 'Log Out', NULL, NULL, 'User diyah logout dari sistem', NULL, NULL, '2025-12-25 23:05:41.788008');
INSERT INTO public.activity_log VALUES (98, 3, 'Login', NULL, NULL, 'User Diyah successfully logged in', NULL, NULL, '2025-12-25 23:05:51.334649');
INSERT INTO public.activity_log VALUES (99, 28, 'Create', 'gallery', 32, 'Gallery image Rapat successfully created', NULL, '{"url": null, "type": "Photo", "title": "Rapat", "image_name": "1766678827_3e346c20a0.jpg", "description": "rapat bulanan", "upload_date": "2025-12-25 23:07:07"}', '2025-12-25 23:07:11.879091');
INSERT INTO public.activity_log VALUES (100, 28, 'Update', 'gallery', 32, 'Gallery image Rapat successfully updated', '{"id": 32, "url": null, "type": "Photo", "title": "Rapat", "image_name": "1766678827_3e346c20a0.jpg", "description": "rapat bulanan", "upload_date": "2025-12-25 23:07:07"}', '{"url": null, "type": "Photo", "title": "Rapat", "image_name": "1766678827_3e346c20a0.jpg", "description": "rapat tahunan"}', '2025-12-25 23:07:39.407122');
INSERT INTO public.activity_log VALUES (101, 28, 'Delete', 'gallery', 32, 'Gallery image Rapat successfully deleted', '{"id": 32, "url": null, "type": "Photo", "title": "Rapat", "image_name": "1766678827_3e346c20a0.jpg", "description": "rapat tahunan", "upload_date": "2025-12-25 23:07:07"}', NULL, '2025-12-25 23:07:59.62316');
INSERT INTO public.activity_log VALUES (102, 3, 'Log Out', NULL, NULL, 'User Diyah logout dari sistem', NULL, NULL, '2025-12-25 23:12:21.920343');
INSERT INTO public.activity_log VALUES (103, 34, 'Login', NULL, NULL, 'User diyah successfully logged in', NULL, NULL, '2025-12-25 23:12:31.449964');
INSERT INTO public.activity_log VALUES (104, 4, 'Login', NULL, NULL, 'User Nita successfully logged in', NULL, NULL, '2025-12-25 23:30:34.730309');
INSERT INTO public.activity_log VALUES (105, 4, 'Log Out', NULL, NULL, 'User Nita logout dari sistem', NULL, NULL, '2025-12-25 23:31:54.631371');
INSERT INTO public.activity_log VALUES (106, 28, 'Log Out', NULL, NULL, 'User operator logout dari sistem', NULL, NULL, '2025-12-25 23:34:28.621519');
INSERT INTO public.activity_log VALUES (107, 5, 'Login', NULL, NULL, 'User Farras successfully logged in', NULL, NULL, '2025-12-25 23:34:34.540892');
INSERT INTO public.activity_log VALUES (108, 4, 'Login', NULL, NULL, 'User Nita successfully logged in', NULL, NULL, '2025-12-26 00:06:02.266');
INSERT INTO public.activity_log VALUES (109, 4, 'Update', 'users', 16, 'User alif successfully updated', '{"id": 16, "email": "24410706011@inle.ac", "id_roles": 3, "password": "$2y$10$ZnwssNynOtlUPr1BjkwJCOhaEr.P1TP6fog.Xdu2wXxCOYKJd3k.K", "username": "alif", "full_name": "Muhammad Alif Ananda", "mahasiswa_id": 11, "remember_token": null, "remember_token_expires_at": null}', 'true', '2025-12-26 00:07:32.56275');
INSERT INTO public.activity_log VALUES (110, 4, 'Delete', 'users', 23, 'User arka successfully deleted', '{"id": 23, "email": "24410706018@inle.ac", "id_roles": 3, "password": "$2y$10$ZnwssNynOtlUPr1BjkwJCOhaEr.P1TP6fog.Xdu2wXxCOYKJd3k.K", "username": "arka", "full_name": "Arka Arifiandi Leonanta", "mahasiswa_id": 18, "remember_token": null, "remember_token_expires_at": null}', NULL, '2025-12-26 00:07:54.705853');
INSERT INTO public.activity_log VALUES (111, 4, 'Log Out', NULL, NULL, 'User Nita logout dari sistem', NULL, NULL, '2025-12-26 00:08:57.584028');
INSERT INTO public.activity_log VALUES (112, 4, 'Login', NULL, NULL, 'User Nita successfully logged in', NULL, NULL, '2025-12-26 00:11:49.114626');
INSERT INTO public.activity_log VALUES (113, 4, 'Update', 'users', 16, 'User alif successfully updated', '{"id": 16, "email": "24410706011@inle.ac.id", "id_roles": 3, "password": "$2y$10$ZnwssNynOtlUPr1BjkwJCOhaEr.P1TP6fog.Xdu2wXxCOYKJd3k.K", "username": "alif", "full_name": "Muhammad Alif Ananda", "mahasiswa_id": 11, "remember_token": null, "remember_token_expires_at": null}', 'true', '2025-12-26 00:13:32.208144');
INSERT INTO public.activity_log VALUES (114, 4, 'Delete', 'users', 12, 'User dafi successfully deleted', '{"id": 12, "email": "24410706007@inle.ac", "id_roles": 3, "password": "$2y$10$ZnwssNynOtlUPr1BjkwJCOhaEr.P1TP6fog.Xdu2wXxCOYKJd3k.K", "username": "dafi", "full_name": "Dafi Azka Banurella Zhuhri", "mahasiswa_id": 7, "remember_token": null, "remember_token_expires_at": null}', NULL, '2025-12-26 00:13:58.202737');
INSERT INTO public.activity_log VALUES (115, 4, 'Log Out', NULL, NULL, 'User Nita logout dari sistem', NULL, NULL, '2025-12-26 00:16:41.088728');
INSERT INTO public.activity_log VALUES (116, 40, 'Login', NULL, NULL, 'User primayunita successfully logged in', NULL, NULL, '2025-12-26 00:16:54.404816');
INSERT INTO public.activity_log VALUES (117, 40, 'Log Out', NULL, NULL, 'User primayunita logout dari sistem', NULL, NULL, '2025-12-26 00:26:39.948049');
INSERT INTO public.activity_log VALUES (118, 41, 'Login', NULL, NULL, 'User mfarras successfully logged in', NULL, NULL, '2025-12-26 00:28:44.107767');
INSERT INTO public.activity_log VALUES (119, 41, 'Log Out', NULL, NULL, 'User mfarras logout dari sistem', NULL, NULL, '2025-12-26 00:30:27.910958');
INSERT INTO public.activity_log VALUES (120, 41, 'Login', NULL, NULL, 'User mfarras successfully logged in', NULL, NULL, '2025-12-26 00:30:34.907097');
INSERT INTO public.activity_log VALUES (121, 41, 'Login', NULL, NULL, 'User mfarras successfully logged in', NULL, NULL, '2025-12-26 00:41:53.819823');
INSERT INTO public.activity_log VALUES (122, 13, 'Login', NULL, NULL, 'User sultan successfully logged in', NULL, NULL, '2025-12-26 00:54:56.008271');
INSERT INTO public.activity_log VALUES (123, 41, 'Login', NULL, NULL, 'User mfarras successfully logged in', NULL, NULL, '2025-12-26 01:00:22.992571');
INSERT INTO public.activity_log VALUES (124, 41, 'Log Out', NULL, NULL, 'User mfarras logout dari sistem', NULL, NULL, '2025-12-26 01:01:58.86613');
INSERT INTO public.activity_log VALUES (125, 40, 'Login', NULL, NULL, 'User primayunita successfully logged in', NULL, NULL, '2025-12-26 01:02:14.461575');
INSERT INTO public.activity_log VALUES (126, 4, 'Login', NULL, NULL, 'User Nita successfully logged in', NULL, NULL, '2025-12-26 01:05:25.586598');
INSERT INTO public.activity_log VALUES (127, 41, 'Log Out', NULL, NULL, 'User mfarras logout dari sistem', NULL, NULL, '2025-12-26 01:07:57.987933');
INSERT INTO public.activity_log VALUES (128, 5, 'Login', NULL, NULL, 'User Farras successfully logged in', NULL, NULL, '2025-12-26 01:08:15.115232');
INSERT INTO public.activity_log VALUES (129, 40, 'Log Out', NULL, NULL, 'User primayunita logout dari sistem', NULL, NULL, '2025-12-26 01:08:33.59052');
INSERT INTO public.activity_log VALUES (130, 8, 'Login', NULL, NULL, 'User amalia successfully logged in', NULL, NULL, '2025-12-26 01:08:47.100416');
INSERT INTO public.activity_log VALUES (131, 8, 'Log Out', NULL, NULL, 'User amalia logout dari sistem', NULL, NULL, '2025-12-26 01:10:39.574683');
INSERT INTO public.activity_log VALUES (132, 8, 'Login', NULL, NULL, 'User amalia successfully logged in', NULL, NULL, '2025-12-26 01:11:43.940028');
INSERT INTO public.activity_log VALUES (133, 8, 'Log Out', NULL, NULL, 'User amalia logout dari sistem', NULL, NULL, '2025-12-26 01:13:59.247412');
INSERT INTO public.activity_log VALUES (134, 8, 'Login', NULL, NULL, 'User amalia successfully logged in', NULL, NULL, '2025-12-26 01:14:44.641813');
INSERT INTO public.activity_log VALUES (135, 8, 'Log Out', NULL, NULL, 'User amalia logout dari sistem', NULL, NULL, '2025-12-26 01:15:33.55915');
INSERT INTO public.activity_log VALUES (136, 8, 'Login', NULL, NULL, 'User amalia successfully logged in', NULL, NULL, '2025-12-26 01:16:32.69257');
INSERT INTO public.activity_log VALUES (137, 8, 'Log Out', NULL, NULL, 'User amalia logout dari sistem', NULL, NULL, '2025-12-26 01:18:01.725624');
INSERT INTO public.activity_log VALUES (138, 34, 'Log Out', NULL, NULL, 'User diyah logout dari sistem', NULL, NULL, '2025-12-26 01:24:06.810133');
INSERT INTO public.activity_log VALUES (139, 3, 'Login', NULL, NULL, 'User Diyah successfully logged in', NULL, NULL, '2025-12-26 01:24:17.124861');
INSERT INTO public.activity_log VALUES (140, 3, 'Update', 'settings', 1, 'Site settings updated', '{"id": 1, "email": "ando@polinema.ac.id", "phone": "081359889181 ", "address": "JL. Soekarno Hatta No. 9", "logo_path": "e9a80ac020864421f1369cbcf68c846d.png", "site_name": "InLET Lab", "created_at": "2025-12-01 05:31:45.189555", "updated_at": null, "favicon_path": null, "social_links": "{\"youtube\": \"www.youtube.com/@bannisatriaandoko2404\", \"facebook\": \"\", \"instagram\": \"\"}", "map_embed_url": "<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.495001151587!2d112.61283267483388!3d-7.947689079176015!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e788276980bd1e1%3A0x8961e0763275a1cf!2sJl.%20Soekarno%20Hatta%20No.9%2C%20Jatimulyo%2C%20Kec.%20Lowokwaru%2C%20Kota%20Malang%2C%20Jawa%20Timur%2065141!5e0!3m2!1sid!2sid!4v1765376239367!5m2!1sid!2sid\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>"}', '{"email": "ando@polinema.ac.id", "phone": "081359889181 ", "address": "JL. Soekarno Hatta No. 9", "site_name": "InLET Lab", "social_links": "{\"facebook\":\"\",\"instagram\":\"\",\"youtube\":\"www.youtube.com/@bannisatriaandoko2404\"}", "map_embed_url": "<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.495001151587!2d112.61283267483388!3d-7.947689079176015!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e788276980bd1e1%3A0x8961e0763275a1cf!2sJl.%20Soekarno%20Hatta%20No.9%2C%20Jatimulyo%2C%20Kec.%20Lowokwaru%2C%20Kota%20Malang%2C%20Jawa%20Timur%2065141!5e0!3m2!1sid!2sid!4v1765376239367!5m2!1sid!2sid\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>"}', '2025-12-26 01:34:17.075947');
INSERT INTO public.activity_log VALUES (141, 3, 'Log Out', NULL, NULL, 'User Diyah logout dari sistem', NULL, NULL, '2025-12-26 01:51:23.172039');
INSERT INTO public.activity_log VALUES (142, 34, 'Login', NULL, NULL, 'User diyah successfully logged in', NULL, NULL, '2025-12-26 01:51:37.246702');
INSERT INTO public.activity_log VALUES (143, 3, 'Login', NULL, NULL, 'User Diyah successfully logged in', NULL, NULL, '2025-12-26 08:42:24.378605');
INSERT INTO public.activity_log VALUES (144, 3, 'Log Out', NULL, NULL, 'User Diyah logout dari sistem', NULL, NULL, '2025-12-26 13:28:19.959982');
INSERT INTO public.activity_log VALUES (145, 34, 'Login', NULL, NULL, 'User diyah successfully logged in', NULL, NULL, '2025-12-26 13:28:29.456656');
INSERT INTO public.activity_log VALUES (146, 34, 'Log Out', NULL, NULL, 'User diyah logout dari sistem', NULL, NULL, '2025-12-26 14:03:14.543252');
INSERT INTO public.activity_log VALUES (147, 3, 'Login', NULL, NULL, 'User Diyah successfully logged in', NULL, NULL, '2025-12-26 14:03:24.282113');
INSERT INTO public.activity_log VALUES (148, 3, 'Login', NULL, NULL, 'User Diyah successfully logged in', NULL, NULL, '2025-12-26 16:48:10.98839');


--
-- TOC entry 3965 (class 0 OID 17618)
-- Dependencies: 296
-- Data for Name: app_menus; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.app_menus VALUES (2, 'Dashboard', 'admin/dashboard', 'bi bi-grid-fill', NULL, 2, true, '2025-12-16 18:03:15.257756', '["read"]', false);
INSERT INTO public.app_menus VALUES (3, 'Content Management', NULL, NULL, NULL, 3, false, '2025-12-16 18:03:15.257756', NULL, false);
INSERT INTO public.app_menus VALUES (4, 'News', 'admin/news', 'bi bi-newspaper', NULL, 4, true, '2025-12-16 18:03:15.257756', '["create", "read", "update", "delete"]', false);
INSERT INTO public.app_menus VALUES (5, 'Social Media', 'admin/social-links', 'bi bi-share-fill', NULL, 5, true, '2025-12-16 18:03:15.257756', '["create", "read", "update", "delete"]', false);
INSERT INTO public.app_menus VALUES (6, 'Project Management', NULL, 'bi bi-folder-fill', NULL, 6, true, '2025-12-16 18:03:15.257756', NULL, false);
INSERT INTO public.app_menus VALUES (7, 'Data Project', 'admin/project-lab', NULL, 6, 1, true, '2025-12-16 18:03:15.257756', '["create", "read", "update", "delete"]', false);
INSERT INTO public.app_menus VALUES (8, 'Categories', 'admin/kategori-project', NULL, 6, 2, true, '2025-12-16 18:03:15.257756', '["create", "read", "update", "delete"]', false);
INSERT INTO public.app_menus VALUES (9, 'Company Profile', NULL, 'bi bi-building-fill', NULL, 7, true, '2025-12-16 18:03:15.257756', NULL, false);
INSERT INTO public.app_menus VALUES (10, 'About Us', 'admin/aboutus', NULL, 9, 1, true, '2025-12-16 18:03:15.257756', '["read", "update"]', false);
INSERT INTO public.app_menus VALUES (11, 'Team', 'admin/team', NULL, 9, 2, true, '2025-12-16 18:03:15.257756', '["create", "read", "update", "delete"]', false);
INSERT INTO public.app_menus VALUES (12, 'Partner', 'admin/partner', NULL, 9, 3, true, '2025-12-16 18:03:15.257756', '["create", "read", "update", "delete"]', false);
INSERT INTO public.app_menus VALUES (13, 'Facilities', 'admin/facilities', NULL, 9, 4, true, '2025-12-16 18:03:15.257756', '["create", "read", "update", "delete"]', false);
INSERT INTO public.app_menus VALUES (14, 'Gallery', NULL, 'bi bi-images', NULL, 8, true, '2025-12-16 18:03:15.257756', NULL, false);
INSERT INTO public.app_menus VALUES (15, 'Image', 'admin/gallery/image', NULL, 14, 1, true, '2025-12-16 18:03:15.257756', '["create", "read", "update", "delete"]', false);
INSERT INTO public.app_menus VALUES (16, 'Video', 'admin/gallery/video', NULL, 14, 2, true, '2025-12-16 18:03:15.257756', '["create", "read", "update", "delete"]', false);
INSERT INTO public.app_menus VALUES (17, 'Product', 'admin/product', 'bi bi-box-seam', NULL, 9, true, '2025-12-16 18:03:15.257756', '["create", "read", "update", "delete"]', false);
INSERT INTO public.app_menus VALUES (18, 'Research Focus', 'admin/research-focus', 'bi bi-lightbulb-fill', NULL, 10, true, '2025-12-16 18:03:15.257756', '["create", "read", "update", "delete"]', false);
INSERT INTO public.app_menus VALUES (19, 'Hero Slider', 'admin/hero-slider', 'bi bi-sliders', NULL, 11, true, '2025-12-16 18:03:15.257756', '["create", "read", "update", "delete"]', false);
INSERT INTO public.app_menus VALUES (20, 'Attendance System', NULL, NULL, NULL, 12, false, '2025-12-16 18:03:15.257756', NULL, false);
INSERT INTO public.app_menus VALUES (21, 'Time Setting', 'admin/attendance-settings', 'bi bi-clock-fill', NULL, 13, true, '2025-12-16 18:03:15.257756', '["create", "read", "update"]', false);
INSERT INTO public.app_menus VALUES (22, 'Attendance Permissions', 'admin/attendance-permissions', 'bi bi-clipboard-check', NULL, 14, true, '2025-12-16 18:03:15.257756', '["read", "update"]', false);
INSERT INTO public.app_menus VALUES (23, 'Attendance History', 'admin/attendance-history', 'bi bi-clock-history', NULL, 15, true, '2025-12-16 18:03:15.257756', '["read"]', false);
INSERT INTO public.app_menus VALUES (24, 'System Management', NULL, NULL, NULL, 16, false, '2025-12-16 18:03:15.257756', NULL, false);
INSERT INTO public.app_menus VALUES (25, 'User Management', NULL, 'bi bi-gear-fill', NULL, 17, true, '2025-12-16 18:03:15.257756', NULL, false);
INSERT INTO public.app_menus VALUES (26, 'Roles', 'admin/roles', NULL, 25, 1, true, '2025-12-16 18:03:15.257756', '["create", "read", "update", "delete"]', false);
INSERT INTO public.app_menus VALUES (27, 'Permissions', 'admin/permissions', NULL, 25, 2, true, '2025-12-16 18:03:15.257756', '["read", "update"]', false);
INSERT INTO public.app_menus VALUES (28, 'Users', 'admin/user', NULL, 25, 3, true, '2025-12-16 18:03:15.257756', '["create", "read", "update", "delete"]', false);
INSERT INTO public.app_menus VALUES (29, 'Mahasiswa', 'admin/mahasiswa', NULL, 25, 4, true, '2025-12-16 18:03:15.257756', '["create", "read", "update", "delete"]', false);
INSERT INTO public.app_menus VALUES (30, 'Site Settings', 'admin/site-settings', 'bi bi-sliders2-vertical', NULL, 18, true, '2025-12-16 18:03:15.257756', '["read", "update"]', false);
INSERT INTO public.app_menus VALUES (31, 'Log Activity', 'admin/log-activity', 'bi bi-file-earmark-text-fill', NULL, 19, true, '2025-12-16 18:03:15.257756', '["read"]', false);
INSERT INTO public.app_menus VALUES (32, 'STUDENT DASHBOARD', NULL, NULL, NULL, 1, false, '2025-12-16 18:03:15.257756', NULL, true);
INSERT INTO public.app_menus VALUES (33, 'Mahasiswa Dashboard', 'mahasiswa/dashboard', 'bi bi-person-workspace', NULL, 2, true, '2025-12-16 18:03:15.257756', '["read"]', true);
INSERT INTO public.app_menus VALUES (34, 'ATTENDANCE', NULL, NULL, NULL, 3, false, '2025-12-16 18:03:15.257756', NULL, true);
INSERT INTO public.app_menus VALUES (35, 'Presence', 'mahasiswa/presence', 'bi bi-fingerprint', NULL, 4, true, '2025-12-16 18:03:15.257756', '["read", "create"]', true);
INSERT INTO public.app_menus VALUES (36, 'Request Permission', 'mahasiswa/request-permission', 'bi bi-send-fill', NULL, 5, true, '2025-12-16 18:03:15.257756', '["create", "read"]', true);
INSERT INTO public.app_menus VALUES (37, 'Log Mahasiswa', 'mahasiswa/log/presence', 'bi bi-journal-text', NULL, 6, true, '2025-12-16 18:03:15.257756', '["read"]', true);
INSERT INTO public.app_menus VALUES (38, 'Profile Mahasiswa', 'mahasiswa/profile', 'bi bi-person-circle', NULL, 7, true, '2025-12-16 18:03:15.257756', '["read", "update"]', true);
INSERT INTO public.app_menus VALUES (1, 'Main Menu', NULL, NULL, NULL, 1, false, '2025-12-16 18:03:15.257756', NULL, false);


--
-- TOC entry 3980 (class 0 OID 17715)
-- Dependencies: 311
-- Data for Name: attendance_logs; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.attendance_logs VALUES (8, '2025-12-16 16:50:54.728838', 'check_in', 'Late Check-in', 'uploads/attendance/presence_4_1765878649.jpeg', '-7.943843969501384', '112.61487', '2025-12-16 16:50:54.728838', 4);
INSERT INTO public.attendance_logs VALUES (9, '2025-12-16 16:52:49.528315', 'check_out', 'On Time Check-out', 'uploads/attendance/presence_4_1765878763.jpeg', '-7.9439859331374', '112.61473795958855', '2025-12-16 16:52:49.528315', 4);
INSERT INTO public.attendance_logs VALUES (10, '2025-12-16 17:15:57.831174', 'check_in', 'Late Check-in', 'uploads/attendance/presence_5_1765880152.jpeg', '-7.94382', '112.61487', '2025-12-16 17:15:57.831174', 5);
INSERT INTO public.attendance_logs VALUES (11, '2025-12-17 14:42:48.668376', 'check_in', 'Late Check-in', 'uploads/attendance/presence_3_1765957363.jpeg', '-7.94433738272921', '112.61478174489794', '2025-12-17 14:42:48.668376', 3);
INSERT INTO public.attendance_logs VALUES (12, '2025-12-25 22:52:06.166929', 'check_in', 'Late Check-in', 'uploads/attendance/presence_24_1766677922.jpeg', '-7.726183', '112.05864', '2025-12-25 22:52:06.166929', 24);
INSERT INTO public.attendance_logs VALUES (13, '2025-12-25 22:53:44.989074', 'check_out', 'On Time Check-out', 'uploads/attendance/presence_24_1766678021.jpeg', '-7.726183', '112.05864', '2025-12-25 22:53:44.989074', 24);
INSERT INTO public.attendance_logs VALUES (14, '2025-12-26 01:03:06.767979', 'check_in', 'Late Check-in', 'uploads/attendance/presence_23_1766685792.jpeg', '-7.446053', '112.223145', '2025-12-26 01:03:06.767979', 23);
INSERT INTO public.attendance_logs VALUES (15, '2025-12-26 01:07:13.104093', 'check_out', 'On Time Check-out', 'uploads/attendance/presence_23_1766686039.jpeg', '-7.44607', '112.223167', '2025-12-26 01:07:13.104093', 23);
INSERT INTO public.attendance_logs VALUES (16, '2025-12-26 01:17:08.568082', 'check_in', 'Late Check-in', 'uploads/attendance/presence_3_1766686634.jpeg', '-7.445032', '112.222686', '2025-12-26 01:17:08.568082', 3);


--
-- TOC entry 3982 (class 0 OID 17729)
-- Dependencies: 313
-- Data for Name: attendance_permissions; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.attendance_permissions VALUES (1, 8, 'sick', '2025-12-17', '2025-12-18', 'saya mual', 'permit_8_1765955335.pdf', 'approved', 1, NULL, '2025-12-17 14:08:55', '2025-12-17 14:09:12');
INSERT INTO public.attendance_permissions VALUES (2, 11, 'sick', '2025-12-17', '2025-12-18', 'sakit parah', 'permit_11_1765955451.png', 'pending', NULL, NULL, '2025-12-17 14:10:51', NULL);


--
-- TOC entry 3978 (class 0 OID 17708)
-- Dependencies: 309
-- Data for Name: attendance_settings; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.attendance_settings VALUES (12, '01:00:00', '01:30:00', '00:00:00', NULL);


--
-- TOC entry 3972 (class 0 OID 17672)
-- Dependencies: 303
-- Data for Name: category_project; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.category_project VALUES (2, 'Kewirausahaan & Ekonomi Digital');
INSERT INTO public.category_project VALUES (3, 'Karier, Industri & Kemitraan');
INSERT INTO public.category_project VALUES (4, 'Analitik Pembelajaran (Learning Analytics)');
INSERT INTO public.category_project VALUES (5, 'Teknologi Pembelajaran (EdTech)');
INSERT INTO public.category_project VALUES (6, 'Teknologi Kesehatan & Analisis Sinyal Otak');
INSERT INTO public.category_project VALUES (7, 'Teknologi Pembelajaran & Human-Computer Interaction (HCI)');
INSERT INTO public.category_project VALUES (8, 'AI & Computer Vision dalam Pembelajaran');
INSERT INTO public.category_project VALUES (9, 'Sistem Informasi Geografis (GIS) & Smart City');


--
-- TOC entry 3949 (class 0 OID 17522)
-- Dependencies: 280
-- Data for Name: facilities; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.facilities VALUES (6, 'All-In-One EEG Electrode Cap Bundle', 'Set lengkap penutup kepala (cap) yang dilengkapi dengan elektroda untuk merekam aktivitas listrik otak (EEG). Alat ini memerlukan penggunaan gel konduktif.', '47191ed29dc380480a1bb950881ca26a.jpg', 1, 'good');


--
-- TOC entry 3955 (class 0 OID 17551)
-- Dependencies: 286
-- Data for Name: gallery; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.gallery VALUES (14, 'Learning Engineering Technology Lab : What is TEL?', '', NULL, '2025-12-02 04:17:50', 'Video', 'https://www.youtube.com/watch?v=eis5aTweBHs');
INSERT INTO public.gallery VALUES (16, 'VIAT-Map promotion Video JTI - POLINEMA', 'VIAT-map (Visual Arguments Toulmin) Application to help Reding Comprehension by using Toulmin Arguments Concept. We are trying to emphasize the logic behind a written text by adding the claim, ground, and warrant following the Toulmin Argument Concept.', NULL, '2025-12-02 04:20:29', 'Video', 'https://www.youtube.com/watch?v=Fcv2-z0WXys&t=532s');
INSERT INTO public.gallery VALUES (17, 'Monthly Research Discussion', 'Conducting a routine monthly research discussion to find new concept and finding', '1764650314_b1c92b57f6.jpg', '2025-12-02 04:38:34', 'Photo', NULL);
INSERT INTO public.gallery VALUES (18, 'Poster Presentation, Japan', 'We did a poster presentation in ICCE 2023, Matsue Japan', '1764650405_d7c54d804d.jpeg', '2025-12-02 04:40:05', 'Photo', NULL);
INSERT INTO public.gallery VALUES (19, 'ICCE 2024 - Atteneo University, Phillipines', '3 of our member went to Phillipines to present our research. It''s been a valuable experiences to meet other researcher''s outside Indonesia', '1764650449_3d40808e73.jpg', '2025-12-02 04:40:49', 'Photo', NULL);
INSERT INTO public.gallery VALUES (20, 'ECTEL 2024 - Krems, Austria', 'Introducing VIAT-map to other researcher in ECTEL conference', '1764652324_79bf2a72cc.jpg', '2025-12-02 05:12:04', 'Photo', NULL);
INSERT INTO public.gallery VALUES (21, 'Visiting Scientist Program', 'In November, 2023. we had a chance to had a research collaboration with Hiroshima University', '1764652392_f3e8be28a3.jpg', '2025-12-02 05:13:12', 'Photo', NULL);
INSERT INTO public.gallery VALUES (22, 'ICCE 2023, Full Paper Presentation', 'We did a full paper presentation in ICCE 2023, Matsue Japan', '1764652444_9d22b0a820.jpg', '2025-12-02 05:14:04', 'Photo', NULL);
INSERT INTO public.gallery VALUES (23, 'International Research Discussion Program', 'Enriching the research area by having Research discussion', '1764653423_d8c5abd9f0.jpg', '2025-12-02 05:30:23', 'Photo', NULL);
INSERT INTO public.gallery VALUES (24, 'Lecturer meeting', '', '1764653543_aa42ffd8a1.jpeg', '2025-12-02 05:32:23', 'Photo', NULL);
INSERT INTO public.gallery VALUES (26, 'Meeting', '', '1764653586_6dead8a2f4.jpg', '2025-12-02 05:33:06', 'Photo', NULL);
INSERT INTO public.gallery VALUES (25, 'Lecturer meeting', '', '1764653556_fcb098fe7f.jpeg', '2025-12-02 05:32:36', 'Photo', NULL);
INSERT INTO public.gallery VALUES (27, 'Tutorial Viat-Map', '', NULL, '2025-12-02 05:35:07', 'Video', 'https://www.youtube.com/watch?v=8vfhk8MugYQ');
INSERT INTO public.gallery VALUES (28, 'ICCE 2023, Full Paper Presentation', '', NULL, '2025-12-02 05:35:47', 'Video', 'https://www.youtube.com/watch?v=GwNiHTUH06Y');


--
-- TOC entry 3945 (class 0 OID 17500)
-- Dependencies: 276
-- Data for Name: hero_slider; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.hero_slider VALUES (3, 'Welcome to Our Laboratory', 'Innovation Starts Here', 'f60a8e709b94f979abcc5d30d8e0ded2.jpg', '', '', 1, true, '2025-12-02 08:28:11');
INSERT INTO public.hero_slider VALUES (4, 'Excellence in Research', 'Committed to Scientific Advancement', '23ead29482674a2f17cb898dc9804aa1.jpg', '', '', 4, true, '2025-12-02 08:30:04');
INSERT INTO public.hero_slider VALUES (5, 'Collaborate. Innovate. Discover.', 'Together We Build the Future of Science', '89ccf919fd6a314603106cfba12d0c56.webp', '', '', 2, true, '2025-12-02 08:30:48');
INSERT INTO public.hero_slider VALUES (2, 'Modern Facilities', 'Equipped for Innovation & Discovery Collaborate. Innovate. Discover the future of science together.', '8808946fc1926f8b6ffe521fafff4345.jpg', '', '', 3, true, '2025-12-02 06:49:50');


--
-- TOC entry 3984 (class 0 OID 17750)
-- Dependencies: 315
-- Data for Name: mahasiswa; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.mahasiswa VALUES (1, '24410706001', 'Sabbaha Naufal Erwanda', 'Politeknik Negeri Malang', 'D4 Sistem Informasi Bisnis', 2025, 1, '081234567890', NULL, 'Aktif', '2025-12-04 00:00:00', NULL);
INSERT INTO public.mahasiswa VALUES (2, '24410706002', 'Muhammad Asadillah Ramadhan', 'Politeknik Negeri Malang', 'D4 Sistem Informasi Bisnis', 2025, 1, '081234567890', NULL, 'Aktif', '2025-12-04 00:00:00', NULL);
INSERT INTO public.mahasiswa VALUES (3, '24410706003', 'Amalia Nuraini', 'Politeknik Negeri Malang', 'D4 Sistem Informasi Bisnis', 2025, 1, '081234567890', NULL, 'Aktif', '2025-12-04 00:00:00', NULL);
INSERT INTO public.mahasiswa VALUES (4, '24410706004', 'Rabiatul Fitra Aulia', 'Politeknik Negeri Malang', 'D4 Sistem Informasi Bisnis', 2025, 1, '081234567890', NULL, 'Aktif', '2025-12-04 00:00:00', NULL);
INSERT INTO public.mahasiswa VALUES (5, '24410706005', 'Inda Khoirun Nisak', 'Politeknik Negeri Malang', 'D4 Sistem Informasi Bisnis', 2025, 1, '081234567890', NULL, 'Aktif', '2025-12-04 00:00:00', NULL);
INSERT INTO public.mahasiswa VALUES (6, '24410706006', 'Rara Deninda Hurianto', 'Politeknik Negeri Malang', 'D4 Sistem Informasi Bisnis', 2025, 1, '081234567890', NULL, 'Aktif', '2025-12-04 00:00:00', NULL);
INSERT INTO public.mahasiswa VALUES (7, '24410706007', 'Dafi Azka Banurella Zhuhri', 'Politeknik Negeri Malang', 'D4 Sistem Informasi Bisnis', 2025, 1, '081234567890', NULL, 'Aktif', '2025-12-04 00:00:00', NULL);
INSERT INTO public.mahasiswa VALUES (8, '24410706008', 'Sultan Achamd Qum Masykuro NS - S2', 'Politeknik Negeri Malang', 'D4 Sistem Informasi Bisnis', 2025, 1, '081234567890', NULL, 'Aktif', '2025-12-04 00:00:00', NULL);
INSERT INTO public.mahasiswa VALUES (9, '24410706009', 'Rajendra Rakha Arya Prabaswara', 'Politeknik Negeri Malang', 'D4 Sistem Informasi Bisnis', 2025, 1, '081234567890', NULL, 'Aktif', '2025-12-04 00:00:00', NULL);
INSERT INTO public.mahasiswa VALUES (10, '24410706010', 'Riris Silvia Zahri', 'Politeknik Negeri Malang', 'D4 Sistem Informasi Bisnis', 2025, 1, '081234567890', NULL, 'Aktif', '2025-12-04 00:00:00', NULL);
INSERT INTO public.mahasiswa VALUES (11, '24410706011', 'Muhammad Alif Ananda', 'Politeknik Negeri Malang', 'D4 Sistem Informasi Bisnis', 2025, 1, '081234567890', NULL, 'Aktif', '2025-12-04 00:00:00', NULL);
INSERT INTO public.mahasiswa VALUES (12, '24410706012', 'Sri Kynanti', 'Politeknik Negeri Malang', 'D4 Sistem Informasi Bisnis', 2025, 1, '081234567890', NULL, 'Aktif', '2025-12-04 00:00:00', NULL);
INSERT INTO public.mahasiswa VALUES (13, '24410706013', 'Daniel Bagus Christyanto', 'Politeknik Negeri Malang', 'D4 Sistem Informasi Bisnis', 2025, 1, '081234567890', NULL, 'Aktif', '2025-12-04 00:00:00', NULL);
INSERT INTO public.mahasiswa VALUES (14, '24410706014', 'Muhammad Fachry Najib', 'Politeknik Negeri Malang', 'D4 Sistem Informasi Bisnis', 2025, 1, '081234567890', NULL, 'Aktif', '2025-12-04 00:00:00', NULL);
INSERT INTO public.mahasiswa VALUES (15, '24410706015', 'Rio Febriandistra Adi', 'Politeknik Negeri Malang', 'D4 Sistem Informasi Bisnis', 2025, 1, '081234567890', NULL, 'Aktif', '2025-12-04 00:00:00', NULL);
INSERT INTO public.mahasiswa VALUES (16, '24410706016', 'Megananda Fadilla Rezeki', 'Politeknik Negeri Malang', 'D4 Sistem Informasi Bisnis', 2025, 1, '081234567890', NULL, 'Aktif', '2025-12-04 00:00:00', NULL);
INSERT INTO public.mahasiswa VALUES (17, '24410706017', 'Bening Sukmaningrum', 'Politeknik Negeri Malang', 'D4 Sistem Informasi Bisnis', 2025, 1, '081234567890', NULL, 'Aktif', '2025-12-04 00:00:00', NULL);
INSERT INTO public.mahasiswa VALUES (18, '24410706018', 'Arka Arifiandi Leonanta', 'Politeknik Negeri Malang', 'D4 Sistem Informasi Bisnis', 2025, 1, '081234567890', NULL, 'Aktif', '2025-12-04 00:00:00', NULL);
INSERT INTO public.mahasiswa VALUES (19, '24410706019', 'Rei Fangky Primandicka', 'Politeknik Negeri Malang', 'D4 Sistem Informasi Bisnis', 2025, 1, '081234567890', NULL, 'Aktif', '2025-12-04 00:00:00', NULL);
INSERT INTO public.mahasiswa VALUES (20, '24410706020', 'Rifqie Muhammad', 'Politeknik Negeri Malang', 'D4 Sistem Informasi Bisnis', 2025, 1, '081234567890', NULL, 'Aktif', '2025-12-04 00:00:00', NULL);
INSERT INTO public.mahasiswa VALUES (23, '244134456789', 'primayunita', 'polinema', 'sib', 2024, 3, '081234567890', 'Jl Soekarno Hatta No 9, Kota  Malang', 'Aktif', '2025-12-23 14:08:33.686916', NULL);
INSERT INTO public.mahasiswa VALUES (24, '244107060032', 'Muhammad Farras Awaludin Alwi', 'Politeknik Negeri Malang', 'D4 Sistem Informasi Bisnis', 2024, 3, '085330636086', 'Dsn. morangan ds. minggiran jl. raya minggiran no.93 rt 001/ rw oo2', 'Aktif', '2025-12-25 18:23:56.920893', NULL);


--
-- TOC entry 3963 (class 0 OID 17603)
-- Dependencies: 294
-- Data for Name: news; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.news VALUES (3, 'Poster Presentation, Japan', '<p><span style="color: rgb(35, 35, 35); font-family: Inter, sans-serif; font-size: 19.2px;">We did a poster presentation in ICCE 2023, Matsue Japan</span></p>', '25291d289e15ac7e248294d0e4d45230.jpeg', '2025-12-02 05:15:19', true, 1);
INSERT INTO public.news VALUES (4, 'Visiting Scientist Program', '<p><span style="color: rgb(35, 35, 35); font-family: Inter, sans-serif; font-size: 19.2px;">In November, 2023. we had a chance to had a research collaboration with Hiroshima University</span></p>', 'a1f4f603130f00330b5eba596ebeacfb.jpg', '2025-12-02 05:15:52', true, 1);
INSERT INTO public.news VALUES (5, 'Monthly Research Discussion', '<p><span style="color: rgb(35, 35, 35); font-family: Inter, sans-serif; font-size: 19.2px;">Conducting a routine monthly research discussion to find new concept and finding</span></p>', 'c4b396d7aff2c29c35c84bbb393c5da1.jpg', '2025-12-02 05:17:07', true, 1);
INSERT INTO public.news VALUES (6, 'ECTEL 2024 - Krems, Austria', '<p><span style="color: rgb(35, 35, 35); font-family: Inter, sans-serif; font-size: 19.2px;">Introducing VIAT-map to other researcher in ECTEL conference</span></p>', '1d02cdd679482c4a0e803897256f9797.jpg', '2025-12-02 05:18:57', true, 1);
INSERT INTO public.news VALUES (7, 'ICCE 2024 - Atteneo University, Phillipines', '<p><span style="color: rgb(35, 35, 35); font-family: Inter, sans-serif; font-size: 19.2px;">3 of our member went to Phillipines to present our research. It''s been a valuable experiences to meet other researcher''s outside Indonesia</span></p>', 'ba9d85ba770445fdc14b3826f5f136c2.jpg', '2025-12-02 05:21:08', true, 1);
INSERT INTO public.news VALUES (8, 'International Research Discussion Program', '<p><span style="color: rgb(35, 35, 35); font-family: Inter, sans-serif; font-size: 19.2px;">Enriching the research area by having Research discussion</span></p>', 'dee15d6442c51e2fe23ae24cca1bdd25.jpg', '2025-12-02 05:21:44', true, 1);
INSERT INTO public.news VALUES (9, 'Best Overall Paper Award', '<p><span style="color: rgb(35, 35, 35); font-family: Inter, sans-serif; font-size: 19.2px;">Enriching the research area by having Best overall paper award in ICCE 2023, Shimane Japan</span></p>', 'a424882d6faf100873dfc31bc1bb9abb.jpg', '2025-12-02 05:22:21', true, 1);
INSERT INTO public.news VALUES (10, 'POLINEMA - Research EXPO 2024', '<p><span style="color: rgb(35, 35, 35); font-family: Inter, sans-serif; font-size: 19.2px;">Introducing VIAT-map to other Indonesian researcher</span></p>', '3e97122b8953ae1ca43c457d5a98203e.jpg', '2025-12-02 05:22:58', true, 1);
INSERT INTO public.news VALUES (11, 'ICAST 2024 - Bandung, Indonesia', '<p><span style="color: rgb(35, 35, 35); font-family: Inter, sans-serif; font-size: 19.2px;">Thrilled and deeply honored to receive the Best Paper Award at ICAST 2024! A special thank you to Professor Hee-Deung Park from Korea University and Dr. Ong Tze Ching from Kuching Polytechnic, Malaysia, for their recognition of my work. This achievement reflects the dedication and passion for advancing research in our field. Grateful for this moment and excited for the&nbsp;journey&nbsp;ahead</span></p>', '58209f4ce18e08bca74d32cc7f1cb180.jpg', '2025-12-02 05:23:38', true, 1);
INSERT INTO public.news VALUES (2, 'ICCE 2023, Full Paper Presentation', '<p><span style="color: rgb(35, 35, 35); font-family: Inter, sans-serif; font-size: 19.2px;">We did a full paper presentation in ICCE 2023, <b>Matsue Japan</b></span></p>', '4a511a994b80602ba9032cf54c6b1bdc.jpg', '2025-12-05 20:12:29', true, 1);


--
-- TOC entry 3951 (class 0 OID 17533)
-- Dependencies: 282
-- Data for Name: partner; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.partner VALUES (9, 'Hummatech', '8e52f79ae019c3357dc3389d8c8f712e.png', NULL);
INSERT INTO public.partner VALUES (11, 'ScaDS.AI', '427c433343e94998944ade8d82b553bb.jpg', NULL);
INSERT INTO public.partner VALUES (10, 'DFKI', '3c2480e2ca49f44f532909aa3294bdec.png', 'www.google.com');
INSERT INTO public.partner VALUES (8, 'Learning Engineering Lab, Hiroshima', 'cd83cee188183f66610ce350fea16ff9.jpg', NULL);


--
-- TOC entry 3953 (class 0 OID 17542)
-- Dependencies: 284
-- Data for Name: product; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.product VALUES (3, 'Viat Map Application', 'VIAT-map (Visual Arguments Toulmin) Application to help Reding Comprehension by using Toulmin Arguments Concept. We are trying to emphasise the logic behind a written text by adding the claim, ground and warrant following the Toulmin Argument Concept.', 'eb320b20777f08d5873246f3cc34ee4d.png', '2025-08-22 00:00:00', NULL, NULL, 'https://vmap.let.polinema.ac.id/');
INSERT INTO public.product VALUES (4, 'PseudoLearn Application', 'Sebuah media pembelajaran rekonstruksi algoritma pseudocode dengan menggunakan pendekatan Element Fill-in-Blank Problems di dalam pemrograman java', '4f0a004dc8c903ff6737755e7a7cb9bc.png', '2025-10-10 00:00:00', '["Realtime Monitoring"]', '{"Platform": "Web Based"}', 'https://pseudolearn.id/');
INSERT INTO public.product VALUES (5, 'Codeasy', 'A Machine Learning-based Data Science platform that provides an automatic grading system and intelligent cognitive analysis to master Python and Business Intelligence.', '8f00c359329078d8e0f61b386bdb63c4.jpg', '2025-12-04 00:00:00', NULL, NULL, 'https://example.com/codeasy-repository');


--
-- TOC entry 3986 (class 0 OID 19052)
-- Dependencies: 320
-- Data for Name: project_category_pivot; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.project_category_pivot VALUES (12, 4);
INSERT INTO public.project_category_pivot VALUES (12, 2);
INSERT INTO public.project_category_pivot VALUES (12, 9);
INSERT INTO public.project_category_pivot VALUES (19, 8);
INSERT INTO public.project_category_pivot VALUES (18, 8);
INSERT INTO public.project_category_pivot VALUES (17, 7);
INSERT INTO public.project_category_pivot VALUES (16, 6);
INSERT INTO public.project_category_pivot VALUES (15, 5);
INSERT INTO public.project_category_pivot VALUES (14, 4);
INSERT INTO public.project_category_pivot VALUES (13, 3);


--
-- TOC entry 3974 (class 0 OID 17681)
-- Dependencies: 305
-- Data for Name: project_lab; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.project_lab VALUES (12, 'E-commerce Polinema', 'Expo Produk Inovasi 2025 yang memamerkan hasil riset dan kewirausahaan mahasiswa, serta promosi kolaborasi pentahelix yang melibatkan industri dan masyarakat dalam mengembangkan ekonomi digital.', '', '{12b3cba499a47548dd109cf00207ef1b.png}', 'completed', '2025-12-02 13:34:10+07');
INSERT INTO public.project_lab VALUES (19, 'GIS Pemkab Lumajang', 'Kolaborasi dalam bentuk penelitian dan pengabdian masyarakat yang fokus pada pengembangan Sistem Pengambil Keputusan Kawasan Kumuh Dinamis berbasis WebGIS. Tujuannya adalah untuk membantu Pemerintah Kabupaten Lumajang mengidentifikasi dan menangani kawasan permukiman kumuh secara lebih efektif dan berbasis data, mendukung upaya mewujudkan smart city.', '', '{5606dc385fd725ff85b63d5768e69593.jpg}', 'completed', '2025-12-02 13:41:36+07');
INSERT INTO public.project_lab VALUES (18, 'Face Expression', 'Penelitian atau proyek yang memanfaatkan teknologi pengenalan ekspresi wajah untuk menganalisis emosi atau respons pengguna, biasanya untuk meningkatkan interaksi pembelPenelitian atau proyek yang memanfaatkan teknologi pengenalan ekspresi wajah untuk menganalisis emosi atau respons pengguna, biasanya untuk meningkatkan interaksi pembelajaran, memahami mood mahasiswa, atau mengembangkan sistem yang adaptif sesuai kondisi emosional pengguna.ajaran, memahami mood mahasiswa, atau mengembangkan sistem yang adaptif sesuai kondisi emosional pengguna.', '', '{8eee06759230b1630b69ba38a494e718.jpg}', 'completed', '2025-12-02 13:41:05+07');
INSERT INTO public.project_lab VALUES (17, 'Eye Tracking', 'Penelitian menggunakan teknologi pelacak gerakan mata untuk mempelajari bagaimana orang melihat dan memproses informasi, biasanya untuk meningkatkan desain pembelajaran, antarmuka pengguna, atau memahami fokus dan perhatian pengguna saat belajar.', '', '{4646909b61575d8b54e0e1ccdf8ac6a2.jpg}', 'completed', '2025-12-02 13:40:34+07');
INSERT INTO public.project_lab VALUES (16, 'EEG signal', 'EEG signal adalah proyek penelitian menggunakan sinyal otak untuk mengendalikan perangkat (misal kursor komputer) dan mendeteksi gangguan medis seperti epilepsi dengan teknologi analisis sinyal dan machine learning.', '', '{7dc8e13c8baab8cbd73879d6fbf0b9da.jpg}', 'completed', '2025-12-02 13:39:46+07');
INSERT INTO public.project_lab VALUES (15, 'Learning Engineering', 'Bidang studi yang menggabungkan teknologi informasi dan pendidikan untuk merancang serta mengembangkan sistem pembelajaran berbasis teknologi yang efektif dan efisien.', '', '{675178fed956f224d2c4d0ff4da1ff7c.jpeg}', 'completed', '2025-12-02 13:38:54+07');
INSERT INTO public.project_lab VALUES (14, 'Multi Modal Learning Analytics', 'Kegiatan mengumpulkan dan menganalisis berbagai jenis data belajar (seperti nilai, interaksi online, video, dll) untuk memahami dan meningkatkan cara mahasiswa belajar.', '', '{f210289b280ce6d3ccf6520ebd4e2299.jpg}', 'completed', '2025-12-02 13:38:08+07');
INSERT INTO public.project_lab VALUES (13, 'PERTAMINA Warehouse', 'Acara rekrutmen bersama dan career talks untuk memfasilitasi mahasiswa dan alumni Polinema agar terserap di dunia kerja, khususnya di Pertamina dan perusahaan mitranya.', '', '{983d02138e3033901eb61796a176da53.jpg}', 'completed', '2025-12-02 13:35:28+07');


--
-- TOC entry 3947 (class 0 OID 17512)
-- Dependencies: 278
-- Data for Name: research_focus; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.research_focus VALUES (3, 'Information Engineering', 'Focus on information systems, computing, and data processing.', 'bi bi-cpu', NULL, 1);
INSERT INTO public.research_focus VALUES (4, 'Learning Engineering', 'Research in pedagogy, digital learning, and instructional design.', 'bi bi-journal-richtext', NULL, 2);
INSERT INTO public.research_focus VALUES (5, 'Learning Technology', 'Innovation in media learning, LMS, AR/VR, and digital classroom tech.', 'bi bi-laptop', NULL, 3);
INSERT INTO public.research_focus VALUES (6, 'Information Technology', 'Technology development, networking, cybersecurity, and cloud systems.', 'bi bi-cloud-check', NULL, 4);


--
-- TOC entry 3966 (class 0 OID 17632)
-- Dependencies: 297
-- Data for Name: role_menus; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.role_menus VALUES (1, 1, '[]');
INSERT INTO public.role_menus VALUES (1, 2, '["read"]');
INSERT INTO public.role_menus VALUES (1, 3, '[]');
INSERT INTO public.role_menus VALUES (1, 4, '["create", "read", "update", "delete"]');
INSERT INTO public.role_menus VALUES (1, 5, '["create", "read", "update", "delete"]');
INSERT INTO public.role_menus VALUES (1, 6, '[]');
INSERT INTO public.role_menus VALUES (1, 7, '["create", "read", "update", "delete"]');
INSERT INTO public.role_menus VALUES (1, 8, '["create", "read", "update", "delete"]');
INSERT INTO public.role_menus VALUES (1, 9, '[]');
INSERT INTO public.role_menus VALUES (1, 10, '["read", "update"]');
INSERT INTO public.role_menus VALUES (1, 11, '["create", "read", "update", "delete"]');
INSERT INTO public.role_menus VALUES (1, 12, '["create", "read", "update", "delete"]');
INSERT INTO public.role_menus VALUES (1, 13, '["create", "read", "update", "delete"]');
INSERT INTO public.role_menus VALUES (1, 14, '[]');
INSERT INTO public.role_menus VALUES (1, 15, '["create", "read", "update", "delete"]');
INSERT INTO public.role_menus VALUES (1, 16, '["create", "read", "update", "delete"]');
INSERT INTO public.role_menus VALUES (1, 17, '["create", "read", "update", "delete"]');
INSERT INTO public.role_menus VALUES (1, 18, '["create", "read", "update", "delete"]');
INSERT INTO public.role_menus VALUES (1, 19, '["create", "read", "update", "delete"]');
INSERT INTO public.role_menus VALUES (1, 20, '[]');
INSERT INTO public.role_menus VALUES (1, 21, '["create", "read", "update"]');
INSERT INTO public.role_menus VALUES (1, 22, '["read", "update"]');
INSERT INTO public.role_menus VALUES (1, 23, '["read"]');
INSERT INTO public.role_menus VALUES (1, 24, '[]');
INSERT INTO public.role_menus VALUES (1, 25, '[]');
INSERT INTO public.role_menus VALUES (1, 26, '["create", "read", "update", "delete"]');
INSERT INTO public.role_menus VALUES (1, 27, '["read", "update"]');
INSERT INTO public.role_menus VALUES (1, 28, '["create", "read", "update", "delete"]');
INSERT INTO public.role_menus VALUES (1, 29, '["create", "read", "update", "delete"]');
INSERT INTO public.role_menus VALUES (1, 30, '["read", "update"]');
INSERT INTO public.role_menus VALUES (1, 31, '["read"]');
INSERT INTO public.role_menus VALUES (3, 1, '[]');
INSERT INTO public.role_menus VALUES (3, 2, '[]');
INSERT INTO public.role_menus VALUES (3, 3, '[]');
INSERT INTO public.role_menus VALUES (3, 4, '[]');
INSERT INTO public.role_menus VALUES (3, 5, '[]');
INSERT INTO public.role_menus VALUES (3, 6, '[]');
INSERT INTO public.role_menus VALUES (3, 7, '[]');
INSERT INTO public.role_menus VALUES (3, 8, '[]');
INSERT INTO public.role_menus VALUES (3, 9, '[]');
INSERT INTO public.role_menus VALUES (3, 10, '[]');
INSERT INTO public.role_menus VALUES (3, 11, '[]');
INSERT INTO public.role_menus VALUES (3, 12, '[]');
INSERT INTO public.role_menus VALUES (3, 13, '[]');
INSERT INTO public.role_menus VALUES (3, 14, '[]');
INSERT INTO public.role_menus VALUES (3, 15, '[]');
INSERT INTO public.role_menus VALUES (3, 16, '[]');
INSERT INTO public.role_menus VALUES (3, 17, '[]');
INSERT INTO public.role_menus VALUES (3, 18, '[]');
INSERT INTO public.role_menus VALUES (3, 19, '[]');
INSERT INTO public.role_menus VALUES (3, 20, '[]');
INSERT INTO public.role_menus VALUES (3, 21, '[]');
INSERT INTO public.role_menus VALUES (3, 22, '[]');
INSERT INTO public.role_menus VALUES (3, 23, '[]');
INSERT INTO public.role_menus VALUES (3, 24, '[]');
INSERT INTO public.role_menus VALUES (3, 25, '[]');
INSERT INTO public.role_menus VALUES (3, 26, '[]');
INSERT INTO public.role_menus VALUES (3, 27, '[]');
INSERT INTO public.role_menus VALUES (3, 28, '[]');
INSERT INTO public.role_menus VALUES (3, 29, '[]');
INSERT INTO public.role_menus VALUES (3, 30, '[]');
INSERT INTO public.role_menus VALUES (3, 31, '[]');
INSERT INTO public.role_menus VALUES (3, 32, '[]');
INSERT INTO public.role_menus VALUES (2, 1, '[]');
INSERT INTO public.role_menus VALUES (3, 34, '[]');
INSERT INTO public.role_menus VALUES (2, 3, '[]');
INSERT INTO public.role_menus VALUES (3, 33, '["read"]');
INSERT INTO public.role_menus VALUES (3, 35, '["read", "create"]');
INSERT INTO public.role_menus VALUES (3, 36, '["read", "create"]');
INSERT INTO public.role_menus VALUES (3, 37, '["read"]');
INSERT INTO public.role_menus VALUES (3, 38, '["read", "update"]');
INSERT INTO public.role_menus VALUES (1, 32, '[]');
INSERT INTO public.role_menus VALUES (1, 33, '[]');
INSERT INTO public.role_menus VALUES (1, 34, '[]');
INSERT INTO public.role_menus VALUES (1, 35, '[]');
INSERT INTO public.role_menus VALUES (1, 36, '[]');
INSERT INTO public.role_menus VALUES (1, 37, '[]');
INSERT INTO public.role_menus VALUES (1, 38, '[]');
INSERT INTO public.role_menus VALUES (2, 6, '[]');
INSERT INTO public.role_menus VALUES (2, 9, '[]');
INSERT INTO public.role_menus VALUES (2, 14, '[]');
INSERT INTO public.role_menus VALUES (2, 19, '[]');
INSERT INTO public.role_menus VALUES (2, 20, '[]');
INSERT INTO public.role_menus VALUES (2, 21, '[]');
INSERT INTO public.role_menus VALUES (2, 22, '[]');
INSERT INTO public.role_menus VALUES (2, 23, '[]');
INSERT INTO public.role_menus VALUES (2, 24, '[]');
INSERT INTO public.role_menus VALUES (2, 25, '[]');
INSERT INTO public.role_menus VALUES (2, 26, '[]');
INSERT INTO public.role_menus VALUES (2, 27, '[]');
INSERT INTO public.role_menus VALUES (2, 28, '[]');
INSERT INTO public.role_menus VALUES (2, 29, '[]');
INSERT INTO public.role_menus VALUES (2, 30, '[]');
INSERT INTO public.role_menus VALUES (2, 31, '[]');
INSERT INTO public.role_menus VALUES (2, 32, '[]');
INSERT INTO public.role_menus VALUES (2, 33, '[]');
INSERT INTO public.role_menus VALUES (2, 34, '[]');
INSERT INTO public.role_menus VALUES (2, 35, '[]');
INSERT INTO public.role_menus VALUES (2, 36, '[]');
INSERT INTO public.role_menus VALUES (2, 37, '[]');
INSERT INTO public.role_menus VALUES (2, 38, '[]');
INSERT INTO public.role_menus VALUES (2, 2, '["read"]');
INSERT INTO public.role_menus VALUES (2, 4, '["read", "create", "update", "delete"]');
INSERT INTO public.role_menus VALUES (2, 5, '["read", "create", "update", "delete"]');
INSERT INTO public.role_menus VALUES (2, 7, '["read", "create", "update", "delete"]');
INSERT INTO public.role_menus VALUES (2, 8, '["read", "create", "update", "delete"]');
INSERT INTO public.role_menus VALUES (2, 10, '["read", "update"]');
INSERT INTO public.role_menus VALUES (2, 11, '["read", "create", "update", "delete"]');
INSERT INTO public.role_menus VALUES (2, 12, '["read", "create", "update", "delete"]');
INSERT INTO public.role_menus VALUES (2, 13, '["read", "create", "update", "delete"]');
INSERT INTO public.role_menus VALUES (2, 15, '["read", "create", "update", "delete"]');
INSERT INTO public.role_menus VALUES (2, 16, '["read", "create", "update", "delete"]');
INSERT INTO public.role_menus VALUES (2, 17, '["read", "create", "update", "delete"]');
INSERT INTO public.role_menus VALUES (2, 18, '["read", "create", "update", "delete"]');


--
-- TOC entry 3957 (class 0 OID 17561)
-- Dependencies: 288
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.roles VALUES (1, 'Admin');
INSERT INTO public.roles VALUES (2, 'Operator');
INSERT INTO public.roles VALUES (3, 'Mahasiswa');


--
-- TOC entry 3943 (class 0 OID 17490)
-- Dependencies: 274
-- Data for Name: site_settings; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.site_settings VALUES (1, 'InLET Lab', 'ando@polinema.ac.id', '081359889181 ', 'JL. Soekarno Hatta No. 9', '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.495001151587!2d112.61283267483388!3d-7.947689079176015!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e788276980bd1e1%3A0x8961e0763275a1cf!2sJl.%20Soekarno%20Hatta%20No.9%2C%20Jatimulyo%2C%20Kec.%20Lowokwaru%2C%20Kota%20Malang%2C%20Jawa%20Timur%2065141!5e0!3m2!1sid!2sid!4v1765376239367!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>', 'e9a80ac020864421f1369cbcf68c846d.png', NULL, '{"youtube": "www.youtube.com/@bannisatriaandoko2404", "facebook": "", "instagram": ""}', '2025-12-01 05:31:45.189555', NULL);


--
-- TOC entry 3990 (class 0 OID 19965)
-- Dependencies: 324
-- Data for Name: social_links; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.social_links VALUES (5, 'LinkedIn', 'bi bi-linkedin', NULL);
INSERT INTO public.social_links VALUES (6, 'GitHub', 'bi bi-github', NULL);
INSERT INTO public.social_links VALUES (7, 'Google Scholar', 'bi bi-google', NULL);
INSERT INTO public.social_links VALUES (8, 'Sinta', 'bi bi-journal-bookmark-fill', NULL);
INSERT INTO public.social_links VALUES (9, 'Scopus', 'bi bi-eye-fill', NULL);
INSERT INTO public.social_links VALUES (14, 'instagram', 'bi bi-instagram', NULL);


--
-- TOC entry 3991 (class 0 OID 19971)
-- Dependencies: 325
-- Data for Name: social_team; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.social_team VALUES (5, 18, 'https://www.linkedin.com/in/vivin-ayu-b5b2a2297/');
INSERT INTO public.social_team VALUES (7, 18, 'https://scholar.google.com/citations?user=2og3UP8AAAAJ&hl=en');
INSERT INTO public.social_team VALUES (8, 18, 'https://sinta.kemdiktisaintek.go.id/authors/profile/6751441');
INSERT INTO public.social_team VALUES (5, 19, 'https://www.linkedin.com/in/irsyadarif/');
INSERT INTO public.social_team VALUES (7, 19, 'https://scholar.google.com/citations?user=mxVOikUAAAAJ&hl=en');
INSERT INTO public.social_team VALUES (8, 19, 'https://sinta.kemdiktisaintek.go.id/authors/profile/6736311');
INSERT INTO public.social_team VALUES (5, 20, 'https://www.linkedin.com/in/banniandoko?originalSubdomain=id');
INSERT INTO public.social_team VALUES (7, 20, 'https://scholar.google.com/citations?user=jetyPtUAAAAJ&hl=en');
INSERT INTO public.social_team VALUES (8, 20, 'https://sinta.kemdiktisaintek.go.id/authors/profile/6090920/');


--
-- TOC entry 3976 (class 0 OID 17698)
-- Dependencies: 307
-- Data for Name: team_member; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.team_member VALUES (6, 'Usman Nurhasan, S.Kom., MT.', '198609232015041001', '0023098604', 'Peneliti', 'Tenaga Pengajar', 'Sistem Informasi Bisnis', 'usmannurhasan@polinema.ac.id', 'Jl. Soekarno Hatta No.9, Jatimulyo, Kec. Lowokwaru, Kota Malang, Jawa Timur 65141', '037d00f7f9b14bd59e128b51896dc703.jpg', '["Teknologi Informasi"]', '{"S1": {"major": "Sarjana Komputer", "university": null}, "S2": {"major": "Magister Teknik", "university": null}, "S3": {"major": "", "university": null}}', '[]', '{"genap": ["Pemrograman Jaringan", "Kecerdasan Artifisial", "Internet of Things"], "ganjil": ["Pemrograman Mobile", "Audit Sistem Informasi"]}', '2025-12-02 03:21:18.411313', NULL);
INSERT INTO public.team_member VALUES (7, 'Budi Harijanto, ST., M.MKom.', '196201051990031002', '0005016211', 'Peneliti', 'Tenaga Pengajar', 'Sistem Informasi Bisnis', 'budi.hijet@gmail.com', 'Jl. Soekarno Hatta No.9, Jatimulyo, Kec. Lowokwaru, Kota Malang, Jawa Timur 65141', 'a159034dab3e7c98f0599db99c16c03c.jpg', '["Information System"]', '{"S1": {"major": "Sarjana Teknik", "university": null}, "S2": {"major": "Magister Komputer", "university": null}, "S3": {"major": "", "university": null}}', '[]', '{"genap": ["Pengenalan Sistem Informasi", "Komunikasi dan Etika Profesi"], "ganjil": ["Konsep Teknologi Informasi ", "Keselamatan dan Kesehatan Kerja", "Kepemimpinan Bidang IT"]}', '2025-12-02 03:26:40.808118', NULL);
INSERT INTO public.team_member VALUES (8, 'Agung Nugroho Pramudhita, S.T., M.T.', '198902102019031020', '0010028903', 'Peneliti', 'Tenaga Pengajar', 'Sistem Informasi Bisnis', 'agung.pramudhita@polinema.ac.id ', 'Jl. Soekarno Hatta No.9, Jatimulyo, Kec. Lowokwaru, Kota Malang, Jawa Timur 65141', '755835ab9cdbcf4fb0e6f2d1ae481089.jpg', '["Software Engineering", "Education Technology", "Information System", "Internet Of Things", "Technopreneurship"]', '{"S1": {"major": "Teknik Komputer", "university": null}, "S2": {"major": "Telecommunication and Information System", "university": null}, "S3": {"major": "", "university": null}}', '[{"name": "SmallTalk English Speaking Level Test", "publisher": "SmallTalk2Me"}, {"name": "Level 3 Award in Leadership and Management", "publisher": "ILM"}, {"name": "Business Analyst Certification", "publisher": "PT. Inixindo Persada Rekayasa Komputer"}, {"name": "Data Analyst", "publisher": "DQLab"}, {"name": "Certified Programming", "publisher": "Badan Nasional Sertifikasi Profesi (BNSP)"}, {"name": "Microsoft Certified Educator (MCE)", "publisher": "Microsoft"}, {"name": "Mobile Application Development", "publisher": "Badan Nasional Sertifikasi Profesi (BNSP)"}]', '{"genap": ["Pemasaran Digital", "Internet of Things"], "ganjil": ["Perancangan Produk Kreatif", "Manajemen Produk", "Kewirausahaan Berbasis Teknologi", "Kepemimpinan Bidang TI", "Critical Thinking and Problem Solving"]}', '2025-12-02 03:34:40.065419', NULL);
INSERT INTO public.team_member VALUES (9, 'Dr. Indra Dharma Wijaya, ST., M.MT.', '197305102008011010', '0010057308', 'Peneliti', 'Tenaga Pengajar', 'Sistem Informasi Bisnis', 'indra.dharma@polinema.ac.id', 'Jl. Soekarno Hatta No.9, Jatimulyo, Kec. Lowokwaru, Kota Malang, Jawa Timur 65141', '019d27cf7a4ccda8b381a40ef9702890.jpg', '["Sistem Informasi"]', '{"S1": {"major": "Sarjana Teknik", "university": null}, "S2": {"major": "Magister Manajemen Teknologi", "university": null}, "S3": {"major": "", "university": null}}', '[]', '{"genap": ["Audit Sistem Informasi", "Analisis Proses Bisnis", "Analisis dan Perancangan Sistem Informasi"], "ganjil": ["Tata Kelola Teknologi Informasi", "Sistem Informasi Manajemen"]}', '2025-12-02 03:38:41.655647', NULL);
INSERT INTO public.team_member VALUES (18, 'Vivin Ayu Lestari, S.Pd., M.Kom.', '199106212019032020', '0021069102', 'Peneliti', 'Tenaga Pengajar', 'Sistem Informasi Bisnis', 'vivin@polinema.ac.id,', 'Jl. Soekarno Hatta No.9, Jatimulyo, Kec. Lowokwaru, Kota Malang, Jawa Timur 65141', 'ade20c0109fa66707a16291bbacbe7b5.jpg', '["Teknologi Informasi", "Sistem Informasi", "Learning Engineering Technology"]', '{"S1": {"major": "Sarjana Pendidikan", "university": "Universitas Negeri Malang"}, "S2": {"major": "Magister Komputer", "university": "Universitas Brawijaya"}, "S3": {"major": "", "university": ""}}', '[]', '{"genap": ["Praktikum Algoritma dan Struktur Data", "Analisis dan Perancangan Sistem Informasi", "Algoritma dan Struktur Data"], "ganjil": ["Praktikum Dasar Pemrograman", "Dasar Pemrograman", "Critical Thinking dan Problem Solving"]}', '2025-12-04 17:01:49.420514', NULL);
INSERT INTO public.team_member VALUES (19, 'Irsyad Arif Mashudi, S.Kom., M.Kom', '198902012019031009', '0701028901', 'Peneliti', 'Tenaga Pengajar', 'Manajemen Informatika (Kampus Kab.Pamekasan)', 'irsyad.arif@polinema.ac.id', 'Jl. Soekarno Hatta No.9, Jatimulyo, Kec. Lowokwaru, Kota Malang, Jawa Timur 65141', '69b19edabbcabea45c7b718906126fc9.jpg', '["Machine Learning", "Game Development", "Artificial Intellegence"]', '{"S1": {"major": "Teknologi Informasi", "university": "Institut Teknologi Sepuluh Nopember Surabaya "}, "S2": {"major": "Teknologi Informasi", "university": "Institut Teknologi Sepuluh Nopember Surabaya "}, "S3": {"major": "", "university": ""}}', '[{"name": "EF SET English Certificate 72/100 (C2 Proficient)", "year": "(terbit bulan September 2018)", "publisher": "EF SET"}, {"name": "Oracle Database Design & Programming", "year": "", "publisher": ""}]', '{"genap": ["Struktur Data", "Praktikum Struktur Data", "Praktikum Jaringan Komputer", "Praktikum Basis Data", "Jaringan Komputer", "Basis Data"], "ganjil": ["Praktikum Jaringan Komputer", "Praktikum Basis Data Lanjut", "Penjaminan Mutu Perangkat Lunak", "Komunikasi Data", "Jaringan Komputer", "Basis Data Lanjut"]}', '2025-12-04 17:50:59.270498', NULL);
INSERT INTO public.team_member VALUES (20, 'Dr. Eng. Banni Satria Andoko, S. Kom., M.MSI.', '198108092010121002', '0009088107', 'Kepala Laboratorium', 'Tenaga Pengajar', 'Rekayasa Teknologi Informasi', 'ando@polinema.ac.id', 'Jl. Soekarno Hatta No.9, Jatimulyo, Kec. Lowokwaru, Kota Malang, Jawa Timur 65141', 'f5c07b6ee6900b074e70886f44c2cb6a.jpg', '["Technology Enhanced Learning", "Learning Engineering Technology", "Learning Analytics"]', '{"S1": {"major": " Teknik Informatika", "university": "STMIK PPKIA Pradnya Paramita"}, "S2": {"major": "Manajemen Sistem Informasi", "university": "Universitas Gunadarma"}, "S3": {"major": " Information Engineering", "university": "Hiroshima University "}}', '[{"name": "Crystal Report XI", "year": "(terbit bulan Maret 2008)", "publisher": ""}]', '{"genap": ["Pemrograman Berbasis Framework", "Manajemen Korporasi", "Manajemen Informasi", "Analisis Pembelajaran", "Analisis dan Perancangan Sistem Informasi"], "ganjil": ["Rekayasa Perangkat Lunak", "Proposal Tesis ", "Pemrograman dan Pengembangan Perangkat Lunak", "Metodologi Riset Teknologi Informasi", "Metodologi Penelitian", "Analisis Pembelajaran", "Analisis dan Perancangan Sistem Informasi"]}', '2025-12-07 12:36:59.505342', NULL);


--
-- TOC entry 3959 (class 0 OID 17570)
-- Dependencies: 290
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.users VALUES (6, 'sabbaha', '$2y$10$ZnwssNynOtlUPr1BjkwJCOhaEr.P1TP6fog.Xdu2wXxCOYKJd3k.K', 'sabbaha@gmail.com', 'Sabbaha Naufal Erwanda', 3, NULL, NULL, 1);
INSERT INTO public.users VALUES (7, 'asadillah', '$2y$10$ZnwssNynOtlUPr1BjkwJCOhaEr.P1TP6fog.Xdu2wXxCOYKJd3k.K', '24410706002@inle.ac', 'Muhammad Asadillah Ramadhan', 3, NULL, NULL, 2);
INSERT INTO public.users VALUES (9, 'rabia', '$2y$10$ZnwssNynOtlUPr1BjkwJCOhaEr.P1TP6fog.Xdu2wXxCOYKJd3k.K', '24410706004@inle.ac', 'Rabiatul Fitra Aulia', 3, NULL, NULL, 4);
INSERT INTO public.users VALUES (10, 'inda', '$2y$10$ZnwssNynOtlUPr1BjkwJCOhaEr.P1TP6fog.Xdu2wXxCOYKJd3k.K', '24410706005@inle.ac', 'Inda Khoirun Nisak', 3, NULL, NULL, 5);
INSERT INTO public.users VALUES (11, 'rara', '$2y$10$ZnwssNynOtlUPr1BjkwJCOhaEr.P1TP6fog.Xdu2wXxCOYKJd3k.K', '24410706006@inle.ac', 'Rara Deninda Hurianto', 3, NULL, NULL, 6);
INSERT INTO public.users VALUES (15, 'riris', '$2y$10$ZnwssNynOtlUPr1BjkwJCOhaEr.P1TP6fog.Xdu2wXxCOYKJd3k.K', '24410706010@inle.ac', 'Riris Silvia Zahri', 3, NULL, NULL, 10);
INSERT INTO public.users VALUES (17, 'kynanti', '$2y$10$ZnwssNynOtlUPr1BjkwJCOhaEr.P1TP6fog.Xdu2wXxCOYKJd3k.K', '24410706012@inle.ac', 'Sri Kynanti', 3, NULL, NULL, 12);
INSERT INTO public.users VALUES (18, 'daniel', '$2y$10$ZnwssNynOtlUPr1BjkwJCOhaEr.P1TP6fog.Xdu2wXxCOYKJd3k.K', '24410706013@inle.ac', 'Daniel Bagus Christyanto', 3, NULL, NULL, 13);
INSERT INTO public.users VALUES (19, 'fachry', '$2y$10$ZnwssNynOtlUPr1BjkwJCOhaEr.P1TP6fog.Xdu2wXxCOYKJd3k.K', '24410706014@inle.ac', 'Muhammad Fachry Najib', 3, NULL, NULL, 14);
INSERT INTO public.users VALUES (20, 'rio', '$2y$10$ZnwssNynOtlUPr1BjkwJCOhaEr.P1TP6fog.Xdu2wXxCOYKJd3k.K', '24410706015@inle.ac', 'Rio Febriandistra Adi', 3, NULL, NULL, 15);
INSERT INTO public.users VALUES (21, 'megananda', '$2y$10$ZnwssNynOtlUPr1BjkwJCOhaEr.P1TP6fog.Xdu2wXxCOYKJd3k.K', '24410706016@inle.ac', 'Megananda Fadilla Rezeki', 3, NULL, NULL, 16);
INSERT INTO public.users VALUES (22, 'bening', '$2y$10$ZnwssNynOtlUPr1BjkwJCOhaEr.P1TP6fog.Xdu2wXxCOYKJd3k.K', '24410706017@inle.ac', 'Bening Sukmaningrum', 3, NULL, NULL, 17);
INSERT INTO public.users VALUES (24, 'rei', '$2y$10$ZnwssNynOtlUPr1BjkwJCOhaEr.P1TP6fog.Xdu2wXxCOYKJd3k.K', '24410706019@inle.ac', 'Rei Fangky Primandicka', 3, NULL, NULL, 19);
INSERT INTO public.users VALUES (25, 'rifqie', '$2y$10$ZnwssNynOtlUPr1BjkwJCOhaEr.P1TP6fog.Xdu2wXxCOYKJd3k.K', '24410706020@inle.ac', 'Rifqie Muhammad', 3, NULL, NULL, 20);
INSERT INTO public.users VALUES (3, 'Diyah', '$2y$10$7mjrwyaVYbVX3SJYXioCZu8nmqSR9CgLAGILtAu.9Sk6.9vstRAze', 'diyah@inlet.ac', 'diyah', 1, NULL, NULL, NULL);
INSERT INTO public.users VALUES (14, 'rajendra', '$2y$10$ZnwssNynOtlUPr1BjkwJCOhaEr.P1TP6fog.Xdu2wXxCOYKJd3k.K', '24410706009@inle.ac', 'Rajendra Rakha Arya Prabaswara', 3, NULL, NULL, 9);
INSERT INTO public.users VALUES (34, 'diyah', '$2y$10$pm2DkfdVt/f0GPAoxPByUedbDcnvfsivQlG95JRDVLEkLVvTTZCnS', '244107060152@polinema.ac.id', 'diyah', 3, NULL, NULL, NULL);
INSERT INTO public.users VALUES (13, 'sultan', '$2y$10$ZnwssNynOtlUPr1BjkwJCOhaEr.P1TP6fog.Xdu2wXxCOYKJd3k.K', '24410706008@inle.ac', 'Sultan Achamd Qum Masykuro NS - S2', 3, NULL, NULL, 8);
INSERT INTO public.users VALUES (1, 'superadmin', '$2y$10$3RL5GOAtze5dEMd4v9i/p.DmSQzIgZmEOpIRyv.KOZab611eG9mSC', 'superadmin@inle.ac', 'Super Administrator', 1, NULL, NULL, NULL);
INSERT INTO public.users VALUES (2, 'Dimas', '$2y$10$71GaaqwNxB89s/QYJvjAZOyEiUTE484KIC1R2QNjUkbltDZ9nYxTi', 'dimas@inlet.ac', 'dimas', 1, NULL, NULL, NULL);
INSERT INTO public.users VALUES (28, 'operator', '$2y$10$gCKzwT2SPLtTG/NwEz9iyebpgT0lK4SeJAJgHAGShlh5EvrlEzvEe', 'operator@inlet.ac.id', 'Operator Lab', 2, NULL, NULL, NULL);
INSERT INTO public.users VALUES (16, 'alif', '$2y$10$ZnwssNynOtlUPr1BjkwJCOhaEr.P1TP6fog.Xdu2wXxCOYKJd3k.K', '24410706011@inle.ac', 'Muhammad Alif Ananda', 3, NULL, NULL, 11);
INSERT INTO public.users VALUES (5, 'Farras', '$2y$10$uwpc70jpVJDJC4ixtH.VXetL5Nugea8d2q6C/TlwswjbY4ICUthvm', 'farras@inlet.ac', 'farras', 1, NULL, NULL, NULL);
INSERT INTO public.users VALUES (4, 'Nita', '$2y$10$tBDlcrO6gLtpMQQNnoazEOR1pxCOkt4.0YwBz8VP9cAM5IDS2ZB42', 'Nita@inlet.ac', 'nita', 1, NULL, NULL, NULL);
INSERT INTO public.users VALUES (41, 'mfarras', '$2y$10$2aG49Q0fAkfT/HYh4FkFc.OFCi7Yiwa7Z79XJRmb/bWUQSvMWnOaG', '244107060032@polinema.ac.id', 'Muhammad Farras Awaludin Alwi', 3, NULL, NULL, 24);
INSERT INTO public.users VALUES (40, 'primayunita', '$2y$10$eTEAZye27ehifb9xqRVJqekd87aggdiAgVwpC5dXyrUaeN2VI8K4S', 'primayunita@inlet.ac.id', 'primayunita', 3, NULL, NULL, 23);
INSERT INTO public.users VALUES (8, 'amalia', '$2y$10$ZnwssNynOtlUPr1BjkwJCOhaEr.P1TP6fog.Xdu2wXxCOYKJd3k.K', '24410706003@inle.ac', 'Amalia Nuraini', 3, NULL, NULL, 3);


--
-- TOC entry 4021 (class 0 OID 0)
-- Dependencies: 298
-- Name: aboutus_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.aboutus_id_seq', 1, false);


--
-- TOC entry 4022 (class 0 OID 0)
-- Dependencies: 300
-- Name: aboutusimages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.aboutusimages_id_seq', 1, false);


--
-- TOC entry 4023 (class 0 OID 0)
-- Dependencies: 291
-- Name: activity_log_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.activity_log_id_seq', 148, true);


--
-- TOC entry 4024 (class 0 OID 0)
-- Dependencies: 295
-- Name: app_menus_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.app_menus_id_seq', 38, true);


--
-- TOC entry 4025 (class 0 OID 0)
-- Dependencies: 310
-- Name: attendance_logs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.attendance_logs_id_seq', 16, true);


--
-- TOC entry 4026 (class 0 OID 0)
-- Dependencies: 312
-- Name: attendance_permissions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.attendance_permissions_id_seq', 3, true);


--
-- TOC entry 4027 (class 0 OID 0)
-- Dependencies: 308
-- Name: attendance_settings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.attendance_settings_id_seq', 12, true);


--
-- TOC entry 4028 (class 0 OID 0)
-- Dependencies: 279
-- Name: facilities_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.facilities_id_seq', 6, true);


--
-- TOC entry 4029 (class 0 OID 0)
-- Dependencies: 285
-- Name: gallery_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.gallery_id_seq', 32, true);


--
-- TOC entry 4030 (class 0 OID 0)
-- Dependencies: 275
-- Name: hero_slider_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.hero_slider_id_seq', 7, true);


--
-- TOC entry 4031 (class 0 OID 0)
-- Dependencies: 302
-- Name: kategori_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.kategori_id_seq', 12, true);


--
-- TOC entry 4032 (class 0 OID 0)
-- Dependencies: 314
-- Name: mahasiswa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.mahasiswa_id_seq', 24, true);


--
-- TOC entry 4033 (class 0 OID 0)
-- Dependencies: 293
-- Name: news_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.news_id_seq', 1, true);


--
-- TOC entry 4034 (class 0 OID 0)
-- Dependencies: 281
-- Name: partner_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.partner_id_seq', 11, true);


--
-- TOC entry 4035 (class 0 OID 0)
-- Dependencies: 283
-- Name: product_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.product_id_seq', 5, true);


--
-- TOC entry 4036 (class 0 OID 0)
-- Dependencies: 304
-- Name: project_lab_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.project_lab_id_seq', 20, true);


--
-- TOC entry 4037 (class 0 OID 0)
-- Dependencies: 277
-- Name: research_focus_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.research_focus_id_seq', 9, true);


--
-- TOC entry 4038 (class 0 OID 0)
-- Dependencies: 287
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.roles_id_seq', 14, true);


--
-- TOC entry 4039 (class 0 OID 0)
-- Dependencies: 273
-- Name: site_settings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.site_settings_id_seq', 1, true);


--
-- TOC entry 4040 (class 0 OID 0)
-- Dependencies: 323
-- Name: social_links_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.social_links_id_seq', 14, true);


--
-- TOC entry 4041 (class 0 OID 0)
-- Dependencies: 306
-- Name: team_member_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.team_member_id_seq', 23, true);


--
-- TOC entry 4042 (class 0 OID 0)
-- Dependencies: 289
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.users_id_seq', 41, true);


--
-- TOC entry 3744 (class 2606 OID 17658)
-- Name: aboutus aboutus_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.aboutus
    ADD CONSTRAINT aboutus_pkey PRIMARY KEY (id);


--
-- TOC entry 3746 (class 2606 OID 17665)
-- Name: aboutusimages aboutusimages_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.aboutusimages
    ADD CONSTRAINT aboutusimages_pkey PRIMARY KEY (id);


--
-- TOC entry 3736 (class 2606 OID 17596)
-- Name: activity_log activity_log_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.activity_log
    ADD CONSTRAINT activity_log_pkey PRIMARY KEY (id);


--
-- TOC entry 3740 (class 2606 OID 17626)
-- Name: app_menus app_menus_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_menus
    ADD CONSTRAINT app_menus_pkey PRIMARY KEY (id);


--
-- TOC entry 3758 (class 2606 OID 17722)
-- Name: attendance_logs attendance_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.attendance_logs
    ADD CONSTRAINT attendance_logs_pkey PRIMARY KEY (id);


--
-- TOC entry 3760 (class 2606 OID 17738)
-- Name: attendance_permissions attendance_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.attendance_permissions
    ADD CONSTRAINT attendance_permissions_pkey PRIMARY KEY (id);


--
-- TOC entry 3756 (class 2606 OID 17713)
-- Name: attendance_settings attendance_settings_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.attendance_settings
    ADD CONSTRAINT attendance_settings_pkey PRIMARY KEY (id);


--
-- TOC entry 3718 (class 2606 OID 17531)
-- Name: facilities facilities_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.facilities
    ADD CONSTRAINT facilities_pkey PRIMARY KEY (id);


--
-- TOC entry 3724 (class 2606 OID 17559)
-- Name: gallery gallery_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gallery
    ADD CONSTRAINT gallery_pkey PRIMARY KEY (id);


--
-- TOC entry 3714 (class 2606 OID 17510)
-- Name: hero_slider hero_slider_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hero_slider
    ADD CONSTRAINT hero_slider_pkey PRIMARY KEY (id);


--
-- TOC entry 3748 (class 2606 OID 17679)
-- Name: category_project kategori_name_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.category_project
    ADD CONSTRAINT kategori_name_key UNIQUE (name);


--
-- TOC entry 3750 (class 2606 OID 17677)
-- Name: category_project kategori_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.category_project
    ADD CONSTRAINT kategori_pkey PRIMARY KEY (id);


--
-- TOC entry 3762 (class 2606 OID 17764)
-- Name: mahasiswa mahasiswa_nim_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.mahasiswa
    ADD CONSTRAINT mahasiswa_nim_key UNIQUE (nim);


--
-- TOC entry 3764 (class 2606 OID 17760)
-- Name: mahasiswa mahasiswa_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.mahasiswa
    ADD CONSTRAINT mahasiswa_pkey PRIMARY KEY (id);


--
-- TOC entry 3738 (class 2606 OID 17611)
-- Name: news news_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.news
    ADD CONSTRAINT news_pkey PRIMARY KEY (id);


--
-- TOC entry 3720 (class 2606 OID 17540)
-- Name: partner partner_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.partner
    ADD CONSTRAINT partner_pkey PRIMARY KEY (id);


--
-- TOC entry 3722 (class 2606 OID 17549)
-- Name: product product_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product
    ADD CONSTRAINT product_pkey PRIMARY KEY (id);


--
-- TOC entry 3766 (class 2606 OID 19056)
-- Name: project_category_pivot project_category_pivot_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.project_category_pivot
    ADD CONSTRAINT project_category_pivot_pkey PRIMARY KEY (project_id, category_id);


--
-- TOC entry 3752 (class 2606 OID 17691)
-- Name: project_lab project_lab_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.project_lab
    ADD CONSTRAINT project_lab_pkey PRIMARY KEY (id);


--
-- TOC entry 3716 (class 2606 OID 17520)
-- Name: research_focus research_focus_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.research_focus
    ADD CONSTRAINT research_focus_pkey PRIMARY KEY (id);


--
-- TOC entry 3742 (class 2606 OID 17639)
-- Name: role_menus role_menus_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_menus
    ADD CONSTRAINT role_menus_pkey PRIMARY KEY (role_id, menu_id);


--
-- TOC entry 3726 (class 2606 OID 17566)
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- TOC entry 3728 (class 2606 OID 17568)
-- Name: roles roles_role_name_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_role_name_key UNIQUE (role_name);


--
-- TOC entry 3712 (class 2606 OID 17498)
-- Name: site_settings site_settings_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.site_settings
    ADD CONSTRAINT site_settings_pkey PRIMARY KEY (id);


--
-- TOC entry 3769 (class 2606 OID 19970)
-- Name: social_links social_links_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.social_links
    ADD CONSTRAINT social_links_pkey PRIMARY KEY (id);


--
-- TOC entry 3771 (class 2606 OID 19975)
-- Name: social_team social_team_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.social_team
    ADD CONSTRAINT social_team_pkey PRIMARY KEY (id_social_media, id_team);


--
-- TOC entry 3754 (class 2606 OID 17706)
-- Name: team_member team_member_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.team_member
    ADD CONSTRAINT team_member_pkey PRIMARY KEY (id);


--
-- TOC entry 3730 (class 2606 OID 17581)
-- Name: users users_email_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);


--
-- TOC entry 3732 (class 2606 OID 17577)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 3734 (class 2606 OID 17579)
-- Name: users users_username_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_username_key UNIQUE (username);


--
-- TOC entry 3767 (class 1259 OID 19169)
-- Name: idx_mv_activity_date; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_mv_activity_date ON public.mv_dashboard_activity_trend USING btree (log_date);


--
-- TOC entry 3779 (class 2606 OID 17666)
-- Name: aboutusimages aboutusimages_aboutus_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.aboutusimages
    ADD CONSTRAINT aboutusimages_aboutus_id_fkey FOREIGN KEY (aboutus_id) REFERENCES public.aboutus(id) ON DELETE CASCADE;


--
-- TOC entry 3776 (class 2606 OID 17627)
-- Name: app_menus app_menus_parent_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_menus
    ADD CONSTRAINT app_menus_parent_id_fkey FOREIGN KEY (parent_id) REFERENCES public.app_menus(id) ON DELETE SET NULL;


--
-- TOC entry 3774 (class 2606 OID 17597)
-- Name: activity_log fk_activity_log_user; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.activity_log
    ADD CONSTRAINT fk_activity_log_user FOREIGN KEY (id_user) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- TOC entry 3780 (class 2606 OID 27636)
-- Name: attendance_permissions fk_attendance_permissions_mahasiswa; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.attendance_permissions
    ADD CONSTRAINT fk_attendance_permissions_mahasiswa FOREIGN KEY (mahasiswa_id) REFERENCES public.mahasiswa(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3782 (class 2606 OID 19062)
-- Name: project_category_pivot fk_pivot_category; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.project_category_pivot
    ADD CONSTRAINT fk_pivot_category FOREIGN KEY (category_id) REFERENCES public.category_project(id) ON DELETE CASCADE;


--
-- TOC entry 3783 (class 2606 OID 19057)
-- Name: project_category_pivot fk_pivot_project; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.project_category_pivot
    ADD CONSTRAINT fk_pivot_project FOREIGN KEY (project_id) REFERENCES public.project_lab(id) ON DELETE CASCADE;


--
-- TOC entry 3772 (class 2606 OID 26162)
-- Name: users fk_users_mahasiswa_id; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT fk_users_mahasiswa_id FOREIGN KEY (mahasiswa_id) REFERENCES public.mahasiswa(id) ON DELETE SET NULL;


--
-- TOC entry 3775 (class 2606 OID 17612)
-- Name: news news_created_by_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.news
    ADD CONSTRAINT news_created_by_fkey FOREIGN KEY (created_by) REFERENCES public.users(id);


--
-- TOC entry 3781 (class 2606 OID 17744)
-- Name: attendance_permissions permissions_approved_by_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.attendance_permissions
    ADD CONSTRAINT permissions_approved_by_fkey FOREIGN KEY (approved_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- TOC entry 3777 (class 2606 OID 17645)
-- Name: role_menus role_menus_menu_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_menus
    ADD CONSTRAINT role_menus_menu_id_fkey FOREIGN KEY (menu_id) REFERENCES public.app_menus(id) ON DELETE CASCADE;


--
-- TOC entry 3778 (class 2606 OID 17640)
-- Name: role_menus role_menus_role_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_menus
    ADD CONSTRAINT role_menus_role_id_fkey FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- TOC entry 3784 (class 2606 OID 19976)
-- Name: social_team social_team_id_social_media_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.social_team
    ADD CONSTRAINT social_team_id_social_media_fkey FOREIGN KEY (id_social_media) REFERENCES public.social_links(id) ON DELETE CASCADE;


--
-- TOC entry 3785 (class 2606 OID 19981)
-- Name: social_team social_team_id_team_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.social_team
    ADD CONSTRAINT social_team_id_team_fkey FOREIGN KEY (id_team) REFERENCES public.team_member(id) ON DELETE CASCADE;


--
-- TOC entry 3773 (class 2606 OID 17582)
-- Name: users users_id_roles_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_id_roles_fkey FOREIGN KEY (id_roles) REFERENCES public.roles(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 3988 (class 0 OID 19158)
-- Dependencies: 322 3994
-- Name: mv_dashboard_activity_trend; Type: MATERIALIZED VIEW DATA; Schema: public; Owner: -
--

REFRESH MATERIALIZED VIEW public.mv_dashboard_activity_trend;


--
-- TOC entry 3987 (class 0 OID 19072)
-- Dependencies: 321 3994
-- Name: mv_dashboard_project_stats; Type: MATERIALIZED VIEW DATA; Schema: public; Owner: -
--

REFRESH MATERIALIZED VIEW public.mv_dashboard_project_stats;


--
-- TOC entry 3992 (class 0 OID 26261)
-- Dependencies: 327 3994
-- Name: mv_dashboard_student_year; Type: MATERIALIZED VIEW DATA; Schema: public; Owner: -
--

REFRESH MATERIALIZED VIEW public.mv_dashboard_student_year;


--
-- TOC entry 3985 (class 0 OID 18952)
-- Dependencies: 319 3994
-- Name: mv_dashboard_user_distribution; Type: MATERIALIZED VIEW DATA; Schema: public; Owner: -
--

REFRESH MATERIALIZED VIEW public.mv_dashboard_user_distribution;


-- Completed on 2025-12-26 19:37:30

--
-- PostgreSQL database dump complete
--

\unrestrict RzhcQbRHBAFESDgJ0hdiPfi8aBGX7vftMNtEBkXRTuCJO4jTp4hISTZKZnx2aeK


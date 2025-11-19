--
-- PostgreSQL database dump
--

\restrict WNRubiYcBXyaN5Th27fN9OKasoo7jU8NqV3rOSsHBsQNvzAncaI0wFJIOGc9rdA

-- Dumped from database version 15.14
-- Dumped by pg_dump version 15.14

-- Started on 2025-11-20 02:12:46

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

DROP DATABASE IF EXISTS db_lab_inlet;
--
-- TOC entry 3460 (class 1262 OID 18172)
-- Name: db_lab_inlet; Type: DATABASE; Schema: -; Owner: postgres
--

CREATE DATABASE db_lab_inlet WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'English_United States.1252';


ALTER DATABASE db_lab_inlet OWNER TO postgres;

\unrestrict WNRubiYcBXyaN5Th27fN9OKasoo7jU8NqV3rOSsHBsQNvzAncaI0wFJIOGc9rdA
\connect db_lab_inlet
\restrict WNRubiYcBXyaN5Th27fN9OKasoo7jU8NqV3rOSsHBsQNvzAncaI0wFJIOGc9rdA

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

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 214 (class 1259 OID 18173)
-- Name: aboutus; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.aboutus (
    id integer NOT NULL,
    title character varying(100) NOT NULL,
    description text NOT NULL,
    vision text,
    mission text
);


ALTER TABLE public.aboutus OWNER TO postgres;

--
-- TOC entry 215 (class 1259 OID 18178)
-- Name: aboutus_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.aboutus_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.aboutus_id_seq OWNER TO postgres;

--
-- TOC entry 3461 (class 0 OID 0)
-- Dependencies: 215
-- Name: aboutus_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.aboutus_id_seq OWNED BY public.aboutus.id;


--
-- TOC entry 216 (class 1259 OID 18179)
-- Name: aboutusimages; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.aboutusimages (
    id integer NOT NULL,
    aboutus_id integer NOT NULL,
    image_name character varying(225) NOT NULL
);


ALTER TABLE public.aboutusimages OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 18182)
-- Name: aboutusimages_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.aboutusimages_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.aboutusimages_id_seq OWNER TO postgres;

--
-- TOC entry 3462 (class 0 OID 0)
-- Dependencies: 217
-- Name: aboutusimages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.aboutusimages_id_seq OWNED BY public.aboutusimages.id;


--
-- TOC entry 239 (class 1259 OID 18660)
-- Name: activity_log; Type: TABLE; Schema: public; Owner: postgres
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


ALTER TABLE public.activity_log OWNER TO postgres;

--
-- TOC entry 238 (class 1259 OID 18659)
-- Name: activity_log_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.activity_log_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.activity_log_id_seq OWNER TO postgres;

--
-- TOC entry 3463 (class 0 OID 0)
-- Dependencies: 238
-- Name: activity_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.activity_log_id_seq OWNED BY public.activity_log.id;


--
-- TOC entry 218 (class 1259 OID 18183)
-- Name: gallery; Type: TABLE; Schema: public; Owner: postgres
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


ALTER TABLE public.gallery OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 18189)
-- Name: gallery_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.gallery_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.gallery_id_seq OWNER TO postgres;

--
-- TOC entry 3464 (class 0 OID 0)
-- Dependencies: 219
-- Name: gallery_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.gallery_id_seq OWNED BY public.gallery.id;


--
-- TOC entry 220 (class 1259 OID 18190)
-- Name: journal; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.journal (
    id integer NOT NULL,
    journal_title character varying(225) NOT NULL,
    authors character varying(50) NOT NULL,
    year timestamp without time zone,
    indexing character varying(100),
    publication_id integer
);


ALTER TABLE public.journal OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 18193)
-- Name: journal_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.journal_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.journal_id_seq OWNER TO postgres;

--
-- TOC entry 3465 (class 0 OID 0)
-- Dependencies: 221
-- Name: journal_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.journal_id_seq OWNED BY public.journal.id;


--
-- TOC entry 222 (class 1259 OID 18194)
-- Name: news; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.news (
    id integer NOT NULL,
    title character varying(100) NOT NULL,
    content text NOT NULL,
    image_name character varying(100),
    publish_date timestamp without time zone,
    is_publish boolean,
    created_by integer
);


ALTER TABLE public.news OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 18199)
-- Name: news_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.news_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.news_id_seq OWNER TO postgres;

--
-- TOC entry 3466 (class 0 OID 0)
-- Dependencies: 223
-- Name: news_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.news_id_seq OWNED BY public.news.id;


--
-- TOC entry 224 (class 1259 OID 18200)
-- Name: partner; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.partner (
    id integer NOT NULL,
    partner_name character varying(150) NOT NULL,
    partner_logo character varying(225),
    url character varying(225)
);


ALTER TABLE public.partner OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 18205)
-- Name: partner_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.partner_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.partner_id_seq OWNER TO postgres;

--
-- TOC entry 3467 (class 0 OID 0)
-- Dependencies: 225
-- Name: partner_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.partner_id_seq OWNED BY public.partner.id;


--
-- TOC entry 226 (class 1259 OID 18206)
-- Name: product; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.product (
    id integer NOT NULL,
    product_name character varying(100) NOT NULL,
    description text,
    image_name character varying(225),
    release_date timestamp without time zone NOT NULL,
    feature jsonb,
    specification jsonb
);


ALTER TABLE public.product OWNER TO postgres;

--
-- TOC entry 227 (class 1259 OID 18211)
-- Name: product_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.product_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.product_id_seq OWNER TO postgres;

--
-- TOC entry 3468 (class 0 OID 0)
-- Dependencies: 227
-- Name: product_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.product_id_seq OWNED BY public.product.id;


--
-- TOC entry 228 (class 1259 OID 18212)
-- Name: project; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.project (
    id integer NOT NULL,
    project_title character varying(100) NOT NULL,
    description text,
    start_date timestamp without time zone NOT NULL,
    end_date timestamp without time zone NOT NULL,
    image_name character varying(100),
    team_id integer
);


ALTER TABLE public.project OWNER TO postgres;

--
-- TOC entry 229 (class 1259 OID 18217)
-- Name: project_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.project_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.project_id_seq OWNER TO postgres;

--
-- TOC entry 3469 (class 0 OID 0)
-- Dependencies: 229
-- Name: project_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.project_id_seq OWNED BY public.project.id;


--
-- TOC entry 230 (class 1259 OID 18218)
-- Name: publication; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.publication (
    id integer NOT NULL,
    title character varying(100) NOT NULL,
    description text,
    publication_year integer,
    url character varying(225),
    publication_type character varying(100),
    team_id integer
);


ALTER TABLE public.publication OWNER TO postgres;

--
-- TOC entry 231 (class 1259 OID 18223)
-- Name: publication_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.publication_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.publication_id_seq OWNER TO postgres;

--
-- TOC entry 3470 (class 0 OID 0)
-- Dependencies: 231
-- Name: publication_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.publication_id_seq OWNED BY public.publication.id;


--
-- TOC entry 232 (class 1259 OID 18224)
-- Name: roles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.roles (
    id integer NOT NULL,
    role_name character varying(100) NOT NULL
);


ALTER TABLE public.roles OWNER TO postgres;

--
-- TOC entry 233 (class 1259 OID 18227)
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.roles_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.roles_id_seq OWNER TO postgres;

--
-- TOC entry 3471 (class 0 OID 0)
-- Dependencies: 233
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- TOC entry 234 (class 1259 OID 18228)
-- Name: team; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.team (
    id integer NOT NULL,
    name character varying(100) NOT NULL,
    "position" character varying(100) NOT NULL,
    nip integer,
    nidn integer,
    study_program character varying(100) NOT NULL,
    description text,
    social_media jsonb
);


ALTER TABLE public.team OWNER TO postgres;

--
-- TOC entry 235 (class 1259 OID 18233)
-- Name: team_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.team_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.team_id_seq OWNER TO postgres;

--
-- TOC entry 3472 (class 0 OID 0)
-- Dependencies: 235
-- Name: team_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.team_id_seq OWNED BY public.team.id;


--
-- TOC entry 236 (class 1259 OID 18234)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id integer NOT NULL,
    username character varying(100) NOT NULL,
    password character varying(100) NOT NULL,
    email character varying(100),
    full_name character varying(100),
    id_roles integer NOT NULL,
    remember_token character varying(255),
    remember_token_expires_at timestamp without time zone
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 237 (class 1259 OID 18237)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO postgres;

--
-- TOC entry 3473 (class 0 OID 0)
-- Dependencies: 237
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 3233 (class 2604 OID 18238)
-- Name: aboutus id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aboutus ALTER COLUMN id SET DEFAULT nextval('public.aboutus_id_seq'::regclass);


--
-- TOC entry 3234 (class 2604 OID 18239)
-- Name: aboutusimages id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aboutusimages ALTER COLUMN id SET DEFAULT nextval('public.aboutusimages_id_seq'::regclass);


--
-- TOC entry 3245 (class 2604 OID 18663)
-- Name: activity_log id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.activity_log ALTER COLUMN id SET DEFAULT nextval('public.activity_log_id_seq'::regclass);


--
-- TOC entry 3235 (class 2604 OID 18240)
-- Name: gallery id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.gallery ALTER COLUMN id SET DEFAULT nextval('public.gallery_id_seq'::regclass);


--
-- TOC entry 3236 (class 2604 OID 18241)
-- Name: journal id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.journal ALTER COLUMN id SET DEFAULT nextval('public.journal_id_seq'::regclass);


--
-- TOC entry 3237 (class 2604 OID 18242)
-- Name: news id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.news ALTER COLUMN id SET DEFAULT nextval('public.news_id_seq'::regclass);


--
-- TOC entry 3238 (class 2604 OID 18243)
-- Name: partner id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.partner ALTER COLUMN id SET DEFAULT nextval('public.partner_id_seq'::regclass);


--
-- TOC entry 3239 (class 2604 OID 18244)
-- Name: product id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.product ALTER COLUMN id SET DEFAULT nextval('public.product_id_seq'::regclass);


--
-- TOC entry 3240 (class 2604 OID 18245)
-- Name: project id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.project ALTER COLUMN id SET DEFAULT nextval('public.project_id_seq'::regclass);


--
-- TOC entry 3241 (class 2604 OID 18246)
-- Name: publication id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.publication ALTER COLUMN id SET DEFAULT nextval('public.publication_id_seq'::regclass);


--
-- TOC entry 3242 (class 2604 OID 18247)
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- TOC entry 3243 (class 2604 OID 18248)
-- Name: team id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.team ALTER COLUMN id SET DEFAULT nextval('public.team_id_seq'::regclass);


--
-- TOC entry 3244 (class 2604 OID 18249)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 3429 (class 0 OID 18173)
-- Dependencies: 214
-- Data for Name: aboutus; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.aboutus VALUES (1, 'Information and Learning Engineering Technology', 'Laboratorium Sistem Informasi merupakan salah satu laboratorium yang berperan penting dalam mendukung kegiatan praktikum, penelitian, dan pengembangan sistem informasi terapan.
	 Laboratorium ini memiliki fokus riset di bidang Technology-Enhanced Learning, Learning Engineering, dan Learning Analytics. Tidak hanya untuk mendukung kegiatan akademik mahasiswa, 
	 tetapi juga sebagai pusat riset dan inovasi di bidang sistem informasi yang dapat diaplikasikan pada berbagai sektor, mulai dari pendidikan, bisnis, hingga industri..', 'Menjadi laboratorium unggulan yang menghasilkan solusi Sistem Informasi terapan untuk kebutuhan pendidikan, bisnis, dan industri.', '1. Mendukung praktikum & pengembangan aplikasi SI (web, mobile, enterprise).
	 2. Melakukan riset terapan di basis data, proses bisnis, analitik data, dan integrasi SI.
	 3. Berkolaborasi dengan industri/lembaga untuk proyek SI dan layanan konsultasi.
	 4. Selaras dengan mandat pendidikan terapan Polinema & kurikulum prodi TI.');
INSERT INTO public.aboutus VALUES (2, 'About Us', 'Kami adalah tim yang berfokus pada pengembangan sistem informasi.', 'Menjadi lab unggulan di bidang teknologi.', 'Berinovasi, berkolaborasi, dan berkembang bersama.');


--
-- TOC entry 3431 (class 0 OID 18179)
-- Dependencies: 216
-- Data for Name: aboutusimages; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.aboutusimages VALUES (1, 1, 'foto1.webp');
INSERT INTO public.aboutusimages VALUES (2, 1, 'foto2.webp');
INSERT INTO public.aboutusimages VALUES (3, 1, 'foto3.webp');


--
-- TOC entry 3454 (class 0 OID 18660)
-- Dependencies: 239
-- Data for Name: activity_log; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.activity_log VALUES (11, 3, 'Login', NULL, NULL, 'User admin berhasil login ke sistem', NULL, NULL, '2025-11-19 20:51:53.440416');
INSERT INTO public.activity_log VALUES (12, 3, 'Log Out', NULL, NULL, 'User admin logout dari sistem', NULL, NULL, '2025-11-19 20:51:58.684925');
INSERT INTO public.activity_log VALUES (15, 3, 'Login', NULL, NULL, 'User admin berhasil login ke sistem', NULL, NULL, '2025-11-19 20:52:19.419972');
INSERT INTO public.activity_log VALUES (16, 3, 'Update', 'users', 1, 'User Admin berhasil diperbarui', '{"id": 1, "role_name": "Admin P"}', '{"role_name": "Admin"}', '2025-11-19 21:12:34.504327');
INSERT INTO public.activity_log VALUES (17, 3, 'Login', NULL, NULL, 'User admin berhasil login ke sistem', NULL, NULL, '2025-11-20 01:49:00.335777');


--
-- TOC entry 3433 (class 0 OID 18183)
-- Dependencies: 218
-- Data for Name: gallery; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3435 (class 0 OID 18190)
-- Dependencies: 220
-- Data for Name: journal; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3437 (class 0 OID 18194)
-- Dependencies: 222
-- Data for Name: news; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3439 (class 0 OID 18200)
-- Dependencies: 224
-- Data for Name: partner; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.partner VALUES (1, 'anjay mabar', 'partner.jpg', 'https://google.com');


--
-- TOC entry 3441 (class 0 OID 18206)
-- Dependencies: 226
-- Data for Name: product; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3443 (class 0 OID 18212)
-- Dependencies: 228
-- Data for Name: project; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3445 (class 0 OID 18218)
-- Dependencies: 230
-- Data for Name: publication; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3447 (class 0 OID 18224)
-- Dependencies: 232
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.roles VALUES (2, 'Operator');
INSERT INTO public.roles VALUES (3, 'Mahasiswa Magang');
INSERT INTO public.roles VALUES (4, 'Mahasiswa Skripsi');
INSERT INTO public.roles VALUES (1, 'Admin');


--
-- TOC entry 3449 (class 0 OID 18228)
-- Dependencies: 234
-- Data for Name: team; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3451 (class 0 OID 18234)
-- Dependencies: 236
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.users VALUES (3, 'admin', '$2y$10$O4StDqIWuEqNevoBYqer7e.aCNDF4PpGdjqsXKHeRpDGN1lmdlRHW', 'adminlab@jti.polinema.ac.id', 'Admin Lab', 1, NULL, NULL);
INSERT INTO public.users VALUES (9, 'abel cantik', '$2y$10$I3PqtqFlAYRlP5YsPqb1de7sDUTCFsj4oEFNOIsSXB/aRaYX2EkkS', 'amanda@gmail.com', 'Abellll', 1, NULL, NULL);


--
-- TOC entry 3474 (class 0 OID 0)
-- Dependencies: 215
-- Name: aboutus_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.aboutus_id_seq', 2, true);


--
-- TOC entry 3475 (class 0 OID 0)
-- Dependencies: 217
-- Name: aboutusimages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.aboutusimages_id_seq', 3, true);


--
-- TOC entry 3476 (class 0 OID 0)
-- Dependencies: 238
-- Name: activity_log_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.activity_log_id_seq', 17, true);


--
-- TOC entry 3477 (class 0 OID 0)
-- Dependencies: 219
-- Name: gallery_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.gallery_id_seq', 1, false);


--
-- TOC entry 3478 (class 0 OID 0)
-- Dependencies: 221
-- Name: journal_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.journal_id_seq', 1, false);


--
-- TOC entry 3479 (class 0 OID 0)
-- Dependencies: 223
-- Name: news_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.news_id_seq', 1, false);


--
-- TOC entry 3480 (class 0 OID 0)
-- Dependencies: 225
-- Name: partner_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.partner_id_seq', 1, true);


--
-- TOC entry 3481 (class 0 OID 0)
-- Dependencies: 227
-- Name: product_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.product_id_seq', 1, false);


--
-- TOC entry 3482 (class 0 OID 0)
-- Dependencies: 229
-- Name: project_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.project_id_seq', 1, false);


--
-- TOC entry 3483 (class 0 OID 0)
-- Dependencies: 231
-- Name: publication_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.publication_id_seq', 1, false);


--
-- TOC entry 3484 (class 0 OID 0)
-- Dependencies: 233
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.roles_id_seq', 10, true);


--
-- TOC entry 3485 (class 0 OID 0)
-- Dependencies: 235
-- Name: team_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.team_id_seq', 1, false);


--
-- TOC entry 3486 (class 0 OID 0)
-- Dependencies: 237
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 9, true);


--
-- TOC entry 3249 (class 2606 OID 18251)
-- Name: aboutus aboutus_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aboutus
    ADD CONSTRAINT aboutus_pkey PRIMARY KEY (id);


--
-- TOC entry 3251 (class 2606 OID 18253)
-- Name: aboutusimages aboutusimages_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aboutusimages
    ADD CONSTRAINT aboutusimages_pkey PRIMARY KEY (id);


--
-- TOC entry 3279 (class 2606 OID 18668)
-- Name: activity_log activity_log_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.activity_log
    ADD CONSTRAINT activity_log_pkey PRIMARY KEY (id);


--
-- TOC entry 3253 (class 2606 OID 18255)
-- Name: gallery gallery_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.gallery
    ADD CONSTRAINT gallery_pkey PRIMARY KEY (id);


--
-- TOC entry 3255 (class 2606 OID 18257)
-- Name: journal journal_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.journal
    ADD CONSTRAINT journal_pkey PRIMARY KEY (id);


--
-- TOC entry 3257 (class 2606 OID 18259)
-- Name: news news_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.news
    ADD CONSTRAINT news_pkey PRIMARY KEY (id);


--
-- TOC entry 3259 (class 2606 OID 18261)
-- Name: partner partner_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.partner
    ADD CONSTRAINT partner_pkey PRIMARY KEY (id);


--
-- TOC entry 3261 (class 2606 OID 18263)
-- Name: product product_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.product
    ADD CONSTRAINT product_pkey PRIMARY KEY (id);


--
-- TOC entry 3263 (class 2606 OID 18265)
-- Name: project project_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.project
    ADD CONSTRAINT project_pkey PRIMARY KEY (id);


--
-- TOC entry 3265 (class 2606 OID 18267)
-- Name: publication publication_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.publication
    ADD CONSTRAINT publication_pkey PRIMARY KEY (id);


--
-- TOC entry 3267 (class 2606 OID 18269)
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- TOC entry 3271 (class 2606 OID 18271)
-- Name: team team_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.team
    ADD CONSTRAINT team_pkey PRIMARY KEY (id);


--
-- TOC entry 3273 (class 2606 OID 18273)
-- Name: users unique_email; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT unique_email UNIQUE (email);


--
-- TOC entry 3269 (class 2606 OID 18275)
-- Name: roles unique_roles; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT unique_roles UNIQUE (role_name);


--
-- TOC entry 3275 (class 2606 OID 18277)
-- Name: users unique_username; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT unique_username UNIQUE (username);


--
-- TOC entry 3277 (class 2606 OID 18279)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 3280 (class 2606 OID 18280)
-- Name: aboutusimages aboutusimages_aboutus_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aboutusimages
    ADD CONSTRAINT aboutusimages_aboutus_id_fkey FOREIGN KEY (aboutus_id) REFERENCES public.aboutus(id) ON DELETE CASCADE;


--
-- TOC entry 3286 (class 2606 OID 18682)
-- Name: activity_log fk_activity_log_user; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.activity_log
    ADD CONSTRAINT fk_activity_log_user FOREIGN KEY (id_user) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- TOC entry 3281 (class 2606 OID 18285)
-- Name: journal journal_publication_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.journal
    ADD CONSTRAINT journal_publication_id_fkey FOREIGN KEY (publication_id) REFERENCES public.publication(id);


--
-- TOC entry 3282 (class 2606 OID 18290)
-- Name: news news_created_by_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.news
    ADD CONSTRAINT news_created_by_fkey FOREIGN KEY (created_by) REFERENCES public.users(id);


--
-- TOC entry 3283 (class 2606 OID 18295)
-- Name: project project_team_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.project
    ADD CONSTRAINT project_team_id_fkey FOREIGN KEY (team_id) REFERENCES public.team(id);


--
-- TOC entry 3284 (class 2606 OID 18300)
-- Name: publication publication_team_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.publication
    ADD CONSTRAINT publication_team_id_fkey FOREIGN KEY (team_id) REFERENCES public.team(id);


--
-- TOC entry 3285 (class 2606 OID 18654)
-- Name: users users_id_roles_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_id_roles_fkey FOREIGN KEY (id_roles) REFERENCES public.roles(id) ON UPDATE CASCADE ON DELETE RESTRICT;


-- Completed on 2025-11-20 02:12:46

--
-- PostgreSQL database dump complete
--

\unrestrict WNRubiYcBXyaN5Th27fN9OKasoo7jU8NqV3rOSsHBsQNvzAncaI0wFJIOGc9rdA


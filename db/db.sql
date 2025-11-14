--
-- PostgreSQL database dump
--

\restrict IbcuFXgYsJaccbeEdbBgRvJ0Eq59sDaA0lTK6Q956ItmvrT63RBeSSmmBW0StGk

-- Dumped from database version 15.14
-- Dumped by pg_dump version 15.14

-- Started on 2025-11-14 15:32:45

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
-- TOC entry 3448 (class 0 OID 0)
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
-- TOC entry 3449 (class 0 OID 0)
-- Dependencies: 217
-- Name: aboutusimages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.aboutusimages_id_seq OWNED BY public.aboutusimages.id;


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
-- TOC entry 3450 (class 0 OID 0)
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
-- TOC entry 3451 (class 0 OID 0)
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
-- TOC entry 3452 (class 0 OID 0)
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
-- TOC entry 3453 (class 0 OID 0)
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
-- TOC entry 3454 (class 0 OID 0)
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
-- TOC entry 3455 (class 0 OID 0)
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
-- TOC entry 3456 (class 0 OID 0)
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
-- TOC entry 3457 (class 0 OID 0)
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
-- TOC entry 3458 (class 0 OID 0)
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
    fk_roles integer,
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
-- TOC entry 3459 (class 0 OID 0)
-- Dependencies: 237
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 3228 (class 2604 OID 18238)
-- Name: aboutus id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aboutus ALTER COLUMN id SET DEFAULT nextval('public.aboutus_id_seq'::regclass);


--
-- TOC entry 3229 (class 2604 OID 18239)
-- Name: aboutusimages id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aboutusimages ALTER COLUMN id SET DEFAULT nextval('public.aboutusimages_id_seq'::regclass);


--
-- TOC entry 3230 (class 2604 OID 18240)
-- Name: gallery id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.gallery ALTER COLUMN id SET DEFAULT nextval('public.gallery_id_seq'::regclass);


--
-- TOC entry 3231 (class 2604 OID 18241)
-- Name: journal id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.journal ALTER COLUMN id SET DEFAULT nextval('public.journal_id_seq'::regclass);


--
-- TOC entry 3232 (class 2604 OID 18242)
-- Name: news id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.news ALTER COLUMN id SET DEFAULT nextval('public.news_id_seq'::regclass);


--
-- TOC entry 3233 (class 2604 OID 18243)
-- Name: partner id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.partner ALTER COLUMN id SET DEFAULT nextval('public.partner_id_seq'::regclass);


--
-- TOC entry 3234 (class 2604 OID 18244)
-- Name: product id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.product ALTER COLUMN id SET DEFAULT nextval('public.product_id_seq'::regclass);


--
-- TOC entry 3235 (class 2604 OID 18245)
-- Name: project id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.project ALTER COLUMN id SET DEFAULT nextval('public.project_id_seq'::regclass);


--
-- TOC entry 3236 (class 2604 OID 18246)
-- Name: publication id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.publication ALTER COLUMN id SET DEFAULT nextval('public.publication_id_seq'::regclass);


--
-- TOC entry 3237 (class 2604 OID 18247)
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- TOC entry 3238 (class 2604 OID 18248)
-- Name: team id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.team ALTER COLUMN id SET DEFAULT nextval('public.team_id_seq'::regclass);


--
-- TOC entry 3239 (class 2604 OID 18249)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 3419 (class 0 OID 18173)
-- Dependencies: 214
-- Data for Name: aboutus; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.aboutus (id, title, description, vision, mission) FROM stdin;
1	Information and Learning Engineering Technology	Laboratorium Sistem Informasi merupakan salah satu laboratorium yang berperan penting dalam mendukung kegiatan praktikum, penelitian, dan pengembangan sistem informasi terapan.\n\t Laboratorium ini memiliki fokus riset di bidang Technology-Enhanced Learning, Learning Engineering, dan Learning Analytics. Tidak hanya untuk mendukung kegiatan akademik mahasiswa, \n\t tetapi juga sebagai pusat riset dan inovasi di bidang sistem informasi yang dapat diaplikasikan pada berbagai sektor, mulai dari pendidikan, bisnis, hingga industri..	Menjadi laboratorium unggulan yang menghasilkan solusi Sistem Informasi terapan untuk kebutuhan pendidikan, bisnis, dan industri.	1. Mendukung praktikum & pengembangan aplikasi SI (web, mobile, enterprise).\n\t 2. Melakukan riset terapan di basis data, proses bisnis, analitik data, dan integrasi SI.\n\t 3. Berkolaborasi dengan industri/lembaga untuk proyek SI dan layanan konsultasi.\n\t 4. Selaras dengan mandat pendidikan terapan Polinema & kurikulum prodi TI.
2	About Us	Kami adalah tim yang berfokus pada pengembangan sistem informasi.	Menjadi lab unggulan di bidang teknologi.	Berinovasi, berkolaborasi, dan berkembang bersama.
\.


--
-- TOC entry 3421 (class 0 OID 18179)
-- Dependencies: 216
-- Data for Name: aboutusimages; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.aboutusimages (id, aboutus_id, image_name) FROM stdin;
1	1	foto1.webp
2	1	foto2.webp
3	1	foto3.webp
\.


--
-- TOC entry 3423 (class 0 OID 18183)
-- Dependencies: 218
-- Data for Name: gallery; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.gallery (id, title, description, image_name, upload_date, type, url) FROM stdin;
\.


--
-- TOC entry 3425 (class 0 OID 18190)
-- Dependencies: 220
-- Data for Name: journal; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.journal (id, journal_title, authors, year, indexing, publication_id) FROM stdin;
\.


--
-- TOC entry 3427 (class 0 OID 18194)
-- Dependencies: 222
-- Data for Name: news; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.news (id, title, content, image_name, publish_date, is_publish, created_by) FROM stdin;
\.


--
-- TOC entry 3429 (class 0 OID 18200)
-- Dependencies: 224
-- Data for Name: partner; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.partner (id, partner_name, partner_logo, url) FROM stdin;
\.


--
-- TOC entry 3431 (class 0 OID 18206)
-- Dependencies: 226
-- Data for Name: product; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.product (id, product_name, description, image_name, release_date, feature, specification) FROM stdin;
\.


--
-- TOC entry 3433 (class 0 OID 18212)
-- Dependencies: 228
-- Data for Name: project; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.project (id, project_title, description, start_date, end_date, image_name, team_id) FROM stdin;
\.


--
-- TOC entry 3435 (class 0 OID 18218)
-- Dependencies: 230
-- Data for Name: publication; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.publication (id, title, description, publication_year, url, publication_type, team_id) FROM stdin;
\.


--
-- TOC entry 3437 (class 0 OID 18224)
-- Dependencies: 232
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.roles (id, role_name) FROM stdin;
2	Operator
3	Mahasiswa Magang
4	Mahasiswa Skripsi
1	Admin
\.


--
-- TOC entry 3439 (class 0 OID 18228)
-- Dependencies: 234
-- Data for Name: team; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.team (id, name, "position", nip, nidn, study_program, description, social_media) FROM stdin;
\.


--
-- TOC entry 3441 (class 0 OID 18234)
-- Dependencies: 236
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, username, password, email, full_name, fk_roles, remember_token, remember_token_expires_at) FROM stdin;
1	admin	$2y$10$qq3/ozM5Avmj51zLoATTE.mgtr0MT87iyc0LnODx1ZQhp2FLmPhIS	admin@labtech.ac.id	Admin Lab	1	5b33fdd0b1211cd91d908b492f2ac64290cc448040bdb5fbfeaeedd916ab96cb	2025-12-14 07:04:54
\.


--
-- TOC entry 3460 (class 0 OID 0)
-- Dependencies: 215
-- Name: aboutus_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.aboutus_id_seq', 2, true);


--
-- TOC entry 3461 (class 0 OID 0)
-- Dependencies: 217
-- Name: aboutusimages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.aboutusimages_id_seq', 3, true);


--
-- TOC entry 3462 (class 0 OID 0)
-- Dependencies: 219
-- Name: gallery_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.gallery_id_seq', 1, false);


--
-- TOC entry 3463 (class 0 OID 0)
-- Dependencies: 221
-- Name: journal_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.journal_id_seq', 1, false);


--
-- TOC entry 3464 (class 0 OID 0)
-- Dependencies: 223
-- Name: news_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.news_id_seq', 1, false);


--
-- TOC entry 3465 (class 0 OID 0)
-- Dependencies: 225
-- Name: partner_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.partner_id_seq', 1, false);


--
-- TOC entry 3466 (class 0 OID 0)
-- Dependencies: 227
-- Name: product_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.product_id_seq', 1, false);


--
-- TOC entry 3467 (class 0 OID 0)
-- Dependencies: 229
-- Name: project_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.project_id_seq', 1, false);


--
-- TOC entry 3468 (class 0 OID 0)
-- Dependencies: 231
-- Name: publication_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.publication_id_seq', 1, false);


--
-- TOC entry 3469 (class 0 OID 0)
-- Dependencies: 233
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.roles_id_seq', 5, true);


--
-- TOC entry 3470 (class 0 OID 0)
-- Dependencies: 235
-- Name: team_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.team_id_seq', 1, false);


--
-- TOC entry 3471 (class 0 OID 0)
-- Dependencies: 237
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 1, true);


--
-- TOC entry 3242 (class 2606 OID 18251)
-- Name: aboutus aboutus_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aboutus
    ADD CONSTRAINT aboutus_pkey PRIMARY KEY (id);


--
-- TOC entry 3244 (class 2606 OID 18253)
-- Name: aboutusimages aboutusimages_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aboutusimages
    ADD CONSTRAINT aboutusimages_pkey PRIMARY KEY (id);


--
-- TOC entry 3246 (class 2606 OID 18255)
-- Name: gallery gallery_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.gallery
    ADD CONSTRAINT gallery_pkey PRIMARY KEY (id);


--
-- TOC entry 3248 (class 2606 OID 18257)
-- Name: journal journal_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.journal
    ADD CONSTRAINT journal_pkey PRIMARY KEY (id);


--
-- TOC entry 3250 (class 2606 OID 18259)
-- Name: news news_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.news
    ADD CONSTRAINT news_pkey PRIMARY KEY (id);


--
-- TOC entry 3252 (class 2606 OID 18261)
-- Name: partner partner_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.partner
    ADD CONSTRAINT partner_pkey PRIMARY KEY (id);


--
-- TOC entry 3254 (class 2606 OID 18263)
-- Name: product product_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.product
    ADD CONSTRAINT product_pkey PRIMARY KEY (id);


--
-- TOC entry 3256 (class 2606 OID 18265)
-- Name: project project_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.project
    ADD CONSTRAINT project_pkey PRIMARY KEY (id);


--
-- TOC entry 3258 (class 2606 OID 18267)
-- Name: publication publication_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.publication
    ADD CONSTRAINT publication_pkey PRIMARY KEY (id);


--
-- TOC entry 3260 (class 2606 OID 18269)
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- TOC entry 3264 (class 2606 OID 18271)
-- Name: team team_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.team
    ADD CONSTRAINT team_pkey PRIMARY KEY (id);


--
-- TOC entry 3266 (class 2606 OID 18273)
-- Name: users unique_email; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT unique_email UNIQUE (email);


--
-- TOC entry 3262 (class 2606 OID 18275)
-- Name: roles unique_roles; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT unique_roles UNIQUE (role_name);


--
-- TOC entry 3268 (class 2606 OID 18277)
-- Name: users unique_username; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT unique_username UNIQUE (username);


--
-- TOC entry 3270 (class 2606 OID 18279)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 3271 (class 2606 OID 18280)
-- Name: aboutusimages aboutusimages_aboutus_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aboutusimages
    ADD CONSTRAINT aboutusimages_aboutus_id_fkey FOREIGN KEY (aboutus_id) REFERENCES public.aboutus(id) ON DELETE CASCADE;


--
-- TOC entry 3272 (class 2606 OID 18285)
-- Name: journal journal_publication_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.journal
    ADD CONSTRAINT journal_publication_id_fkey FOREIGN KEY (publication_id) REFERENCES public.publication(id);


--
-- TOC entry 3273 (class 2606 OID 18290)
-- Name: news news_created_by_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.news
    ADD CONSTRAINT news_created_by_fkey FOREIGN KEY (created_by) REFERENCES public.users(id);


--
-- TOC entry 3274 (class 2606 OID 18295)
-- Name: project project_team_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.project
    ADD CONSTRAINT project_team_id_fkey FOREIGN KEY (team_id) REFERENCES public.team(id);


--
-- TOC entry 3275 (class 2606 OID 18300)
-- Name: publication publication_team_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.publication
    ADD CONSTRAINT publication_team_id_fkey FOREIGN KEY (team_id) REFERENCES public.team(id);


--
-- TOC entry 3276 (class 2606 OID 18305)
-- Name: users users_fk_roles_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_fk_roles_fkey FOREIGN KEY (fk_roles) REFERENCES public.roles(id);


-- Completed on 2025-11-14 15:32:45

--
-- PostgreSQL database dump complete
--

\unrestrict IbcuFXgYsJaccbeEdbBgRvJ0Eq59sDaA0lTK6Q956ItmvrT63RBeSSmmBW0StGk


--
-- PostgreSQL database dump
--

\restrict CvACereIXrhSeWfdaVJeAmUONbZcPIifvrSFDdDST1jOKZ1nDdbBiR3THBX5u8R

-- Dumped from database version 16.13 (Ubuntu 16.13-0ubuntu0.24.04.1)
-- Dumped by pg_dump version 16.13 (Ubuntu 16.13-0ubuntu0.24.04.1)

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
-- Name: branches; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.branches (
    id bigint NOT NULL,
    code character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: branches_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.branches_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: branches_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.branches_id_seq OWNED BY public.branches.id;


--
-- Name: cache; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


--
-- Name: contracts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.contracts (
    id bigint NOT NULL,
    employee_id bigint NOT NULL,
    type character varying(255) NOT NULL,
    start_date date NOT NULL,
    end_date date,
    salary numeric(14,2) NOT NULL,
    currency character varying(3) NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: contracts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.contracts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: contracts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.contracts_id_seq OWNED BY public.contracts.id;


--
-- Name: departments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.departments (
    id bigint NOT NULL,
    branch_id bigint NOT NULL,
    parent_id bigint,
    name character varying(255) NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: departments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.departments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: departments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.departments_id_seq OWNED BY public.departments.id;


--
-- Name: employee_branches; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.employee_branches (
    id bigint NOT NULL,
    employee_id bigint NOT NULL,
    branch_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: employee_branches_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.employee_branches_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: employee_branches_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.employee_branches_id_seq OWNED BY public.employee_branches.id;


--
-- Name: employee_departments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.employee_departments (
    id bigint NOT NULL,
    employee_id bigint NOT NULL,
    department_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: employee_departments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.employee_departments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: employee_departments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.employee_departments_id_seq OWNED BY public.employee_departments.id;


--
-- Name: employees; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.employees (
    id bigint NOT NULL,
    employee_code character varying(255) NOT NULL,
    full_name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    phone character varying(255),
    password character varying(255) NOT NULL,
    branch_id bigint,
    department_id bigint,
    job_title_id bigint,
    hire_date date,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: employees_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.employees_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: employees_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.employees_id_seq OWNED BY public.employees.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


--
-- Name: job_titles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.job_titles (
    id bigint NOT NULL,
    department_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: job_titles_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.job_titles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: job_titles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.job_titles_id_seq OWNED BY public.job_titles.id;


--
-- Name: jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: model_has_permissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.model_has_permissions (
    permission_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);


--
-- Name: model_has_roles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.model_has_roles (
    role_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


--
-- Name: permissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.permissions (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.permissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;


--
-- Name: personal_access_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name text NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- Name: role_has_permissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.role_has_permissions (
    permission_id bigint NOT NULL,
    role_id bigint NOT NULL
);


--
-- Name: roles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.roles (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


--
-- Name: branches id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.branches ALTER COLUMN id SET DEFAULT nextval('public.branches_id_seq'::regclass);


--
-- Name: contracts id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.contracts ALTER COLUMN id SET DEFAULT nextval('public.contracts_id_seq'::regclass);


--
-- Name: departments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.departments ALTER COLUMN id SET DEFAULT nextval('public.departments_id_seq'::regclass);


--
-- Name: employee_branches id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employee_branches ALTER COLUMN id SET DEFAULT nextval('public.employee_branches_id_seq'::regclass);


--
-- Name: employee_departments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employee_departments ALTER COLUMN id SET DEFAULT nextval('public.employee_departments_id_seq'::regclass);


--
-- Name: employees id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employees ALTER COLUMN id SET DEFAULT nextval('public.employees_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: job_titles id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.job_titles ALTER COLUMN id SET DEFAULT nextval('public.job_titles_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: permissions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);


--
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- Data for Name: branches; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.branches (id, code, name, status, created_at, updated_at) FROM stdin;
13	HQ	Headquarters	active	2026-03-16 23:46:01	2026-03-16 23:46:01
14	CAI	Cairo	active	2026-03-16 23:46:01	2026-03-16 23:46:01
15	ALX	Alexandria	active	2026-03-16 23:46:01	2026-03-16 23:46:01
16	GIZ	Giza	active	2026-03-16 23:46:01	2026-03-16 23:46:01
17	MNS	Mansoura	inactive	2026-03-16 23:46:01	2026-03-16 23:46:01
18	POSTMAN-BR	Postman Branch XX Updated	inactive	2026-03-16 23:48:34	2026-03-16 23:48:55
\.


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.cache (key, value, expiration) FROM stdin;
laravel-cache-spatie.permission.cache	a:3:{s:5:"alias";a:4:{s:1:"a";s:2:"id";s:1:"b";s:4:"name";s:1:"c";s:10:"guard_name";s:1:"r";s:5:"roles";}s:11:"permissions";a:20:{i:0;a:4:{s:1:"a";i:521;s:1:"b";s:10:"roles.view";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:38;i:1;i:42;}}i:1;a:4:{s:1:"a";i:522;s:1:"b";s:12:"roles.create";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:38;i:1;i:42;}}i:2;a:4:{s:1:"a";i:523;s:1:"b";s:16:"permissions.view";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:38;i:1;i:42;}}i:3;a:4:{s:1:"a";i:524;s:1:"b";s:22:"employees.assign_roles";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:38;i:1;i:42;}}i:4;a:4:{s:1:"a";i:525;s:1:"b";s:22:"employees.manage_scope";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:38;i:1;i:42;}}i:5;a:4:{s:1:"a";i:526;s:1:"b";s:14:"employees.view";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:38;i:1;i:39;}}i:6;a:4:{s:1:"a";i:527;s:1:"b";s:16:"employees.create";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:38;}}i:7;a:4:{s:1:"a";i:528;s:1:"b";s:16:"employees.update";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:38;}}i:8;a:4:{s:1:"a";i:529;s:1:"b";s:14:"contracts.view";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:38;}}i:9;a:4:{s:1:"a";i:530;s:1:"b";s:16:"contracts.create";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:38;}}i:10;a:4:{s:1:"a";i:531;s:1:"b";s:16:"contracts.update";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:38;}}i:11;a:4:{s:1:"a";i:532;s:1:"b";s:13:"branches.view";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:38;i:1;i:42;}}i:12;a:4:{s:1:"a";i:533;s:1:"b";s:15:"branches.create";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:38;i:1;i:42;}}i:13;a:4:{s:1:"a";i:534;s:1:"b";s:15:"branches.update";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:38;i:1;i:42;}}i:14;a:4:{s:1:"a";i:535;s:1:"b";s:16:"departments.view";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:38;i:1;i:42;}}i:15;a:4:{s:1:"a";i:536;s:1:"b";s:18:"departments.create";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:38;i:1;i:42;}}i:16;a:4:{s:1:"a";i:537;s:1:"b";s:18:"departments.update";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:38;i:1;i:42;}}i:17;a:4:{s:1:"a";i:538;s:1:"b";s:15:"job_titles.view";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:38;i:1;i:42;}}i:18;a:4:{s:1:"a";i:539;s:1:"b";s:17:"job_titles.create";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:38;i:1;i:42;}}i:19;a:4:{s:1:"a";i:540;s:1:"b";s:17:"job_titles.update";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:38;i:1;i:42;}}}s:5:"roles";a:3:{i:0;a:3:{s:1:"a";i:38;s:1:"b";s:11:"super_admin";s:1:"c";s:3:"web";}i:1;a:3:{s:1:"a";i:42;s:1:"b";s:18:"organization_admin";s:1:"c";s:3:"web";}i:2;a:3:{s:1:"a";i:39;s:1:"b";s:8:"employee";s:1:"c";s:3:"web";}}}	1773791253
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: contracts; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.contracts (id, employee_id, type, start_date, end_date, salary, currency, status, created_at, updated_at) FROM stdin;
4	40	full_time	2026-03-01	\N	13500.00	EGP	terminated	2026-03-16 23:54:42	2026-03-16 23:55:26
\.


--
-- Data for Name: departments; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.departments (id, branch_id, parent_id, name, status, created_at, updated_at) FROM stdin;
11	13	\N	Head Office	active	2026-03-16 23:46:01	2026-03-16 23:46:01
12	13	11	Operations	active	2026-03-16 23:46:01	2026-03-16 23:46:01
13	13	12	Logistics	active	2026-03-16 23:46:01	2026-03-16 23:46:01
14	13	12	Facilities	active	2026-03-16 23:46:01	2026-03-16 23:46:01
15	14	\N	Engineering	active	2026-03-16 23:46:01	2026-03-16 23:46:01
16	14	\N	Finance	active	2026-03-16 23:46:01	2026-03-16 23:46:01
17	14	\N	Human Resources	active	2026-03-16 23:46:01	2026-03-16 23:46:01
18	15	\N	Customer Support	active	2026-03-16 23:46:01	2026-03-16 23:46:01
19	15	\N	Sales	active	2026-03-16 23:46:01	2026-03-16 23:46:01
20	16	\N	Procurement	active	2026-03-16 23:46:01	2026-03-16 23:46:01
21	17	\N	Field Services	inactive	2026-03-16 23:46:01	2026-03-16 23:46:01
22	13	\N	Postman DepartmentXX Updated	inactive	2026-03-16 23:50:37	2026-03-16 23:51:12
\.


--
-- Data for Name: employee_branches; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.employee_branches (id, employee_id, branch_id, created_at, updated_at) FROM stdin;
5	40	13	2026-03-16 23:47:33	2026-03-16 23:47:33
6	40	14	2026-03-16 23:47:33	2026-03-16 23:47:33
\.


--
-- Data for Name: employee_departments; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.employee_departments (id, employee_id, department_id, created_at, updated_at) FROM stdin;
3	40	15	2026-03-16 23:48:03	2026-03-16 23:48:03
4	40	16	2026-03-16 23:48:03	2026-03-16 23:48:03
\.


--
-- Data for Name: employees; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.employees (id, employee_code, full_name, email, phone, password, branch_id, department_id, job_title_id, hire_date, status, remember_token, created_at, updated_at) FROM stdin;
39	EMP-000001	Super Admin	admin@raf.local	\N	$2y$12$yFjpDBVG.qROYBGtUh8p6O8F/2A4ufT1CCYv3RMATtjIoazeTGR7a	\N	\N	\N	\N	active	\N	2026-03-16 23:40:33	2026-03-16 23:40:33
40	EMP-503777	Kailyn Cole MD	toni26@example.org	+1 (949) 518-3854	$2y$12$umg83MrC8tDZUIFRKO3pK.SVwKFzdg3upANHKr0neqZIr2sMxb76G	\N	\N	\N	1974-01-24	active	\N	2026-03-16 23:40:33	2026-03-16 23:40:33
41	EMP-252049	Emory Gorczany PhD	jesse.howell@example.net	470-743-7291	$2y$12$umg83MrC8tDZUIFRKO3pK.SVwKFzdg3upANHKr0neqZIr2sMxb76G	\N	\N	\N	1988-06-06	active	\N	2026-03-16 23:40:33	2026-03-16 23:40:33
42	EMP-458217	Cleta Haag	louvenia.dare@example.com	1-430-502-6782	$2y$12$1cjHDnqN3po/gItThNW2SO3klcXHre7NOZZH.GI69P9Uz4Jk0SKUa	\N	\N	\N	1971-11-29	active	\N	2026-03-16 23:46:01	2026-03-16 23:46:01
43	EMP-085747	Justyn Berge Jr.	lucinda.feeney@example.com	1-430-543-7935	$2y$12$1cjHDnqN3po/gItThNW2SO3klcXHre7NOZZH.GI69P9Uz4Jk0SKUa	\N	\N	\N	1980-04-27	active	\N	2026-03-16 23:46:01	2026-03-16 23:46:01
44	EMP-140951	Dr. Jaclyn Hill II	romaine.quitzon@example.org	+1 (838) 998-3951	$2y$12$v/yHOSHGIxK8t05NKl2RneYF8CtrT4dWB7hO/FYL8tgLJl9qRvI2a	\N	\N	\N	1993-11-02	active	\N	2026-03-16 23:46:22	2026-03-16 23:46:22
45	EMP-900778	Miss Petra Hane DDS	bonita.pagac@example.org	+1-281-533-9448	$2y$12$v/yHOSHGIxK8t05NKl2RneYF8CtrT4dWB7hO/FYL8tgLJl9qRvI2a	\N	\N	\N	2012-03-02	active	\N	2026-03-16 23:46:22	2026-03-16 23:46:22
46	EMP-900779	Postman Employee	postman.employee@raf.local	01000000000	$2y$12$yr.nQwVRuQlrr5W1cWpIIe4/D19mSC9tMjoIurhqxHWaKLNi1KM1u	13	22	5	2026-03-01	active	\N	2026-03-16 23:53:24	2026-03-16 23:53:24
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: job_titles; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.job_titles (id, department_id, name, status, created_at, updated_at) FROM stdin;
6	22	Postman Job Title2	active	2026-03-16 23:51:54	2026-03-16 23:51:54
7	22	Postman Job Title3	active	2026-03-16 23:51:57	2026-03-16 23:51:57
5	22	Postman Job TitleXX Updated	inactive	2026-03-16 23:51:52	2026-03-16 23:52:26
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2026_03_16_204052_create_personal_access_tokens_table	1
5	2026_03_16_204156_create_permission_tables	1
6	2026_03_16_214000_fix_sessions_user_id_column	1
7	2026_03_16_220000_create_employee_scope_tables	1
8	2026_03_17_090000_create_hr_core_tables	1
9	2026_03_17_090100_add_hr_core_foreign_keys_to_employees	1
10	2026_03_17_110000_create_contracts_table	1
\.


--
-- Data for Name: model_has_permissions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.model_has_permissions (permission_id, model_type, model_id) FROM stdin;
\.


--
-- Data for Name: model_has_roles; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.model_has_roles (role_id, model_type, model_id) FROM stdin;
39	App\\Models\\Employee	40
38	App\\Models\\Employee	39
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: permissions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.permissions (id, name, guard_name, created_at, updated_at) FROM stdin;
521	roles.view	web	2026-03-16 23:40:33	2026-03-16 23:40:33
522	roles.create	web	2026-03-16 23:40:33	2026-03-16 23:40:33
523	permissions.view	web	2026-03-16 23:40:33	2026-03-16 23:40:33
524	employees.assign_roles	web	2026-03-16 23:40:33	2026-03-16 23:40:33
525	employees.manage_scope	web	2026-03-16 23:40:33	2026-03-16 23:40:33
526	employees.view	web	2026-03-16 23:40:33	2026-03-16 23:40:33
527	employees.create	web	2026-03-16 23:40:33	2026-03-16 23:40:33
528	employees.update	web	2026-03-16 23:40:33	2026-03-16 23:40:33
529	contracts.view	web	2026-03-16 23:40:33	2026-03-16 23:40:33
530	contracts.create	web	2026-03-16 23:40:33	2026-03-16 23:40:33
531	contracts.update	web	2026-03-16 23:40:33	2026-03-16 23:40:33
532	branches.view	web	2026-03-16 23:40:33	2026-03-16 23:40:33
533	branches.create	web	2026-03-16 23:40:33	2026-03-16 23:40:33
534	branches.update	web	2026-03-16 23:40:33	2026-03-16 23:40:33
535	departments.view	web	2026-03-16 23:40:33	2026-03-16 23:40:33
536	departments.create	web	2026-03-16 23:40:33	2026-03-16 23:40:33
537	departments.update	web	2026-03-16 23:40:33	2026-03-16 23:40:33
538	job_titles.view	web	2026-03-16 23:40:33	2026-03-16 23:40:33
539	job_titles.create	web	2026-03-16 23:40:33	2026-03-16 23:40:33
540	job_titles.update	web	2026-03-16 23:40:33	2026-03-16 23:40:33
\.


--
-- Data for Name: personal_access_tokens; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.personal_access_tokens (id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, expires_at, created_at, updated_at) FROM stdin;
2	App\\Models\\Employee	39	{{device_name}}	6972ee84101078a595cbb94ed1315eeb08dc0b044f2451fe4e6ca434415e7c62	["*"]	2026-03-16 23:55:26	\N	2026-03-16 23:40:37	2026-03-16 23:55:26
\.


--
-- Data for Name: role_has_permissions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.role_has_permissions (permission_id, role_id) FROM stdin;
521	38
522	38
523	38
524	38
525	38
526	38
527	38
528	38
529	38
530	38
531	38
532	38
533	38
534	38
535	38
536	38
537	38
538	38
539	38
540	38
526	39
521	42
522	42
523	42
524	42
525	42
532	42
533	42
534	42
535	42
536	42
537	42
538	42
539	42
540	42
\.


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.roles (id, name, guard_name, created_at, updated_at) FROM stdin;
38	super_admin	web	2026-03-16 23:40:33	2026-03-16 23:40:33
39	employee	web	2026-03-16 23:40:33	2026-03-16 23:40:33
40	hr_manager	web	2026-03-16 23:40:33	2026-03-16 23:40:33
41	branch_manager	web	2026-03-16 23:40:33	2026-03-16 23:40:33
42	organization_admin	web	2026-03-16 23:40:33	2026-03-16 23:40:33
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
D4VPzf7tEZzWRoi0OAGTTQCggbgNi5ubsKLYBmvs	\N	127.0.0.1	Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36	YTozOntzOjY6Il90b2tlbiI7czo0MDoiRnZNNXNVVTBNTFpWdHBiWThza1V5OXE4bTVwUVJ2cWlxeEpLRldHcSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vcmFmLWhyaXMudGVzdCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==	1773705375
\.


--
-- Name: branches_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.branches_id_seq', 18, true);


--
-- Name: contracts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.contracts_id_seq', 4, true);


--
-- Name: departments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.departments_id_seq', 22, true);


--
-- Name: employee_branches_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.employee_branches_id_seq', 6, true);


--
-- Name: employee_departments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.employee_departments_id_seq', 4, true);


--
-- Name: employees_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.employees_id_seq', 46, true);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: job_titles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.job_titles_id_seq', 7, true);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.migrations_id_seq', 10, true);


--
-- Name: permissions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.permissions_id_seq', 540, true);


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 2, true);


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.roles_id_seq', 42, true);


--
-- Name: branches branches_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.branches
    ADD CONSTRAINT branches_code_unique UNIQUE (code);


--
-- Name: branches branches_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.branches
    ADD CONSTRAINT branches_pkey PRIMARY KEY (id);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: contracts contracts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.contracts
    ADD CONSTRAINT contracts_pkey PRIMARY KEY (id);


--
-- Name: departments departments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.departments
    ADD CONSTRAINT departments_pkey PRIMARY KEY (id);


--
-- Name: employee_branches employee_branches_employee_id_branch_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employee_branches
    ADD CONSTRAINT employee_branches_employee_id_branch_id_unique UNIQUE (employee_id, branch_id);


--
-- Name: employee_branches employee_branches_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employee_branches
    ADD CONSTRAINT employee_branches_pkey PRIMARY KEY (id);


--
-- Name: employee_departments employee_departments_employee_id_department_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employee_departments
    ADD CONSTRAINT employee_departments_employee_id_department_id_unique UNIQUE (employee_id, department_id);


--
-- Name: employee_departments employee_departments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employee_departments
    ADD CONSTRAINT employee_departments_pkey PRIMARY KEY (id);


--
-- Name: employees employees_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employees
    ADD CONSTRAINT employees_email_unique UNIQUE (email);


--
-- Name: employees employees_employee_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employees
    ADD CONSTRAINT employees_employee_code_unique UNIQUE (employee_code);


--
-- Name: employees employees_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employees
    ADD CONSTRAINT employees_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: job_titles job_titles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.job_titles
    ADD CONSTRAINT job_titles_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: model_has_permissions model_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_pkey PRIMARY KEY (permission_id, model_id, model_type);


--
-- Name: model_has_roles model_has_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_pkey PRIMARY KEY (role_id, model_id, model_type);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: permissions permissions_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_name_guard_name_unique UNIQUE (name, guard_name);


--
-- Name: permissions permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- Name: role_has_permissions role_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_pkey PRIMARY KEY (permission_id, role_id);


--
-- Name: roles roles_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_name_guard_name_unique UNIQUE (name, guard_name);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: branches_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX branches_status_index ON public.branches USING btree (status);


--
-- Name: cache_expiration_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cache_expiration_index ON public.cache USING btree (expiration);


--
-- Name: cache_locks_expiration_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cache_locks_expiration_index ON public.cache_locks USING btree (expiration);


--
-- Name: contracts_employee_id_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX contracts_employee_id_status_index ON public.contracts USING btree (employee_id, status);


--
-- Name: contracts_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX contracts_status_index ON public.contracts USING btree (status);


--
-- Name: departments_branch_id_parent_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX departments_branch_id_parent_id_index ON public.departments USING btree (branch_id, parent_id);


--
-- Name: departments_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX departments_status_index ON public.departments USING btree (status);


--
-- Name: employee_branches_branch_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX employee_branches_branch_id_index ON public.employee_branches USING btree (branch_id);


--
-- Name: employee_departments_department_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX employee_departments_department_id_index ON public.employee_departments USING btree (department_id);


--
-- Name: employees_branch_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX employees_branch_id_index ON public.employees USING btree (branch_id);


--
-- Name: employees_department_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX employees_department_id_index ON public.employees USING btree (department_id);


--
-- Name: employees_job_title_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX employees_job_title_id_index ON public.employees USING btree (job_title_id);


--
-- Name: employees_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX employees_status_index ON public.employees USING btree (status);


--
-- Name: job_titles_department_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX job_titles_department_id_index ON public.job_titles USING btree (department_id);


--
-- Name: job_titles_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX job_titles_status_index ON public.job_titles USING btree (status);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: model_has_permissions_model_id_model_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX model_has_permissions_model_id_model_type_index ON public.model_has_permissions USING btree (model_id, model_type);


--
-- Name: model_has_roles_model_id_model_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX model_has_roles_model_id_model_type_index ON public.model_has_roles USING btree (model_id, model_type);


--
-- Name: personal_access_tokens_expires_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX personal_access_tokens_expires_at_index ON public.personal_access_tokens USING btree (expires_at);


--
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: contracts contracts_employee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.contracts
    ADD CONSTRAINT contracts_employee_id_foreign FOREIGN KEY (employee_id) REFERENCES public.employees(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: departments departments_branch_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.departments
    ADD CONSTRAINT departments_branch_id_foreign FOREIGN KEY (branch_id) REFERENCES public.branches(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: departments departments_parent_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.departments
    ADD CONSTRAINT departments_parent_id_foreign FOREIGN KEY (parent_id) REFERENCES public.departments(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: employee_branches employee_branches_employee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employee_branches
    ADD CONSTRAINT employee_branches_employee_id_foreign FOREIGN KEY (employee_id) REFERENCES public.employees(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: employee_departments employee_departments_employee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employee_departments
    ADD CONSTRAINT employee_departments_employee_id_foreign FOREIGN KEY (employee_id) REFERENCES public.employees(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: employees employees_branch_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employees
    ADD CONSTRAINT employees_branch_id_foreign FOREIGN KEY (branch_id) REFERENCES public.branches(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: employees employees_department_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employees
    ADD CONSTRAINT employees_department_id_foreign FOREIGN KEY (department_id) REFERENCES public.departments(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: employees employees_job_title_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employees
    ADD CONSTRAINT employees_job_title_id_foreign FOREIGN KEY (job_title_id) REFERENCES public.job_titles(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: job_titles job_titles_department_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.job_titles
    ADD CONSTRAINT job_titles_department_id_foreign FOREIGN KEY (department_id) REFERENCES public.departments(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: model_has_permissions model_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: model_has_roles model_has_roles_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

\unrestrict CvACereIXrhSeWfdaVJeAmUONbZcPIifvrSFDdDST1jOKZ1nDdbBiR3THBX5u8R


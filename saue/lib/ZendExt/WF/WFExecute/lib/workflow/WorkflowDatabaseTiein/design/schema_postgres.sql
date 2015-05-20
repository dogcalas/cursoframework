--
-- PostgreSQL database dump
--

-- Started on 2010-12-16 11:10:51

SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

--
-- TOC entry 308 (class 2612 OID 16386)
-- Name: plpgsql; Type: PROCEDURAL LANGUAGE; Schema: -; Owner: -
--

--CREATE PROCEDURAL LANGUAGE plpgsql;


SET search_path = public, pg_catalog;

--
-- TOC entry 1487 (class 1259 OID 26334)
-- Dependencies: 3
-- Name: execution_execution_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE execution_execution_id_seq
    INCREMENT BY 1
    MAXVALUE 9999999999
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 1779 (class 0 OID 0)
-- Dependencies: 1487
-- Name: execution_execution_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('execution_execution_id_seq', 46, true);


SET default_tablespace = '';

SET default_with_oids = true;

--
-- TOC entry 1480 (class 1259 OID 26288)
-- Dependencies: 1755 3
-- Name: execution; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE execution (
    workflow_id numeric(10,0) NOT NULL,
    execution_id numeric(10,0) DEFAULT nextval('execution_execution_id_seq'::regclass) NOT NULL,
    execution_parent numeric(10,0) NOT NULL,
    execution_started numeric(12,0) NOT NULL,
    execution_variables text NOT NULL,
    execution_waiting_for text NOT NULL,
    execution_threads text NOT NULL,
    execution_next_thread_id numeric(10,0) NOT NULL
);
ALTER TABLE ONLY execution ALTER COLUMN execution_id SET STATISTICS 0;
ALTER TABLE ONLY execution ALTER COLUMN execution_parent SET STATISTICS 0;
ALTER TABLE ONLY execution ALTER COLUMN execution_started SET STATISTICS 0;
ALTER TABLE ONLY execution ALTER COLUMN execution_variables SET STATISTICS 0;
ALTER TABLE ONLY execution ALTER COLUMN execution_waiting_for SET STATISTICS 0;
ALTER TABLE ONLY execution ALTER COLUMN execution_next_thread_id SET STATISTICS 0;


--
-- TOC entry 1481 (class 1259 OID 26298)
-- Dependencies: 3
-- Name: execution_state; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE execution_state (
    execution_id numeric(10,0) NOT NULL,
    node_id numeric(10,0) NOT NULL,
    node_state text NOT NULL,
    node_activated_from text NOT NULL,
    node_thread_id numeric(10,0) NOT NULL
);
ALTER TABLE ONLY execution_state ALTER COLUMN execution_id SET STATISTICS 0;
ALTER TABLE ONLY execution_state ALTER COLUMN node_id SET STATISTICS 0;
ALTER TABLE ONLY execution_state ALTER COLUMN node_state SET STATISTICS 0;
ALTER TABLE ONLY execution_state ALTER COLUMN node_activated_from SET STATISTICS 0;
ALTER TABLE ONLY execution_state ALTER COLUMN node_thread_id SET STATISTICS 0;


--
-- TOC entry 1488 (class 1259 OID 26336)
-- Dependencies: 3
-- Name: node_node_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE node_node_id_seq
    INCREMENT BY 1
    MAXVALUE 999999999
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 1780 (class 0 OID 0)
-- Dependencies: 1488
-- Name: node_node_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('node_node_id_seq', 94, true);


--
-- TOC entry 1482 (class 1259 OID 26308)
-- Dependencies: 1756 3
-- Name: node; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE node (
    workflow_id numeric(10,0) NOT NULL,
    node_id numeric(10,0) DEFAULT nextval('node_node_id_seq'::regclass) NOT NULL,
    node_class character varying(255) NOT NULL,
    node_configuration text NOT NULL
);
ALTER TABLE ONLY node ALTER COLUMN workflow_id SET STATISTICS 0;
ALTER TABLE ONLY node ALTER COLUMN node_id SET STATISTICS 0;
ALTER TABLE ONLY node ALTER COLUMN node_class SET STATISTICS 0;
ALTER TABLE ONLY node ALTER COLUMN node_configuration SET STATISTICS 0;


--
-- TOC entry 1483 (class 1259 OID 26316)
-- Dependencies: 3
-- Name: node_connection; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE node_connection (
    incoming_node_id numeric(10,0) NOT NULL,
    outgoing_node_id numeric(10,0) NOT NULL
);
ALTER TABLE ONLY node_connection ALTER COLUMN incoming_node_id SET STATISTICS 0;
ALTER TABLE ONLY node_connection ALTER COLUMN outgoing_node_id SET STATISTICS 0;


--
-- TOC entry 1484 (class 1259 OID 26319)
-- Dependencies: 3
-- Name: variable_handler; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE variable_handler (
    workflow_id numeric(10,0) NOT NULL,
    variable character varying(255) NOT NULL,
    class character varying(255) NOT NULL
);
ALTER TABLE ONLY variable_handler ALTER COLUMN workflow_id SET STATISTICS 0;
ALTER TABLE ONLY variable_handler ALTER COLUMN variable SET STATISTICS 0;
ALTER TABLE ONLY variable_handler ALTER COLUMN class SET STATISTICS 0;


--
-- TOC entry 1486 (class 1259 OID 26332)
-- Dependencies: 3
-- Name: workflow_workflow_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE workflow_workflow_id_seq
    INCREMENT BY 1
    MAXVALUE 999999999999
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 1781 (class 0 OID 0)
-- Dependencies: 1486
-- Name: workflow_workflow_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('workflow_workflow_id_seq', 19, true);


--
-- TOC entry 1485 (class 1259 OID 26327)
-- Dependencies: 1757 3
-- Name: workflow; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE workflow (
    workflow_id numeric(10,0) DEFAULT nextval('workflow_workflow_id_seq'::regclass) NOT NULL,
    workflow_name character varying(32) NOT NULL,
    workflow_version numeric(20,0) NOT NULL,
    workflow_created numeric(20,0) NOT NULL
);
ALTER TABLE ONLY workflow ALTER COLUMN workflow_id SET STATISTICS 0;
ALTER TABLE ONLY workflow ALTER COLUMN workflow_name SET STATISTICS 0;
ALTER TABLE ONLY workflow ALTER COLUMN workflow_version SET STATISTICS 0;
ALTER TABLE ONLY workflow ALTER COLUMN workflow_created SET STATISTICS 0;


--
-- TOC entry 1768 (class 0 OID 26288)
-- Dependencies: 1480
-- Data for Name: execution; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 1769 (class 0 OID 26298)
-- Dependencies: 1481
-- Data for Name: execution_state; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 1770 (class 0 OID 26308)
-- Dependencies: 1482
-- Data for Name: node; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO node (workflow_id, node_id, node_class, node_configuration) VALUES (18, 86, 'ezcWorkflowNodeStart', '');
INSERT INTO node (workflow_id, node_id, node_class, node_configuration) VALUES (18, 87, 'ezcWorkflowNodeEnd', '');
INSERT INTO node (workflow_id, node_id, node_class, node_configuration) VALUES (18, 88, 'ezcWorkflowNodeInput', 'YToyOntzOjQ6ImVkYWQiO086Mjg6ImV6Y1dvcmtmbG93Q29uZGl0aW9uSXNTdHJpbmciOjA6e31zOjQ6InNleG8iO086Mjg6ImV6Y1dvcmtmbG93Q29uZGl0aW9uSXNTdHJpbmciOjA6e319');
INSERT INTO node (workflow_id, node_id, node_class, node_configuration) VALUES (18, 89, 'ezcWorkflowNodeAction', 'YToyOntzOjU6ImNsYXNzIjtzOjE1OiJteVNlcnZpY2VPYmplY3QiO3M6OToiYXJndW1lbnRzIjthOjA6e319');
INSERT INTO node (workflow_id, node_id, node_class, node_configuration) VALUES (19, 90, 'ezcWorkflowNodeStart', '');
INSERT INTO node (workflow_id, node_id, node_class, node_configuration) VALUES (19, 91, 'ezcWorkflowNodeEnd', '');
INSERT INTO node (workflow_id, node_id, node_class, node_configuration) VALUES (19, 92, 'ezcWorkflowNodeInput', 'YToyOntzOjY6Im5vbWJyZSI7TzoyODoiZXpjV29ya2Zsb3dDb25kaXRpb25Jc1N0cmluZyI6MDp7fXM6ODoiYXBlbGxpZG8iO086Mjg6ImV6Y1dvcmtmbG93Q29uZGl0aW9uSXNTdHJpbmciOjA6e319');
INSERT INTO node (workflow_id, node_id, node_class, node_configuration) VALUES (19, 93, 'ezcWorkflowNodeSubWorkflow', 'YToyOntzOjg6IndvcmtmbG93IjtzOjg6IlNVQldGX0ExIjtzOjk6InZhcmlhYmxlcyI7YToyOntzOjI6ImluIjthOjI6e3M6Njoibm9tYnJlIjtzOjY6Im5vbWJyZSI7czo4OiJhcGVsbGlkbyI7czo4OiJhcGVsbGlkbyI7fXM6Mzoib3V0IjthOjA6e319fQ==');
INSERT INTO node (workflow_id, node_id, node_class, node_configuration) VALUES (19, 94, 'ezcWorkflowNodeAction', 'YToyOntzOjU6ImNsYXNzIjtzOjE4OiJteVNlcnZpY2VPYmplY3RCeWUiO3M6OToiYXJndW1lbnRzIjthOjA6e319');


--
-- TOC entry 1771 (class 0 OID 26316)
-- Dependencies: 1483
-- Data for Name: node_connection; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO node_connection (incoming_node_id, outgoing_node_id) VALUES (86, 88);
INSERT INTO node_connection (incoming_node_id, outgoing_node_id) VALUES (88, 89);
INSERT INTO node_connection (incoming_node_id, outgoing_node_id) VALUES (89, 87);
INSERT INTO node_connection (incoming_node_id, outgoing_node_id) VALUES (90, 92);
INSERT INTO node_connection (incoming_node_id, outgoing_node_id) VALUES (92, 93);
INSERT INTO node_connection (incoming_node_id, outgoing_node_id) VALUES (93, 94);
INSERT INTO node_connection (incoming_node_id, outgoing_node_id) VALUES (94, 91);


--
-- TOC entry 1772 (class 0 OID 26319)
-- Dependencies: 1484
-- Data for Name: variable_handler; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 1773 (class 0 OID 26327)
-- Dependencies: 1485
-- Data for Name: workflow; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO workflow (workflow_id, workflow_name, workflow_version, workflow_created) VALUES (18, 'SUBWF_A1', 1, 1292455658);
INSERT INTO workflow (workflow_id, workflow_name, workflow_version, workflow_created) VALUES (19, 'WF', 1, 1292455658);


--
-- TOC entry 1759 (class 2606 OID 26295)
-- Dependencies: 1480 1480 1480
-- Name: execution_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY execution
    ADD CONSTRAINT execution_pkey PRIMARY KEY (workflow_id, execution_id);


--
-- TOC entry 1761 (class 2606 OID 26305)
-- Dependencies: 1481 1481 1481
-- Name: execution_state_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY execution_state
    ADD CONSTRAINT execution_state_pkey PRIMARY KEY (execution_id, node_id);


--
-- TOC entry 1763 (class 2606 OID 26315)
-- Dependencies: 1482 1482
-- Name: node_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY node
    ADD CONSTRAINT node_pkey PRIMARY KEY (node_id);


--
-- TOC entry 1765 (class 2606 OID 26326)
-- Dependencies: 1484 1484 1484
-- Name: variable_handler_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY variable_handler
    ADD CONSTRAINT variable_handler_pkey PRIMARY KEY (workflow_id, class);


--
-- TOC entry 1767 (class 2606 OID 26331)
-- Dependencies: 1485 1485
-- Name: workflow_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY workflow
    ADD CONSTRAINT workflow_pkey PRIMARY KEY (workflow_id);


--
-- TOC entry 1778 (class 0 OID 0)
-- Dependencies: 3
-- Name: public; Type: ACL; Schema: -; Owner: -
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2010-12-16 11:10:53

--
-- PostgreSQL database dump complete
--


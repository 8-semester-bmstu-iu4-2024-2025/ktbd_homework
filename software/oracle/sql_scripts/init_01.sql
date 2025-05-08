PROMPT 'Creating tables'

DROP TABLE products CASCADE CONSTRAINTS;
CREATE TABLE products (
    prds_id       INTEGER NOT NULL
    ,prds_stat     VARCHAR2(32) NOT NULL
    ,prds_name     VARCHAR2(32) NOT NULL
    ,prds_quantity INTEGER NOT NULL
    ,prds_tp_type  VARCHAR2(32) NOT NULL
    ,prds_eqpt_id  INTEGER NULL
    ,prds_empl_id  INTEGER NULL
    ,prds_docs_id  INTEGER NULL
    ,prds_dfct_id  INTEGER NULL
);
DROP TABLE employees CASCADE CONSTRAINTS;
CREATE TABLE employees (
    empl_id   INTEGER NOT NULL
    ,empl_adr  VARCHAR2(32) NULL
    ,empl_job  VARCHAR2(32) NULL
    ,empl_secn VARCHAR2(32) NULL
    ,empl_surn VARCHAR2(32) NOT NULL
    ,empl_name VARCHAR2(32) NOT NULL
    ,empl_pass VARCHAR2(32) NOT NULL
);

DROP TABLE equipment CASCADE CONSTRAINTS;
CREATE TABLE equipment (
    eqpt_id   INTEGER NOT NULL
    ,eqpt_date DATE NULL
    ,eqpt_type VARCHAR2(32) NULL
    ,eqpt_desc VARCHAR2(32) NULL
    ,eqpt_name VARCHAR2(32) NOT NULL
);

DROP TABLE defects CASCADE CONSTRAINTS;
CREATE TABLE defects (
    dfct_id   INTEGER NOT NULL
    ,dfct_date DATE NULL
    ,dfct_type VARCHAR2(32) NULL
    ,dfct_desc VARCHAR2(32) NULL
    ,dfct_name VARCHAR2(32) NOT NULL
);

DROP TABLE components CASCADE CONSTRAINTS;
CREATE TABLE components (
    comp_id   INTEGER NOT NULL
    ,comp_date DATE NULL
    ,comp_type VARCHAR2(32) NULL
    ,comp_name VARCHAR2(32) NOT NULL
);

DROP TABLE materials CASCADE CONSTRAINTS;
CREATE TABLE materials (
    mat_id    INTEGER NOT NULL
    ,mat_date  DATE NULL
    ,mat_quant INTEGER NOT NULL
    ,mat_type  VARCHAR2(32) NULL
    ,mat_name  VARCHAR2(32) NOT NULL
);

DROP TABLE documents CASCADE CONSTRAINTS;
CREATE TABLE documents (
    docs_id   INTEGER NOT NULL
    ,docs_auth VARCHAR2(20) NOT NULL
    ,docs_type VARCHAR2(32) NULL
    ,docs_date DATE NOT NULL
    ,docs_name VARCHAR2(20) NOT NULL
);

COMMIT;